<?php

namespace App\Http\Controllers\CI;

use App\CIUserSession;
use App\Complaint;
use App\ComplaintFormType;
use App\ComplaintFormTypeHistory;
use App\Services\CI\ApiAccessTokenService;
use App\Services\CI\CIRequestResponseService;
use App\Services\CI\getCustomerDetailsService;
use App\Services\CI\OTPSendEmailService;
use App\Services\CI\PBSessionIdApiService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rules\SpecialCharacterFilter;

use App\WForm;
use App\BpId;
use App\Comment;
use App\OtpCode;
use App\Sequence;
use App\SMSEmail;
use App\UnitItem;
use App\Reference;
use App\WFormType;
use App\Attachment;
use App\IssueConfig;
use App\OutgoingSMS;
use App\SubgroupInfo;
use App\IssueWorkflow;
use App\OutgoingEMAIL;
use App\WFormTypeHistory;
use App\IssueGroupWorkflow;
use App\CustomerInterfaceToken;
use App\IssueAttachmentConfig;
use App\Setting;
use App\Enum\FlowEnum;
use App\Feedback;
use Carbon\Carbon;
use Throwable;
use DateTime;

use App\Http\Requests\CIWFormRequest;
use App\Services\CI\FieldSetGroupService;
use App\Services\CI\TokenValidatedService;
use App\Services\CI\OTPGenerateService;
use App\Services\CI\CICURLService;
use App\Services\CI\CIFModificationService;

class CustomerInterfaceController extends Controller
{
    public function index()
    {
	   // dd(encrypt('^*PB#ci#2026Jan:vSeRve}=))'));
	    return abort(404, 'Page not found!');
        //return view('BBL_CI.index');
    }

    public function accessCI(Request $request)
    {
        $sessionId = $request->sessionId;
        $myPrimeId = $request->myPrimeId;
        Log::info('CI Request Log', [$request->all()]);

        $cus_email = '';
        $cus_phone = '';
        $setting = Setting::select('setting_id', 'ci_session_time')->first();

        if (empty($sessionId) || empty($myPrimeId)) {
            Log::error('CI Request Log', ['message' => 'Invalid Request Url', 'request' => $request->all()]);
            return response()->json([
                'error' => [
                    'code' => '422',
                    'message' => 'Invalid Request Url!',
                ]
            ], 422);
        }

        // Local
        //$api_credentials = ['username' => 'PrIMeCIFbL', 'password' => '^*PBLci#455a:vServe}=))'];
	
	    $api_credentials = (array)DB::table('api_credential')->select('ci_username', 'ci_password')->first();
	
        $username = $request->header('username');
        $password = $request->header('password');

       //getting username and password when request from browser
       // $username = $request->getUser();
       //: $password = $request->getPassword();
	
	    Log::info('CI Request Header', ['username' => $username, 'password' => $password]);	
	
        try {
           // $password = decrypt($password);
        } catch (Throwable $e) {
            Log::error('Password Error', ['message' => 'Invalid Password!']);
            return response()->json([
                'error' => [
                    'code' => '422',
                    'message' => 'Invalid Password!',
                ]
            ], 422);
        }

        if (empty($username) || empty($password)) {
			Log::error('Password Error2', ['message' => 'Username or Password is required!']);
            return response()->json([
                'error' => [
                    'code' => '422',
                    'message' => 'Username or Password is required!',
                ]
            ], 422);
        }


        // validated username and password
        if ($api_credentials['ci_username'] !== $username || $api_credentials['ci_password'] !== $password) {
			Log::error('Password Error3', ['message' => 'Username or Password dose not Match!']);
            return response()->json([
                'error' => [
                    'code' => '444',
                    'message' => 'Username or Password dose not Match!',
                ]
            ], 444);
        }


        // TODO: Here need to validate 'myPrimeId' & 'sessionId'
        $checkSession = PBSessionIdApiService::checkPrimeSession($myPrimeId, $sessionId);
	
        if (!empty($checkSession['resCif'])) {
            $customerDetails = getCustomerDetailsService::getCustomerDetailsById($checkSession);
            $uniqueToken = Str::uuid()->toString();

            $CIToken = new CustomerInterfaceToken();
            $CIToken->sessionId = $sessionId;
            $CIToken->cif_number = $checkSession['resCif'] ?? '';
            $CIToken->email = $customerDetails['email'] ?? '';
            $CIToken->mobile_no = $customerDetails['phone'] ?? '';
            $CIToken->token = $uniqueToken;
            $CIToken->myPrimeId = $myPrimeId;
            $CIToken->callback_url = $checkSession['resCallbackUrl'] ?? '';
            //$CIToken->is_verify = 1;
            $CIToken->last_activity_time = date('d-m-Y H:i:s');
            //$CIToken->expires_at = date('Y-m-d H:i:s', strtotime(Carbon::now()->addMinutes($setting->ci_session_time ?? 10)));
            $CIToken->save();

            CIUserSession::create([
                'token' => $uniqueToken,
                'cif_number' => $checkSession['resCif'],
                'time' => date('Y-m-d H:i:s', strtotime(Carbon::now())),
            ]);

	        Log::info('CI Return Success!', ['ci_web_url' => url('/') . '/CI/dashboard/?CIToken=' . $uniqueToken]);

           return response()->json([
                'code' => '000',
                'message' => 'Success',
                'ci_web_url' => url('/') . '/CI/dashboard/?CIToken=' . $uniqueToken,
            ], 200);

        } else {
            Log::error('CI Error', ['message' => 'CIF number not found!', 'request' => $request->all()]);
            return response()->json([
                'error' => [
                    'code' => '404',
                    'message' => 'Page Not Found!',
                ]
            ], 404);
        }
    }


    public function dashboard()
    {
        $CIToken = \request()->get('CIToken');

        // Check Token if exit
        $data = CustomerInterfaceToken::where('token', $CIToken)->first();

        if (!empty($data)) {
            if ($data->is_verify == 0){
                session()->forget('auth_token');
                session()->put('auth_token', $CIToken);
                $data->is_verify = 1;
                $data->expires_at = date('Y-m-d H:i:s', strtotime(Carbon::now()->addMinutes($setting->ci_session_time ?? 10)));
                $data->last_activity_time = date('d-m-Y H:i:s');
                $data->update();
                Log::info('info', ['message' => 'Return Route Successful!']);
                return redirect()->route('CI.service', ['CIToken' => $CIToken]);
            }
        }
        Log::error('error', ['message' => 'This Url has been expired']);
        return abort(404, 'Unauthorized User!');
    }

    public function service()
    {
        $CIToken = \request()->get('CIToken');
        $data = CustomerInterfaceToken::where('token', $CIToken)->where('is_verify', 1)->first();
        if ($data) {
            return view('BBL_CI.service_home', ["ci_token" => $CIToken, 'step1' => 'active']);
        }
        Log::error('error', ['message' => 'Session Id not found!']);
        return abort(404, 'Unauthorized User!');
    }

    public function serviceType()
    {

        $CIToken = \request()->get('CIToken');
        $requestType = \request()->get('request_type');
        // Check Token if exit
         $data = CustomerInterfaceToken::where('token', $CIToken)->where('is_verify', 1)->first();

        $backUrl = url('/') . '/CI/service/?CIToken=' . $CIToken . '&request_type=' . $requestType;

        if ($data) {
            if (!empty(session()->get('auth_token')) && session()->get('auth_token') === $CIToken) {
                if ($requestType === 'service') {
                    return view('BBL_CI.service_type', [
                        "ci_token" => $CIToken,
                        'step1' => 'active',
                        'backUrl' => $backUrl,
                    ]);
                } elseif ($requestType === 'complaint') {
                    return view('BBL_CI.complaint_type', [
                        "ci_token" => $CIToken,
                        'step1' => 'active',
                        'backUrl' => $backUrl,
                    ]);
                } elseif ($requestType === 'feedback') {
                    return view('BBL_CI.feedback_type', [
                        "ci_token" => $CIToken,
			'backUrl' => $backUrl,
                    ]);
                }
            } else {
                // return abort(403, 'Unauthorized User!');
                Log::error('error', ['message' => 'Unauthorized User!']);
                return abort(404, 'Unauthorized User!');
            }
        }

        Log::error('error', ['message' => 'Session Id not found!']);
        return abort(404, 'Session Id not found!');
        // return view('errors.errors_msg')->with('msg', 'Session Id not found!');
    }
    public function serviceDetails()
    {
        return view('BBL_CI.service_details');
    }

    public function sendBackDetails()
    {
        $ci_token = \request()->get('CIToken');
        $requestType = \request()->get('request_type');
        $check_token = TokenValidatedService::validatedToken($ci_token);
        if ($check_token) {
            $group_name = '';
            $sendBackTickets = WForm::with('ciTicketStatus', 'serviceName')
                ->where('w_form.SIF_Number', $check_token->cif_number)
                ->whereNotIn('w_form.w_form_type', [getId('BPID'), getId('AUCTION_REQUEST')])
                ->where(function ($query) {
                    $query->where('w_form.source', 'CI Web')
                        ->orWhere('w_form.source', 'CI App');
                })
                ->join('reference', 'w_form.reference_number', 'reference.reference_number')
                ->where('reference.form_status', 7)
                ->orderBy('w_form.id', 'DESC')
                ->paginate(10);

            $group_name = \Illuminate\Support\Facades\DB::table('group_info')->where('id', 195)->first(['name']);

            $backUrl = url('/') . '/CI/service-type/?CIToken=' . $ci_token . '&request_type=' . $requestType;
            return view('BBL_CI.send_back_details', compact('sendBackTickets', 'ci_token', 'backUrl', 'group_name'));
        }
        return view('errors.errors_msg')->with('msg', 'invalid access token.');
    }

    public function complaintSendBackStatus()
    {
        $ci_token = \request()->get('CIToken');
        $requestType = \request()->get('request_type');
        $check_token = TokenValidatedService::validatedToken($ci_token);
        if ($check_token) {
            $group_name = '';
            $sendBackTickets = Complaint::with('ciTicketStatus', 'serviceName')
                ->where('complaint.SIF_Number', $check_token->cif_number)
                ->where(function ($query) {
                    $query->where('complaint.source', 'CI Web')
                        ->orWhere('complaint.source', 'CI App');
                })
                ->join('reference', 'complaint.reference_number', 'reference.reference_number')
                ->where('reference.form_status', 7)
                ->orderBy('complaint.id', 'DESC')
                ->paginate(10);

            $group_name = \Illuminate\Support\Facades\DB::table('group_info')->where('id', 195)->first(['name']);

            $backUrl = url('/') . '/CI/service-type/?CIToken=' . $ci_token . '&request_type=' . $requestType;
            return view('BBL_CI.complaint.send_back_details', compact('sendBackTickets', 'ci_token', 'backUrl', 'group_name'));
        }
        return view('errors.errors_msg')->with('msg', 'invalid access token.');
    }

    public function ticketStatusDetails()
    {
        $ci_token = \request()->get('CIToken');
        $requestType = \request()->get('request_type');
        $check_token = TokenValidatedService::validatedToken($ci_token);
        if ($check_token) {
            $group_name = '';
            $ticketStatus = WForm::with('serviceName')
                ->where('w_form.SIF_Number', $check_token->cif_number)
                ->whereNotIn('w_form.w_form_type', [getId('BPID'), getId('AUCTION_REQUEST')])
                ->where(function ($query) {
                    $query->where('w_form.source', 'CI Web')
                        ->orWhere('w_form.source', 'CI App');
                })
                ->join('reference', 'w_form.reference_number', 'reference.reference_number')
                ->where('reference.form_status', '<>', -7)
                ->orderBy('w_form.id', 'DESC')
                ->paginate(10);

            $group_name = \Illuminate\Support\Facades\DB::table('group_info')->where('id', 195)->first(['name']);

            $backUrl = url('/') . '/CI/service-type/?CIToken=' . $ci_token . '&request_type=' . $requestType;

            return view('BBL_CI.ticket_status_details', compact('ticketStatus', 'ci_token', 'backUrl', 'group_name'));
        }
        return view('errors.errors_msg')->with('msg', 'invalid access token.');
    }

