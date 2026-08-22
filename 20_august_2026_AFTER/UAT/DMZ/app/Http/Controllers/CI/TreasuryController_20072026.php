<?php

namespace App\Http\Controllers\CI;

use App\Attachment;
use App\BpId;
use App\CustomerInterfaceToken;
use App\Http\Controllers\Controller;
use App\IssueAttachmentConfig;
use App\Services\CI\CICURLService;
use App\Services\CI\FieldSetGroupService;
use App\Services\CI\TokenValidatedService;
use App\UnitItem;
use App\WForm;
use App\WFormType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Throwable;

class TreasuryController extends Controller
{
    // BPID service page
    public function bpidService()
    {
        //$setting = Setting::select('setting_id', 'ci_session_time')->first();
        $CIToken = \request()->get('CIToken');
        $first = \request()->get('first');
        $requestType = \request()->get('request_type');
        $check_token = TokenValidatedService::validatedToken($CIToken);

        $sameIssueRequestFound  = false;
        $findSameUserIssue = DB::table('w_form')
                ->leftJoin('reference', 'w_form.reference_number', 'reference.reference_number')
                ->select('w_form.reference_number')
                ->where('w_form.SIF_Number', '=', $check_token->cif_number)
                ->where('w_form.w_form_type', '=', getId('BPID'))
                ->whereNotIn('reference.form_status', [-7])
                ->first();

        if ($findSameUserIssue) {
            $sameIssueRequestFound  = true;
        }

        if (!empty($first) && $first == 1) {
            try {
                $token = decrypt($CIToken);
                session()->forget('auth_token');
                session()->put('auth_token', $token);
            } catch (Throwable $e) {
                Log::error('service() Error', ['message' => 'invalid access token.']);
                return abort(404, 'Page not found!');
            }
        } else {
            $token = \request()->get('CIToken');
        }

        // Check Token if exit
        $data = CustomerInterfaceToken::where('token', $token)->first();
        $backUrl = url('/') . '/CI/service/?CIToken=' . $CIToken;

        if ($data) {
            if (!empty(session()->get('auth_token')) && session()->get('auth_token') === $token) {
                return view('BBL_BPID.service_home', [
                    "ci_token" => $token, 
                    'step1' => 'active', 
                    'backUrl' => $backUrl, 
                    'request_type' => $requestType,
                    'sameIssueRequestFound' => $sameIssueRequestFound
                ]);
            } else {
                // return abort(403, 'Unauthorized User!');
                Log::error('error', ['message' => 'Unauthorized User!']);
                return abort(404, 'Unauthorized User!');
            }
        }

        Log::error('error', ['message' => 'Session Id not found!']);
        return abort(404, 'Unauthorized User!');
        // return view('errors.errors_msg')->with('msg', 'Session Id not found!');
    }

    // BPID service type page
    public function bpidServiceType()
    {
        $CIToken = \request()->get('CIToken');
        $requestType = \request()->get('request_type');

        
        $issueId = null;
        if ($requestType == 'Auction') {
            $type = 'Auction';
            $issueId = 1193;
        } else {
            $type = 'BPID';
            $issueId = 1192;
        }

        $isEmailOtp = 1;
        $check_token = TokenValidatedService::validatedToken($CIToken);
        $product_type = 2;

        // prd($check_token);
        if ($check_token) {

            $response = CICURLService::ciCurl($check_token->cif_number, $product_type);

            $response = $response->getContent();
            $response = json_decode($response, true);
            $accountNumbers = $response['accountNumbers'];
            $api_response = $response['data'];

            if ($accountNumbers) {
                $unit_items = UnitItem::where('unit_items.product_type_id', $product_type)
                    // ->join('issue_workflows', 'unit_items.id', 'issue_workflows.issue_id')
                    // ->join('issue_group_workflows', 'issue_workflows.issue_workflow_id', 'issue_group_workflows.issue_workflow_id')
                    // ->where('issue_group_workflows.group_info_id', 203)
                    ->where('unit_items.id', $issueId)
                    ->where('unit_items.issues_from', 'wform')
                    ->where('unit_items.status', 1)
                    ->where('unit_items.is_ci', 1)
                    ->orderBy('name', 'asc')
                    ->get(['unit_items.id', 'unit_items.name', 'unit_items.is_ci'])
                    ->toArray();

                $issue_fields = [];
                $attachment_item = "";
                $backUrl = url('/') . '/BPID/service/?CIToken=' . $CIToken . '&request_type=' . $requestType;
                $data = [
                    'issue_fields' => $issue_fields,
                    'is_send_back' => 0,
                    'unit_items' => $unit_items,
                    'attachment_item' => $attachment_item,
                    'ci_token' => $CIToken,
                    'backUrl' => $backUrl,
                    'product_type' => $product_type,
                    'accountNumbers' => $accountNumbers,
                    'api_response' => encrypt($api_response),
                    'isEmailOtp' => $isEmailOtp,
                    'request_type' => $requestType,
                    'issueId' => $issueId,
                ];
                return view('BBL_BPID.service', $data);
            } else {
                return redirect()->back()->with('warning', 'No ' . $type . ' info found against this Id!');
            }
        }
        return redirect()->back()->with('warning', 'Your session is invalid or expired');
    }

