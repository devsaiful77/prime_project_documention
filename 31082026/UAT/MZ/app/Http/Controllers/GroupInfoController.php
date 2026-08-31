<?php


namespace App\Http\Controllers;


use App\GroupInfo;
use App\GroupInfoTmp;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class GroupInfoController extends Controller
{
    public $checker = false;
	public function __construct()
    {
        // $this->middleware('auth');
       // $this->middleware('ability:superadmin|admin,accessGroup');
        $this->middleware(['role_or_permission:superadmin|admin|accessGroup']);
        parent::__construct();
    }

    public function accessGroupChecker(){
        if (Auth::check() && Auth::user()->hasPermissionTo('accessGroupChecker')) {
            return $this->checker = true;
        }else{
            return $this->checker;
        }
    }

    public function index(){
        $tblData = GroupInfo::all();
        $checker = $this->accessGroupChecker();
        return view('group_info.index',compact('tblData', 'checker'));
    }
    public function create(){
        $checker = $this->accessGroupChecker();
        if ($checker) {
            abort(403, 'You do not have permission to access this page.');
        }
        return view('group_info.create');
    }
    public function store(Request $request){

        $columnArr = ['name'];
        $charArr = ['<', '>', '"', "'", '|', '=', '#', '%', '&', '*', '!'];
        $validationResult = validateSpecialChars($request->all(), $columnArr, $charArr);
        if ($validationResult !== true) {
            return back()->withErrors($validationResult)->withInput();
        }

        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'department_id'=>'required',
            'group_level_id'=>'required'
        ],['department_id.required'=> 'The department field is required.']);

        $validator->after(function ($validator) use ($request) {
            if (DB::table('group_info')->where('name', $request->name)->exists()) {
                $validator->errors()->add('name', 'The name has already been taken in group info table.');
            }

            if (DB::table('group_info_tmps')->where('name', $request->name)->exists()) {
                $validator->errors()->add('name', 'The name has already been taken, please wait for Checker Approval.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $groupInfoTmp = new GroupInfoTmp();
        $groupInfoTmp->name = $request->name;
        $groupInfoTmp->department_id = $request->department_id;
        $groupInfoTmp->description = $request->description;
        $groupInfoTmp->address = $request->address;
        $groupInfoTmp->group_level_id = $request->group_level_id;
        $groupInfoTmp->action = "Add";
        $groupInfoTmp->status = 2;
        $groupInfoTmp->created_by = Auth::user()->id;
        $groupInfoTmp->ip = $this->getClientIp();

        if($groupInfoTmp->save()){
            flash('Group info has been inserted For Review in Checker', 'success');
            return redirect('group-info');
        }else{
            flash('Failed to insert data', 'danger');
            return redirect('group-info/create');
        }
    }

    public function edit($id){
        $checker = $this->accessGroupChecker();
        if ($checker) {
            abort(403, 'You do not have permission to access this page.');
        }

        $tmpId = 0;
        $row = GroupInfo::find(decrypt($id));
        return view('group_info.edit',compact('row', 'tmpId'));
    }

    public function groupInfoTmpEdit(Request $request, $id = null){
        $checker = $this->accessGroupChecker();
        if ($checker) {
            abort(403, 'You do not have permission to access this page.');
        }

        $groupInfoTmp = GroupInfoTmp::findOrFail($id);
        $row = $groupInfoTmp;
        $tmpId = $id;

        return view('group_info.edit', compact('row', 'tmpId'));

    }
    public function update(Request $request,$id){

        $isTemp = !empty($request->tmpId) && $request->tmpId != 0;

        $columnArr = ['name'];
        $charArr = ['<', '>', '"', "'", '|', '=', '#', '%', '&', '*', '!'];
        $validationResult = validateSpecialChars($request->all(), $columnArr, $charArr);
        if ($validationResult !== true) {
            return back()->withErrors($validationResult)->withInput();
        }

        $tableName = $isTemp ? 'group_info_tmps' : 'group_info';

        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                Rule::unique($tableName, 'name')->ignore($id, 'id')
            ],
            'department_id' => 'required',
            'group_level_id' => 'required',
        ], [
            'name.required' => 'The group name is required.',
            'department_id.required' => 'The department field is required.',
            'group_level_id.required' => 'The group level field is required.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $existingRecords = $isTemp ? GroupInfoTmp::where('id', $id)->get() : GroupInfoTmp::where('master_id', $id)->get();

        $hasInvalidRecord = $existingRecords->contains(function ($record) {
            return $record->form_status != 7 && $record->form_status != -1;
        });

        if ($hasInvalidRecord) {
            flash('An entry already exists, please wait for Checker Approval', 'danger');
            return Redirect::back();
        }


        // $validator->after(function ($validator) use ($request, $id, $hasInvalidRecord) {
        //     $nameExistsInGroupInfo = DB::table('group_info')
        //         ->where('name', $request->name)
        //         ->where('id', '!=', $id)
        //         ->exists();

        //     $nameExistsInTmp = DB::table('group_info_tmps')
        //         ->where('name', $request->name)
        //         ->where('id', '!=', $id)
        //         ->where('form_status', '!=', 7)
        //         ->where('form_status', '!=', -1)
        //         ->exists();


        //     if ($nameExistsInGroupInfo) {
        //         $validator->errors()->add('name', 'The group name has already been taken in the group_info table.');
        //     }

        //     if ($nameExistsInTmp) {
        //         $validator->errors()->add('name', 'This group name is currently under review by Checker. Please wait for approval before proceeding.');
        //     }

        //     if ($hasInvalidRecord) {
        //         $validator->errors()->add('name', 'There are invalid records under review in the group_info_tmps table. Please wait for the Checker to approve or reject.');
        //     }
        // });

        // if ($validator->fails()) {
        //     return redirect()->back()->withErrors($validator)->withInput();
        // }

        $groupInfo = new GroupInfo();
        $data = $request->all();

        $additionalParams = (!empty($data['additionalParams'])) ? $data['additionalParams'] : "";
        unset($data['additionalParams']);

        $data['modified_by'] = Auth::user()->id;

        if($isTemp){
            $groupInfoTmp = GroupInfoTmp::findOrFail($id);
        }else{
            $groupInfoTmp = new GroupInfoTmp();
            $update = $groupInfo->where('id', $id)->first();
        }

        $groupInfoTmp->name = $request->name;
        $groupInfoTmp->department_id = $request->department_id;
        $groupInfoTmp->group_level_id = $request->group_level_id;
        $groupInfoTmp->description = $request->description;
        $groupInfoTmp->address = $request->address;
        $groupInfoTmp->form_status = 0;
        $groupInfoTmp->status = 2;
        $groupInfoTmp->action = "Edit";
        $groupInfoTmp->master_id = $isTemp ? $groupInfoTmp->master_id : $update->id;
        $groupInfoTmp->created_by = Auth::user()->id;
        $groupInfoTmp->ip = $this->getClientIp();

        if ($groupInfoTmp->save()) {
            flash('Group info has been updated For Review in Checker', 'success');
            if($isTemp){
                flash("Group info temporary data updated successfully.", "success");
                return redirect('group-info/action-queue-list');
            }
            return redirect('/group-info'.$additionalParams);
        } else {
            flash('Failed to update group info, Please try again', 'danger');
        }
    }
    public function destroy($id){
        $row = GroupInfo::find(decrypt($id));
        $row->delete();
        return redirect()->back();
    }
    public function activate($id,$state){
        $row = GroupInfo::find(decrypt($id));
        $row->update([
           'is_active'=>$state
        ]);
        return redirect()->back();
    }
    public function groupList($department_id){
        $rows = GroupInfo::where('is_active',true)->where('department_id',$department_id)->get();
        return json_encode($rows);
    }
    public function getGroup(Request $request){

        $search = $request->search;

        if($search == ''){
            $groups = GroupInfo::where('is_active','=',1)->where('group_level_id','<>',1)->orderby('name','asc')->select('id','name')->limit(5)->get();
        }else{
            $groups = GroupInfo::where('is_active','=',1)->where('group_level_id','<>',1)->orderby('name','asc')->select('id','name')->where('name', 'like', '%' .$search . '%')->limit(5)->get();
        }

        $response = array();
        foreach($groups as $group){
            $response[] = array("value"=>$group->id,"label"=>$group->name);
        }

        return json_encode($response);
    }

    public function status($id = null, $status = "")
    {
        $checker = $this->accessGroupChecker();
        if ($checker) {
            abort(403, 'You do not have permission to access this page.');
        }

        $existingTmpRecords = GroupInfoTmp::where('master_id', $id)->get();

        $hasInvalidRecord = $existingTmpRecords->contains(function ($record) {
            return $record->form_status != 7 && $record->form_status != -1;
        });

        if ($hasInvalidRecord) {
            flash('An entry already exists, please wait for Checker Approval', 'danger');
            return Redirect::back();
        }

        $groupInfo = new GroupInfo();
        if ($status != 1 && $status != 0) {
            flash($status.' is not Allowed!!!','danger');
            return Redirect::back();
        }

        $update = $groupInfo->where([['id', $id]])->first();

        $groupInfoTmp = new GroupInfoTmp();
        if ($status == 0){
            $groupInfoTmp->status = 0;
            $groupInfoTmp->action = "Inactive";
        }else{
            $groupInfoTmp->status = 1;
            $groupInfoTmp->action = "Active";
        }
        $groupInfoTmp->master_id = $update->id;
        $groupInfoTmp->name = $update->name;
        $groupInfoTmp->department_id = $update->department_id;
        $groupInfoTmp->group_level_id = $update->group_level_id;

        $groupInfoTmp->description = $update->description;
        $groupInfoTmp->address = $update->address;
        $groupInfoTmp->created_by = Auth::user()->id;
        $groupInfoTmp->ip = $this->getClientIp();

        $groupInfoTmp->save();

        if ($status == 1) {
            flash('Group info Active Request Send to Checker', 'success');
        } elseif ($status == 0) {
            flash('Group info Inactive Request Send to Checker', 'danger');
        }
        // return redirect('/Divisions');
        return Redirect::back();
    }

    public function approveGroupInfo($id)
    {
        $checker = $this->accessGroupChecker();
        if($checker == false){
            abort(403, 'You do not have permission to access this page.');
        }

        DB::beginTransaction();

        try {
            // $groupInfoTmp = DB::table('group_info_tmps')->where('status', '!=', 3)->first();
            $groupInfoTmp = DB::table('group_info_tmps')->where('id', $id)->first();

            if (!$groupInfoTmp) {
                flash('Group info not found or not approved yet.', 'danger');
                return redirect()->back();
            }

            $groupInfoData = [
                'name'           => $groupInfoTmp->name,
                'description'    => $groupInfoTmp->description,
                'address'    => $groupInfoTmp->address,
                'department_id'  => $groupInfoTmp->department_id,
                'group_level_id' => $groupInfoTmp->group_level_id,
                'is_active'      => $groupInfoTmp->status == 2 ? 1: $groupInfoTmp->status,
                'created_at'     => now(),
                'updated_at'     => now(),
            ];

            if($groupInfoTmp->master_id != null && $groupInfoTmp->action != "Add"){
                DB::table('group_info')->where('id', $groupInfoTmp->master_id)->update($groupInfoData);
            }else{
                DB::table('group_info')->insert($groupInfoData);
            }

            DB::table('group_info_tmps')->where('id', $groupInfoTmp->id)->delete();
            DB::commit();

            if($groupInfoTmp->status == 0){
                flash('Group info has been inActive.', 'danger');
            }elseif($groupInfoTmp->status == 1){
                flash('Group info has been activated.', 'success');
            }else{
                flash('Group info has been approved.', 'success');
            }
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            flash('An error occurred during the approval process: ' . $e->getMessage(), 'danger');
            return redirect()->back();
        }
    }

    public function groupInfoQueueList(){
        $checker = $this->accessGroupChecker();
        if($checker){
            $isChecker = true;
            $groupInfoTmp = GroupInfoTmp::with('department')->whereNotIn('form_status', [7, -1])->get();
        }else{
            $isChecker = false;
            $groupInfoTmp = GroupInfoTmp::with('department')->whereIn('form_status', [7, -1])->get();
        }

        return view('group_info.index_tmp',compact('groupInfoTmp', 'isChecker'));
    }

    public function groupInfoAssign($id){
        $tmpData = GroupInfoTmp::where('id', $id)->first();

        $tmpData->form_status = 1;
        $tmpData->modified_by = Auth::user()->user_id;
        $tmpData->save();

        $data = [
            'message' => 'Assigned Successfully from your End!',
            'id' => $id,
        ];

        return response()->json($data, 200);
    }

    public function buttonControl($id){
        $tmpData = GroupInfoTmp::findOrFail($id);

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
        $tmpData = GroupInfoTmp::with('department')->findOrFail($id);

        if (!empty($tmpData->master_id)) {
            $oldData = GroupInfo::with('dept')->findOrFail($tmpData->master_id);
        }

        $tmpData->department_name = $tmpData->department->name ?? null;
        $tmpData->group_level = $tmpData->group_level_id == 1 ? "Touch Point" : "N/A";
        $tmpData->status = $tmpData->status == 1 ? "Active" : "Inactive";
        unset($tmpData->department,
            $tmpData->department_id,
            $tmpData->group_level_id,
            $tmpData->is_active
        );

        if (isset($oldData)) {
            $oldData->department_name = $oldData->dept->name ?? null;
            $oldData->group_level = $oldData->group_level_id == 1 ? "Touch Point" : "N/A";
            $oldData->status = $oldData->is_active == 1 ? "Active" : "Inactive";
            unset($oldData->dept,
                $oldData->department_id,
                $oldData->group_level_id,
                $oldData->is_active
            );
        }

        $columnsToSend = ['name', 'department_name', 'description', 'address', 'group_level', 'status'];

        $filteredTmpData = $tmpData->only($columnsToSend);

        $filteredOldData = isset($oldData) ? $oldData->only($columnsToSend) : null;

        $response = [
            'old_data' => $filteredOldData,
            'new_data' => $filteredTmpData
        ];

        return response()->json($response, 200);
    }

    public function groupInfoSendback(Request $request, $id){
        $checker = $this->accessGroupChecker();
        if ($checker == false) {
            abort(403, 'You do not have permission to access this page.');
        }

        $request->validate([
            'comments' => 'required'
        ]);

        $tmpData = GroupInfoTmp::where('id', $id)->first();
        $tmpData->form_status = 7;
        $tmpData->modified_by = null;
        $tmpData->comments = $request->comments;
        $tmpData->save();

        flash('Send back Successfully!', 'success');
        return redirect()->back();
    }

    public function groupInfoReject(Request $request, $id){
        $checker = $this->accessGroupChecker();
        if ($checker == false) {
            abort(403, 'You do not have permission to access this page.');
        }

        $request->validate([
            'comments' => 'required'
        ]);

        $tmpData = GroupInfoTmp::where('id', $id)->first();
        $tmpData->form_status = -1;
        $tmpData->modified_by = Auth::user()->user_id;
        $tmpData->comments = $request->comments;
        $tmpData->save();

        flash('Request rejected!!', 'danger');
        return Redirect::back();
    }
}