    public function comaplaintTicketStatus()
    {
        $ci_token = \request()->get('CIToken');
        $requestType = \request()->get('request_type');
        $check_token = TokenValidatedService::validatedToken($ci_token);
        if ($check_token) {
            $group_name = '';
            $ticketStatus = Complaint::with('serviceName')
                ->where('complaint.SIF_Number', $check_token->cif_number)
                ->where(function ($query) {
                    $query->where('complaint.source', 'CI Web')
                        ->orWhere('complaint.source', 'CI App');
                })
                ->join('reference', 'complaint.reference_number', 'reference.reference_number')
                ->where('reference.form_status', '<>', -7)
                ->orderBy('complaint.id', 'DESC')
                ->paginate(10);
            $group_name = \Illuminate\Support\Facades\DB::table('group_info')->where('id', 195)->first(['name']);
            $backUrl = url('/') . '/CI/service-type/?CIToken=' . $ci_token . '&request_type=' . $requestType;
            return view('BBL_CI.complaint.ticket_status_details', compact('ticketStatus', 'ci_token', 'backUrl', 'group_name'));
        }
        return view('errors.errors_msg')->with('msg', 'invalid access token.');
    }

    public function accountVerify(Request $request, $product_type, $request_type)
    {
        if ($product_type == 4) {
            $type = 'Loan Account';
        } elseif ($product_type == 3) {
            $type = 'Debit Card';
        } elseif ($product_type == 1) {
            $type = 'Credit Card';
        } else {
            $type = 'Account';
        }

        $isEmailOtp = 1;
        $ci_token = \request()->get('CIToken');
        $requestType = request()->segment(5);
        $check_token = TokenValidatedService::validatedToken($ci_token);

        // prd($check_token);
        if ($check_token) {

            /*$email_address = $check_token->email;
            $mobile_number = $check_token->mobile_no;
            $cif_number = $check_token->cif_number;

            // Valid Email Checking
            $validator = validator(['email' => $email_address], ['email' => 'required|email']);
            if($validator->fails()){
                $isEmailOtp = 0;
            };*/

            $response = CICURLService::ciCurl($check_token->cif_number, $product_type);


            $response = $response->getContent();
            $response = json_decode($response, true);
            $accountNumbers = $response['accountNumbers'];

            session()->forget('accountNumbers');
            session()->put('accountNumbers', $accountNumbers);


            $api_response = $response['data'];

            if ($accountNumbers) {
                $unit_items = UnitItem::where('unit_items.product_type_id', $product_type)
                    ->join('issue_workflows', 'unit_items.id', 'issue_workflows.issue_id')
                    ->join('issue_group_workflows', 'issue_workflows.issue_workflow_id', 'issue_group_workflows.issue_workflow_id')
                    ->where('issue_group_workflows.group_info_id', 195)
                    ->whereNotIn('unit_items.id', [getId('BPID'), getId('AUCTION_REQUEST')]);

                if ($request_type == 'complaint') {
                    $unit_items = $unit_items->where('unit_items.issues_from', 'complaint');
                } else {
                    $unit_items = $unit_items->where('unit_items.issues_from', 'wform');
                }
                $unit_items = $unit_items->where('unit_items.status', 1)
                    ->where('unit_items.is_ci', 1)
                    ->orderBy('name', 'asc')
                    ->get(['unit_items.id', 'unit_items.name', 'unit_items.is_ci'])
                    ->toArray();
                $issue_fields = [];
                $attachment_item = "";
                $backUrl = url('/') . '/CI/service-type/?CIToken=' . $ci_token . '&request_type=' . $requestType;
                $data = [
                    'issue_fields' => $issue_fields,
                    'is_send_back' => 0,
                    'unit_items' => $unit_items,
                    'product_type' => $product_type,
                    'attachment_item' => $attachment_item,
                    'ci_token' => $ci_token,
                    'backUrl' => $backUrl,
                    'accountNumbers' => $accountNumbers,
                    'api_response' => encrypt($api_response),
                    'isEmailOtp' => $isEmailOtp,
                    'request_type' => $request_type,
                ];
                return view('BBL_CI.account_verify', $data);
            } else {
                return redirect()->back()->with('warning', 'No ' . $type . ' info found against this Id!');
            }
        }
        return redirect()->back()->with('warning', 'Your session is invalid or expired');
    }

    public function otpSubmitPage(Request $request)
    {
        // $data = $request->all();
        $data = $request->get('data');
        // dd($data);
        return view('BBL_CI.partials.success_page', ['data' => $data]);
    }

    public function requestPage(Request $request)
    {
        // $data = $request->all();
        $data = $request->get('data');
        return view('BBL_CI.partials.request_page', ['data' => $data]);
    }

    public function OtpVerifyPage(Request $request)
    {
        $data = $request->get('data');
        // dd($data);
        return view('BBL_CI.partials.otp_page', ['data' => $data]);
    }

    public function issueFormField(Request $request)
    {
        /* zihad */
        $request_for = $request->request_for;
        $fields = FieldSetGroupService::getFieldSet($request->issue_id);
        if ($request_for == 'web') {
            return view('BBL_CI.partials.extra_form_field_with_group', ['issue_fields' => $fields]);
        } else {
            return view('BBL_CI.partials.extra_form_field_with_group_app', ['issue_fields' => $fields]);
        }
    }

    public function otpReGenerate(Request $request)
    {
        if ($request->otpGenId != null && gettype((int) $request->otpGenId) == 'integer') {

            $ci_token = $request->ci_token;
            $check_token = TokenValidatedService::validatedToken($ci_token);
            $email_address = $check_token->email;
            $oldOtpObj = OtpCode::find(decrypt($request->otpGenId));
            $oldOtpObj->status = 0;
            $oldOtpObj->save();
            //$otpCode = null;
            $dataArr = OTPGenerateService::otpCodeGenerate($oldOtpObj->cif_number, $oldOtpObj->service_type, $oldOtpObj->product_type, $oldOtpObj->account_number, $oldOtpObj->reference_number, $oldOtpObj->mobile, $email_address, $request->otp_mode);
            $otpGenId = $dataArr['otpGenId'];
            $otpCode = $dataArr['otpCode'];

            return response()->json(['otpGenId' => $otpGenId, 'invalidCount' => 0]);
        } else {
            return response()->json('not found');
        }
    }

    public function sendBackNotifyWithReason($reference_number, $issue_id, $mobile_no, $email)
    {
        $outgoingSMSMessage = $this->outgoingSMSEmail("sendbackWithReason", $issue_id, $reference_number, "notification", "");
        if (!empty($outgoingSMSMessage['sms']) && !empty($mobile_no)) {
            $this->sendBackSMS($mobile_no, $outgoingSMSMessage['sms'], $reference_number, 0, 1);
        }
        if (!empty($outgoingSMSMessage['mail']) && !empty($email)) {
            $this->sendBackEMAIL($email, $outgoingSMSMessage['mail'], $reference_number, 0, "notification", 1);
        }
    }

    public function sendBackNotifyWithOutReason($reference_number, $issue_id, $mobile_no, $email)
    {
        $outgoingSMSMessage = $this->outgoingSMSEmail("sendbackWithOutReason", $issue_id, $reference_number, "notification", $name = "");
        if (!empty($outgoingSMSMessage['sms']) && !empty($mobile_no)) {
            $this->sendBackSMS($mobile_no, $outgoingSMSMessage['sms'], $reference_number, 0, 1);
        }
        if (!empty($outgoingSMSMessage['mail']) && !empty($email)) {
            $this->sendBackEMAIL($email, $outgoingSMSMessage['mail'], $reference_number, 0, "notification", 1);
        }
    }

    public function sendBackTicketClosedAfter24hour($reference_number, $issue_id, $mobile_no, $email)
    {
        $outgoingSMSMessage['sms'] = "If the form is not submitted within the next 24 hours, the ticket will be automatically closed";
        $outgoingSMSMessage['mail'] = "If the form is not submitted within the next 24 hours, the ticket will be automatically closed";
        if (!empty($outgoingSMSMessage['sms']) && !empty($mobile_no)) {
            $this->sendBackSMS($mobile_no, $outgoingSMSMessage['sms'], $reference_number, 0, 1);
        }
        if (!empty($outgoingSMSMessage['mail']) && !empty($email)) {
            $this->sendBackEMAIL($email, $outgoingSMSMessage['mail'], $reference_number, 0, "notification", 1);
        }
    }