    public function issueFormFieldBPID(Request $request)
    {
        $bpid_data = null;
        if($request->issue_id == 1193){
            $check_bpid = DB::table('bp_ids')->where('account_number', $request->account_number)->latest()->first();
            if ($check_bpid && !empty($check_bpid->bp_id)) {
                $bpid_data = $check_bpid;
            }
            else{
                return view('BBL_BPID.partials.BPID_no_data_found', ['account_number' => $request->account_number]);
            }
        }
        
        // return $bpid_data;
        

        // For BPID
        $request_for = $request->request_for;
        $fields = FieldSetGroupService::getFieldSet($request->issue_id);
        if ($request_for == 'web') {
            return view('BBL_BPID.partials.extra_form_field_with_group_bpid', [
                'issue_fields' => $fields, 
                'issue_id' => $request->issue_id,
                'bpid_data' => $bpid_data
            ]);
        } else {
            // dd("okay");
            return view('BBL_BPID.partials.extra_form_field_with_group_app_bpid', [
                'issue_fields' => $fields, 
                'issue_id' => $request->issue_id,
                'bpid_data' => $bpid_data
            ]);
        }
    }


    // print Bp Id Btn
    public function printBpIdBtn(Request $request)
    {

        // dd($request->all());
        // return $request->all();
        // Request data convert into object
        $raw = (object) $request->except('_token');

        $docDestPath = 'public/temp_attachments';

        // Create directory if not exists
        if (!File::exists($docDestPath)) {
            File::makeDirectory($docDestPath, 0755, true);
        }

        // Remove all existing files inside temp_attachments
        $files = File::files($docDestPath);
        foreach ($files as $file) {
            File::delete($file);
        }

        // Dynamic file upload fields detection
        $uploadedFiles = [];
        // Loop through all request data to find uploaded files
        foreach ($request->all() as $key => $value) {
            // Check if this is a file upload field
            if ($request->hasFile($key)) {
                $uploadedFiles[] = $key;
            }
        }

        foreach ($uploadedFiles as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);

                // Clean file name
                $name = substr(str_replace(' ', '_', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)), 0, 20);

                // Unique file name
                $fileName = $name . '_' . time() . '.' . $file->getClientOriginalExtension();

                // Move file
                $file->move($docDestPath, $fileName);

                // Save path dynamically
                $raw->{$field . '_path_url'} = $docDestPath . '/' . $fileName;
            } else {
                $raw->{$field . '_path_url'} = '';
            }
        }

        return view('BBL_BPID.printBpIdform', compact('raw'));
    }
