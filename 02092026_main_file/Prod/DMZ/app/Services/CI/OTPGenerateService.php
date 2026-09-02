<?php

namespace App\Services\CI;

use App\OtpCode;
use App\SMSEmail;
use Carbon\Carbon;
use App\OutgoingSMS;
use SimpleXMLElement;
use App\OutgoingEMAIL;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class OTPGenerateService
{
    public static function otpCodeGenerate($cif_number, $w_form_type, $product_type, $account_number, $reference_number, $mobile_number, $email_address, $otp_mode)
    {
        $otp = rand(111111, 999999);
        $encryptOtp = encrypt($otp);
        $otpObj = new OtpCode();
        $otpObj->cif_number = $cif_number;
        $otpObj->service_type = $w_form_type;
        $otpObj->product_type = $product_type;
        $otpObj->account_number = $account_number;
        $otpObj->reference_number = $reference_number;
        $otpObj->mobile = $mobile_number;
        $otpObj->send_status = 0;
        $otpObj->otp = $encryptOtp;
        // $otpObj->expire_at = Carbon::now()->addSecond(30);
        // $otpObj->generated_date_time = Carbon::now();
        $otpObj->otp_status = 'pending';
        $otpObj->save();
        // $data['otpGenId'] = $otpObj->id;
        $data['otpGenId'] = encrypt($otpObj->id);
        $data['otpCode'] = $otp;
        $maskedOtp = self::maskOtpNumber($otp);
        // dd($maskedOtp);
        $otpResponse = self::otpCodeSend($cif_number, $maskedOtp, $product_type, $otp, $mobile_number, $reference_number, $email_address, $otp_mode);

        OtpCode::where('id', $otpObj->id)->update([
            /*'otp_status' => $otpResponse->getData()->errorCode == 000? 'Success' : 'Failed',*/
            'send_status' => $otpResponse->getData()->errorCode == 000? 1 : 0,
            'error_code' => $otpResponse->getData()->errorCode,
            'response_message' => $otpResponse->getData()->errorMessage,
            'global_transaction_id' => $otpResponse->getData()->globalTransactionId,
            'service_code' => $otpResponse->getData()->serviceCode,
            'channel_code' => $otpResponse->getData()->channelCode,
            'expire_at' => Carbon::now()->addSecond(180),
            'generated_date_time' => Carbon::now(),
        ]);

        if ($otpResponse->getData()->errorCode == 000) {
            OutgoingSMS::where('id', $otpResponse->getData()->sendSms)->update([
                'senttime' => Carbon::now(),
                'status' => 1
            ]);
        }else{
            OutgoingSMS::where('id', $otpResponse->getData()->sendSms)->update([
                'senttime' => Carbon::now(),
                'status' => 4
            ]);
        }

        return $data;
    }


    public static function maskOtpNumber($otpNumber)
    {
        $visibleDigits = 2;
        $totalLength = strlen($otpNumber);
        $maskedDigits = $totalLength - (2 * $visibleDigits);
        $mask = str_repeat('*', $maskedDigits);
        $visiblePart = substr($otpNumber, 0, $visibleDigits);
        $maskedPart = substr($otpNumber, $visibleDigits, $maskedDigits);
        $lastVisiblePart = substr($otpNumber, -$visibleDigits);
        $maskedOtpNumber = $visiblePart . $mask . $lastVisiblePart;
        return $maskedOtpNumber;
    }

    public static function otpCodeSend($cif_number, $maskedOtp, $product_type, $code, $mobile_number, $reference_number, $email_address, $otp_mode)
    {
        $smsEmailModel = new SMSEmail();
        $smsEmailData = $smsEmailModel->orderBy('id','DESC')->first();
        if (!empty($smsEmailData)) {
            $sms = $smsEmailData['otp_sms'];
            $mail = $smsEmailData['otp_email'];
        }

        // Store OTP outgoingsmstable table:
        if ($otp_mode == 1){
            if (!empty($sms)) {
                $unmaskedSms = str_replace("{otp_code}", $code, $sms);
                $maskedSms = str_replace("{otp_code}", $maskedOtp, $sms);
                $msg['sms'] = $unmaskedSms;
                $msg['maskedSms'] = $maskedSms;
                $sendSms = self::sendSMS($mobile_number, $msg['maskedSms'], $reference_number, 0, 0);
            }
        }

        // Store OTP outgoingemailtable table:
        /*if ($otp_mode == 2){
            if (!empty($mail)) {
                $mail = str_replace("{otp_code}", $code, $mail);
                $msg['mail'] = $mail;
                $sendSms = self::sendEMAIL($email_address, $msg['mail'],$reference_number,0, "Ticket Log OTP",0);
            }
        }*/

        $accessToken = ApiAccessTokenService::getAccessToken();
	
        $requestId = 'MIB' . date('yyyymmddHis'); //'MIB' . substr(str_replace('-', '', Str::uuid()->getHex()), 0, 16);

        $msc = microtime(true);

        if (!empty($accessToken) && !empty($accessToken['responseCode']) && $accessToken['responseCode'] == 200){
            $restUrl = "https://pblconnect-local.primebank.com.bd:8443/utility-api/v1/sms/sms-service/send-request";
	    
	    Log::error('access_code', [$accessToken['data']['accessToken']]);
	    Log::error('OTP-SMS', [$unmaskedSms]);
	
        $payloadMasked = [
            "requestId" => $requestId,
            "sourceChannel" => "MIB",
            "mobileNumber" => str_replace("+88","",$mobile_number),
            "messageBody" => $unmaskedSms,
            "textEncodingType" => 1
        ];

        $payloadUnmasked = $payloadMasked;

	    $accessToken = $accessToken['data']['accessToken'];

            try{
                // Send unmasked SMS
                $curl = curl_init($restUrl);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Authorization: ' . $accessToken,
                ]);

                curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
                curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                // Enable verbose output for debugging
                curl_setopt($curl, CURLOPT_VERBOSE, true);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payloadUnmasked));
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification if needed

                $response = curl_exec($curl);
                curl_close($curl);

            }catch(\Exception $e){
                Log::error('OTP SMS Error Log', [$e->getMessage()]);
            }
            
        }


        $msc = microtime(true) - $msc;
        $execution_time = number_format($msc, 2);
	    Log::info('resp', [$response]);
        $responseArr = json_decode($response, true);
        // Log to DB
        CIRequestResponseService::ciRequestResponse([
            'cif_number' => $cif_number,
            'product_type' => $product_type,
            'global_transaction_id' => $requestId,
            'status_code' => $responseArr['responseCode'] ?? '',
            'status_msg' => implode(", ", $responseArr['messages'] ?? []),
            'endpoint' => $restUrl,
            'json_node' => $response,
            'execution_time' => $execution_time,
        ]);

        return response()->json([
            'data' => $response,
            'sendSms' => $sendSms,
            'maskedOtp' => $maskedOtp,
            'errorCode' => $responseArr['responseCode'] ?? '',
            'channelCode' => $responseArr['data']['channel'] ?? '',
            'serviceCode' => '001',
            'errorMessage' => implode(", ", $responseArr['messages'] ?? []),
            'globalTransactionId' => $requestId,
        ]);
    }


    public static function sendSMS($mobile_no, $msg, $ref_no = "",$supp_status=NULL,$sendbackStatus=0)
    {
        date_default_timezone_set('Asia/Dhaka');
        $savedtime = date("Y-m-d H:i:s");
        $mobile_no_1 = str_replace("+88(00)", "+88", $mobile_no);
        $mnumber = formatMobileNumber($mobile_no_1);
        if($mnumber != "") {
            if(is_numeric($mnumber) && strlen($mnumber) == 14) {
                $outgoingSMSModel = new OutgoingSMS;
                $outgoingSMSModel->sentSMSid = 0;
                $outgoingSMSModel->send_back_status = $sendbackStatus;
                $outgoingSMSModel->message = $msg;
                $outgoingSMSModel->savetime = $savedtime;
                $outgoingSMSModel->senttime = '';
                $outgoingSMSModel->status = '3';
                $outgoingSMSModel->support_status = $supp_status;
                $outgoingSMSModel->mobileNo = $mnumber;
                $outgoingSMSModel->reference_number = $ref_no;
                $outgoingSMSModel->save();
                return $outgoingSMSModel->id;
            }
        }
    }

    public static function sendEMAIL($email_address, $mail, $ref_no = "",$supp_status=NULL, $subject=null,$sendbackStatus=0)
    {
        date_default_timezone_set('Asia/Dhaka');
        $savedtime = date("Y-m-d H:i:s");
        if($mail != "") {
            $outgoingEMAILModel = new OutgoingEMAIL;
            $outgoingEMAILModel->subject = $subject;
            $outgoingEMAILModel->send_back_status = $sendbackStatus;
            $outgoingEMAILModel->body = $mail;
            $outgoingEMAILModel->savetime = $savedtime;
            $outgoingEMAILModel->senttime = '';
            $outgoingEMAILModel->status = '3';
            $outgoingEMAILModel->support_status = $supp_status;
            $outgoingEMAILModel->email_address = $email_address;
            $outgoingEMAILModel->reference_number = $ref_no;
            $outgoingEMAILModel->save();
        }
    }
}
