<?php

namespace App\Http\Controllers;

use App\ApiCredential;
use App\ApiCredentialTmp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ApiCredentialController extends Controller
{
    public $checker = false;

    public function __construct()
    {
        parent::__construct();
        // $this->middleware(['role:superadmin|CEX_ADMIN|CEX_ADMIN_CHECKER']);
        $this->middleware(['role_or_permission:superadmin|accessApiCredential|accessApiCredentialChecker']);
    }

    public function accessApiCredentialChecker()
    {
        if (Auth::check() && Auth::user()->hasPermissionTo('accessApiCredentialChecker')) {
            return $this->checker = true;
        } else {
            return $this->checker;
        }
    }

    public function edit()
    {
        $checker = $this->accessApiCredentialChecker();
        if ($checker) {
            abort(403, 'You do not have permission to access this page.');
        }

        $apiCredential = ApiCredential::find(1);

        // $tmpId = $apiCredential->id;
        $tmpId = 0;
        return view('api_credential.edit', compact('apiCredential', 'checker', 'tmpId'));
    }

    public function apiCredentialsTmpEdit($id = null, Request $req)
    {
        $checker = $this->accessApiCredentialChecker();
        if ($checker) {
            abort(403, 'You do not have permission to access this page.');
        }

        $apiCredential = ApiCredentialTmp::find(1);

        $tmpId = $id;
        // $tmpId = 0;
        return view('api_credential.edit', compact('apiCredential', 'checker', 'tmpId'));
    }

    public function update(Request $request, $id)
    {
        $isTemp = !empty($request->tmpId) && $request->tmpId != 0;
        $checker = $this->accessApiCredentialChecker();
        if ($checker) {
            abort(403, 'You do not have permission to access this page.');
        }
        $request->validate([
            'user_name' => 'required|string|max:255',
            'user_password' => 'required|string',
            'ci_username' => 'required|string|max:255',
            'ci_password' => 'required|string',
            'token_url' => 'required|string',
            'Pull_API_URL' => 'required|string',
            'SMS_API_URL' => 'required|string',
            'loan_api_request' => 'required|string',
            'Post_No_Debit_API_URL' => 'required|string',
            'BPID_API_URL' => 'required|string',
        ]);

        $existingTmpRecords = ApiCredentialTmp::where('master_id', $id)->get();

        $hasInvalidRecord = $existingTmpRecords->contains(function ($record) {
            return $record->form_status != 7 && $record->form_status != -1;
        });

        if ($hasInvalidRecord) {
            flash('An entry already exists, please wait for Checker Approval', 'danger');
            return Redirect::back();
        }

        $data = $request->all();
        $data['modified_by'] = Auth::user()->id;

        if ($isTemp) {
            $tmpApiCredential = ApiCredentialTmp::where('id', $id)->first();
        } else {
            $tmpApiCredential = new ApiCredentialTmp();
            $update = ApiCredential::where('id', $id)->first();
        }
        // $tmpApiCredential = new ApiCredentialTmp();

        $tmpApiCredential->user_name = $request->user_name;
        $tmpApiCredential->user_password = $request->user_password;
        $tmpApiCredential->ci_username = $request->ci_username;
        $tmpApiCredential->ci_password = $request->ci_password;
        $tmpApiCredential->token_url = $request->token_url;
        $tmpApiCredential->Pull_API_URL = $request->Pull_API_URL;
        $tmpApiCredential->SMS_API_URL = $request->SMS_API_URL;
        $tmpApiCredential->loan_api_request = $request->loan_api_request;
        $tmpApiCredential->action = "Edit";
        $tmpApiCredential->form_status = 0;
        $tmpApiCredential->master_id = $isTemp ? $tmpApiCredential->master_id : $update->id;
        $tmpApiCredential->Post_No_Debit_API_URL = $request->Post_No_Debit_API_URL;
        $tmpApiCredential->BPID_API_URL = $request->BPID_API_URL;
        $tmpApiCredential->created_by = Auth::user()->id;

        if ($tmpApiCredential->save()) {
            flash('API Credential successfully update. please wait for Checker Approval', 'success');
            return redirect()->route('apiCredential.edit');
        } else {
            flash('Failed to update API Credential, Please try again', 'danger');
        }
    }


    public function approveApiCredential($id)
    {
        $checker = $this->accessApiCredentialChecker();
        if ($checker == false) {
            abort(403, 'You do not have permission to access this page.');
        }

        DB::beginTransaction();

        try {
            $apiCredentialTmp = DB::table('api_credential_tmp')->first();
            // $tmpDepartment = DB::table('divisions_tmp')->where('id', $id)->where('status', 0)->first();

            if (!$apiCredentialTmp) {
                flash('Department not found or not approved yet.', 'danger');
                return redirect()->back();
            }

            $apiCredential = [
                'user_name'        => $apiCredentialTmp->user_name,
                'user_password' => $apiCredentialTmp->user_password,
                'ci_username' => $apiCredentialTmp->ci_username,
                'ci_password' => $apiCredentialTmp->ci_password,
                'token_url' => $apiCredentialTmp->token_url,
                'Pull_API_URL'      => $apiCredentialTmp->Pull_API_URL,
                'SMS_API_URL'        => $apiCredentialTmp->SMS_API_URL,
                'loan_api_request' => $apiCredentialTmp->loan_api_request,
                'Post_No_Debit_API_URL' => $apiCredentialTmp->Post_No_Debit_API_URL,
                'BPID_API_URL' => $apiCredentialTmp->BPID_API_URL,
            ];

            if ($apiCredentialTmp->master_id != null && $apiCredentialTmp->action != "Add") {
                DB::table('api_credential')->where('id', $apiCredentialTmp->master_id)->update($apiCredential);
            } else {

                DB::table('api_credential')->insert($apiCredential);
            }

            DB::table('api_credential_tmp')->where('id', $apiCredentialTmp->id)->delete();
            DB::commit();
            flash('Api Credential has been approved.', 'success');
            // if($apiCredentialTmp->status == 0){
            //     flash('Api Credential has been inActive.', 'danger');
            // }elseif($apiCredentialTmp->status == 1){
            //     flash('Api Credential has been activated.', 'success');
            // }else{
            //     flash('Api Credential has been approved.', 'success');
            // }
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            flash('An error occurred during the approval process: ' . $e->getMessage(), 'danger');
            return redirect()->back();
        }
    }

    public function apiCredentialTmpList()
    {
        $checker = $this->accessApiCredentialChecker();
        if ($checker) {
            $isChecker = true;
            $tmpData = ApiCredentialTmp::whereNotIn('form_status', [7, -1])->get();
        } else {
            $isChecker = false;
            $tmpData = ApiCredentialTmp::whereIn('form_status', [7, -1])->get();
        }

        return view('api_credential.index_tmp', compact('tmpData', 'isChecker'));
    }

    public function apiCredentialAssign($id)
    {
        $tmpData = ApiCredentialTmp::where('id', $id)->first();

        $tmpData->form_status = 1;
        $tmpData->modified_by = Auth::user()->user_id;
        $tmpData->save();

        $data = [
            'message' => 'Assigned Successfully from your End!',
            'id' => $id,
        ];

        return response()->json($data, 200);
    }

    public function apiCredentialSendback(Request $request, $id)
    {
        $checker = $this->accessApiCredentialChecker();
        if ($checker == false) {
            abort(403, 'You do not have permission to access this page.');
        }

        $request->validate([
            'comments' => 'required'
        ]);
        $tmpData = ApiCredentialTmp::where('id', $id)->first();
        $tmpData->form_status = 7;
        $tmpData->modified_by = null;
        $tmpData->comments = $request->comments;
        $tmpData->save();

        flash('Send back Successfully!', 'success');
        return redirect()->back();
    }

    public function apiCredentialReject(Request $request, $id)
    {
        $checker = $this->accessApiCredentialChecker();
        if ($checker == false) {
            abort(403, 'You do not have permission to access this page.');
        }

        $request->validate([
            'comments' => 'required'
        ]);
        $tmpData = ApiCredentialTmp::where('id', $id)->first();
        $tmpData->form_status = -1;
        $tmpData->modified_by = Auth::user()->user_id;
        $tmpData->comments = $request->comments;
        $tmpData->save();

        flash('Rejected !!!', 'danger');
        return redirect()->back();
    }

    public function buttonControl($id)
    {
        $tmpData = ApiCredentialTmp::findOrFail($id);

        if ($tmpData->form_status == 0) {
            $assign_btn = true;
        } else {
            $assign_btn = false;
        }

        if ($tmpData->modified_by == Auth::user()->user_id) {
            $all_buttons = true;
        } else {
            $all_buttons = false;
        }

        $response = [
            'assign_btn' => $assign_btn,
            'all_buttons' => $all_buttons,
            'modified_by' => $tmpData->modified_by,
        ];

        return response()->json($response, 200);
    }

    public function checkerTableData($id)
    {
        $tmpData = ApiCredentialTmp::findOrFail($id);
        // $tmpData = ApiCredentialTmp::where('id',$id)->first();

        if (!empty($tmpData->master_id)) {
            $oldData = ApiCredential::findOrFail($tmpData->master_id);
        }

        $tmpData->user_name = $tmpData->user_name;
        $tmpData->user_password = $tmpData->user_password;

        $tmpData->token_url = $tmpData->token_url;
        $tmpData->Pull_API_URL = $tmpData->Pull_API_URL;
        $tmpData->SMS_API_URL = $tmpData->SMS_API_URL;
        $tmpData->loan_api_request = $tmpData->loan_api_request;

        if (isset($oldData)) {
            $oldData->user_name = $oldData->user_name ?? 'N/A';
            $oldData->user_password = $oldData->user_password ?? 'N/A';
            $oldData->token_url = $oldData->token_url ?? 'N/A';
            $oldData->Pull_API_URL = $oldData->Pull_API_URL ?? 'N/A';
            $oldData->SMS_API_URL = $oldData->SMS_API_URL ?? null;
            $oldData->loan_api_request = $oldData->loan_api_request ?? null;
        }

        $columnsToSend = ['user_name', 'user_password', 'token_url', 'Pull_API_URL', 'SMS_API_URL', 'loan_api_request'];
        $filteredTmpData = $tmpData->only($columnsToSend);
        $filteredOldData = isset($oldData) ? $oldData->only($columnsToSend) : null;
        $response = [
            'old_data' => $filteredOldData,
            'new_data' => $filteredTmpData
        ];

        return response()->json($response, 200);
    }
}