    public function otpSubmit(Request $request)
    {
        // dd("ok");
        $inputedOtpCode = $request->otp1 . $request->otp2 . $request->otp3 . $request->otp4 . $request->otp5 . $request->otp6 . $request->otp7;
        $otpCode = $request->otpCode;
        $otpGenId = $request->otp_auto_id;
        $findOtp = OtpCode::where('id', decrypt($otpGenId))
            // ->where('otp', encrypt($inputedOtpCode))
            // ->where('expire_at', '>=', date('Y-m-d H:i:s', strtotime(Carbon::now())))
            // ->where('status', 1)
            ->first();
        $ci_token = $request->ci_token;
        $check_token = TokenValidatedService::validatedToken($ci_token);
        $requestType = \request()->get('request_type');
        if ($check_token) {
            $reference_number = $request->reference_number;
            $product_type = $request->product_type;
            $mobile_number = $check_token->mobile_no;
            $email_address = $check_token->email;
            $backUrl = url('/') . '/CI/service/?CIToken=' . $ci_token;

            // for BPID Ticket Status URL
            if ($request->request_type == getId('BPID')) {
                $ticketStatusUrl = url('/') . '/BPID/ticket-status'
                    . '?CIToken=' . $ci_token
                    . '&request_type=' . $requestType;
            } elseif ($request->request_type == getId('AUCTION_REQUEST')) {
                $ticketStatusUrl = url('/') . '/BPID/ticket-status'
                    . '?CIToken=' . $ci_token
                    . '&request_type=' . $requestType;
            }
            // for BPID Ticket Status URL
            elseif ($request->request_type == 'complaint') {
                $ticketStatusUrl = url('/') . '/CI/complaint-ticket-status?CIToken=' . $ci_token . '&request_type=' . $requestType;
            } else {                
                $ticketStatusUrl = url('/') . '/CI/ticket/status/details?CIToken=' . $ci_token . '&request_type=' . $requestType;
            }
            $CIUrl = '';
            try {
                $encryptOtp = decrypt($findOtp->otp);
                $token = $ci_token;
                $callbackUrl = \App\CustomerInterfaceToken::where('token', $token)->first('callback_url');
                $CIUrl = $callbackUrl['callback_url'];
            } catch (Throwable $e) {
                return response()->json(['otpMessage' => $e->getMessage()]);
            }

            if ($findOtp && $encryptOtp == $inputedOtpCode) {

                $reqDate = date("Y-m-d H:i:s", strtotime($request->currentTime));

                if ($findOtp->expire_at < date('Y-m-d H:i:s', strtotime(Carbon::now()))) {
                    $findOtp->status = 0;
                    $findOtp->otp_status = 'expired';
                    $findOtp->save();
                    return response()->json(['otpMessage' => 'OTP code is expired !']);
                }
                $findOtp->status = 0;
                $findOtp->otp_status = 'verified';
                $findOtp->save();
                $form_status = '';
                $referenceModelName = Reference::where('reference_number', $reference_number)->first();
                $form_status = $referenceModelName->form_status;
                $referenceModelName->form_status = null;
                $issue_from = $referenceModelName->issues_from;

                $unitItemModelName = new UnitItem;
                if ($issue_from == 'wform') {
                    $requestTypeForm = WForm::where('reference_number', $reference_number)->first();
                } else {
                    $requestTypeForm = Complaint::where('reference_number', $reference_number)->first();
                }
                $issue_name = '';
                if (!empty($referenceModelName->save()) && !empty($requestTypeForm->account_number)) {
                    $unitItemData = $unitItemModelName
                        ->where("issues_from", $issue_from == 'wform' ? "wform" : "complaint")
                        ->where("master_id", $referenceModelName->issue_id)
                        ->first();
                    /*$workflow = IssueWorkflow::where('issue_id', $unitItemData->id)->first();*/

                    // if (!empty($workflow['log'] == 1)) {
                    $outgoingSMSMessage = $this->outgoingSMSEmail($issue_from, $product_type, $reference_number, "open", $unitItemData['name']);
                    if (!empty($outgoingSMSMessage['sms'])) {
                        if (!empty($mobile_number)) {
                            $this->sendSMS($mobile_number, $outgoingSMSMessage['sms'], $reference_number, 0);
                        }
                    }
                    if (!empty($outgoingSMSMessage['mail'])) {
                        if (!empty($email_address)) {
                            $this->sendEMAIL($email_address, $outgoingSMSMessage['mail'], $reference_number, 0);
                        }
                    }
                    // }

                    $issue_name = $unitItemData->name;
                    //CIF API calling for STP Update
                    if (!empty($unitItemData) && $issue_from == 'wform') {
                        $is_ci_cif = $unitItemData->is_ci_cif;
                        // dd($is_ci_cif);
                        if ($is_ci_cif == 1) {
                            /* ---- CIF API calling for STP Update ---*/
                            $payload = [
                                'ref_no' => encrypt($reference_number),
                                'cif_no' => encrypt($check_token->cif_number),
                                'account_number' => encrypt($requestTypeForm->account_number),
                                'req_from' => 'wform',
                                'failed_api' => [],
                            ];

                            $cifResponse = CIFModificationService::apiUpdate($payload)->getContent();
                            // dd($cifResponse);
                            $cifResponse = json_decode($cifResponse);

                            if ($cifResponse !== null && isset($cifResponse->status) && $cifResponse->status === 1) {
                                // dd("if");
                                $referenceModelName->form_status = 11;
                                $referenceModelName->unit_id = 1;
                                $referenceModelName->subgroup_id = 195;
                                $referenceModelName->sub_group_info_id = 557;
                            } else {
                                // dd("else");
                                $referenceModelName->form_status = 0;
                                // Local CI User ID CI & UAT CI
                                $this->audit(['reference_number' => $reference_number, 'unit_id' => 1, 'group_id' => 195, 'user_id' => 'CI', 'action' => 'CIF API Calling Failed', 'comments' => 'STP Failed so sent to CI checker', 'isapproved' => 0, 'subgroup_id' => 557]);
                            }
                            $referenceModelName->save();
                        }
                    }
                }
                if ($form_status == 7) {
                    $this->audit(['reference_number' => $reference_number, 'unit_id' => 1, 'group_id' => '', 'user_id' => 'CI', 'action' => 'Sentback Ticket re-submitted', 'comments' => '', 'isapproved' => '1', 'subgroup_id' => '']);
                }
                if ($request->request_mode_in_otp == "app") {
                    // dd("here");
                    return response()->json([
                        'CIUrl' => $CIUrl,
                        'ticketStatusUrl' => $ticketStatusUrl,
                        'product_type' => $product_type,
                        'request_type' => $request->request_type,
                        'reference_number' => $reference_number,
                        'ci_token' => $ci_token,
                        'backUrl' => $backUrl,
                        'otpGenId' => $otpGenId,
                        'issue_name' => $issue_name,
                        'success' => true,
                    ]);
                } else {
                    // dd("there");
                    return response()->json([
                        'product_type' => $product_type,
                        'request_type' => $request->request_type,
                        'reference_number' => $reference_number,
                        'ci_token' => $ci_token,
                        'backUrl' => $backUrl,
                        'otpGenId' => $otpGenId,
                        'issue_name' => $issue_name,
                        'step3' => 'active',
                        'success' => true,
                    ]);
                }

            } else {
                $invalidCount = $request->invalidCount + 1;
                return response()->json(['otpMessage' => 'Invalid OTP Code! Verify will disable if wrong OTP is provided 3 times', 'invalidCount' => $invalidCount]);

            }
        }
        return view('errors.errors_msg')->with('msg', 'invalid access token.');
    }

    public function sendBackTicket($issueId, $refNum, $viewMode)
    {
        $ci_token = \request()->get('CIToken');
        $requestType = \request()->get('request_type');
        $check_token = TokenValidatedService::validatedToken($ci_token);
        if ($check_token) {
            $service_name = UnitItem::where('id', $issueId)
                ->where('issues_from', 'wform')
                ->where('status', 1)
                ->first();
            $accNum = WForm::where('reference_number', $refNum)->first(['account_number', 'mask_card_no']);

            $product_type = $service_name->product_type_id;

            $unit_items = UnitItem::where('product_type_id', $product_type)
                ->where('issues_from', 'wform')
                ->where('status', 1)
                ->get(['id', 'name'])
                ->toArray();

            // Field
            $fields = FieldSetGroupService::getFieldSet($issueId);

            $extra_fields = WFormType::where('reference_number', $refNum)->first('extra_field');
            $extra_fieldsArray = ((array) json_decode($extra_fields->extra_field, true));
            $arraySingle = [];
            if (!empty($extra_fieldsArray)) {
                $arraySingle = call_user_func_array('array_merge', $extra_fieldsArray);
            }

            $issue_fields = [];
            $attachment_item = "";
            $isEmailOtp = 1;

            $attachment_item = IssueAttachmentConfig::where('issue_id', $issueId)
                ->orderBy('order_by', "ASC")
                ->get();
            $uploadedAttachment = Attachment::where('reference_number', $refNum)->get(['id', 'file_name', 'name']);

            // Valid Email Checking
            $validator = validator(['email' => $check_token->email], ['email' => 'required|email']);
            if ($validator->fails()) {
                $isEmailOtp = 0;
            }

            $backUrl = url('/') . '/CI/send-back/details/?CIToken=' . $ci_token . '&request_type=' . $requestType;

            $data = [
                'accNumber' => $accNum,
                'service_name' => $service_name,
                'refNum' => $refNum,
                'unit_items' => $unit_items,
                'issue_fields' => $issue_fields,
                'attachment_item' => $attachment_item,
                'product_type' => $product_type,
                'fields' => $fields,
                'extra_fieldsArray' => $extra_fieldsArray,
                'arraySingle' => $arraySingle,
                'mobile_no' => maskPhoneNumber($check_token->mobile_no),
                'ci_token' => $ci_token,
                'viewMode' => $viewMode,
                'isEmailOtp' => $isEmailOtp,
                'is_send_back' => 1,
                'uploadedAttachment' => $uploadedAttachment,
                'backUrl' => $backUrl,

            ];

            return view('BBL_CI.send_back_ticket', $data);
        }

        return view('errors.errors_msg')->with('msg', 'invalid access token.');
    }

    public function complaintSendBackTicket($issueId, $refNum, $viewMode)
    {
        $ci_token = \request()->get('CIToken');
        $requestType = \request()->get('request_type');
        $check_token = TokenValidatedService::validatedToken($ci_token);
        if ($check_token) {
            $service_name = UnitItem::where('id', $issueId)
                ->where('issues_from', 'complaint')
                ->where('status', 1)
                ->first();
            $accNum = Complaint::where('reference_number', $refNum)->first(['account_number', 'mask_card_no']);

            $product_type = $service_name->product_type_id;

            $unit_items = UnitItem::where('product_type_id', $product_type)
                ->where('issues_from', 'complaint')
                ->where('status', 1)
                ->get(['id', 'name'])
                ->toArray();
            // Field
            $fields = FieldSetGroupService::getFieldSet($issueId);

            $extra_fields = ComplaintFormType::where('reference_number', $refNum)->first('extra_field');
            $extra_fieldsArray = ((array) json_decode($extra_fields->extra_field, true));
            $arraySingle = [];
            if (!empty($extra_fieldsArray)) {
                $arraySingle = call_user_func_array('array_merge', $extra_fieldsArray);
            }

            $issue_fields = [];
            $attachment_item = "";
            $isEmailOtp = 1;

            $attachment_item = IssueAttachmentConfig::where('issue_id', $issueId)
                ->orderBy('order_by', "ASC")
                ->get();
            $uploadedAttachment = Attachment::where('reference_number', $refNum)->get(['id', 'file_name', 'name']);

            // Valid Email Checking
            $validator = validator(['email' => $check_token->email], ['email' => 'required|email']);
            if ($validator->fails()) {
                $isEmailOtp = 0;
            }

            $backUrl = url('/') . '/CI/complaint-sendback-status/?CIToken=' . $ci_token . '&request_type=' . $requestType;

            $data = [
                'accNumber' => $accNum,
                'service_name' => $service_name,
                'refNum' => $refNum,
                'unit_items' => $unit_items,
                'issue_fields' => $issue_fields,
                'attachment_item' => $attachment_item,
                'product_type' => $product_type,
                'fields' => $fields,
                'extra_fieldsArray' => $extra_fieldsArray,
                'arraySingle' => $arraySingle,
                'mobile_no' => maskPhoneNumber($check_token->mobile_no),
                'ci_token' => $ci_token,
                'viewMode' => $viewMode,
                'isEmailOtp' => $isEmailOtp,
                'is_send_back' => 1,
                'uploadedAttachment' => $uploadedAttachment,
                'backUrl' => $backUrl,

            ];

            return view('BBL_CI.complaint.send_back_ticket', $data);
        }

        return view('errors.errors_msg')->with('msg', 'invalid access token.');
    }

    private function sameIssueRequestCheck($accountNumbers, $issue_id)
    {
        $sameIssueRequestFound = true;
        $unitItems = UnitItem::select('master_id', 'issues_from')->where('master_id', $issue_id)->first();

        if ($unitItems->issues_from == 'wform') {
            $findSameUserIssue = DB::table('w_form')
                ->leftJoin('reference', 'w_form.reference_number', 'reference.reference_number')
                ->select('w_form.reference_number')
                ->where('w_form.account_number', '=', $accountNumbers)
                ->where('w_form.w_form_type', '=', $issue_id)
                ->whereNotIn('reference.form_status', [11, -7])
                ->first();

            if (empty($findSameUserIssue)) {
                $sameIssueRequestFound  = false;
            }
        } elseif ($unitItems->issues_from == 'complaint') {
            $findSameUserIssue = DB::table('complaint')
                ->leftJoin('reference', 'complaint.reference_number', 'reference.reference_number')
                ->select('complaint.reference_number')
                ->where('complaint.account_number', '=', $accountNumbers)
                ->where('complaint.complaint_type', '=', $issue_id)
                ->whereNotIn('reference.form_status', [11, -7])
                ->first();

            if (empty($findSameUserIssue)) {
                $sameIssueRequestFound  = false;
            }
        }

        return $sameIssueRequestFound;
    }

