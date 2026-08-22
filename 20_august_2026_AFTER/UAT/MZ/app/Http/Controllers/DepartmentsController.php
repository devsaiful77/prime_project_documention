<?php

namespace App\Http\Controllers;

use App\Division;
use App\Department;
use App\DepartmentTmp;
use App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\DepartmentRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class DepartmentsController extends Controller
{
    public $checker = false;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware('ability:superadmin|admin,accessDepartment|true');
        $this->middleware(['role_or_permission:superadmin|admin|accessDepartment|accessDepartmentChecker']);
        parent::__construct();
    }

    public function accessDepartmentChecker(){
        if (Auth::check() && Auth::user()->hasPermissionTo('accessDepartmentChecker')) {
            return $this->checker = true;
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $departmentModelName = new Department;
        // http_build_query($searchDataForView) division
        $dataForView = array();
        $searchDataForView = $request->all();
        $tblData = array();
        $dataObj = $departmentModelName
                        ->select("id","name","description","division_id",DB::raw("CASE WHEN status = 1 THEN 'Active' WHEN status = 0 THEN 'Inactive' ELSE 'Invalid' END AS status_name"),"status")
                        ->with([
                                'division'=>function($e){
                                    $e->select('id','name');
                                }
                            ]);
        $dataObj = $dataObj->orderBy("name","ASC")
                            ->get();
                            //->where("status", '=', '1')
                            //->paginate(PAGINATION_NUMBER);

        if (!empty($dataObj)) {
            $tblData = $dataObj->toArray();
        }

        $title = "Department List";
        $title_for_layout = "Department List";
        $checker = $this->accessDepartmentChecker();
        //prd($tblData);
        return view('Departments.index',compact('title','title_for_layout','tblData','searchDataForView','dataObj','checker'));
    }
    public function add()
    {
        $checker = $this->accessDepartmentChecker();
        if ($checker) {
            abort(403, 'You do not have permission to access this page.');
        }

        $id = 0;
        $tmpId = 0;
        $dataForView = array();


        $title = "Add Department";
        $title_for_layout = "Add Department";


        return view('Departments.add',compact('title','title_for_layout','id','dataForView', 'tmpId'));
    }

    public function store(Request $request)
    {
        $columnArr = ['name'];
        $charArr = ['<', '>', '"', "'", '|', '=', '#', '%', '&', '*', '!'];
        $validationResult = validateSpecialChars($request->all(), $columnArr, $charArr);
        if ($validationResult !== true) {
            return back()->withErrors($validationResult)->withInput();
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:departments,name',
            'division_id' => 'required',
        ], [
            'division_id.required' => 'The division field is required.',
        ]);
        $validator->after(function ($validator) use ($request) {
            if (\DB::table('departments')->where('name', $request->name)->exists()) {
                $validator->errors()->add('name', 'The name has already been taken.');
            }

            if (\DB::table('departments_tmp')->where('name', $request->name)->exists()) {
                $validator->errors()->add('name', 'The name has already been taken.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $departmentModelName = new DepartmentTmp();
        // $departmentModelName = new Department;

        if ($request->isMethod('post')) {

            $departmentModelName->name = $request->name;
            $departmentModelName->description = (!empty($request->description)) ? $request->description : '';
            $departmentModelName->division_id = $request->division_id;
            $departmentModelName->status = 2; // status = 1 is published
            $departmentModelName->action = "Add";
            $departmentModelName->created_by = Auth::user()->id;
            $departmentModelName->ip = $this->getClientIp();

            if ($departmentModelName->save()) {
                flash('Department has been inserted successfully, please wait for Checker Approval', 'success');
                return redirect('Departments');
            } else {
                flash('Failed to insert data', 'danger');
                return redirect('Departments/add');
            }
        }
    }

    public function edit($id)
    {
        $checker = $this->accessDepartmentChecker();
        if ($checker) {
            abort(403, 'You do not have permission to access this page.');
        }
        $tmpId = 0;

        $departmentModelName = new Department;
        $title = " Edit Department";
        $title_for_layout = 'Edit Department';

        $dataForView = $departmentModelName->where
                                            ([
                                                ['id', $id]
                                            ])->first();

        //prd($dataForView->toArray());
        if ($dataForView->status == 0) {
            abort(403,'Edit Not Allowed !!!');
        }
        return view('Departments.add', compact('title', 'title_for_layout', 'dataForView','id', 'tmpId'));
    }

    public function departmentTmpEdit($id = null, Request $req)
    {
        // dd("tmp e ase");
        $checker = $this->accessDepartmentChecker();
        if ($checker) {
            abort(403, 'You do not have permission to access this page.');
        }

        $departmentModelName = new DepartmentTmp;
        $title = "Send Back Department Edit";
        $title_for_layout = 'Edit Department';
        $searchDataForView = $req->all();
        $tmpId = $id;

        $dataForView = $departmentModelName->where
                                            ([
                                                ['id', $id]
                                            ])->first();

        return view('Departments.add', compact('title', 'title_for_layout','dataForView', 'searchDataForView', 'id', 'tmpId'));
    }

    public function update(Request $request,$id)
    {
        $isTemp = !empty($request->tmpId) && $request->tmpId != 0;

        $columnArr = ['name'];
        $charArr = ['<', '>', '"', "'", '|', '=', '#', '%', '&', '*', '!'];
        $validationResult = validateSpecialChars($request->all(), $columnArr, $charArr);
        if ($validationResult !== true) {
            return back()->withErrors($validationResult)->withInput();
        }
        
        $tableName = $isTemp ? 'departments_tmp' : 'departments';

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:'.$tableName.',name,'.$id,
            'division_id' => 'required',
        ], [
            'division_id.required' => 'The division field is required.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        $data = $request->all();

        $existingRecords = $isTemp ? DepartmentTmp::where('id', $id)->get() : DepartmentTmp::where('master_id', $id)->get();

        $hasInvalidRecord = $existingRecords->contains(function ($record) {
            return $record->form_status != 7 && $record->form_status != -1;
        });

        if ($hasInvalidRecord) {
            flash('An entry already exists, please wait for Checker Approval', 'danger');
            return Redirect::back();
        }

        $additionalParams = (!empty($data['additionalParams'])) ? $data['additionalParams'] : "";
        unset($data['additionalParams']);

        $data['modified_by'] = Auth::user()->id;
        $data['division_id'] = $request->division_id;
        // $update = $departmentModelName->where([['id', $id]])->first();
        // $departmentModelName = new Department;
        // $departmentModelName = new DepartmentTmp();
        if ($isTemp) {
            $departmentModelName = DepartmentTmp::where('id', $id)->first();
        }else{
            $departmentModelName = new DepartmentTmp();
            $update = Department::where('id', $id)->first();
        }

        $departmentModelName->name = $request->name;
        $departmentModelName->description = $request->description;
        $departmentModelName->division_id = $request->division_id;
        // $departmentModelName->status = $update->status;
        $departmentModelName->status = 2;
        $departmentModelName->form_status = 0;
        $departmentModelName->action = "Edit";
        // $departmentModelName->master_id = $update->id;
        $departmentModelName->master_id = $isTemp ? $departmentModelName->master_id : $update->id;
        // dd($departmentModelName->master_id);
        $departmentModelName->created_by = Auth::user()->id;
        $departmentModelName->ip = $this->getClientIp();


        if ($departmentModelName->save()) {
            flash('Department has been updated successfully, please wait for Checker Approval.', 'success');
            return redirect('/Departments'.$additionalParams);
        } else {
            flash('Failed to update Departments, Please try again', 'danger');
        }
    }

    public function status($id = null, $status = "")
    {
        $checker = $this->accessDepartmentChecker();
        if ($checker) {
            abort(403, 'You do not have permission to access this page.');
        }

        $departmentModelName = new Department;
        if ($status != 1 && $status != 0) {
            flash($status.' is not Allowed!!!','danger');
            return Redirect::back();
        }

        // Check if there's already a record in DepartmentTmp with the same master_id
        $existingTmpRecords = DepartmentTmp::where('master_id', $id)->get();

        $hasInvalidRecord = $existingTmpRecords->contains(function ($record) {
            return $record->form_status != 7 && $record->form_status != -1;
        });

        if ($hasInvalidRecord) {
            flash('An entry already exists, please wait for Checker Approval', 'danger');
            return Redirect::back();
        }


        $data['status'] = $status;

        $update = $departmentModelName->where([['id', $id]])->first();
        $departmentTmpModel = new DepartmentTmp();
         if ($status == 0){
            $departmentTmpModel->status = 0;
            $departmentTmpModel->action = "Inactive";
        }else{
            $departmentTmpModel->status = 1;
            $departmentTmpModel->action = "Active";
        }
        $departmentTmpModel->name = $update->name;
        $departmentTmpModel->description = $update->description;
        $departmentTmpModel->division_id = $update->division_id;
        $departmentTmpModel->master_id = $update->id;
        $departmentTmpModel->created_by = Auth::user()->id;
        $departmentTmpModel->ip = $this->getClientIp();

        $departmentTmpModel->save($data);

        if ($status == 1) {
            flash('Department Active Request Send to Checker', 'success');
        } elseif ($status == 0) {
            flash('Department InActive Request Send to Checker', 'danger');
        }
        // return redirect('/Groups');
        return Redirect::back();

    }






    public function approveDepartment($id)
    {
        $checker = $this->accessDepartmentChecker();
        if ($checker == false) {
            abort(403, 'You do not have permission to access this page.');
        }

        try {
            DB::beginTransaction();
            $tmpDepartment = DB::table('departments_tmp')->where('id', $id)->where('status', '!=', 3)->first();
            // $tmpDepartment = DB::table('divisions_tmp')->where('id', $id)->where('status', 0)->first();

            if (!$tmpDepartment) {
                flash('Department not found or not approved yet.', 'danger');
                return redirect()->back();
            }

            $departmentData = [
                'name'        => $tmpDepartment->name,
                'description' => $tmpDepartment->description,
                'division_id' => $tmpDepartment->division_id,
                'status'      => $tmpDepartment->status ==2? 1: $tmpDepartment->status,
                'created_by'  => Auth::user()->id,
                'modified_by' => Auth::user()->id,
                'ip'          => $this->getClientIp(),
                'created_at'  => now(),
                'updated_at'  => now(),
            ];

            if($tmpDepartment->master_id != null && $tmpDepartment->action != "Add"){
                DB::table('departments')->where('id', $tmpDepartment->master_id)->update($departmentData);
            }else{

                DB::table('departments')->insert($departmentData);
            }

            DB::table('departments_tmp')->where('id', $tmpDepartment->id)->delete();
            DB::commit();
            if($tmpDepartment->status == 0){
                flash('Department has been inActive.', 'danger');
            }elseif($tmpDepartment->status == 1){
                flash('Department has been approved.', 'success');
            }else{
                flash('Department has been approved.', 'success');
            }
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            flash('An error occurred during the approval process: ' . $e->getMessage(), 'danger');
            return redirect()->back();
        }
    }

    public function departmentTmpList()
    {
        $checker = $this->accessDepartmentChecker();
        if ($checker) {
            $isChecker = true;
            $departmentTmpData = DepartmentTmp::whereNotIn('form_status',[7, -1])->get();
        }
        else{
            $isChecker = false;
            $departmentTmpData = DepartmentTmp::whereIn('form_status', [7, -1])->get();
        }
        return view('Departments.index_tmp',compact('departmentTmpData', 'isChecker'));
    }

    public function checkerTableData($id)
    {
        $tmpData = DepartmentTmp::findOrFail($id);

        if (!empty($tmpData->master_id)) {
            $oldData = Department::findOrFail($tmpData->master_id);
        }

        $tmpData->name = $tmpData->name ?? null;
        $tmpData->description = $tmpData->description;
        $tmpData->status = $tmpData->status == 1 ? "Active" : "Inactive";
        unset(
            $tmpData->division_id,
        );

        if (isset($oldData)) {
            $oldData->name = $oldData->name ?? null;
            $oldData->description = $oldData->description ?? "N/A";
            $oldData->status = $oldData->status == 1 ? "Active" : "Inactive";
            unset(
                $oldData->division_id,
            );
        }

        $columnsToSend = ['name','description','status'];

        $filteredTmpData = $tmpData->only($columnsToSend);

        $filteredOldData = isset($oldData) ? $oldData->only($columnsToSend) : null;

        $response = [
            'old_data' => $filteredOldData,
            'new_data' => $filteredTmpData
        ];

        return response()->json($response, 200);
    }

    public function departmentSendback(Request $request, $id)
    {
        $checker = $this->accessDepartmentChecker();
        if ($checker == false) {
            abort(403, 'You do not have permission to access this page.');
        }

        $request->validate([
            'comments' => 'required'
        ]);
        $tmpData = DepartmentTmp::where('id', $id)->first();
        $tmpData->form_status = 7;
        $tmpData->modified_by = null;
        $tmpData->comments = $request->comments;
        $tmpData->save();

        flash('Send back Successfully!', 'success');
        return redirect()->back();
    }

    public function departmentReject(Request $request, $id)
    {
        $checker = $this->accessDepartmentChecker();
        if ($checker == false) {
            abort(403, 'You do not have permission to access this page.');
        }

        $request->validate([
            'comments' => 'required'
        ]);
        $tmpData = DepartmentTmp::where('id', $id)->first();
        $tmpData->form_status = -1;
        $tmpData->modified_by = Auth::user()->user_id;
        $tmpData->comments = $request->comments;
        $tmpData->save();

        flash('Rejected !!!', 'danger');
        return redirect()->back();
    }

    public function departmentAssign($id)
    {
        $tmpData = DepartmentTmp::where('id', $id)->first();
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
        $tmpData = DepartmentTmp::findOrFail($id);

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

}
