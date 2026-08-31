<?php

namespace App\Services\CI;

use App\Services\CI\CIRequestResponseService;
use DomDocument;
use Illuminate\Support\Facades\DB;
use SimpleXMLElement;
use App\CustomerInterfaceAPI;
use App\APIDemoResponse\getCustomerDetails;
use Illuminate\Support\Facades\Log;
use App\Traits\LogCurlError;

class CICURLService
{
    use LogCurlError;

    public static function generateRandomString($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
	
	public static function encrypt_decrypt_rtgs($action, $string)
    {
        $encryptionKey = '79368512319356J8AC80HY0807lA5P49';
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = $encryptionKey;
        $secret_iv = 'J13M4HG5815638K9';
        // hash
        $key = $secret_key;//hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = $secret_iv;//substr(hash('sha256', $secret_iv), 0, 16);
        if ($action == 'ENC') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 1, $iv);
            $output = base64_encode($output);
        } else if ($action == 'DEC') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 1, $iv);
        }

        return $output;
    }

    public static function ciCurl($CIFNumber, $product_type)
    {
        $api_credential = DB::table('api_credential')->first();
        $accountNumberList = [];
        $ciApimodel = new CustomerInterfaceAPI;
        $ciApiObj = $ciApimodel->where('product_type', $product_type)->first();
        $accessToken = ApiAccessTokenService::getAccessToken();
        $response = '';

        if ($product_type == 2) { //account
            if (!empty($accessToken) && !empty($accessToken['responseCode']) && $accessToken['responseCode'] == 200) {
                $ciApiObj = $ciApimodel->where('product_type', 'account')->first();
                $restUrl = $ciApiObj->endpoint;

                $headers = [
                    'Content-Type: application/json',
                    'Authorization: ' . $accessToken['data']['accessToken'],
                ];

                $postData = json_encode([
                    'customerId' => $CIFNumber,
                ]);

                $msc = microtime(true);

                CIRequestResponseService::ciRequestResponse([
                    'cif_number' => $CIFNumber,
                    'product_type' => $product_type,
                    'type' => 1,
                    'endpoint' => $restUrl,
                    'json_node' => $postData,
                ]);

                $ch = curl_init();
                // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
                curl_setopt($ch, CURLOPT_URL, $restUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);

                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                if (curl_errno($ch)) {
                    self::logCurlError($ch, $restUrl, $CIFNumber, $product_type);
                } else {
                    self::logHttpResponse($httpCode, $response, $restUrl, $CIFNumber, $product_type);
                }

                curl_close($ch);

                //$response = file_get_contents(base_path('/app/APIDemoResponse/getCustomerDetails.json'));

                if (!empty($response)) {
                    $responseData = json_decode($response, true);
                    $customerCode = $responseData['responseCode'] ?? null;

                    $msc = microtime(true) - $msc;
                    $execution_time = number_format($msc, 2);

                    CIRequestResponseService::ciRequestResponse([
                        'cif_number' => $CIFNumber,
                        'product_type' => $product_type,
                        'type' => 2,
                        'endpoint' => $restUrl,
                        'json_node' => $response,
                        'execution_time' => $execution_time,
                    ]);

                    if ($customerCode == 200) {
                        if (!empty($responseData['data']) && !empty($responseData['data']['accounts'])) {
                            foreach ($responseData['data']['accounts'] as $data) {
                                $accountNumberValue = $data['accountId'];
                                if (strlen($accountNumberValue) >= 5) {
                                    if (str_starts_with($accountNumberValue, 'LD') || $accountNumberValue[4] == 7) {
                                        continue;
                                    }
                                }
                                $productDescriptionValue = $data['categoryTitle'];
                                $accountNumberList[$accountNumberValue] = "$accountNumberValue | $productDescriptionValue";
                            }
                        }
                    }
                }
            }
        } elseif ($product_type == 3) { //Debit Card
            if (!empty($accessToken) && !empty($accessToken['responseCode']) && $accessToken['responseCode'] == 200) {
                $ciApiObj = $ciApimodel->where('product_type', 'card')->first();
                $restUrl = $ciApiObj->endpoint;

                $headers = [
                    'Content-Type: application/json',
                    'Authorization: ' . $accessToken['data']['accessToken'],
                ];

                $requestIdLength = random_int(16, 20);
                $requestId = self::generateRandomString($requestIdLength);

                $postData = json_encode([
                    'cifNumber' => $CIFNumber,
                    'requestDateTime' => date('m/d/Y'),
                    'requestId' => $requestId,
                    'sourceChannel' => 'MIB'
                ]);

                $msc = microtime(true);

                CIRequestResponseService::ciRequestResponse([
                    'cif_number' => $CIFNumber,
                    'product_type' => $product_type,
                    'type' => 1,
                    'endpoint' => $restUrl,
                    'json_node' => $postData,
                ]);

                $ch = curl_init();
                // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
                curl_setopt($ch, CURLOPT_URL, $restUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		$response = curl_exec($ch);
		Log::error('Debit Card Response', [$response]);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                if (curl_errno($ch)) {
                    self::logCurlError($ch, $restUrl, $CIFNumber, $product_type);
                } else {
                    self::logHttpResponse($httpCode, $response, $restUrl, $CIFNumber, $product_type);
                }

                curl_close($ch);

                // $response = file_get_contents(base_path('/app/APIDemoResponse/cardDetailsByCif.json'));

                $responseData = json_decode($response, true);
                $customerCode = $responseData['responseCode'] ?? null;

                $msc = microtime(true) - $msc;
                $execution_time = number_format($msc, 2);

                CIRequestResponseService::ciRequestResponse([
                    'cif_number' => $CIFNumber,
                    'product_type' => $product_type,
                    'type' => 2,
                    'endpoint' => $restUrl,
                    'json_node' => $response,
                    'execution_time' => $execution_time,
                ]);

                if ($customerCode == 200) {
                    if (!empty($responseData['data']) && !empty($responseData['data']['cardDetails'])) {

                        $onlyDCData = collect($responseData['data']['cardDetails'])
                            ->reject(function ($card) {
                                return !in_array($card['typeOfCard'], ['DC', 'PP']);
                            })
                            ->values()
                            ->all();

                        if (!empty($onlyDCData)) {
                            foreach ($onlyDCData as $data) {
                                // $dyc = self::encrypt_decrypt_rtgs('DEC',$data['cardNumber']);
                                // $maskedCardNumber = ccMasking($dyc);

                                $accountNumberValue = $data['cardNumber'];
                                $productDescriptionValue = $data['cardProductName'];
                                $accountNumberList[$accountNumberValue] = "$accountNumberValue | $productDescriptionValue";
                            }
                        }
                    }
                }
            }
        } elseif ($product_type == 1) { // Credit Card
            if (!empty($accessToken) && !empty($accessToken['responseCode']) && $accessToken['responseCode'] == 200) {
                $ciApiObj = $ciApimodel->where('product_type', 'card')->first();
                $restUrl = $ciApiObj->endpoint;


                $headers = [
                    'Content-Type: application/json',
                    'Authorization: ' . $accessToken['data']['accessToken'],
                ];

                $requestIdLength = random_int(16, 20);
                $requestId = self::generateRandomString($requestIdLength);

                $postData = json_encode([
                    'cifNumber' => $CIFNumber,
                    'requestDateTime' => date('m/d/Y'),
                    'requestId' => $requestId,
                    'sourceChannel' => 'MIB'
                ]);

                $msc = microtime(true);

                CIRequestResponseService::ciRequestResponse([
                    'cif_number' => $CIFNumber,
                    'product_type' => $product_type,
                    'type' => 1,
                    'endpoint' => $restUrl,
                    'json_node' => $postData,
                ]);

                $ch = curl_init();
                // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
                curl_setopt($ch, CURLOPT_URL, $restUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);

                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                if (curl_errno($ch)) {
                    self::logCurlError($ch, $restUrl, $CIFNumber, $product_type);
                } else {
                    self::logHttpResponse($httpCode, $response, $restUrl, $CIFNumber, $product_type);
                }

                curl_close($ch);

                //$response = file_get_contents(base_path('/app/APIDemoResponse/cardDetailsByCif.json'));

                $responseData = json_decode($response, true);
                $customerCode = $responseData['responseCode'] ?? null;

                $msc = microtime(true) - $msc;
                $execution_time = number_format($msc, 2);

                CIRequestResponseService::ciRequestResponse([
                    'cif_number' => $CIFNumber,
                    'product_type' => $product_type,
                    'type' => 2,
                    'endpoint' => $restUrl,
                    'json_node' => $response,
                    'execution_time' => $execution_time,
                ]);

                if ($customerCode == 200) {
                    if (!empty($responseData['data']) && !empty($responseData['data']['cardDetails'])) {
                        $onlyCCData = collect($responseData['data']['cardDetails'])
                            ->reject(function ($card) {
                                return $card['typeOfCard'] !== 'CR';
                            })
                            ->values()
                            ->all();
                        if (!empty($onlyCCData)) {
                            foreach ($onlyCCData as $data) {
                                // $dyc = self::encrypt_decrypt_rtgs('DEC',$data['cardNumber']);
                                // $maskedCardNumber = ccMasking($dyc);

                                $accountNumberValue = $data['cardNumber'];
                                $productDescriptionValue = $data['cardProductName'];
                                $accountNumberList[$accountNumberValue] = "$accountNumberValue | $productDescriptionValue";
                            }
                        }
                    }
                }
            }
        } elseif ($product_type == 4) { // Loan
            if (!empty($accessToken) && !empty($accessToken['responseCode']) && $accessToken['responseCode'] == 200) {
                $ciApiObj = $ciApimodel->where('product_type', 'loan')->first();
                $restUrl = $ciApiObj->endpoint;

                $headers = [
                    'Content-Type: application/json',
                    'Authorization: ' . $accessToken['data']['accessToken'],
                ];

                $postData = json_encode([
                    'customerId' => $CIFNumber,
                ]);

                $msc = microtime(true);

                CIRequestResponseService::ciRequestResponse([
                    'cif_number' => $CIFNumber,
                    'product_type' => $product_type,
                    'type' => 1,
                    'endpoint' => $restUrl,
                    'json_node' => $postData,
                ]);

                $ch = curl_init();
                // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
                curl_setopt($ch, CURLOPT_URL, $restUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);

                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                if (curl_errno($ch)) {
                    self::logCurlError($ch, $restUrl, $CIFNumber, $product_type);
                } else {
                    self::logHttpResponse($httpCode, $response, $restUrl, $CIFNumber, $product_type);
                }

                curl_close($ch);

                if (!empty($response)) {
                    $responseData = json_decode($response, true);
                    $customerCode = $responseData['responseCode'] ?? null;

                    $msc = microtime(true) - $msc;
                    $execution_time = number_format($msc, 2);

                    CIRequestResponseService::ciRequestResponse([
                        'cif_number' => $CIFNumber,
                        'product_type' => $product_type,
                        'type' => 2,
                        'endpoint' => $restUrl,
                        'json_node' => $response,
                        'execution_time' => $execution_time,
                    ]);

                    if ($customerCode == 200) {
                        if (!empty($responseData['data']) && !empty($responseData['data']['loanList'])) {
                            foreach ($responseData['data']['loanList'] as $data) {
                                $accountNumberValue = $data['accountId'];
                                $productDescriptionValue = $data['loanType'];
                                $accountNumberList[$accountNumberValue] = "$accountNumberValue | $productDescriptionValue";
                            }
                        }
                    }
                }
            }
        }

        return response()->json([
            'accountNumbers' => $accountNumberList,
            'data' => $response,
        ]);
    }

    public static function apiResponse($jsonResponse, $accountNumber, $product_type)
    {
        $accountHolderName = null;
        $maskedCardNumber = null;

        if (!empty($jsonResponse)) {
            $response = json_decode($jsonResponse, true);

            if (!empty($response['data'])) {
                if ($product_type == 2) {
                    $result = !empty($response['data']['accounts']) ? $response['data']['accounts'][0]['accountTitle'] : '';
                    $accountHolderName = $result;
                }

                if ($product_type == 4) {
                    $result = !empty($response['data']['loanList']) ? $response['data']['loanList'][0]['customerTitle'] : '';
                    $accountHolderName = $result;
                }

                if ($product_type == 1) {
                    $result = !empty($response['data']['cardDetails']) ? $response['data']['cardDetails'][0]['cardHolderName'] : '';
                    //$result2 = !empty($response['data']['cardDetails']) ? $response['data']['cardDetails'][0]['customerTitle'] : '';
                    $accountHolderName = $result;
                }

                if ($product_type == 3) {
                    $result = !empty($response['data']['cardDetails']) ? $response['data']['cardDetails'][0]['cardHolderName'] : '';
                    //$result2 = !empty($response['data']['cardDetails']) ? $response['data']['cardDetails'][0]['customerTitle'] : '';
                    $accountHolderName = $result;
                }
            }

        }

        return response()->json([
            'accountHolderName' => $accountHolderName,
            'maskedCardNumber' => $maskedCardNumber,
        ]);
    }

}
