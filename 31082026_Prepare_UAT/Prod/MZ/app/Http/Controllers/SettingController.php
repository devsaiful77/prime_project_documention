<?php
/**
 * User:Tanay Kumar Roy
 * Email:tanayroy12@gmail.com
 * Created by Tanay Kumar Roy<tanayroy12@gmail.com> on 3/31/2020.
 */

namespace App\Http\Controllers;


use App\Setting;
use App\SettingTmp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class SettingController extends Controller
{
    public $checker = false;

	public function __construct()
    {
        parent::__construct();
        $this->middleware(['role_or_permission:superadmin|accessSetting|accessSettingChecker']);
    }

    public function accessSettingChecker(){
        if (Auth::check() && Auth::user()->hasPermissionTo('accessSettingChecker')) {
            return $this->checker = true;
        }else{
            return $this->checker;
        }
    }


    public function index(){
        $checker = $this->accessSettingChecker();
        $rows = Setting::all();

        return view('settings.index',compact('rows','checker'));
    }

    public function create(){
        return view('settings.create');
    }

    public function store(Request $request){
        $this->validate($request,[
            'session_lifetime'=>'required',
            'ci_session_time'=>'required',
            'file_size_limit'=>'required|numeric|min:1|max:20',
        ], [
            'file_size_limit.min' => 'File size limit must be at least 1 MB',
            'file_size_limit.max' => 'File size limit must not exceed 20 MB',
        ]);

        $allow_ip_restriction = false;
        
        if ($request->allow_ip_restriction !== null) {
            $allow_ip_restriction = true;
        }
        
        Setting::create([
            'session_lifetime'=>$request->session_lifetime,
            'ci_session_time'=>$request->ci_session_time,
            'term_condition'=>$request->term_condition,
            'password_change_time'=>$request->password_change_time,
            'sla_blink'=>$request->sla_blink,
            'sla_email_time'=>$request->sla_email_time,
            'allow_ip_restriction'=>$allow_ip_restriction,
            'forward_time'=>$request->forward_time,
            'file_size_limit' => $request->file_size_limit * 1024, // Convert MB to KB
        ]);

        return redirect('settings');
    }

    public function edit($id){
        $checker = $this->accessSettingChecker();

        if ($checker) {
            abort(403, 'You do not have permission to access this page.');
        }

        $tmpId = 0;
        $row = Setting::find(decrypt($id));
        
        if ($row && $row->file_size_limit) {
            // Convert KB to MB for display only
            $row->file_size_limit = $row->file_size_limit / 1024;
        }
        
        return view('settings.edit', compact('row', 'tmpId'));
    }

    public function settingTmpEdit($id = null, Request $req)
    {
        $checker = $this->accessSettingChecker();
        if ($checker) {
            abort(403, 'You do not have permission to access this page.');
        }

        $departmentModelName = new SettingTmp;
        $title = "Send Back Setting Edit";
        $title_for_layout = 'Edit Setting';
        $searchDataForView = $req->all();
        $tmpId = $id;

        $row = $departmentModelName->where('id', $id)->first();

        if ($row && $row->file_size_limit) {
            // Convert KB to MB for display only
            $row->file_size_limit = $row->file_size_limit / 1024;
        }
        
        return view('settings.edit', compact('title', 'title_for_layout','row', 'searchDataForView', 'id', 'tmpId'));
    }


    public function update(Request $request, $id){
        $isTemp = !empty($request->tmpId) && $request->tmpId != 0;
        
        $this->validate($request,[
            'session_lifetime'=>'required',
            'ci_session_time'=>'required',
            'password_change_time'=>'required',
            'sla_blink'=>'required',
            'sla_email_time'=>'required',
            'forward_time'=>'required',
            'file_size_limit'=>'required|numeric|min:1|max:20',
        ], [
            'ci_session_time' => 'please enter Customer Portal Time',
            'file_size_limit.min' => 'File size limit must be at least 1 MB',
            'file_size_limit.max' => 'File size limit must not exceed 20 MB',
        ]);

        // Check if there's already a record in DepartmentTmp with the same master_id
        $existingTmpRecords = SettingTmp::where('master_id', decrypt($id))->get();

        $hasInvalidRecord = $existingTmpRecords->contains(function ($record) {
            return $record->form_status != 7 && $record->form_status != -1;
        });

        if ($hasInvalidRecord) {
            flash('An entry already exists, please wait for Checker Approval', 'danger');
            return Redirect::back();
        }

        $allow_ip_restriction = false;
        if ($request->allow_ip_restriction !== null) {
            $allow_ip_restriction = true;
        }

		$noncustomersms = false;
        if ($request->noncustomersms !== null) {
            $noncustomersms = true;
        }

        $data = $request->all();
        $data['modified_by'] = Auth::user()->id;

        if ($isTemp) {
            $settingTmp = SettingTmp::where('setting_id', $id)->first();
        }else{
            $settingTmp = new SettingTmp();
            $update = Setting::where('setting_id', decrypt($id))->first();
        }

        $settingTmp->session_lifetime = $request->session_lifetime;
        $settingTmp->ci_session_time = $request->ci_session_time;
        $settingTmp->term_condition = $request->term_condition;
        $settingTmp->password_change_time = $request->password_change_time;
        $settingTmp->sla_blink = $request->sla_blink;
        $settingTmp->sla_email_time = $request->sla_email_time;
        $settingTmp->allow_ip_restriction = $allow_ip_restriction;
        $settingTmp->forward_time = $request->forward_time;
        $settingTmp->noncustomersms = $noncustomersms;
        $settingTmp->file_size_limit = $request->file_size_limit * 1024; // Convert MB to KB
        $settingTmp->action = "Edit";
        $settingTmp->form_status = 0;
        $settingTmp->master_id = $isTemp ? $settingTmp->master_id : $update->setting_id;
        $settingTmp->created_by =  auth()->user()->id;

        if ($settingTmp->save()) {
            flash('Settings successfully updated. Please wait for checker approval', 'success');
            return redirect('settings');
        } else {
            flash('Failed to update Settings, Please try again', 'danger');
            return redirect('settings');
        }
    }

    public function approveSetting($id)
    {
        $checker = $this->accessSettingChecker();
        if ($checker == false) {
            abort(403, 'You do not have permission to access this page.');
        }

        DB::beginTransaction();

        try {
            $tmpSettings = DB::table('settings_tmp')->first();
            
            if (!$tmpSettings) {
                flash('Settings not found or not approved yet.', 'danger');
                return redirect()->back();
            }

            $allow_ip_restriction = false;
            if ($tmpSettings->allow_ip_restriction !== null) {
                $allow_ip_restriction = true;
            }

            $noncustomersms = false;
            if ($tmpSettings->noncustomersms !== null) {
                $noncustomersms = true;
            }

            $SettingsData = [
                'session_lifetime' => $tmpSettings->session_lifetime,
                'ci_session_time' => $tmpSettings->ci_session_time,
                'term_condition' => $tmpSettings->term_condition,
                'sla_blink'  => $tmpSettings->sla_blink,
                'sla_email_time' => $tmpSettings->sla_email_time,
                'allow_ip_restriction' => $tmpSettings->allow_ip_restriction,
                'forward_time'  => $tmpSettings->forward_time,
                'noncustomersms'  => $tmpSettings->noncustomersms,
                'file_size_limit' => $tmpSettings->file_size_limit, // Convert MB to KB
                'created_at'  => now(),
                'updated_at'  => now(),
            ];

            if($tmpSettings->master_id != null && $tmpSettings->action != "Add"){
                DB::table('settings')->where('setting_id', $tmpSettings->master_id)->update($SettingsData);
            }else{
                DB::table('settings')->insert($SettingsData);
            }

            DB::table('settings_tmp')->where('id', $tmpSettings->id)->delete();
            DB::commit();
            flash('Settings has been approved.', 'success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            flash('An error occurred during the approval process: ' . $e->getMessage(), 'danger');
            return redirect()->back();
        }
    }

    public function settingTmpList()
    {
        $checker = $this->accessSettingChecker();
        if ($checker) {
            $isChecker = true;
            $tmpData = SettingTmp::whereNotIn('form_status', [7, -1])->get();
        }else{
            $isChecker = false;
            $tmpData = SettingTmp::whereIn('form_status', [7, -1])->get();
        }

        return view('settings.index_tmp',compact('tmpData','isChecker'));
    }

    public function settingAssign($id)
    {
        $tmpData = SettingTmp::where('id', $id)->first();

        $tmpData->form_status = 1;
        $tmpData->modified_by = Auth::user()->user_id;
        $tmpData->save();

        $data = [
            'message' => 'Assigned Successfully from your End!',
            'id' => $id,
        ];

        return response()->json($data, 200);
    }

    public function settingSendback(Request $request, $id)
    {
        $checker = $this->accessSettingChecker();

        if ($checker == false) {
            abort(403, 'You do not have permission to access this page.');
        }

        $request->validate([
            'comments' => 'required'
        ]);

        $tmpData = SettingTmp::where('id', $id)->first();
        $tmpData->form_status = 7;
        $tmpData->modified_by = null;
        $tmpData->comments = $request->comments;
        $tmpData->save();

        flash('Send back Successfully!', 'success');
        return redirect()->back();
    }

    public function settingReject(Request $request, $id)
    {
        $checker = $this->accessSettingChecker();
        if ($checker == false) {
            abort(403, 'You do not have permission to access this page.');
        }

        $request->validate([
            'comments' => 'required'
        ]);

        $tmpData = SettingTmp::where('id', $id)->first();
        $tmpData->form_status = -1;
        $tmpData->modified_by = Auth::user()->user_id;
        $tmpData->comments = $request->comments;
        $tmpData->save();

        flash('Rejected !!!', 'danger');
        return redirect()->back();
    }

    public function buttonControl($id){
        $tmpData = SettingTmp::where('id', $id)->first();
        
        if($tmpData->form_status == 0){
            $assign_btn = true;
        }else{
            $assign_btn = false;
        }

        if($tmpData->modified_by == Auth::user()->user_id){
            $all_buttons = true;
        }else{
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
        $tmpData = SettingTmp::where('id',$id)->first();

        if (!empty($tmpData->master_id)) {
            $oldData = Setting::findOrFail($tmpData->master_id);
        }

        $tmpData->session_lifetime = $tmpData->session_lifetime;
        $tmpData->ci_session_time = $tmpData->ci_session_time;
        $tmpData->allow_ip_restriction = $tmpData->allow_ip_restriction  == 1 ? "Yes" : "No";
        $tmpData->sla_blink = $tmpData->sla_blink;
        $tmpData->sla_email_time = $tmpData->sla_email_time;
        $tmpData->forward_time = $tmpData->forward_time;
        $tmpData->noncustomersms = $tmpData->noncustomersms == 1 ? "Yes" : "No";
        $tmpData->file_size_limit = $tmpData->file_size_limit / 1024 . " MB"; // Convert KB to MB

        unset(
            $tmpData->term_condition,
        );

        if (isset($oldData)) {
            $oldData->session_lifetime = $oldData->session_lifetime ?? 'N/A';
            $oldData->ci_session_time = $oldData->ci_session_time ?? null;
            $oldData->allow_ip_restriction = $oldData->allow_ip_restriction == 1 ? "Yes" : "No";
            $oldData->sla_blink = $oldData->sla_blink ?? null;
            $oldData->sla_email_time = $oldData->sla_email_time ?? null;
            $oldData->forward_time = $oldData->forward_time ?? null;
            $oldData->noncustomersms = $oldData->noncustomersms == 1 ? "Yes" : "No";
            $oldData->file_size_limit = $oldData->file_size_limit / 1024 . " MB"; // Convert KB to MB
            unset(
                $tmpData->term_condition,
            );
        }

        $columnsToSend = ['session_lifetime','ci_session_time','allow_ip_restriction','sla_blink','sla_email_time','forward_time','noncustomersms', 'file_size_limit'];
        $filteredTmpData = $tmpData->only($columnsToSend);
        $filteredOldData = isset($oldData) ? $oldData->only($columnsToSend) : null;
        $response = [
            'old_data' => $filteredOldData,
            'new_data' => $filteredTmpData
        ];

        return response()->json($response, 200);
    }

}