    public function CIWFormSubmit(CIWFormRequest $request)
    {
        ($request->request_mode == "app") ? $source = 'CI App' : $source = 'CI Web';
        $ci_token = $request->ci_token;
        $requestType = \request()->get('request_type');
        $check_token = TokenValidatedService::validatedToken($ci_token);

         $sessionAccounts = session()->get('accountNumbers');
         $requestAccount  = $request->account_number;

        // if (!array_key_exists($requestAccount, $sessionAccounts)) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Invalid account number selected.'
        //     ], 404);
        // }
        
        if ($check_token && $request->api_response && $request->account_number && $request->product_type) {
	        $sameIssueRequest = $this->sameIssueRequestCheck($request->account_number, $request->w_form_type);
            if($sameIssueRequest){
                return response()->json([
                    'errorType' => '2',
                    'success' => false,
                    'message' => 'You have already submit a request with this issue, Please wait for while we process your request.',
                ], 404);
            }
            $api_response = decrypt($request->api_response);
            $response = CICURLService::apiResponse($api_response, $request->account_number, $request->product_type);
            $response = $response->getContent();
            $response = json_decode($response, true);
            $customer_name = $response['accountHolderName'];
            /* $maskedCardNumber = $response['maskedCardNumber'];*/
            $cif_number = $check_token->cif_number;
            $mobile_number = $check_token->mobile_no ?? '';
            $email_address = $check_token->email ?? '';
            $product_type = $request->product_type;
            $extra_field = '';
            $otpGenId = null;
            $otpCode = null;
	    
	        $prodTypeAlpha = "";
            if ($product_type == 1) {
                $prodTypeAlpha = "CC";
            } elseif ($product_type == 2) {
            	$prodTypeAlpha = "AC";
            } elseif ($product_type == 3) {
            	$prodTypeAlpha = "DC";
            } elseif ($product_type == 4) {
            	$prodTypeAlpha = "LN";
            } elseif ($product_type == 5) {
                $prodTypeAlpha = "TR";
            }
            $reference_number = "SA" . date("ymd") . $prodTypeAlpha . userIdPadLeftWith0($this->dayWiseSequence('sr'), 6, '0');
	    
            $docDestPath = base_path("../public/attachments");

            // BPID Attachment upload
            DB::beginTransaction();

            try {
                if (!File::exists($docDestPath)) {
                    File::makeDirectory($docDestPath, 0755, true, true);
                }

                if ($request->w_form_type == getId('BPID')) {

                    foreach ($request->allFiles() as $field => $file) {
                        if (!$file->isValid()) {
                            throw new \Exception("Field {$field} has an invalid file upload. Error code: " . $file->getError());
                        }

                        $fileName = $field . '_attach_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                        try {
                            $file->move($docDestPath, $fileName);
                        } catch (\Throwable $e) {
                            Log::error('attachment_error : ', [$e->getMessage()]);
                            throw new \Exception("Failed to save file '{$fileName}' on disk ");
                        }

                        $filePath = $docDestPath . '/' . $fileName;
                        if (!file_exists($filePath)) {
                            throw new \Exception("Attachment file cannot be saved!");
                        }

                        Attachment::create([
                            'file_name'        => $fileName,
                            'reference_number' => $reference_number,
                            'attachment_date'  => now()->toDateString(),
                            'uploaded_by'      => auth()->id(),
                            'name'             => ucwords(str_replace('_', ' ', $field)),
                        ]);

                        //$file->move($docDestPath, $fileName);
                    }
                } else {

                    if ($request->file_name) {
                        $docDestPath = base_path("../public/attachments");
                        foreach ($request->file_name as $key => $row) {
                            if (array_key_exists('file', $row)) {
                                $extension = $row['file']->getClientOriginalExtension();
                                $origin_name = pathinfo($row['file']->getClientOriginalName(), PATHINFO_FILENAME);
                                $origin_name = str_replace(' ', '_', $origin_name);
                                $origin_name = substr($origin_name, 0, 20);
                                $fileName = $origin_name . "_attach_nX_" . round(microtime(true) * 10) . "_" . ($key + 1) . '.' . $extension;
                                $attachment = new Attachment();
                                $attachment->file_name = $fileName;
                                $attachment->name = $row['name'];
                                $attachment->reference_number = $reference_number;
                                $attachment->attachment_date = date('Y-m-d');
                                $attachment->uploaded_by = $cif_number;
                                $attachment->save();
                                //$files->move($docDestPath, $fileName);
                                //$fileContent = File::get($row['file']->getRealPath());

                                //Storage::disk('custom_storage')->put($fileName, $fileContent);

                                //Upload File to external server UAT & LIVE
                                try {
                                    $row['file']->move($docDestPath, $fileName);
                                } catch (Throwable $e) {
                                    Log::error('attachment_error : ', [$e->getMessage()]);
                                }
                            }
                        }

                        try {
                            $attachments = Attachment::where('reference_number', $reference_number)->get();
                            foreach ($attachments as $attachment) {
                                //UAT
                                //$filePath = Storage::disk('custom_storage')->path($attachment->file_name);
                                //Live
                                $filePath = $docDestPath . '/' . $attachment->file_name;
                    
                                if (!file_exists($filePath)) {
                                    // delete attachment data
                                    Attachment::where('reference_number', $reference_number)->delete();
                                    throw new \Exception('Attachment file cannot be saved!');
                                }
                            }
                        } catch (\Exception $e) {
                            Log::error('attachment-move-error', [$e->getMessage()]);
                            return response()->json([
                                'file_storage_error' => true,
                                'success' => false,
                                'message' => $e->getMessage(),
                            ], 500);
                        }
                    }
                }

                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();

                Log::error('Attachment Upload Error: ' . $e->getMessage(), [
                    'exception' => $e,
                    'reference_number' => $reference_number
                ]);

                return response()->json([
                    'file_storage_error' => true,
                    'success' => false,
                    'message' => 'Upload failed: ' . $e->getMessage(),
                ], 500);
            }

            if ($request->isMethod('post')) {
                $issue_config = IssueConfig::where('issue_id', $request->w_form_type)->get();
                if (count($issue_config) != 0) {
                    foreach ($issue_config as $issue_con) {
                        if ($request[$issue_con->field_name] != null) {
                            $dataName[] = [$issue_con->label_name => $request[$issue_con->field_name]];
                        } else {
                            $dataName[] = [$issue_con->label_name => ''];
                        }
                    }
                    $extra_field = json_encode($dataName);
                }
                $unitItemModelName = new UnitItem;
                $unitItemData = $unitItemModelName->select("id", "unit_id", "is_sent_sms", "name")
                    ->where("master_id", $request->w_form_type)
                    ->where("issues_from", "wform")
                    ->first();
                $workflowlist = "";
                $workflow = IssueWorkflow::where('issue_id', $unitItemData->id)->first();
                // Local Customer Interface Group ID Local 162 & UAT 185 & LIVE 186
                $firstWorkFlow = IssueGroupWorkflow::where('group_info_id', 195)
                    ->where('issue_workflow_id', $workflow->issue_workflow_id)
                    ->first();
                if ($workflow->flow_type == FlowEnum::REGULAR) {
                    if ($firstWorkFlow->touch_checker == 1) {
                        $subgroup_id = $firstWorkFlow->group_info_id;
                        $next_label = $firstWorkFlow->touch_checker;
                        $unit_label = 2;
                    } else {
                        $workflowlist = IssueGroupWorkflow::where('issue_workflow_id', $workflow->issue_workflow_id)
                            ->where('is_touch_point', '<>', 1)
                            ->orderBy('issue_group_workflow_id', 'ASC')
                            ->first();
                        if ($workflowlist->touch_maker == 1) {
                            $subgroup_id = $workflowlist->group_info_id;
                            $next_label = $workflowlist->touch_maker;
                            $unit_label = 1; //'1' mean Maker
                        } else {
                            $subgroup_id = $workflowlist->group_info_id;
                            $next_label = $workflowlist->touch_maker;
                            $unit_label = 2; //'2' mean Checker
                        }
                    }
                }
                if ($workflow->flow_type == FlowEnum::FORWARD) {
                    $subgroup_id = $firstWorkFlow->group_info_id;
                    $next_label = $firstWorkFlow->touch_checker;
                    $unit_label = 2;
                }
                $referenceModelName = new Reference;
                if ($referenceModelName->save()) {
                    $issueId = (!empty($workflow->issue_id)) ? $workflow->issue_id : 0;
                    
                    $referenceModelName->reference_number = $reference_number;
                    $referenceModelName->unit_id = (!empty($unit_label)) ? $unit_label : 2;
                    // Local Customer Interface Group ID 162 & UAT 185
                    $referenceModelName->subgroup_id = (!empty($subgroup_id)) ? $subgroup_id : 195;
                    if (!empty($subgroup_id)) {
                        $subgroup_info_id = SubgroupInfo::where('group_info_id', $subgroup_id)->first();
                        // Local Customer Interface Sub-Group ID 367 & UAT 383
                        $referenceModelName->sub_group_info_id = (!empty($subgroup_info_id->id)) ? $subgroup_info_id->id : 557;
                    }
                    $referenceModelName->issue_id = (!empty($workflow->issue_id)) ? $workflow->issue_id : 0;
                    $referenceModelName->date = strtotime(date('d-m-Y h:i:s A'));
                    $referenceModelName->created_by = 'CI';
                    $referenceModelName->account_type = $product_type;
                    $referenceModelName->status = 47;
                    $referenceModelName->form_status = -7;
                    $referenceModelName->issues_from = 'wform';
                    $referenceModelName->save();

                    $wformModelName = new WForm;
                    $wformModelName->reference_number = $reference_number;
                    $wformModelName->customer_name = $customer_name;
                    $wformModelName->acc_name = $customer_name;
                    $wformModelName->mobile_number = $mobile_number;
                    $wformModelName->email_address = $email_address;
                    $wformModelName->product_type = $product_type;
                    $wformModelName->time_and_ext = date('d-m-Y h:i:s A');
                    $wformModelName->source = $source;
                    $wformModelName->SIF_Number = $cif_number;
                    $wformModelName->w_form_type = $request->w_form_type;

                    if ($product_type == 2 || $product_type == 4) {
                        $wformModelName->account_number = $request->account_number;
                    }
                    if ($product_type == 1 || $product_type == 3) {
                        $wformModelName->mask_card_no = $request->account_number;
                        $wformModelName->account_number = $request->account_number;
                    }
                    $wformModelName->save();

                    $w_form_type = $request->w_form_type;
                    $wformTypeModelName = new WFormType;
                    $wformTypeModelName->reference_number = $reference_number;
                    $wformTypeModelName->extra_field = $extra_field;
                    $wformTypeModelName->save();
		
                    $referenceModelName = Reference::where('reference_number', $reference_number)->first();
                    $issue_name = '';
                    $unitItemModelName = new UnitItem;
                    if (!empty($referenceModelName)) {
                        $unitItemData = $unitItemModelName
                            ->select("name")
                            ->where("issues_from", 'wform')
                            ->where("master_id", $referenceModelName->issue_id)
                            ->first();
                        if (!empty($unitItemData)) {
                            $issue_name = $unitItemData->name;
                        }
                    }


                    // OTP generate and send sms & email
                    if (!empty($mobile_number) && !empty($cif_number)) {
                        $dataArr = OTPGenerateService::otpCodeGenerate($cif_number, $request->w_form_type, $product_type, $request->account_number, $reference_number, $mobile_number, $email_address, $request->otp_mode);
                        $otpGenId = $dataArr['otpGenId'];
                        $otpCode = $dataArr['otpCode'];
                    }

                    // Local CI Group ID UAT 203 & LIVE 186
                    // Local CI Sub-Group ID UAT 383 & LIVE 710
                    // Local CI User ID UAT CI & LIVE CI
                    $this->audit(['reference_number' => $reference_number, 'unit_id' => 1, 'group_id' => 195, 'user_id' => 'CI', 'action' => 'Ticket Logged', 'comments' => '', 'isapproved' => '1', 'subgroup_id' => 557]);

                    $backUrl = url('/') . '/CI/service/?CIToken=' . $ci_token . '&request_type=' . $requestType;

                    if ($request->module == "BPID") {
                        /* ====================== Store bp id START ====================== */
                        if ($request->w_form_type == getId('BPID')) {
                            $bpId = new BpId();
                            $bpId->bp_id = null;
                            $bpId->account_number = $request->account_number;
                            $bpId->reference_number = $reference_number;
                            $bpId->branch_name = $request->branch_name;
                            $bpId->account_title = $request->account_name;

                            $bpId->contact_no_1 = $request->first_app_contact_no ?? null;
                            $bpId->email_1 = $request->first_app_email ?? null;

                            $bpId->contact_no_2 = $request->second_app_contact_no ?? null;
                            $bpId->email_2 = $request->second_app_email ?? null;

                            $bpId->contact_no_3 = $request->third_app_contact_no ?? null;
                            $bpId->email_3 = $request->third_app_email ?? null;

                            $bpId->contact_no_4 = $request->four_app_contact_no ?? null;
                            $bpId->email_4 = $request->four_app_email ?? null;
                            $bpId->save();
                        }
                        /* ====================== Store bp id END ====================== */

                        $backUrl = url('/') . '/BPID/service/?CIToken=' . $ci_token . '&request_type=' . $requestType;
                    }

                    flash('Service Request have been saved successfully. Ticket No: ' . $reference_number, 'success');
                    $mobile_no = maskPhoneNumber($mobile_number);
                    $mask_email = maskEmail($email_address);
		   
                    if ($request->request_mode == "app") {
                        return response()->json([
                            'success' => true,
                            'message' => 'Created successfully!',
                            'product_type' => $product_type,
                            'request_type' => $request->request_type,
                            'reference_number' => $reference_number,
                            'ci_token' => $ci_token,
                            'backUrl' => $backUrl,
                            'otpGenId' => $otpGenId,
                            'issue_name' => $issue_name,
                            'mobile_no' => $mobile_no,
                            'mask_email' => $mask_email,
                            'is_send_back' => $request->is_send_back,
                            'otp_mode' => $request->otp_mode,
                            'invalidCount' => 0
                        ], 200);
                    } else {
                        return response()->json([
                            'success' => true,
                            'message' => 'Created successfully!',
                            'product_type' => $product_type,
                            'request_type' => $request->request_type,
                            'reference_number' => $reference_number,
                            'ci_token' => $ci_token,
                            'backUrl' => $backUrl,
                            'otpGenId' => $otpGenId,
                            'issue_name' => $issue_name,
                            'mobile_no' => $mobile_no,
                            'mask_email' => $mask_email,
                            'step2' => 'active',
                            'is_send_back' => $request->is_send_back,
                            'otp_mode' => $request->otp_mode,
                            'invalidCount' => 0
                        ], 200);
                    }
                } else {
                    flash('Failed to save data', 'danger');
                    return redirect()->back();
                }
            }
        }