// Auction Print Form
    public function printAuctionRequestBtn(Request $request)
    {

        // return $request->all();

        // Request data convert into object
        $raw = (object) $request->except('_token');
        $raw->bidding_amount_words = $this->numberToWords($raw->bidding_amount);


        // BPID
        $bpid = BpId::where('bp_id', $raw->bp_id)->first();
        if (!$bpid) {
            flash('No BPID found for this account number.', 'danger');
            return redirect()->back();
        }

        // Attachment
        $attachments = Attachment::where('reference_number', $bpid->reference_number)->get()->groupBy('name');

        $attachmentMap = [];
        $genericAttachments = [];

        foreach ($attachments as $attachmentName => $attachmentGroup) {
            if (!empty($attachmentGroup) && $attachmentGroup->isNotEmpty()) {
                $file = $attachmentGroup->first();

                $genericAttachments[$attachmentName] = $file->file_name;
                $normalizedName = strtolower(trim($attachmentName));

                if (str_contains($normalizedName, 'signature image')) {
                    if (str_contains($normalizedName, 'two')) {
                        $attachmentMap['applicant_2_signature'] = $file->file_name;
                    } elseif (str_contains($normalizedName, 'third')) {
                        $attachmentMap['applicant_3_signature'] = $file->file_name;
                    } elseif (str_contains($normalizedName, 'fourth')) {
                        $attachmentMap['applicant_4_signature'] = $file->file_name;
                    } else {
                        $attachmentMap['applicant_1_signature'] = $file->file_name;
                    }
                }
            }
        }

        // raw object attach
        foreach ($attachmentMap as $key => $fileName) {
            $raw->{$key} = $fileName;
        }


        $treasury_bills = [
            [
                'id' => 1,
                'name' => '91 Days',
            ],
            [
                'id' => 2,
                'name' => '182 Days',
            ],
            [
                'id' => 3,
                'name' => '364 Days',
            ],
        ];
        $treasury_bounds = [
            [
                'id' => 1,
                'name' => '2 Years',
            ],
            [
                'id' => 2,
                'name' => '5 Years',
            ],
            [
                'id' => 3,
                'name' => '10 Years',
            ],
            [
                'id' => 4,
                'name' => '20 Years',
            ],
        ];

        // return $raw;

        return view('BBL_BPID.printAuctionRequestform', compact('raw', 'treasury_bills', 'treasury_bounds'));
    }


    public function ticketStatusDetails()
    {
        $ci_token = \request()->get('CIToken');
        $requestType = \request()->get('request_type');

	

        $title = $requestType == getId('BPID') ? 'BPID' : 'Auction Request';
        $request_type = $requestType == getId('BPID') ? 'BPID' : 'Auction';



        $check_token = TokenValidatedService::validatedToken($ci_token);
        if ($check_token) {
            $group_name = '';
            $ticketStatus = WForm::with('serviceName')
                ->where('w_form.SIF_Number', $check_token->cif_number)
                ->where('w_form.w_form_type', $requestType)
                ->where(function ($query) {
                    $query->where('w_form.source', 'CI Web')
                        ->orWhere('w_form.source', 'CI App');
                })
                ->join('reference', 'w_form.reference_number', 'reference.reference_number')
                ->where('reference.form_status', '<>', -7)
                ->orderBy('w_form.id', 'DESC')
                ->paginate(10);

            $group_name = \Illuminate\Support\Facades\DB::table('group_info')->where('id', 190)->first(['name']);


            $backUrl = url('/') . '/BPID/service/?CIToken=' . $ci_token . '&request_type=' . $request_type;

            return view('BBL_BPID.ticket_status_details', compact('ticketStatus', 'ci_token', 'backUrl', 'group_name', 'title'));
        }
        return view('errors.errors_msg')->with('msg', 'invalid access token.');
    }


    public function sendBackDetails()
    {

        $ci_token = \request()->get('CIToken');
        $requestType = \request()->get('request_type');
        $title = $requestType == getId('BPID') ? 'BPID' : 'Auction Request';
        $request_type = $requestType == getId('BPID') ? 'BPID' : 'Auction';
        $check_token = TokenValidatedService::validatedToken($ci_token);
        if ($check_token) {
            $group_name = '';
            $sendBackTickets = WForm::with('ciTicketStatus', 'serviceName')
                ->where('w_form.SIF_Number', $check_token->cif_number)
                ->where('w_form.w_form_type', $requestType)
                ->where(function ($query) {
                    $query->where('w_form.source', 'CI Web')
                        ->orWhere('w_form.source', 'CI App');
                })
                ->join('reference', 'w_form.reference_number', 'reference.reference_number')
                ->where('reference.form_status', 7)
                ->orderBy('w_form.id', 'DESC')
                ->paginate(10);
            

            $group_name = \Illuminate\Support\Facades\DB::table('group_info')->where('id', 190)->first(['name']);

            $backUrl = url('/') . '/BPID/service/?CIToken=' . $ci_token . '&request_type=' . $request_type;
            return view('BBL_BPID.send_back_details', compact('sendBackTickets', 'ci_token', 'backUrl', 'group_name', 'title', 'request_type'));
        }
        return view('errors.errors_msg')->with('msg', 'invalid access token.');
    }



    public function sendBackTicket($issueId, $refNum, $viewMode)
    {
        $ci_token = \request()->get('CIToken');
        $requestType = \request()->get('request_type');
        $request_type = $requestType == 'BPID' ? 1192 : 1193; 

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

            $backUrl = url('/') . '/BPID/send-back/details/?CIToken=' . $ci_token . '&request_type=' . $request_type;

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
                'request_type' => $request_type

            ];

            return view('BBL_BPID.send_back_ticket', $data);
        }

        return view('errors.errors_msg')->with('msg', 'invalid access token.');
    }




}