        return view('errors.errors_msg')->with('msg', 'invalid access token.');
    }

    public function CIWFormUpdate(CIWFormRequest $request)
    {
	
        $ci_token = $request->ci_token;
        $requestType = \request()->get('request_type');
        $check_token = TokenValidatedService::validatedToken($ci_token);

        if ($check_token) {

            $cif_number = $check_token->cif_number;
            $mobile_number = $check_token->mobile_no;
            $email_address = $check_token->email;
            $otpGenId = null;
            $otpCode = null;
            $product_type = $request->product_type;
            $reference_number = $request->reference_number;
            $extra_field = '';

            if ($request->isMethod('post')) {
                $w_form_type = WFormType::where('reference_number', $reference_number)->first();
		
                WFormTypeHistory::create([
                    'reference_number' => $reference_number,
                    'extra_field' => $w_form_type->extra_field,
                    'check_list' => $w_form_type->check_list,
                    'user_id' => $cif_number,
                ]);
		
                $referenceModelName = Reference::where('reference_number', $reference_number)->first();
                $issue_config = IssueConfig::where('issue_id', $referenceModelName->issue_id)->get();
                if (count($issue_config) != 0) {
                    foreach ($issue_config as $issue_con) {
                        if ($request[$issue_con->field_name] != null) {
                            $dataName[] = [$issue_con->label_name => $request[$issue_con->field_name]];
                        } else {
                            $dataName[] = [$issue_con->label_name => ''];
                        }
                    }
                    $extra_field = json_encode($dataName);
                }
                $unitItemModelName = new UnitItem;
                $unitItemData = $unitItemModelName->select("id", "unit_id", "is_sent_sms", "name")
                    ->where("master_id", $request->w_form_type)
                    ->where("issues_from", "wform")
                    ->first();
                $workflowlist = "";
                $workflow = IssueWorkflow::where('issue_id', $unitItemData->id)->first();
                // Local Customer Interface Group ID UAT 203 & LIVE 186
                $firstWorkFlow = IssueGroupWorkflow::where('group_info_id', 195)
                    ->where('issue_workflow_id', $workflow->issue_workflow_id)
                    ->first();
                if ($workflow->flow_type == FlowEnum::REGULAR) {
                    if ($firstWorkFlow->touch_checker == 1) {
                        $subgroup_id = $firstWorkFlow->group_info_id;
                        $next_label = $firstWorkFlow->touch_checker;
                        $unit_label = 2;
                    } else {
                        $workflowlist = IssueGroupWorkflow::where('issue_workflow_id', $workflow->issue_workflow_id)
                            ->where('is_touch_point', '<>', 1)
                            ->orderBy('issue_group_workflow_id', 'ASC')
                            ->first();
                        if ($workflowlist->touch_maker == 1) {
                            $subgroup_id = $workflowlist->group_info_id;
                            $next_label = $workflowlist->touch_maker;
                            $unit_label = 1; //'1' mean Maker
                        } else {
                            $subgroup_id = $workflowlist->group_info_id;
                            $next_label = $workflowlist->touch_maker;
                            $unit_label = 2; //'2' mean Checker
                        }
                    }
                }
                if ($workflow->flow_type == FlowEnum::FORWARD) {
                    $subgroup_id = $firstWorkFlow->group_info_id;
                    $next_label = $firstWorkFlow->touch_checker;
                    $unit_label = 2;
                }
                if (!empty($referenceModelName)) {
                    $referenceModelName->unit_id = (!empty($unit_label)) ? $unit_label : 2;
                    $referenceModelName->subgroup_id = (!empty($subgroup_id)) ? $subgroup_id : 195; // CI Group ID UAT 203 & LIVE 186
                    if (!empty($subgroup_id)) {
                        $subgroup_info_id = SubgroupInfo::where('group_info_id', $subgroup_id)->first();
                        $referenceModelName->sub_group_info_id = (!empty($subgroup_info_id->id)) ? $subgroup_info_id->id : 557; // CI Group ID UAT 383 & LIVE 710
                    }
                    $referenceModelName->save();

                    $wformTypeModelName = WFormType::where('reference_number', $reference_number)->first();
                    if (!empty($extra_field)) {
                        $wformTypeModelName->extra_field = $extra_field;
                    }
                    $wformTypeModelName->save();

                    $issue_name = '';
                    $unitItemModelName = new UnitItem;
                    $unitItemData = $unitItemModelName
                        ->select("name")
                        ->where("issues_from", 'wform')
                        ->where("master_id", $referenceModelName->issue_id)
                        ->first();
                    if (!empty($unitItemData)) {
                        $issue_name = $unitItemData->name;
                    }
		    
                    if (!empty($mobile_number) && !empty($cif_number)) {
                        $dataArr = OTPGenerateService::otpCodeGenerate($cif_number, $request->w_form_type, $request->product_type, $request->account_number, $reference_number, $mobile_number, $email_address, $request->otp_mode);
                        $otpGenId = $dataArr['otpGenId'];
                        $otpCode = $dataArr['otpCode'];
                    }
	
                    // ==========================
                    // Attachment Process (Storage Disk)
                    // ==========================
                    $storedFileNames = [];

                    DB::beginTransaction();

                    try {
                        if ($request->w_form_type == getId('BPID')) {

                            foreach ($request->allFiles() as $field => $file) {
                                if (!$file->isValid()) {
                                    throw new \Exception("Field {$field} has an invalid file upload. Error: " . $file->getErrorMessage());
                                }

                                if ($file->getSize() > 3000000) { // 3MB
                                    throw new \Exception('File too large');
                                }

                                $allowed = ['image/jpeg', 'image/png', 'image/jpg'];
                                if (!in_array($file->getMimeType(), $allowed)) {
                                    throw new \Exception('Invalid file type');
                                }


                                $displayName = $this->formatFieldName($field);
                                $existingAttachment = Attachment::where('reference_number', $reference_number)->where('name', $displayName)->first();

                                if ($existingAttachment) {
                                    Storage::disk('custom_storage')->delete($existingAttachment->file_name);
                                    $existingAttachment->delete();
                                }

                                $fileName = $field . '_attach_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                                $fileContent = File::get($file->getRealPath());
                                $isUploaded = Storage::disk('custom_storage')->put($fileName, $fileContent);

                                if (!$isUploaded) {
                                    throw new \Exception("Failed to save file '{$fileName}' on custom_storage disk.");
                                }

                                Attachment::create([
                                    'file_name'        => $fileName,
                                    'reference_number' => $reference_number,
                                    'attachment_date'  => now()->toDateString(),
                                    'uploaded_by'      => auth()->id(),
                                    'name'             => ucwords(str_replace('_', ' ', $field)),
                                ]);
                            }
                        } else {

                            if ($request->file_name) {
                                foreach ($request->file_name as $key => $row) {
                                    if (array_key_exists('file', $row) && $row['file'] instanceof \Illuminate\Http\UploadedFile) {

                                        $file = $row['file'];

                                        if (!$file->isValid()) {
                                            throw new \Exception("File at index {$key} upload failed. Error: " . $file->getErrorMessage());
                                        }

                                        $extension = $file->getClientOriginalExtension();
                                        $origin_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                                        $origin_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $origin_name);
                                        $origin_name = substr($origin_name, 0, 20);
                                        $fileName = $origin_name . "_attach_nX_" . round(microtime(true) * 10) . "_" . ($key + 1) . '.' . $extension;

                                        // Storage Disk
                                        $fileContent = File::get($file->getRealPath());
                                        $isUploaded = Storage::disk('custom_storage')->put($fileName, $fileContent);

                                        if (!$isUploaded) {
                                            throw new \Exception("Failed to save file '{$fileName}' on custom_storage disk.");
                                        }

                                        $attachment = new Attachment();
                                        $attachment->file_name = $fileName;
                                        $attachment->name = $row['name'] ?? 'Attachment';
                                        $attachment->reference_number = $reference_number;
                                        $attachment->attachment_date = date('Y-m-d');
                                        $attachment->uploaded_by = $cif_number;
                                        $attachment->save();
                                    }
                                }
                            }
                        }

                        DB::commit();
                    } catch (\Throwable $e) {
                        DB::rollBack();

                        Log::error('Attachment Upload Error: ' . $e->getMessage(), [
                            'exception' => $e,
                            'reference_number' => $reference_number
                        ]);

                        return response()->json([
                            'file_storage_error' => true,
                            'success' => false,
                            'message' => 'Upload failed: ' . $e->getMessage(),
                        ], 500);
                    }

                    DB::commit();
                    // Local CI User ID 'ci' & UAT 'ci'
                    /*$this->audit(['reference_number'=>$reference_number,'unit_id'=>1,'group_id'=>'','user_id'=>'ci','action'=>'Sentback Ticket re-submitted','comments'=>'', 'isapproved'=>'1', 'subgroup_id' => '']);*/
                    $backUrl = url('/') . '/CI/service/?CIToken=' . $ci_token . '&request_type=' . $requestType;
                    if ($referenceModelName->issue_id == getId('BPID') || $referenceModelName->issue_id == getId('AUCTION_REQUEST')) {
                        $backUrl = url('/') . '/BPID/service/?CIToken=' . $ci_token . '&request_type=' . $requestType;
                    }

                    flash('Sentback service request have been re-submitted successfully. Ticket No: ' . $reference_number, 'success');
                    $mobile_no = maskPhoneNumber($mobile_number);
                    $mask_email = maskEmail($email_address);
                    if ($request->request_mode == "app") {
                        return response()->json([
                            'success' => true,
                            'message' => 'Created successfully!',
                            'product_type' => $product_type,
                            'reference_number' => $reference_number,
                            'storedFiles' => $storedFileNames,
                            'ci_token' => $ci_token,
                            'backUrl' => $backUrl,
                            'otpGenId' => $otpGenId,
                            'issue_name' => $issue_name,
                            'mobile_no' => $mobile_no,
                            'mask_email' => $mask_email,
                            'otp_mode' => $request->otp_mode,
                            'is_send_back' => $request->is_send_back,
                            'invalidCount' => 0
                        ], 200);
                    } else {
                        return response()->json([
                            'success' => true,
                            'message' => 'Created successfully!',
                            'product_type' => $product_type,
                            'reference_number' => $reference_number,
                            'storedFiles' => $storedFileNames,
                            'ci_token' => $ci_token,
                            'backUrl' => $backUrl,
                            'otpGenId' => $otpGenId,
                            'issue_name' => $issue_name,
                            'mobile_no' => $mobile_no,
                            'mask_email' => $mask_email,
                            'otp_mode' => $request->otp_mode,
                            'step2' => 'active',
                            'is_send_back' => $request->is_send_back,
                            'invalidCount' => 0
                        ], 200);
                    }
                } else {
                    flash('Failed to save data', 'danger');
                    return redirect()->back();
                }
            }
        }
        return view('errors.errors_msg')->with('msg', 'invalid access token.');
    }

    public function CIComplaintFormSubmit(CIWFormRequest $request)
    {
        ($request->request_mode == "app") ? $source = 'CI App' : $source = 'CI Web';
        $ci_token = $request->ci_token;
        $requestType = \request()->get('request_type');
        $check_token = TokenValidatedService::validatedToken($ci_token);

        if ($check_token && $request->api_response && $request->account_number && $request->product_type) {


            $sessionAccounts = session()->get('accountNumbers');
            $requestAccount  = $request->account_number;

            if (!array_key_exists($requestAccount, $sessionAccounts)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid account number selected.'
                ], 404);
            }

	        $sameIssueRequest = $this->sameIssueRequestCheck($request->account_number, $request->w_form_type);

            if($sameIssueRequest){
                return response()->json([
                    'errorType' => '2',
                    'success' => false,
                    'message' => 'You have already submit a request with this issue, Please wait for while we process your request.',
                ], 422);
            }

            $api_response = decrypt($request->api_response);
            $response = CICURLService::apiResponse($api_response, $request->account_number, $request->product_type);
            $response = $response->getContent();
            $response = json_decode($response, true);
            $customer_name = $response['accountHolderName'];
            /* $maskedCardNumber = $response['maskedCardNumber'];*/
            $cif_number = $check_token->cif_number;
            $mobile_number = $check_token->mobile_no ?? '';
            $email_address = $check_token->email ?? '';
            $product_type = $request->product_type;
            $extra_field = '';
            $otpGenId = null;
            $otpCode = null;
	    
	        $prodTypeAlpha = "";
            if ($product_type == 1) {
            	$prodTypeAlpha = "CC";
            } elseif ($product_type == 2) {
                $prodTypeAlpha = "AC";
            } elseif ($product_type == 3) {
                $prodTypeAlpha = "DC";
            } elseif ($product_type == 4) {
                $prodTypeAlpha = "LN";
            } elseif ($product_type == 5) {
                $prodTypeAlpha = "TR";
            }

            $reference_number = "CA" . date("ymd") . $prodTypeAlpha . userIdPadLeftWith0($this->dayWiseSequence('cm'), 6, '0');
	    
            if ($request->file_name) {
                $docDestPath = base_path("../public/attachments");
                foreach ($request->file_name as $key => $row) {
                    if (array_key_exists('file', $row)) {
                        $extension = $row['file']->getClientOriginalExtension();
                        $origin_name = pathinfo($row['file']->getClientOriginalName(), PATHINFO_FILENAME);
                        $origin_name = str_replace(' ', '_', $origin_name);
                        $origin_name = substr($origin_name, 0, 20);
                        $fileName = $origin_name . "_attach_nX_" . round(microtime(true) * 10) . "_" . ($key + 1) . '.' . $extension;
                        $attachment = new Attachment();
                        $attachment->file_name = $fileName;
                        $attachment->name = $row['name'];
                        $attachment->reference_number = $reference_number;
                        $attachment->attachment_date = date('Y-m-d');
                        $attachment->uploaded_by = $cif_number;
                        $attachment->save();
                        //$files->move($docDestPath, $fileName);
                        //$fileContent = File::get($row['file']->getRealPath());
                        //Storage::disk('custom_storage')->put($fileName, $fileContent);

                        //Upload File to external server UAT & LIVE
                        try {
				            $row['file']->move($docDestPath, $fileName);
                        } catch (Throwable $e) {
                            Log::error('attachment_error : ', [$e->getMessage()]);
                        }
                    }
                }

                try {
                    $attachments = Attachment::where('reference_number', $reference_number)->get();
                    foreach ($attachments as $attachment) {
                        //UAT
                        //$filePath = Storage::disk('custom_storage')->path($attachment->file_name);

                        //Live
                        $filePath = $docDestPath . '/' . $attachment->file_name;
                        if (!file_exists($filePath)) {
                            // delete attachment data
                            Attachment::where('reference_number', $reference_number)->delete();
                            throw new \Exception('Attachment file cannot be saved!');
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('attachment-move-error', [$e->getMessage()]);
                    return response()->json([
                        'file_storage_error' => true,
                        'success' => false,
                        'message' => $e->getMessage(),
                    ], 500);
                }
            }

            if ($request->isMethod('post')) {
                $issue_config = IssueConfig::where('issue_id', $request->w_form_type)->get();
                if (count($issue_config) != 0) {
                    foreach ($issue_config as $issue_con) {
                        if ($request[$issue_con->field_name] != null) {
                            $dataName[] = [$issue_con->label_name => $request[$issue_con->field_name]];
                        } else {
                            $dataName[] = [$issue_con->label_name => ''];
                        }
                    }
                    $extra_field = json_encode($dataName);
                }
                $unitItemModelName = new UnitItem;
                $unitItemData = $unitItemModelName->select("id", "unit_id", "is_sent_sms", "name")
                    ->where("master_id", $request->w_form_type)
                    ->where("issues_from", "complaint")
                    ->first();
                $workflowlist = "";
                $workflow = IssueWorkflow::where('issue_id', $unitItemData->id)->first();
                // Local Customer Interface Group ID Local 162 & UAT 203 & LIVE 186
                $firstWorkFlow = IssueGroupWorkflow::where('group_info_id', 195)
                    ->where('issue_workflow_id', $workflow->issue_workflow_id)
                    ->first();
                if ($workflow->flow_type == FlowEnum::REGULAR) {
                    if ($firstWorkFlow->touch_checker == 1) {
                        $subgroup_id = $firstWorkFlow->group_info_id;
                        $next_label = $firstWorkFlow->touch_checker;
                        $unit_label = 2;
                    } else {
                        $workflowlist = IssueGroupWorkflow::where('issue_workflow_id', $workflow->issue_workflow_id)
                            ->where('is_touch_point', '<>', 1)
                            ->orderBy('issue_group_workflow_id', 'ASC')
                            ->first();
                        if ($workflowlist->touch_maker == 1) {
                            $subgroup_id = $workflowlist->group_info_id;
                            $next_label = $workflowlist->touch_maker;
                            $unit_label = 1; //'1' mean Maker
                        } else {
                            $subgroup_id = $workflowlist->group_info_id;
                            $next_label = $workflowlist->touch_maker;
                            $unit_label = 2; //'2' mean Checker
                        }
                    }
                }
                if ($workflow->flow_type == FlowEnum::FORWARD) {
                    $subgroup_id = $firstWorkFlow->group_info_id;
                    $next_label = $firstWorkFlow->touch_checker;
                    $unit_label = 2;
                }
                $referenceModelName = new Reference;
                if ($referenceModelName->save()) {
                    $issueId = (!empty($workflow->issue_id)) ? $workflow->issue_id : 0;
                    $referenceModelName->reference_number = $reference_number;
                    $referenceModelName->unit_id = (!empty($unit_label)) ? $unit_label : 2;
                    // Local Customer Interface Group ID 203 & UAT 185
                    $referenceModelName->subgroup_id = (!empty($subgroup_id)) ? $subgroup_id : 195;
                    if (!empty($subgroup_id)) {
                        $subgroup_info_id = SubgroupInfo::where('group_info_id', $subgroup_id)->first();
                        // Local Customer Interface Sub-Group ID 367 & UAT 383
                        $referenceModelName->sub_group_info_id = (!empty($subgroup_info_id->id)) ? $subgroup_info_id->id : 557;
                    }
                    $referenceModelName->issue_id = (!empty($workflow->issue_id)) ? $workflow->issue_id : 0;
                    $referenceModelName->date = strtotime(date('d-m-Y h:i:s A'));
                    $referenceModelName->created_by = 'CI';
                    $referenceModelName->account_type = $product_type;
                    $referenceModelName->status = 47;
                    $referenceModelName->form_status = -7;
                    $referenceModelName->issues_from = 'complaint';
                    $referenceModelName->save();

                    $wformModelName = new Complaint;
                    $wformModelName->reference_number = $reference_number;
                    $wformModelName->customer_name = $customer_name;
                    $wformModelName->acc_name = $customer_name;
                    $wformModelName->mobile_number = $mobile_number;
                    $wformModelName->email_address = $email_address;
                    $wformModelName->product_type = $product_type;
                    $wformModelName->time_and_ext = date('d-m-Y h:i:s A');
                    $wformModelName->source = $source;
                    $wformModelName->SIF_Number = $cif_number;
                    $wformModelName->complaint_details = $request->complaint_details;
                    $wformModelName->complaint_type = $request->w_form_type;

                    if ($product_type == 2 || $product_type == 4) {
                        $wformModelName->account_number = $request->account_number;
                    }
                    if ($product_type == 1 || $product_type == 3) {
                        $wformModelName->mask_card_no = $request->account_number;
                        $wformModelName->account_number = $request->account_number;
                    }
                    $wformModelName->save();

                    $w_form_type = $request->w_form_type;
                    $wformTypeModelName = new ComplaintFormType;
                    $wformTypeModelName->reference_number = $reference_number;
                    $wformTypeModelName->extra_field = $extra_field;
                    $wformTypeModelName->save();


                    $referenceModelName = Reference::where('reference_number', $reference_number)->first();
                    $issue_name = '';
                    $unitItemModelName = new UnitItem;
                    if (!empty($referenceModelName)) {
                        $unitItemData = $unitItemModelName
                            ->select("name")
                            ->where("issues_from", 'complaint')
                            ->where("master_id", $referenceModelName->issue_id)
                            ->first();
                        if (!empty($unitItemData)) {
                            $issue_name = $unitItemData->name;
                        }
                    }


                    // OTP generate and send sms & email
                    if (!empty($mobile_number) && !empty($cif_number)) {
                        $dataArr = OTPGenerateService::otpCodeGenerate($cif_number, $request->w_form_type, $product_type, $request->account_number, $reference_number, $mobile_number, $email_address, $request->otp_mode);
                        $otpGenId = $dataArr['otpGenId'];
                        $otpCode = $dataArr['otpCode'];
                    }

                    // Local CI Group ID UAT 203 & LIVE 186
                    // Local CI Sub-Group ID UAT 383 & LIVE 710
                    // Local CI User ID UAT ci & LIVE ci
                    $this->audit(['reference_number' => $reference_number, 'unit_id' => 1, 'group_id' => 195, 'user_id' => 'CI', 'action' => 'Ticket Logged', 'comments' => '', 'isapproved' => '1', 'subgroup_id' => 557]);

                    $backUrl = url('/') . '/CI/service/?CIToken=' . $ci_token . '&request_type=' . $requestType;
                    flash('Complaint Request have been saved successfully. Ticket No: ' . $reference_number, 'success');
                    $mobile_no = maskPhoneNumber($mobile_number);
                    $mask_email = maskEmail($email_address);

                    if ($request->request_mode == "app") {
                        return response()->json([
                            'success' => true,
                            'message' => 'Created successfully!',
                            'product_type' => $product_type,
                            'request_type' => $request->request_type,
                            'reference_number' => $reference_number,
                            'ci_token' => $ci_token,
                            'backUrl' => $backUrl,
                            'otpGenId' => $otpGenId,
                            'issue_name' => $issue_name,
                            'mobile_no' => $mobile_no,
                            'mask_email' => $mask_email,
                            'is_send_back' => $request->is_send_back,
                            'otp_mode' => $request->otp_mode,
                            'invalidCount' => 0
                        ], 200);
                    } else {
                        return response()->json([
                            'success' => true,
                            'message' => 'Created successfully!',
                            'product_type' => $product_type,
                            'request_type' => $request->request_type,
                            'reference_number' => $reference_number,
                            'ci_token' => $ci_token,
                            'backUrl' => $backUrl,
                            'otpGenId' => $otpGenId,
                            'issue_name' => $issue_name,
                            'mobile_no' => $mobile_no,
                            'mask_email' => $mask_email,
                            'step2' => 'active',
                            'is_send_back' => $request->is_send_back,
                            'otp_mode' => $request->otp_mode,
                            'invalidCount' => 0
                        ], 200);
                    }
                } else {
                    flash('Failed to save data', 'danger');
                    return redirect()->back();
                }
            }
        }

        return view('errors.errors_msg')->with('msg', 'invalid access token.');
    }

    public function CIComplaintUpdate(CIWFormRequest $request)
    {
        $ci_token = $request->ci_token;
        $requestType = \request()->get('request_type');
        $check_token = TokenValidatedService::validatedToken($ci_token);
        if ($check_token) {

            $cif_number = $check_token->cif_number;
            /*$mobile_number = $check_token->mobile_no;
            $email_address = $check_token->email;*/
            $mobile_number = $check_token->mobile_no ?? '';
            $email_address = $check_token->email ?? '';
            $otpGenId = null;
            $otpCode = null;
            $product_type = $request->product_type;
            $reference_number = $request->reference_number;
            $extra_field = '';
            if ($request->isMethod('post')) {
                $w_form_type = ComplaintFormType::where('reference_number', $reference_number)->first();
                ComplaintFormTypeHistory::create([
                    'reference_number' => $reference_number,
                    'extra_field' => $w_form_type->extra_field,
                    'check_list' => $w_form_type->check_list,
                    'user_id' => $cif_number,
                ]);
                $referenceModelName = Reference::where('reference_number', $reference_number)->first();
                $issue_config = IssueConfig::where('issue_id', $referenceModelName->issue_id)->get();
                if (count($issue_config) != 0) {
                    foreach ($issue_config as $issue_con) {
                        if ($request[$issue_con->field_name] != null) {
                            $dataName[] = [$issue_con->label_name => $request[$issue_con->field_name]];
                        } else {
                            $dataName[] = [$issue_con->label_name => ''];
                        }
                    }
                    $extra_field = json_encode($dataName);
                }
                $unitItemModelName = new UnitItem;
                $unitItemData = $unitItemModelName->select("id", "unit_id", "is_sent_sms", "name")
                    ->where("master_id", $request->w_form_type)
                    ->where("issues_from", "complaint")
                    ->first();
                $workflowlist = "";
                $workflow = IssueWorkflow::where('issue_id', $unitItemData->id)->first();
                // Local Customer Interface Group ID UAT 203 & LIVE 186
                $firstWorkFlow = IssueGroupWorkflow::where('group_info_id', 195)
                    ->where('issue_workflow_id', $workflow->issue_workflow_id)
                    ->first();
                if ($workflow->flow_type == FlowEnum::REGULAR) {
                    if ($firstWorkFlow->touch_checker == 1) {
                        $subgroup_id = $firstWorkFlow->group_info_id;
                        $next_label = $firstWorkFlow->touch_checker;
                        $unit_label = 2;
                    } else {
                        $workflowlist = IssueGroupWorkflow::where('issue_workflow_id', $workflow->issue_workflow_id)
                            ->where('is_touch_point', '<>', 1)
                            ->orderBy('issue_group_workflow_id', 'ASC')
                            ->first();
                        if ($workflowlist->touch_maker == 1) {
                            $subgroup_id = $workflowlist->group_info_id;
                            $next_label = $workflowlist->touch_maker;
                            $unit_label = 1; //'1' mean Maker
                        } else {
                            $subgroup_id = $workflowlist->group_info_id;
                            $next_label = $workflowlist->touch_maker;
                            $unit_label = 2; //'2' mean Checker
                        }
                    }
                }
                if ($workflow->flow_type == FlowEnum::FORWARD) {
                    $subgroup_id = $firstWorkFlow->group_info_id;
                    $next_label = $firstWorkFlow->touch_checker;
                    $unit_label = 2;
                }
                if (!empty($referenceModelName)) {
                    $referenceModelName->unit_id = (!empty($unit_label)) ? $unit_label : 2;
                    $referenceModelName->subgroup_id = (!empty($subgroup_id)) ? $subgroup_id : 195; // CI Group ID UAT 203 & LIVE 186
                    if (!empty($subgroup_id)) {
                        $subgroup_info_id = SubgroupInfo::where('group_info_id', $subgroup_id)->first();
                        $referenceModelName->sub_group_info_id = (!empty($subgroup_info_id->id)) ? $subgroup_info_id->id : 557; // CI Group ID UAT 383 & LIVE 710
                    }
                    $referenceModelName->save();

                    $wformTypeModelName = ComplaintFormType::where('reference_number', $reference_number)->first();
                    if (!empty($extra_field)) {
                        $wformTypeModelName->extra_field = $extra_field;
                    }
                    $wformTypeModelName->save();

                    $issue_name = '';
                    $unitItemModelName = new UnitItem;
                    $unitItemData = $unitItemModelName
                        ->select("name")
                        ->where("issues_from", 'complaint')
                        ->where("master_id", $referenceModelName->issue_id)
                        ->first();
                    if (!empty($unitItemData)) {
                        $issue_name = $unitItemData->name;
                    }

                    if (!empty($mobile_number) && !empty($cif_number)) {
                        $dataArr = OTPGenerateService::otpCodeGenerate($cif_number, $request->w_form_type, $request->product_type, $request->account_number, $reference_number, $mobile_number, $email_address, $request->otp_mode);
                        $otpGenId = $dataArr['otpGenId'];
                        $otpCode = $dataArr['otpCode'];
                    }
		    
                    if ($request->file_name) {
                        $docDestPath = base_path('../public/attachments');
                        $storedFileNames = [];
                        foreach ($request->file_name as $key => $row) {
                            if (array_key_exists('file', $row)) {
                                $extension = $row['file']->getClientOriginalExtension();
                                $origin_name = pathinfo($row['file']->getClientOriginalName(), PATHINFO_FILENAME);
                                $origin_name = str_replace(' ', '_', $origin_name);
                                $origin_name = substr($origin_name, 0, 20);
                                $fileName = $origin_name . "_attach_nX_" . round(microtime(true) * 10) . "_" . ($key + 1) . '.' . $extension;
                                $storedFileNames[] = $fileName;
                                $attachment = new Attachment();
                                $attachment->file_name = $fileName;
                                $attachment->name = $row['name'];
                                $attachment->reference_number = $reference_number;
                                $attachment->attachment_date = date('Y-m-d');
                                $attachment->uploaded_by = $cif_number;
                                $attachment->save();
                                //$files->move($docDestPath, $fileName);
                                //$fileContent = File::get($row['file']->getRealPath());
                                //Storage::disk('custom_storage')->put($fileName, $fileContent);

                                //Upload File to external server UAT & LIVE
                                try {
				                    $row['file']->move($docDestPath, $fileName);
                                } catch (Throwable $e) {
                                    Log::error('attachment_error : ', [$e->getMessage()]);
                                }

                            }
                        }
                    }
                    // Local CI User ID 'ci' & UAT 'ci'
                    /*$this->audit(['reference_number'=>$reference_number,'unit_id'=>1,'group_id'=>'','user_id'=>'ci','action'=>'Sentback Ticket re-submitted','comments'=>'', 'isapproved'=>'1', 'subgroup_id' => '']);*/
                    $backUrl = url('/') . '/CI/service/?CIToken=' . $ci_token . '&request_type=' . $requestType;
                    flash('Sentback Complaint request have been re-submitted successfully. Ticket No: ' . $reference_number, 'success');
                    $mobile_no = maskPhoneNumber($mobile_number);
                    $mask_email = maskEmail($email_address);
                    if ($request->request_mode == "app") {
                        return response()->json([
                            'success' => true,
                            'message' => 'Created successfully!',
                            'product_type' => $product_type,
                            'reference_number' => $reference_number,
                            'storedFiles' => $storedFileNames,
                            'ci_token' => $ci_token,
                            'backUrl' => $backUrl,
                            'otpGenId' => $otpGenId,
                            'issue_name' => $issue_name,
                            'mobile_no' => $mobile_no,
                            'mask_email' => $mask_email,
                            'otp_mode' => $request->otp_mode,
                            'is_send_back' => $request->is_send_back,
                            'invalidCount' => 0
                        ], 200);
                    } else {
                        return response()->json([
                            'success' => true,
                            'message' => 'Created successfully!',
                            'product_type' => $product_type,
                            'reference_number' => $reference_number,
                            'storedFiles' => $storedFileNames,
                            'ci_token' => $ci_token,
                            'backUrl' => $backUrl,
                            'otpGenId' => $otpGenId,
                            'issue_name' => $issue_name,
                            'mobile_no' => $mobile_no,
                            'mask_email' => $mask_email,
                            'otp_mode' => $request->otp_mode,
                            'step2' => 'active',
                            'is_send_back' => $request->is_send_back,
                            'invalidCount' => 0
                        ], 200);
                    }
                } else {
                    flash('Failed to save data', 'danger');
                    return redirect()->back();
                }
            }
        }
        return view('errors.errors_msg')->with('msg', 'invalid access token.');
    }

    public function dayWiseSequence($sequenceFrom = "")
    {
        $sequenceModel = new Sequence($sequenceFrom);
        $lastSequence = $sequenceModel->where('created_at', '<', date('Y-m-d'))->orderBy('id', 'DESC')->first();
        if (!empty($lastSequence)) {
            $sequenceModel->truncate();
        }
        $sequenceModel = new Sequence($sequenceFrom);
        $sequenceModel->save();
        return $sequenceModel->id;
    }

    public function audit($params = array())
    {
        $params['duration_in_minutes'] = (!empty($params['duration_in_minutes'])) ? $params['duration_in_minutes'] : 0;
        if (!empty($params['form_load'])) {
            $formLoadTimeObj = new DateTime($params['form_load']);
            $currentTimeObj = new DateTime(date('Y-m-d H:i:s'));
            $interval = $formLoadTimeObj->diff($currentTimeObj);
            $hoursPenalty = $interval->format('%h');
            $minutesPenalty = $interval->format('%i');
            $totalPenalty = ($hoursPenalty * 60) + $minutesPenalty;
            $params['duration_in_minutes'] = $params['duration_in_minutes'] + $totalPenalty;
        }
        $commentModel = new Comment;
        $commentModel->reference_number = (!empty($params['reference_number'])) ? $params['reference_number'] : '';
        $commentModel->comments = (!empty($params['comments'])) ? $params['comments'] : '';
        $commentModel->time = strtotime(date('Y-m-d H:i:s'));
        $commentModel->user_id = (!empty($params['user_id'])) ? $params['user_id'] : 0;
        $commentModel->unit_id = (!empty($params['unit_id'])) ? $params['unit_id'] : 0;
        $commentModel->group_id = (!empty($params['group_id'])) ? $params['group_id'] : 0;
        $commentModel->action = (!empty($params['action'])) ? $params['action'] : 'INVALID';
        $commentModel->duration_in_minutes = $params['duration_in_minutes'];
        $commentModel->isapproved = (!empty($params['isapproved'])) ? $params['isapproved'] : 0;
        $commentModel->issendback = (!empty($params['issendback'])) ? $params['issendback'] : 0;
        $commentModel->subgroup_id = (!empty($params['subgroup_id'])) ? $params['subgroup_id'] : 0;
        $commentModel->ip = $this->getClientIp();
        $commentModel->save();
    }

    public function outgoingSMSEmail($supportType = "", $masterId = "", $referenceNumber = "", $notificationType = "", $issue_name = "")
    {
        $msg = array();
        $sms = "";
        $mail = "";
        if (empty($issue_name)) {
            $unitItemModelName = new UnitItem;
            $unitItemData = $unitItemModelName
                ->select("master_id", "name")
                ->where("issues_from", $supportType)
                ->where("master_id", $masterId)
                ->first();
            if (!empty($unitItemData)) {
                $issue_name = $unitItemData['name'];
            }
        }
        if ($supportType == "noncustomer") {
            $smsEmailModel = new SMSEmail();
            $smsEmailData = $smsEmailModel->orderBy('id', 'DESC')->first();
            if (!empty($smsEmailData)) {
                $sms = $smsEmailData['non_cust_sms'];
                $mail = $smsEmailData['non_cust_email'];
            }
            if (!empty($sms)) {
                $sms = str_replace("{reference_no}", $referenceNumber, $sms);
                $sms = str_replace("{form_request}", $issue_name, $sms);
                $msg['sms'] = $sms;
            }
            if (!empty($mail)) {
                $mail = str_replace("{reference_no}", $referenceNumber, $mail);
                $mail = str_replace("{form_request}", $issue_name, $mail);
                $msg['mail'] = $mail;
            }
        }
        if ($supportType == "sendbackWithReason") {

            $SendBackReason = DB::table('comments')
                ->select('reference_number', 'action', 'issendback', 'isapproved')
                ->where('reference_number', $referenceNumber)
                ->where('issendback', 1)
                ->where('isapproved', 1)
                ->latest()
                ->first();

            $smsEmailModel = new SMSEmail();
            $smsEmailData = $smsEmailModel->orderBy('id', 'DESC')->first();
            if (!empty($smsEmailData)) {
                $sms = $smsEmailData['send_back_sms'];
                $mail = $smsEmailData['send_back_email'];
            }

            if (!empty($sms)) {
                $sms = str_replace("{reference_no}", $referenceNumber, $sms);
                $sms = str_replace("{form_request}", $issue_name, $sms);
                $sms = str_replace("{Send_back_reason}", $SendBackReason->action ?? '', $sms);
                $msg['sms'] = $sms;
            }

            if (!empty($mail)) {
                $mail = str_replace("{reference_no}", $referenceNumber, $mail);
                $mail = str_replace("{form_request}", $issue_name, $mail);
                $mail = str_replace("{Send_back_reason}", $SendBackReason->action ?? '', $mail);
                $msg['mail'] = $mail;
            }
        }

        if ($supportType == "sendbackWithOutReason") {
            $smsEmailModel = new SMSEmail();
            $smsEmailData = $smsEmailModel->orderBy('id', 'DESC')->first();

            if (!empty($smsEmailData)) {
                $sms = $smsEmailData['send_back_auto_sms'];
                $mail = $smsEmailData['send_back_auto_email'];
            }

            if (!empty($sms)) {
                $sms = str_replace("{reference_no}", $referenceNumber, $sms);
                $sms = str_replace("{form_request}", $issue_name, $sms);
                $msg['sms'] = $sms;
            }

            if (!empty($mail)) {
                $mail = str_replace("{reference_no}", $referenceNumber, $mail);
                $mail = str_replace("{form_request}", $issue_name, $mail);
                $msg['mail'] = $mail;
            }
        }
        if (!empty($issue_name)) {
            $smsEmailModel = new SMSEmail();
            $smsEmailData = $smsEmailModel->orderBy('id', 'DESC')->first();
            if (!empty($smsEmailData)) {
                if ($notificationType == "open") {
                    if ($supportType == "wform") {
                        $sms = $smsEmailData['issue_opening_sms_wform'];
                        $mail = $smsEmailData['issue_opening_email_wform'];
                    } elseif ($supportType == "complaint") {
                        $sms = $smsEmailData['issue_opening_sms_complaint'];
                        $mail = $smsEmailData['issue_opening_email_complaint'];
                    }
                } elseif ($notificationType == "close") {
                    if ($supportType == "wform") {
                        $sms = $smsEmailData['issue_closing_sms_wform'];
                        $mail = $smsEmailData['issue_closing_email_wform'];
                    } elseif ($supportType == "complaint") {
                        $sms = $smsEmailData['issue_closing_sms_complaint'];
                        $mail = $smsEmailData['issue_closing_email_complaint'];
                    }
                }
            }
            if (!empty($sms)) {
                $sms = str_replace("{reference_no}", $referenceNumber, $sms);
                $sms = str_replace("{form_request}", $issue_name, $sms);
                $msg['sms'] = $sms;
            }
            if (!empty($mail)) {
                $mail = str_replace("{reference_no}", $referenceNumber, $mail);
                $mail = str_replace("{form_request}", $issue_name, $mail);
                $msg['mail'] = $mail;
            }
        }
        return $msg;
    }

    public function sendSMS($mobile_no, $msg, $ref_no = "", $supp_status = NULL)
    {
        date_default_timezone_set('Asia/Dhaka');
        $savedtime = date("Y-m-d H:i:s");
        $mobile_no_1 = str_replace("+88(00)", "+88", $mobile_no);
        $mnumber = formatMobileNumber($mobile_no_1);
        if ($mnumber != "") {
            if (is_numeric($mnumber) && strlen($mnumber) == 14) {
                $outgoingSMSModel = new OutgoingSMS;
                $outgoingSMSModel->sentSMSid = 0;
                $outgoingSMSModel->message = $msg;
                $outgoingSMSModel->savetime = $savedtime;
                $outgoingSMSModel->senttime = '';
                $outgoingSMSModel->status = '3';
                $outgoingSMSModel->support_status = $supp_status;
                $outgoingSMSModel->mobileNo = $mnumber;
                $outgoingSMSModel->reference_number = $ref_no;
                $outgoingSMSModel->save();
            }
        }
    }

    public function sendBackSMS($mobile_no, $msg, $ref_no = "", $supp_status = NULL, $sendbackStatus = 0)
    {
        date_default_timezone_set('Asia/Dhaka');
        $savedtime = date("Y-m-d H:i:s");
        $mobile_no_1 = str_replace("+88(00)", "+88", $mobile_no);
        $mnumber = formatMobileNumber($mobile_no_1);
        if ($mnumber != "") {
            if (is_numeric($mnumber) && strlen($mnumber) == 14) {
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
            }
        }
    }

    public function sendEMAIL($email_address, $mail, $ref_no = "", $supp_status = NULL)
    {
        date_default_timezone_set('Asia/Dhaka');
        $savedtime = date("Y-m-d H:i:s");
        if ($mail != "") {
            $outgoingEMAILModel = new OutgoingEMAIL;
            $outgoingEMAILModel->subject = 'BBL Support';
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

    public function sendBackEMAIL($email_address, $mail, $ref_no = "", $supp_status = NULL, $subject = null, $sendbackStatus = 0)
    {
        date_default_timezone_set('Asia/Dhaka');
        $savedtime = date("Y-m-d H:i:s");
        if ($mail != "") {
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

    //Zahidul Islam Zihad
    public function CIissueAttachment(Request $request)
    {
        $CIissueAttachment = IssueAttachmentConfig::where('issue_id', $request->issue_id)
            ->orderBy('order_by', "ASC")
            ->get();
        if ($request->request_for == 'app') {
            return view('BBL_CI.partials.CIissue_attachment_item_app', ['attachment_item' => $CIissueAttachment, 'type' => $request->type]);
        } else {
            return view('BBL_CI.partials.CIissue_attachment_item', ['attachment_item' => $CIissueAttachment, 'type' => $request->type]);
        }
    }

    public function callbackUrl(Request $request)
    {
        try {
            $sessionTokenDecrypt = decrypt($request->token);
            $user = CustomerInterfaceToken::where('token', $sessionTokenDecrypt)->where('is_verify', 1)->first();
            $user->logout_time = date('d-m-Y h:i:s A');
            $user->update();
            return response()->json(['callback_url' => $request->callbackUrl]);
        } catch (Throwable $e) {
            return response()->json(['callback_url' => $request->callbackUrl]);
            //dd($e->getMessage());
        }
    }

    /*public function attachmentDownload($filename)
    {
        try {
            $disk = Storage::disk('custom_storage');
            if ($disk->exists($filename)) {
                $file = $disk->get($filename);

                return response($file, 200, [
                    'Content-Type' => $disk->mimeType($filename),
                    'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                ]);
            }
            return view('errors.images');
        } catch (Throwable $e) {
            //dd($e->getMessage());
	    Log::error('attachment_download_error', [$e->getMessage()]);
            abort(403, 'Somethings went worng!');
        }
    }*/

    public function attachmentDownload($filename)
    {
        try {
            $filename = basename($filename);
            $path = base_path("../public/attachments/" . $filename);

            if (file_exists($path)) {
                return response()->download($path, $filename);
            }

            return view('errors.images');
        } catch (Throwable $e) {
            Log::error('attachment-download-error', [$e->getMessage()]);
            abort(403, 'Something went wrong!');
        }
    }

    public function feedbackStore(Request $request)
    {
        $request->validate([
            'comments' => [
            'required', 
            'string',
            function($attribute, $value, $fail){
                if(preg_match('/[^\x00-\x7F]/', $value)){
                $fail('The '.$attribute.' must only contain English Character.');
                }
            },
            new SpecialCharacterFilter()
            ]
        ]);

        $ci_token = $request->ci_token;
	    $backUrl = url('/') . '/CI/service/?CIToken='.$ci_token;
        $check_token = TokenValidatedService::validatedToken($ci_token);

        if ($check_token) {
            $customer = CustomerInterfaceToken::where('token', $ci_token)->first();
            $reference_number = "F" . date("ymd") . userIdPadLeftWith0($this->dayWiseSequence('sr'), 6, '0');
            $feedback = new Feedback();
            $feedback->comments = preg_replace('/\s+/', ' ', $request->comments);
            $feedback->ticket_number = $reference_number;
            $feedback->cif_number = $customer->cif_number;
            $feedback->mobile_no = $customer->mobile_no;
            $feedback->email = $customer->email;
            $feedback->log_date = Carbon::now();
            $feedback->status = 0;
            $feedback->loger = 'CI';
            $feedback->save();
		
            //return redirect()->back()->with('success_feedback', 'Feedback Submitted Successfully!');

            return redirect()->back()->with([
                'success_feedback' => 'Feedback Submitted Successfully!',
                'backUrl' => $backUrl,
            ]);
        }

        return view('errors.errors_msg')->with('msg', 'invalid access token.');
    }

    public function attachmentRemover(Request $request)
    {
        $reference_number = $request->reference_number;
        $storedFiles = $request->input('files', []);
        $docPath = base_path("../public/attachments");

        $deletedFiles = [];
        $failedFiles = [];

        if (!is_array($storedFiles)) {
            return response()->json([
                'result' => false,
                'message' => 'Invalid file list'
            ]);
        }

        foreach ($storedFiles as $file) {
            $file = basename($file);

            $fullPath = $docPath . '/' . $file;

            if (file_exists($fullPath)) {
                try {
                    if (unlink($fullPath)) {
                        $deletedFiles[] = $file;
                        $attachment = Attachment::where('reference_number',$reference_number)->where('file_name', $file)->first();
                        $attachment->delete();
                    } else {
                        $failedFiles[] = $file;
                    }
                } catch (\Throwable $e) {
                    Log::error('File delete error: ' . $e->getMessage());
                    $failedFiles[] = $file;
                }
            } else {
                $failedFiles[] = $file;
            }
        }

        return response()->json([
            'result' => count($failedFiles) === 0,
            'deleted' => $deletedFiles,
            'failed' => $failedFiles
        ]);
    }
}
