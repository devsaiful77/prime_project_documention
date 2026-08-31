<?php

namespace App\Http\Controllers;

use Image;
use App\Role;
use App\Unit;
use App\User;
use Validator;
use App\Module;
use App\Control;
use App\UserTmp;
use App\Division;
use App\RoleUser;
use App\SMSEmail;
use App\UserUnitTmp;
// use Illuminate\Http\UploadedFile;

use App\UserUnit;
use App\GroupInfo;
use App\Department;
use App\OutgoingSMS;
use App\SubgroupInfo;
use App\IssueGroup;
use App\Http\Requests;

use App\OutgoingEMAIL;
use App\PasswordHistory;
use App\Exports\UsersExport;

use Illuminate\Http\Request;

use PhpParser\Node\Expr\New_;
use App\Exports\UserTmpExport;

use OwenIt\Auditing\Models\Audit;
use Adldap\Laravel\Facades\Adldap;
use App\Enum\AccessApp;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\UserProfileRequest;
use App\Http\Requests\UserPasswordRequest;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Contracts\Encryption\DecryptException;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Str;

class UsersController extends Controller
{

    protected $modelName;
    protected $modelNameTmp;
    public $checker = false;

    public function __construct()
    {
        $this->modelName = new User;
        $this->modelNameTmp = new UserTmp;
       parent::__construct();
        // $this->middleware(['role:superadmin|useraccess'])->only('add','store','edit','update');
        // $this->middleware(['role:superadmin|useraccessChecker'])->only('approveUnit','setCallBackUnit','approveUnassign','approveUnit');
        $this->middleware(['role_or_permission:superadmin|accessUser|accessUserChecker'])->except('changeProfilePhoto','uploadProfilePhoto','setPassword','updatePassword', 'resetPassword');
    }

    public function accessUserChecker(){
        if (Auth::check() && Auth::user()->hasPermissionTo('accessUserChecker')) {
            return $this->checker = true;
        }
    }
    public function index(Request $request)
    {
        $dataForView = array();
        $searchDataForView = array();
        $userData = array();

        $selectedValue = $request->get('search_value');
        $selectedStatus = $request->get('status');
        $selectedUnit = $request->get('unit_id');
        $statusForSearch = 1;

        if ($selectedStatus == 2) {
            $statusForSearch = 0;
        }

        $userModel = new User;
        $userDataObj = $userModel
            ->select(
                'users.id',
                'users.name',
                'users.designation',
                'users.user_id',
                'users.emp_id',
                'users.email',
                'users.mobile_no',
                'users.status',
                'users.remarks',
                'users.created_at',
                DB::raw("CASE WHEN users.status = 1 THEN 'Active' WHEN users.status = 0 THEN 'Inactive' WHEN users.status = -2 THEN 'Close' ELSE 'Invalid' END AS status_name"),
                DB::raw('MAX(log_users.log_in_at) as last_login_time')
            )
            ->leftJoin('log_users', 'log_users.user_id', '=', 'users.id')
            ->groupBy('users.id')

            ->with([
                'roles' => function ($q) {
                    $q->where('roles.module', AccessApp::ServiceComplaint);
                },
                'user_unit' => function ($q) {
                    $q->where('user_units.module', AccessApp::ServiceComplaint);
                }
            ]);
            // ->with(['roles' => function ($q) { return $q; }, 'user_unit']);

        if (!empty($selectedUnit)) {
            $userDataObj = $userDataObj
                ->leftJoin('user_units', 'user_units.user_id', 'users.id')
                ->where(function ($q) use ($selectedUnit) {
                    $q
                        ->where('user_units.unit_id', $selectedUnit)
                        ->orWhere('user_units.unit_id', 'LIKE', '%,'.$selectedUnit)
                        ->orWhere('user_units.unit_id', 'LIKE', $selectedUnit.',%')
                        ->orWhere('user_units.unit_id', 'LIKE', '%,'.$selectedUnit.',%');
                });
        }

        if (!empty($selectedValue)) {
            $searchDataForView['search_value'] = $selectedValue;
            $userDataObj = $userDataObj
                ->where(function ($q) use ($selectedValue) {
                    $q
                        ->where('users.user_id', 'LIKE', '%'.$selectedValue.'%')
                        ->orWhere('users.emp_id', 'LIKE', '%'.$selectedValue.'%')
                        ->orWhere('users.mobile_no', 'LIKE', '%'.$selectedValue.'%')
                        ->orWhere('users.name', 'LIKE', '%'.$selectedValue.'%')
                        ->orWhere('users.email', 'LIKE', '%'.$selectedValue.'%');
                });
        }

        $searchDataForView['status'] = $selectedStatus;
        $searchDataForView['unit_id'] = $selectedUnit;
        $searchDataForView['search_value'] = $selectedValue;

        $isDownload = $request->get('download');

        if ($isDownload) {
            $userData = $userDataObj->get();
            $fileName = 'user_details_report_' . date('dmYhis') . '.xlsx';
            return Excel::download(new UsersExport($userData), $fileName);
        }

        $userDataObj = $userDataObj->orderBy("users.id", "DESC")->paginate(PAGINATION_NUMBER);

        if (!empty($userDataObj)) {
            $userData = $userDataObj->toArray();
        }

        $userId = (!empty(Auth::user()->id)) ? Auth::user()->id : 0;

        $roleModelName = new Role;
        $allRoleData = $roleModelName
            ->select('id', 'display_name', 'module')
            ->where('name', '<>', 'superadmin')
            ->orderBy('display_name', 'ASC')
            ->pluck('display_name', 'id')
            ->toArray();

        $unitModelName = new Unit;
        $allUnitData = $unitModelName
            ->select('id', 'name', 'module')
            ->where('status', 1)
            ->orderBy('name', 'ASC')
            ->pluck('name', 'id', 'subgroup_info_id', 'group_info_id')
            ->toArray();

        $home_menu_icon = "fa fa-user";
        $title = "User List";
        $title_for_layout = 'User List';
        $checker = $this->accessUserChecker();
        // dd($userData['data']);
        return view('Users/users', compact(
            "userData",
            "dataForView",
            "searchDataForView",
            "selectedValue",
            "selectedStatus",
            "selectedUnit",
            "title_for_layout",
            "title",
            "userDataObj",
            "userId",
            "home_menu_icon",
            "allRoleData",
            "allUnitData",
            'checker'
        ));
    }

    public function add()
    {
        $checker = $this->accessUserChecker();
        if ($checker) {
            abort(403, 'You do not have permission to access this page.');
        }
        $uploadError = 0;
        $id = 0;
        $uploadErrorMsg = "";
        $dataForView = array();
        $dataForViewUser = array();
        $userInfo = array();



        $title = "Add User";
        $title_for_layout = 'Add User';
        $home_menu_icon = "fa fa-user";

        $roleModelName = new Role;
        $allRoleData = $roleModelName
                            ->select('id','display_name')
                            ->where('name','<>','superadmin')
                            ->where('module', AccessApp::ServiceComplaint)
                            ->where(function($q) {
                                $q->where('is_user', 0)->orWhereNull('is_user');
                            })
                            ->orderBy('display_name','ASC')
                            ->pluck('display_name', 'id')
                            ->toArray();
        $currentRoleId = 0;
        // add unit setup code
        $divisionModelName = new Division;
        $allDivisionData = $divisionModelName
                            ->select('id','name')
                            ->where('status',1)
                            ->orderBy('name','ASC')
                            ->pluck('name', 'id')
                            ->toArray();

        $departmentModelName = new Department;
        $allDepartmentData = $departmentModelName
                            ->select('id','name')
                            ->where('status',1)
                            ->orderBy('name','ASC')
                            ->pluck('name', 'id')
                            ->toArray();

        $groupModelName = new GroupInfo();
        $allGroupData = $groupModelName
                            ->select('id','name')
                            ->where('is_active',1)
                            ->orderBy('name','ASC')
                            ->pluck('name', 'id')
                            ->toArray();

        $subgroupModelName = new SubgroupInfo();
        $allSubgroupData = $subgroupModelName
                            ->select('id','name')
                            ->where('is_active',1)
                            ->orderBy('name','ASC')
                            ->pluck('name', 'id')
                            ->toArray();

        return view('Users/add',compact("uploadErrorMsg","dataForViewUser", "id","uploadError","dataForView","allRoleData","currentRoleId","title","title_for_layout","home_menu_icon", 'userInfo','dataForView','allDivisionData','allDepartmentData','allGroupData','allSubgroupData'));
    }


    public function store(UserRequest $request)
    {

        $existingTmpRecords = UserTmp::where('user_id', $request->user_id)->get();

        $hasInvalidRecord = $existingTmpRecords->contains(function ($record) {
            return $record->form_status != 7 && $record->form_status != -1;
        });

        if ($hasInvalidRecord) {
            flash('An entry already exists, please wait for Checker Approval', 'danger');
            return Redirect::back();
        }

        DB::beginTransaction();

        try {
            $data = $request->all();
            Log::info('Request data for store:', $data);

            $existingTmpRecords = UserTmp::where('user_id', $request->user_id)->get();

            Log::info('Existing temp records:', $existingTmpRecords->toArray());

            $hasInvalidRecord = $existingTmpRecords->contains(function ($record) {
                return $record->form_status != 7 && $record->form_status != -1;
            });
            Log::info('Has invalid record: ' . $hasInvalidRecord);

            if ($hasInvalidRecord) {
                flash('User already exists, please wait for Checker Approval', 'danger');
                return Redirect::back();
            }

            // Create a new temporary record
            $this->modelNameTmp->user_id = $request->user_id;
            $this->modelNameTmp->emp_id = $request->emp_id;
            $this->modelNameTmp->name = $request->name;
            $this->modelNameTmp->designation = $request->designation;
            $this->modelNameTmp->email = $request->email;
            $this->modelNameTmp->mobile_no = $request->mobile_no;
            $this->modelNameTmp->remarks = $request->remarks;
            $this->modelNameTmp->ip = $this->getClientIp();
            $this->modelNameTmp->status = $request->status;
            $this->modelNameTmp->role_id = $request->role_id ?? 0;
            $this->modelNameTmp->action = "Add";
            $this->modelNameTmp->master_id = null;
            $this->modelNameTmp->form_status = 0;
            $this->modelNameTmp->created_by = auth()->user()->user_id;
            $this->modelNameTmp->modified_by = "";
            $this->modelNameTmp->module = AccessApp::ServiceComplaint;
            $this->modelNameTmp->comments = "Created by " . auth()->user()->name;

            Log::info('Temporary model before save:', $this->modelNameTmp->toArray());

            // Save the new record
            if (!$this->modelNameTmp->save()) {
                throw new \Exception('Failed to save the new user record.');
            }
            $newRecordId = $this->modelNameTmp->id;
            $userId = $this->modelNameTmp->user_id;
            $name = $this->modelNameTmp->name;

            Log::info('Temporary record created successfully');

            DB::table('user_units_tmp')->where('user_id', $request->user_id)->delete();
            DB::table('model_has_roles_tmp')->where('model_id', $request->user_id)->delete();

            if($request->role_id >0){
                DB::table('model_has_roles_tmp')->insert([
                    'role_id' => $request->role_id,
                    'model_type' => 'App\User',
                    'model_id' => $request->user_id,
                    'module' => AccessApp::ServiceComplaint,
                ]);
            }else{
                $unitIds = $request->unit_id ?? [];
                // if(count($unitIds) > 0){
                $hasRequ = collect($unitIds)->first(fn($id) => Str::startsWith($id, 'requ-'));
                    switch ($request['type']) {

                        case 'Subgroup':
                            if (empty($hasRequ)) {
                                $this->handleSubgroup($newRecordId, $request, $unitIds, $userId);
                                break;
                            } else {
                                flash('Failed to update the user, because you selected requisition region role.', 'danger');
                                return Redirect::back();
                            }

                        case 'Group':
                            $this->handleGroup($newRecordId, $request, $unitIds, $userId); // No $id for a new user
                            break;

                        case 'Department':
                            $this->handleDepartment($newRecordId, $request, $unitIds, $userId); // No $id for a new user
                            break;

                        case 'Division':
                            $this->handleDivision($newRecordId, $request, $unitIds, $userId); // No $id for a new user
                            break;

                        case 'Region':
                            if (!empty($hasRequ) && count($unitIds) == 1) {
                                $unitId = Str::after($hasRequ, 'requ-');
                                $this->handleRegion($newRecordId, $request, $unitId, $userId);
                                break;
                            } else {
                                flash('Failed to update the user, because you selected subgroup role.', 'danger');
                                return Redirect::back();
                            }

                        default:
                            throw new \Exception('Invalid type provided.');
                    }

                // }
            }

            if (!empty($request->get('oldDataForAudit'))) {
                $audit = new Audit();
                $audit->auditable_type = 'App\User';
                $audit->user_type = 'App\User';
                $audit->user_id = Auth::id();
                $audit->event = 'create';
                $audit->auditable_id = $this->modelNameTmp->id;
                $audit->old_values = json_decode($request->get('oldDataForAudit'));
                $audit->save();
            }

            // Commit the transaction
            DB::commit();
            flash('User has been created successfully', 'success');
            return redirect('/Users' . $request->get('additionalParams'));

        } catch (\Exception $e) {
            // Rollback the transaction if any error occurs
            DB::rollback();
            Log::error('User creation failed: ' . $e->getMessage());
            flash('Failed to create the user, please try again.', 'danger');
            return Redirect::back();
        }
    }


    public function edit($id = null, Request $req)
    {
        $checker = $this->accessUserChecker();
        if ($checker) {
            abort(403, 'You do not have permission to access this page.');
        }

        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            toast()->error('Internal Error Occurs!!!', 'Danger');
            return Redirect::back();
        }
        $dataForViewUser = $this->modelName
                                ->with([
                                    'roles'
                                ])
                                ->where('id', $id)
                                ->first();

        $roleModelName = new Role;
        $allRoleData = "";
        $uploadErrorMsg = "";
        $uploadError = "";

        if(Auth::user()->roles->first()->id == 12){

        $allRoleData = $roleModelName
                        ->select('id','display_name')
                        ->where('name','<>','superadmin')
                        ->where('name','<>','admin')
                        ->where(function($q) {
                            $q->where('is_user', 0)->orWhereNull('is_user');
                        })
                        ->orderBy('display_name','ASC')
                        ->pluck('display_name', 'id')
                        ->toArray();

        }else{
        $allRoleData = $roleModelName
                            ->select('id','display_name')
                            ->where('name','<>','superadmin')
                            ->where(function($q) {
                                $q->where('is_user', 0)->orWhereNull('is_user');
                            })
                            ->orderBy('display_name','ASC')
                            ->pluck('display_name', 'id')
                            ->toArray();
        }
        // prd(Auth::user()->role_user->role_id);

        $currentRoleId = (count($dataForViewUser->roles) >= 1) ? $dataForViewUser->roles->first()->id : 0;

        // add unit setup code
        $divisionModelName = new Division;
        $allDivisionData = $divisionModelName
                            ->select('id','name')
                            ->where('status',1)
                            ->orderBy('name','ASC')
                            ->pluck('name', 'id')
                            ->toArray();

        $departmentModelName = new Department;
        $allDepartmentData = $departmentModelName
                            ->select('id','name')
                            ->where('status',1)
                            ->orderBy('name','ASC')
                            ->pluck('name', 'id')
                            ->toArray();

        $groupModelName = new GroupInfo();
        $allGroupData = $groupModelName
                            ->select('id','name')
                            ->where('is_active',1)
                            ->orderBy('name','ASC')
                            ->pluck('name', 'id')
                            ->toArray();

        $subgroupModelName = new SubgroupInfo();
        $allSubgroupData = $subgroupModelName
                            ->select('id','name')
                            ->where('is_active',1)
                            ->orderBy('name','ASC')
                            ->pluck('name', 'id')
                            ->toArray();

        $userInfo = array();
        $userInfoObj = $dataForViewUser;
        // $userInfoObj = $this->modelName->where('id', $id)->first();
        if (empty($userInfoObj)) {
            abort(403,'Edit Not possible!!!');
        } else {
            $userInfo = $userInfoObj->toArray();
        }


        $userUnitModel = new UserUnit;
        $dataForView = $userUnitModel->where
                                            ([
                                                ['user_id', $id]
                                            ])->first();

        $home_menu_icon = "fa fa-user";
        $title = "Edit User";
        $title_for_layout = 'Edit User';
                                        // dd($dataForView);
        // return view('Users.add', compact('home_menu_icon', 'title', 'title_for_layout', 'dataForView', 'allRoleData', 'currentRoleId','id'));
        return view('Users/add',compact("uploadErrorMsg","id","uploadError","dataForView","allRoleData","currentRoleId","title","title_for_layout","home_menu_icon", 'userInfo','dataForView','allDivisionData','allDepartmentData','allGroupData','allSubgroupData'));

    }

    public function update($id = null, UserRequest $request)
    {
        $existingTmpRecords = UserTmp::where('user_id', $request->user_id)->get();

        $hasInvalidRecord = $existingTmpRecords->contains(function ($record) {
            return $record->form_status != 7 && $record->form_status != -1;
        });

        if ($hasInvalidRecord) {
            flash('An entry already exists, please wait for Checker Approval', 'danger');
            return Redirect::back();
        }

        DB::beginTransaction();

        try {
            try {
                $id = decrypt($id);
                Log::info('Decrypted ID: ' . $id);
            } catch (DecryptException $e) {
                Log::error('Decryption failed: ' . $e->getMessage());
                return abort(403, 'Unauthorized Access');
            }

            $data = $request->all();
            Log::info('Request data:', $data);

            $update = $this->modelName->find($id);
            Log::info('Fetched main record:', $update->toArray());

            $this->modelNameTmp->user_id = $update->user_id;
            $this->modelNameTmp->emp_id = $request->emp_id;
            $this->modelNameTmp->name = $request->name;
            $this->modelNameTmp->designation = $request->designation;
            $this->modelNameTmp->email = $request->email;
            $this->modelNameTmp->mobile_no = $request->mobile_no;
            $this->modelNameTmp->remarks = $request->remarks;
            $this->modelNameTmp->ip = $this->getClientIp();
            $this->modelNameTmp->status = $request->status;
            $this->modelNameTmp->role_id = $request->role_id ?? 0;
            $this->modelNameTmp->action = "Edit";
            $this->modelNameTmp->master_id = $update->id;
            $this->modelNameTmp->form_status = 0;
            $this->modelNameTmp->created_by = auth()->user()->user_id;
            $this->modelNameTmp->modified_by = "";
            $this->modelNameTmp->module = AccessApp::ServiceComplaint;
            $this->modelNameTmp->comments = "Edit by " . auth()->user()->name;

            Log::info('Temporary model before save:', $this->modelNameTmp->toArray());

            if (!$this->modelNameTmp->save()) {
                throw new \Exception('Failed to save the user record.');
            }

            Log::info('Temporary record saved successfully');


            DB::table('user_units_tmp')->where('user_id', $id)->delete();
            DB::table('model_has_roles_tmp')->where('model_id', $id)->delete();
            if($request->role_id >0){
                DB::table('model_has_roles_tmp')->insert([
                    'role_id' => $request->role_id,
                    'model_type' => 'App\User',
                    'model_id' => $update->user_id,
                    // 'module' => AccessApp::ServiceComplaint,
                ]);
            }else{
                $unitIds = $request->unit_id ?? [];
                // if(count($unitIds) > 0){
                    switch ($request['type']) {
                        case 'Subgroup':
                            $this->handleSubgroup($id, $request, $unitIds, $update->user_id);
                            break;

                        case 'Group':
                            $this->handleGroup($id, $request, $unitIds, $update->user_id);
                            break;

                        case 'Department':
                            $this->handleDepartment($id, $request, $unitIds, $update->user_id);
                            break;

                        case 'Division':
                            $this->handleDivision($id, $request, $unitIds, $update->user_id);
                            break;

                        case 'Region':
                            $regionIds = $request->region_id ?? [];
                            $requUnitId = $request->requ_unit_id;
                            if (!empty($requUnitId)) {
                                $this->handleRegion($id, $request, $requUnitId, $regionIds, $update->user_id);
                                break;
                            } else {
                                flash('Failed to update the user, please select region role.', 'danger');
                                return Redirect::back();
                            }

                        default:
                            throw new \Exception('Invalid type provided.');
                    }
                // }
            }
            if (!empty($request->get('oldDataForAudit'))) {
                $audit = new Audit();
                $audit->auditable_type = 'App\User';
                $audit->user_type = 'App\User';
                $audit->user_id = Auth::id();
                $audit->event = 'Modify';
                $audit->auditable_id = $id;
                $audit->old_values = json_decode($request->get('oldDataForAudit'));
                $audit->save();
            }

            DB::commit();
            flash('User has been updated successfully', 'success');
            return redirect('/Users' . $request->get('additionalParams'));

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('User update failed: ' . $e->getMessage());
            flash('Failed to update the user, please try again.', 'danger');
            return Redirect::back();
        }
    }



    protected function handleSubgroup($id, $request, $unitIds, $userId)
    {
        $this->validate($request, [
            'subgroup_info_id' => 'required',
            'unit_id' => 'required',
            'unit_id.*' => 'required',
        ]);



        if ($request->subgroup_info_id == 380) {
            $unitIds = array_filter($unitIds, function ($value) {
                return $value !== '1';
            });
        }


        foreach ($unitIds as $unit_id) {
            DB::table('model_has_roles_tmp')->insert([
                'role_id' => $unit_id,
                'model_type' => 'App\User',
                'model_id' => $userId,
                'module' => AccessApp::ServiceComplaint,
            ]);
        }

        $data = [
            'user_id' => $userId,
            'subgroup_info_id' => $request->subgroup_info_id,
            'is_email_allow' => $request->is_email_allow,
            'unit_id' => !empty($unitIds) ? implode(',', $unitIds) : null,
            'is_unit_head' => $request->is_unit_head ?? 0,
            'ip' => $this->getClientIp(),
            'action' => 'Assign',
            'master_id' => $id,
            'form_status' => 0,
            'module' => AccessApp::ServiceComplaint,
            'created_by' => Auth::user()->user_id,
        ];

        DB::table('user_units_tmp')->insert($data);
    }

    protected function handleGroup($id, $request, $unitIds, $userId)
    {
        $groupData = DB::table('units')->where([['is_head', 1], ['status', 1]])->first();
        if (!empty($groupData)) {
            DB::table('model_has_roles_tmp')->insert([
                'role_id' => $groupData->id,
                'model_type' => 'App\User',
                'model_id' => $userId,
                'module' => AccessApp::ServiceComplaint,
            ]);
        }

        $data = [
            'user_id' => $userId,
            'group_info_id' => $request->group_info_id,
            'is_email_allow' => $request->is_email_allow,
            'unit_id' => 6,
            'is_group_info_head' => $request->group_head ? $request->group_head : 0,
            'ip' => $this->getClientIp(),
            'action' => 'Assign',
            'master_id' => $id,
            'form_status' => 0,
            'module' => AccessApp::ServiceComplaint,
            'created_by'=> Auth::user()->user_id,
        ];

        DB::table('user_units_tmp')->insert($data);
    }


    protected function handleDepartment($id, $request, $unitIds, $userId)
    {
        $departmentData = DB::table('units')->where([['is_head', 2], ['status', 1]])->first();
        if (!empty($departmentData)) {
            DB::table('model_has_roles_tmp')->insert([
                'role_id' => $departmentData->id,
                'model_type' => 'App\User',
                'model_id' => $userId,
                'module' => AccessApp::ServiceComplaint,
            ]);
        }


        $data = [
            'user_id' => $userId,
            'department_id' => $request->department_id,
            'is_email_allow' => $request->is_email_allow,
            'unit_id' => 7,
            'is_department_head' => $request->department_head ? $request->department_head : 0,
            'ip' => $this->getClientIp(),
            'action' => 'Assign',
            'master_id' => $id,
            'form_status' => 0,
            'module' => AccessApp::ServiceComplaint,
            'created_by'=> Auth::user()->user_id,
        ];

        DB::table('user_units_tmp')->insert($data);
    }

    protected function handleDivision($id, $request, $unitIds, $userId)
    {
        $divisionData = DB::table('units')->where([['is_head', 3], ['status', 1]])->first();
        if (!empty($divisionData)) {
            DB::table('model_has_roles_tmp')->insert([
                'role_id' => $divisionData->id,
                'model_type' => 'App\User',
                'model_id' => $userId,
                'module' => AccessApp::ServiceComplaint,
            ]);
        }


        $data = [
            'user_id' => $userId,
            'division_id' => $request->division_id,
            'is_email_allow' => $request->is_email_allow,
            'unit_id' => 11,
            'is_division_head' => $request->division_head ? $request->division_head : 0,
            'ip' => $this->getClientIp(),
            'action' => 'Assign',
            'master_id' => $id,
            'form_status' => 0,
            'module' => AccessApp::ServiceComplaint,
            'created_by'=> Auth::user()->user_id,
        ];

        DB::table('user_units_tmp')->insert($data);
    }

    protected function handleRegion($id, $request, $requUnitId, $regionIds, $userId)
    {
        $regionData = DB::table('units')->where([['is_head', 4], ['status', 1]])->first();
        if (!empty($regionData)) {
            DB::table('model_has_roles_tmp')->insert([
                'role_id' => $regionData->id,
                'model_type' => 'App\User',
                'model_id' => $userId,
                'module' => AccessApp::ServiceComplaint,
            ]);
        }

        $data = [
            'user_id' => $userId,
            'region_id' => !empty($regionIds) ? implode(',', $regionIds) : null,
            'is_email_allow' => $request->is_email_allow,
            'unit_id' => $requUnitId,
            'ip' => $this->getClientIp(),
            'action' => 'Assign',
            'master_id' => $id,
            'form_status' => 0,
            'module' => AccessApp::ServiceComplaint,
            'created_by'=> Auth::user()->user_id,
        ];

        DB::table('user_units_tmp')->insert($data);
    }

    public function approve($id)
    {
        // dd('okay');
        $checker = $this->accessUserChecker();
        if ($checker == false) {
            abort(403, 'You do not have permission to access this page.');
        }

        DB::beginTransaction();

        try {
            Log::debug('Approval process started for UserTmp ID: ' . $id);

            $userTmp = UserTmp::where('status', '!=', 3)
                ->whereNotIn('form_status', [7, -1])
                ->where('id', $id)
                ->first();

            if (!$userTmp) {
                Log::debug('UserTmp not found or not approved yet. UserTmp ID: ' . $id);
                flash('User not found or not approved yet.', 'danger');
                return redirect()->back();
            }

            Log::debug('UserTmp found: ', $userTmp->toArray());

            $userData = [
                'user_id'     => $userTmp->user_id,
                'name'        => $userTmp->name,
                'designation' => $userTmp->designation,
                'email'       => $userTmp->email,
                'emp_id'       => $userTmp->emp_id,
                'remarks'       => $userTmp->remarks,
                'password'    => Hash::make(1),
                'mobile_no'   => $userTmp->mobile_no,
                'ip'          => $this->getClientIp(),
                'status'      => $userTmp->status,
                'created_at'  => now(),
                'updated_at'  => now(),
            ];

            Log::debug('User data prepared: ', $userData);

            // Check if updating or adding a new user
            if ($userTmp->master_id != null && $userTmp->action != "Add") {
                $existingUser = User::find($userTmp->master_id);
                $supp_status = "Creation";

                if ($existingUser) {
                    $userData['c_maker'] =  $userTmp->created_by;
                    $userData['c_checker'] = auth()->user()->user_id;
                    $userData['cm_date'] = $userTmp->created_at;
                    $userData['cc_date'] = now();

                    Log::debug('Existing user found: ', $existingUser->toArray());

                    $this->storeAudit('App\User', $existingUser->id, $existingUser->toArray(), $userData);

                    //$existingUser->syncRoles($userTmp->role_id);
                    DB::table('users')->where('id', $userTmp->master_id)->update($userData);
                    $userIdForUnit = $userTmp->master_id;

                    Log::debug('Existing user updated: User ID ' . $userIdForUnit);
                }
            } else {
                $userData['f_maker'] = $userTmp->created_by;
                $userData['f_checker'] = auth()->user()->user_id;
                $userData['fm_date'] = $userTmp->created_at;
                $userData['fc_date'] = now();
                $newUserId = DB::table('users')->insertGetId($userData);
                $newUser = User::find($newUserId);
                $supp_status = "Modification";
                if ($newUser) {
                    Log::debug('New user created: User ID ' . $newUserId);
                    $newUser->syncRoles($userTmp->role_id);
                }
                $userIdForUnit = $newUserId;
            }

            // Check and delete existing unit if found
            $unitExist = DB::table('user_units')->where('user_id', $userIdForUnit)->first();
            if ($unitExist) {
                Log::debug('Existing unit found: ', (array)$unitExist);
                $this->storeAudit('App\UserUnit', $unitExist->id, (array)$unitExist, null);

                DB::table('user_units')->where('user_id', $userIdForUnit)->delete();
                Log::debug('Existing unit deleted for User ID ' . $userIdForUnit);
            }

            // Insert new unit from user_units_tmp
            $unitTmpExist = DB::table('user_units_tmp')->where('user_id', $userTmp->user_id)->first();
            if ($unitTmpExist) {
                DB::table('user_units')->insert([
                    'user_id' => $userIdForUnit,
                    'unit_id' => $unitTmpExist->unit_id,
                    'is_email_allow' => $unitTmpExist->is_email_allow ?? 0,
                    'is_unit_head' => $unitTmpExist->is_unit_head ?? 0,
                    'subgroup_info_id' => $unitTmpExist->subgroup_info_id,
                    'is_group_info_head' => $unitTmpExist->is_group_info_head,
                    'group_info_id' => $unitTmpExist->group_info_id,
                    'department_id' => $unitTmpExist->department_id,
                    'is_department_head' => $unitTmpExist->is_department_head,
                    'division_id' => $unitTmpExist->division_id,
                    'is_division_head' => $unitTmpExist->is_division_head,
                    'region_id' => $unitTmpExist->region_id,
                    'module' => AccessApp::ServiceComplaint,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // update issue group record
                // $issueGroup = IssueGroup::where('user_id', $userIdForUnit)->first();
                // if($issueGroup){
                //     $issueGroup->subgroup_info_id = $unitTmpExist->subgroup_info_id;
                //     $issueGroup->save();
                // }
                Log::debug('New unit inserted for User ID ' . $userIdForUnit);
            }

            // Check and delete existing roles
            $MHRole = DB::table('model_has_roles')->where('model_id', $userIdForUnit)->first();
            if ($MHRole) {
                // dd($MHRole);
                Log::debug('Existing role found: ', (array)$MHRole);
                $this->storeAudit('App\ModelHasRole', $MHRole->model_id, (array)$MHRole, null);

                DB::table('model_has_roles')->where('model_id', $userIdForUnit)->delete();
                Log::debug('Existing roles deleted for User ID ' . $userIdForUnit);
            }

            // Insert new roles from model_has_roles_tmp
            $MHRoleTmp = DB::table('model_has_roles_tmp')->where('model_id', $userTmp->user_id)->get();
            // dd($userTmp);
            Log::alert('test1 '. json_encode($MHRoleTmp) . " === " . json_encode($unitTmpExist));

            if (count($MHRoleTmp) > 0) {
                foreach ($MHRoleTmp as $roleTmp) {
                    DB::table('model_has_roles')->insert([
                        'role_id' => $roleTmp->role_id,
                        'model_type' => 'App\User',
                        'model_id' => $userIdForUnit,
                        'module' => AccessApp::ServiceComplaint,
                    ]);
                }
                Log::debug('New roles inserted for User ID ' . $userIdForUnit);
            }else{
                if(!$MHRoleTmp->isEmpty()) {
                    DB::table('model_has_roles')->insert([
                        'role_id' => $MHRoleTmp->role_id,
                        'model_type' => 'App\User',
                        'model_id' => $userIdForUnit,
                        'module' => AccessApp::ServiceComplaint,
                    ]);
                }
            }

            // Delete temporary data
            $userTmp->delete();
            DB::table('user_units_tmp')->where('user_id', $userTmp->user_id)->delete();
            DB::table('model_has_roles_tmp')->where('model_id', $userTmp->user_id)->delete();

            Log::debug('Temporary data deleted for UserTmp ID ' . $userTmp->user_id);

            DB::commit();

            $outgoingSMSMessage = $this->outgoingSMSEmail(  $userTmp->name, $userTmp->user_id);

            if (!empty($outgoingSMSMessage['sms'])) {
                $this->sendSMS($userTmp->mobile_no, $outgoingSMSMessage['sms'], '0000', $supp_status);
            }

            if (!empty($outgoingSMSMessage['mail'])) {
                if (!empty($userTmp->email)) {
                    $this->sendEMAIL($userTmp->email, $outgoingSMSMessage['mail'], '0000', $supp_status);
                }
            }

            // flash('User has been approved and updated successfully', 'success');

            if ($userTmp->status == 0) {
                flash('User has been inactivated.', 'danger');
            } elseif ($userTmp->status == 1) {
                flash('User has been activated.', 'success');
            } elseif ($userTmp->status == -2) {
                flash('User has been closed.', 'success');
            } else {
                flash('User has been approved.', 'success');
            }

            return redirect('/Users/action-queue-list');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Approval failed: ' . $e->getMessage() . ' ' . $e->getTraceAsString());
            flash('Failed to approve the user, please try again.', 'danger');
            return Redirect::back();
        }
    }


    protected function storeAudit($auditableType, $auditableId, $oldValues, $newValues)
    {
        $audit = new Audit();
        $audit->auditable_type = $auditableType;
        $audit->auditable_id = $auditableId;
        $audit->user_type = 'App\User';
        $audit->user_id = Auth::id();
        $audit->event = 'update';
        $audit->old_values = json_encode($oldValues);
        $audit->new_values = json_encode($newValues);
        $audit->save();
    }

    public function approveUsers($id)
    {
        $checker = $this->accessUserChecker();
        if ($checker == false) {
            abort(403, 'You do not have permission to access this page.');
        }

        try {
            DB::beginTransaction();

            $userTmp = UserTmp::where('status', '!=', 3)->whereNotIn('form_status', [7,-1])->where('id', $id)->first();

            if (!$userTmp) {
                flash('User not found or not approved yet.', 'danger');
                return redirect()->back();
            }

            // Prepare the data to insert/update the 'users' table
            $userData = [
                'user_id'     => $userTmp->user_id,
                'name'        => $userTmp->name,
                'designation' => $userTmp->designation,
                'email'       => $userTmp->email,
                'password'    => Hash::make(1),
                'mobile_no'   => $userTmp->mobile_no,
                'ip'          => $this->getClientIp(),
                'status'      => $userTmp->status == 2 ? 1 : $userTmp->status,
                'created_at'  => now(),
                'updated_at'  => now(),
            ];

            // if ($userTmp->master_id != null && $userTmp->action != "Add") {
            //     // Update roles for the user
            //     $userTmp->syncRoles($userTmp->role_id);
            //     // Update existing user in the 'users' table
            //     DB::table('users')->where('id', $userTmp->master_id)->update($userData);
            // }
            if ($userTmp->master_id != null && $userTmp->action != "Add") {
                // Re-fetch the user from the 'users' table
                $existingUser = User::find($userTmp->master_id);

                if ($existingUser) {
                    $existingUser->syncRoles($userTmp->role_id);

                    DB::table('users')->where('id', $userTmp->master_id)->update($userData);
                }
            }
            else {
                $newUserId = DB::table('users')->insertGetId($userData);
                $newUser = User::find($newUserId);
                if ($newUser) {
                    $newUser->syncRoles($userTmp->role_id);
                }
            }

            DB::table('users_tmp')->where('id', $userTmp->id)->delete();

            DB::commit();

            if ($userTmp->status == 0) {
                flash('User has been inactivated.', 'danger');
            } elseif ($userTmp->status == 1) {
                flash('User has been activated.', 'success');
            } else {
                flash('User has been approved.', 'success');
            }

            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollBack();
            flash('An error occurred: ' . $e->getMessage(), 'danger');
            return redirect()->back();
        }
        catch (\Exception $e) {
            DB::rollBack();
            flash('An error occurred during the approval process: ' . $e->getMessage(), 'danger');
            return redirect()->back();
        }
    }

    public function userTmpList(Request $request)
    {
        $checker = $this->accessUserChecker();

        if ($checker) {
            // $tmpData = DB::table('users_tmp')
            //     ->leftJoin('user_units_tmp', 'users_tmp.user_id', '=', 'user_units_tmp.user_id')
            //     ->leftJoin('roles', 'users_tmp.role_id', '=', 'roles.id')
            //     // ->leftJoin('units', 'user_units_tmp.unit_id', '=', 'units.id')
            //     ->leftJoin('units', function($join) {
            //         $join->whereRaw('FIND_IN_SET(units.id, user_units_tmp.unit_id)');
            //     })
            //     ->leftJoin('subgroup_info', 'user_units_tmp.subgroup_info_id', '=', 'subgroup_info.id')
            //     ->leftJoin('group_info', 'user_units_tmp.group_info_id', '=', 'group_info.id')
            //     ->leftJoin('departments', 'user_units_tmp.department_id', '=', 'departments.id')
            //     ->leftJoin('divisions', 'user_units_tmp.division_id', '=', 'divisions.id')
            //     ->whereNotIn('users_tmp.form_status', [7, -1])
            //     ->where('users_tmp.module', AccessApp::ServiceComplaint)
            //     ->select(
            //         'users_tmp.id as uid',
            //         'users_tmp.user_id',
            //         'users_tmp.emp_id',
            //         'users_tmp.name',
            //         'users_tmp.action as uaction',
            //         'users_tmp.designation',
            //         'users_tmp.email',
            //         'users_tmp.mobile_no',
            //         'units.name as unit_name',
            //         DB::raw("
            //             CASE
            //                 WHEN subgroup_info.name IS NOT NULL THEN subgroup_info.name
            //                 WHEN group_info.name IS NOT NULL THEN group_info.name
            //                 WHEN departments.name IS NOT NULL THEN departments.name
            //                 WHEN divisions.name IS NOT NULL THEN divisions.name
            //                 ELSE ''
            //             END as group_name
            //         "),
            //         'roles.display_name as role_name',
            //         'users_tmp.status as ustatus',
            //         'users_tmp.form_status as u_form_status',
            //         'users_tmp.comments as ucomments',
            //         'users_tmp.created_by as uCreated_by'
            //     )
            //     ->get();

            $tmpData = DB::table('users_tmp')
                ->leftJoin('user_units_tmp', 'users_tmp.user_id', '=', 'user_units_tmp.user_id')
                ->leftJoin('roles', 'users_tmp.role_id', '=', 'roles.id')
                ->leftJoin('units', function($join) {
                    $join->whereRaw('FIND_IN_SET(units.id, user_units_tmp.unit_id)');
                })
                ->leftJoin('subgroup_info', 'user_units_tmp.subgroup_info_id', '=', 'subgroup_info.id')
                ->leftJoin('group_info', 'user_units_tmp.group_info_id', '=', 'group_info.id')
                ->leftJoin('departments', 'user_units_tmp.department_id', '=', 'departments.id')
                ->leftJoin('divisions', 'user_units_tmp.division_id', '=', 'divisions.id')
                ->whereNotIn('users_tmp.form_status', [7, -1])
                ->where('users_tmp.module', AccessApp::ServiceComplaint)
                ->select(
                    'users_tmp.id as uid',
                    'users_tmp.user_id',
                    'users_tmp.emp_id',
                    'users_tmp.name',
                    'users_tmp.action as uaction',
                    'users_tmp.designation',
                    'users_tmp.email',
                    'users_tmp.mobile_no',
                    DB::raw("GROUP_CONCAT(DISTINCT units.name ORDER BY units.id SEPARATOR ', ') as unit_name"),
                    DB::raw("
                        CASE
                            WHEN subgroup_info.name IS NOT NULL THEN subgroup_info.name
                            WHEN group_info.name IS NOT NULL THEN group_info.name
                            WHEN departments.name IS NOT NULL THEN departments.name
                            WHEN divisions.name IS NOT NULL THEN divisions.name
                            ELSE ''
                        END as group_name
                    "),
                    'roles.display_name as role_name',
                    'users_tmp.status as ustatus',
                    'users_tmp.form_status as u_form_status',
                    'users_tmp.comments as ucomments',
                    'users_tmp.created_by as uCreated_by'
                )
                ->groupBy(
                    'users_tmp.id', 'users_tmp.user_id', 'users_tmp.emp_id', 'users_tmp.name',
                    'users_tmp.action', 'users_tmp.designation', 'users_tmp.email', 'users_tmp.mobile_no',
                    'group_name', 'roles.display_name', 'users_tmp.status', 'users_tmp.form_status',
                    'users_tmp.comments', 'users_tmp.created_by'
                )
                ->get();

        } else {
            $tmpData = DB::table('users_tmp')
                ->leftJoin('user_units_tmp', 'users_tmp.user_id', '=', 'user_units_tmp.user_id')
                ->leftJoin('roles', 'users_tmp.role_id', '=', 'roles.id')
                ->leftJoin('units', 'user_units_tmp.unit_id', '=', 'units.id')
                ->leftJoin('subgroup_info', 'user_units_tmp.subgroup_info_id', '=', 'subgroup_info.id')
                ->leftJoin('group_info', 'user_units_tmp.group_info_id', '=', 'group_info.id')
                ->leftJoin('departments', 'user_units_tmp.department_id', '=', 'departments.id')
                ->leftJoin('divisions', 'user_units_tmp.division_id', '=', 'divisions.id')
                ->whereIn('users_tmp.form_status', [7, -1])
                ->select(
                    'users_tmp.id as uid',
                    'users_tmp.user_id',
                    'users_tmp.emp_id',
                    'users_tmp.name',
                    'users_tmp.action as uaction',
                    'users_tmp.designation',
                    'users_tmp.email',
                    'users_tmp.mobile_no',
                    'units.name as unit_name',
                    DB::raw("
                        CASE
                            WHEN subgroup_info.name IS NOT NULL THEN subgroup_info.name
                            WHEN group_info.name IS NOT NULL THEN group_info.name
                            WHEN departments.name IS NOT NULL THEN departments.name
                            WHEN divisions.name IS NOT NULL THEN divisions.name
                            ELSE ''
                        END as group_name
                    "),
                    'roles.display_name as role_name',
                    'users_tmp.status as ustatus',
                    'users_tmp.form_status as u_form_status',
                    'users_tmp.comments as ucomments',
                    'users_tmp.created_by as uCreated_by'
                )
                ->get();

        }

        $isDownload = $request->get('download');
        if ($isDownload) {
            $fileName = 'user_tmp_list_' . date('dmYhis') . '.xlsx';
            return Excel::download(new UserTmpExport($tmpData), $fileName);
        }

        // return $tmpData;

        return view('Users.users_tmp',compact('tmpData', 'checker'));
    }

    public function deleteTmpData($id, $table)
    {
        $userTmp = DB::table($table)->find($id);

        if ($userTmp) {
            DB::table('user_units_tmp')->where('user_id', $userTmp->user_id)->delete();
            DB::table('model_has_roles_tmp')->where('model_id', $userTmp->user_id)->delete();

            DB::table($table)->where('id', $id)->delete();

            if (str_ends_with($table, '_tmp') || str_ends_with($table, '_tmps')) {
                $table = preg_replace('/_tmps?$/', ' temporary', $table);
            }

            flash($table . ' data has been deleted.', 'danger');
        } else {
            flash('Record not found.', 'danger');
        }

        return redirect()->back();
    }

    public function checkerTableData($id)
    {
        $tmpData = UserTmp::with('roleName')->findOrFail($id);

        if (!empty($tmpData->master_id)) {
            $oldData = User::findOrFail($tmpData->master_id);
            $oldData->unit_name = 'N/A';

            $userUnit = DB::table('user_units')->where('user_id',$oldData->id)->first();

            if ($userUnit && !empty($userUnit->unit_id)) {
                $unitIds = is_array($userUnit->unit_id)
                    ? $userUnit->unit_id
                    : explode(',', $userUnit->unit_id);

                $oldData->unit_name = Unit::whereIn('id', $unitIds)
                    ->pluck('name')
                    ->implode(', ');
            } else {
                $oldData->unit_name = null;
            }
        }

        $tmpData->name = $tmpData->name ?? null;
        $tmpData->designation = $tmpData->designation ?? null;
        $tmpData->user_id = $tmpData->user_id ?? null;
        $tmpData->emp_id = $tmpData->emp_id ?? null;
        $tmpData->email = $tmpData->email ?? null;

        $tmpData->role = $tmpData->roleName->display_name ?? 'N/A';
        $tmpData->mobile_no = $tmpData->mobile_no ?? null;
        $tmpData->status = $tmpData->status == 1 ? "Active" : "Inactive";
        $tmpData->unit_name = 'N/A';


        $userUnitTmp = DB::table('user_units_tmp')->where('user_id',$tmpData->user_id)->first();

        if ($userUnitTmp && !empty($userUnitTmp->unit_id)) {
            $unitIds = is_array($userUnitTmp->unit_id)
                ? $userUnitTmp->unit_id
                : explode(',', $userUnitTmp->unit_id);

            $tmpData->unit_name = Unit::whereIn('id', $unitIds)
                ->pluck('name')
                ->implode(', ');
        } else {
            $tmpData->unit_name = null;
        }
        
        // dd($tmpData->unit_name);

        if (isset($oldData)) {
            $oldData->name = $oldData->name ?? null;
            $oldData->designation = $oldData->designation ?? null;
            $oldData->user_id = $oldData->user_id ?? null;
            $oldData->emp_id = $oldData->emp_id ?? null;
            $oldData->email = $oldData->email ?? null;
            // $oldData->role = $oldData->getRoleNames()->first() ?? 'N/A';
            $oldData->role = $oldData->roles->first()->display_name ?? 'N/A';
            $oldData->mobile_no = $oldData->mobile_no ?? null;
            $oldData->status = $oldData->status == 1 ? "Active" : "Inactive";
        }
        $columnsToSend = ['name', 'designation', 'user_id', 'emp_id', 'email', 'role', 'unit_name', 'mobile_no', 'status'];

        $filteredTmpData = $tmpData->only($columnsToSend);
        $filteredOldData = isset($oldData) ? $oldData->only($columnsToSend) : null;

        $response = [
            'old_data' => $filteredOldData,
            'new_data' => $filteredTmpData
        ];

        return response()->json($response, 200);
    }


    public function userAssign($id)
    {
        $tmpData = UserTmp::where('id', $id)->first();

        $tmpData->form_status = 1;
        $tmpData->modified_by = Auth::user()->user_id;
        $tmpData->save();

        $data = [
            'message' => 'Assigned Successfully from your End!',
            'id' => $id,
        ];

        return response()->json($data, 200);
    }

    public function userSendback(Request $request, $id)
    {
        $checker = $this->accessUserChecker();
        if ($checker == false) {
            abort(403, 'You do not have permission to access this page.');
        }

        $request->validate([
            'comments' => 'required'
        ]);
        $tmpData = UserTmp::where('id', $id)->first();
        $tmpData->form_status = 7;
        $tmpData->modified_by = null;
        $tmpData->comments = $request->comments;
        $tmpData->save();

        if ($tmpData) {
            DB::table('user_units_tmp')->where('user_id', $tmpData->user_id)->delete();
            DB::table('model_has_roles_tmp')->where('model_id', $tmpData->user_id)->delete();
        }

        flash('Send back Successfully!', 'success');
        return redirect()->back();
    }

    public function userReject(Request $request, $id)
    {
        $checker = $this->accessUserChecker();
        if ($checker == false) {
            abort(403, 'You do not have permission to access this page.');
        }

        $request->validate([
            'comments' => 'required'
        ]);
        $tmpData = UserTmp::where('id', $id)->first();
        $tmpData->form_status = -1;
        $tmpData->modified_by = Auth::user()->user_id;
        $tmpData->comments = $request->comments;
        $tmpData->save();

        if ($tmpData) {
            DB::table('user_units_tmp')->where('user_id', $tmpData->user_id)->delete();
            DB::table('model_has_roles_tmp')->where('model_id', $tmpData->user_id)->delete();
        }

        flash('Rejected !!!', 'danger');
        return redirect()->back();
    }

    public function buttonControl($id){
        $tmpData = UserTmp::findOrFail($id);

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
















    public function setPassword($id = null)
    {

        $currentPath = Route::getFacadeRoot()->current()->uri(); //Authenticate User can Change his password, No Special Permission need for this

        try {
            if ($currentPath == "ChangePassword") {
               $id = Auth::user()->id;
            } else {
               $id = decrypt($id);
            }
        } catch (DecryptException $e) {
            toast()->error('Internal Error Occurs!!!', 'Danger');
            return Redirect::back();
        }

        if (($id == 1) && (Auth::user()->id != 1)) {
            toast()->error('Un-Authorize Access!!!', 'Danger');
            return redirect('/Users');
        }
        $dataForView = array();
        $userInfo = $this->modelName->where([['id', $id]])->first()->toArray();

        $title = "Set Password";
        $title_for_layout = 'Set Password';
        $home_menu_icon = "fa fa-gear";

        return view('Users/set_password',compact("userInfo","id","dataForView","title","title_for_layout","home_menu_icon","currentPath"));
    }
    public function updatePassword($id = null, UserPasswordRequest $request)
    {
        $currentPath = "";
        try {
            if ($id == "ChangePassword") {
               $id = Auth::user()->id;
               $currentPath = "ChangePassword";
            } else {
               $id = decrypt($id);
            }
        } catch (DecryptException $e) {
            toast()->error('Internal Error Occurs!!!', 'Danger');
            return Redirect::back();
        }

        $reqData = $request->all();
        $data['password'] = Hash::make($reqData['user_password']);
        $data['status']   = '1';
        $data['password_changed_at']=date('Y-m-d h:m:s' );
        $update = $this->modelName->where([['id', $id]])->first();

        $additionalParams = (!empty($reqData['additionalParams'])) ? $reqData['additionalParams'] : "";
        unset($data['additionalParams']);
        $data['password'] = Hash::make($reqData['user_password']);
        if ($update->update($data)) {
            flash('New Password has been set successfully', 'success');
            if ($currentPath == "ChangePassword") {
                Auth::logout();
                flash('Password Change successfully, Please Login Again', 'success');
                return redirect('/');
            } else {
                return redirect('Users'.$additionalParams);
            }

        } else {
            toast()->error('Password set failed!!!', 'Danger');
        }
    }

    public function status($id = null, $status = "")
    {
        $checker = $this->accessUserChecker();
        if ($checker) {
            abort(403, 'You do not have permission to access this page.');
        }

        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            abort(403,'Internal Error Occurs!!!');
        }

        $existingTmpRecords = UserTmp::where('master_id', $id)->get();

        $hasInvalidRecord = $existingTmpRecords->contains(function ($record) {
            return $record->form_status != 7 && $record->form_status != -1;
        });

        if ($hasInvalidRecord) {
            flash('An entry already exists, please wait for Checker Approval', 'danger');
            return Redirect::back();
        }

        if ($status != '0' && $status != '1' && $status != '-2') {
            flash($status.' is not Allowed!!!', 'danger');
            return Redirect::back();
        }
        $data['status'] = $status;
        $roleId = 0;
        $update = $this->modelName->where([['id', $id]])->first();

        if ($update) {
            $role = DB::table('model_has_roles')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('model_has_roles.model_id', $update->id)
                ->select('roles.id as role_id', 'roles.name as role_name')
                ->first();

            if ($role) {
                $roleId = $role->role_id;
                $roleName = $role->role_name;
            }
        }

        if ($status == 0){
            $this->modelNameTmp->status = 0;
            $this->modelNameTmp->action = "Inactive";

        }elseif ($status == -2){
            $this->modelNameTmp->status = -2;
            $this->modelNameTmp->action = "Close";

        }else{
            $this->modelNameTmp->status = 1;
            $this->modelNameTmp->action = "Active";
        }
        $this->modelNameTmp->user_id = $update->user_id;
        $this->modelNameTmp->emp_id = $update->emp_id;
        $this->modelNameTmp->name = $update->name;
        $this->modelNameTmp->designation = $update->designation;
        $this->modelNameTmp->email = $update->email;
        $this->modelNameTmp->master_id = $update->id;
        // $this->modelNameTmp->password = Hash::make($update->password);
        $this->modelNameTmp->mobile_no = $update->mobile_no;
        $this->modelNameTmp->ip = $this->getClientIp();
        $this->modelNameTmp->role_id = $roleId;
        $successfullyUpload = false;

        $this->modelNameTmp->save();


        // $update->update($data);
        if ($status == '1') {
            flash('User has been activated, request sent for checker approval', 'success');
        }elseif($status == '-2') {
            flash('User has been closed, request sent for checker approval', 'danger');
        } else {
            flash('User has been deactivated, request sent for checker approval', 'danger');
        }

        return Redirect::back();
    }

    public function accessControl($id = null)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            toast()->error('You are compromizing the System!!!', 'Danger');
            return Redirect::back();
        }

        $moduleModel = new Module;
        $moduleListObj = $moduleModel
                        ->with([
                            'control'=>function($q) use ($id){
                                $q->select('id', 'module_id','user_id','status')
                                    ->where([['user_id', $id]])
                                ;
                            },
                        ])
                        ->where('status','=','1')
                        ->orderBy("sort_order","ASC")
                        ->get();

        $moduleList = array();
        if (!empty($moduleListObj)) {
            $moduleList = $moduleListObj->toArray();
        }
        // prd($moduleList);
        $userInfo = $this->modelName->where([['id', $id]])->first()->toArray();
        $home_menu_icon = "fa fa-universal-access";
        $title = "Access Control";
        $title_for_layout = 'Access Control';

        return view('Users/access_control',
            compact(
                "id"
                ,"userInfo", "userData"
                ,"dataForView", "selectedValue"
                ,"title_for_layout", "title"
                ,"userDataObj", "userId"
                ,"home_menu_icon", "moduleList"
            )
        );

    }


    public function setRole(Request $request, $id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            toast()->error('You are compromizing the System!!!', 'Danger');
            return Redirect::back();
        }

        $userModel = new User;

        $role_id = $request->role_id;

        $user = $userModel->find($id);

            DB::table('role_user')->where('user_id', $id)->delete();

        foreach ($role_id as $r_id) {
            $roleUser = new RoleUser();
            $roleUser->user_id =$id;
            $roleUser->role_id =$r_id;
            $roleUser->save();
            /*RoleUser::create([
                'user_id'=>$id,
                'role_id'=>$r_id
            ]);*/
        }
           // $user->attachRole($role_id);

        /*$user->role_id = $role_id;
        $user->save();*/
        flash('Access Control has been assigned succesfully.', 'success');

        return back();
    }

    public function setUnit($id = null, Request $req)
    {

        // UserUnit
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            abort(403,'Not possible');
        }

        $divisionModelName = new Division;
        $allDivisionData = $divisionModelName
                            ->select('id','name')
                            ->where('status',1)
                            ->orderBy('name','ASC')
                            ->pluck('name', 'id')
                            ->toArray();

        $departmentModelName = new Department;
        $allDepartmentData = $departmentModelName
                            ->select('id','name')
                            ->where('status',1)
                            ->orderBy('name','ASC')
                            ->pluck('name', 'id')
                            ->toArray();

        $groupModelName = new GroupInfo();
        $allGroupData = $groupModelName
                            ->select('id','name')
                            ->where('is_active',1)
                            ->orderBy('name','ASC')
                            ->pluck('name', 'id')
                            ->toArray();

        $subgroupModelName = new SubgroupInfo();
        $allSubgroupData = $subgroupModelName
                            ->select('id','name')
                            ->where('is_active',1)
                            ->orderBy('name','ASC')
                            ->pluck('name', 'id')
                            ->toArray();

        $userInfo = array();
        $userInfoObj = $this->modelName->where('id', $id)->first();

        if (empty($userInfoObj)) {
            abort(403,'Edit Not possible!!!');
        } else {
            $userInfo = $userInfoObj->toArray();
        }


        $userUnitModel = new UserUnit;
        $dataForView = $userUnitModel->where
                                            ([
                                                ['user_id', $id]
                                            ])->first();
        // pr($allDivisionData);
        // pr($allDepartmentData);
        // dd($dataForView);
        $dataForAudit = '';
        if (!empty($dataForView)){
            $dataForAudit = array_filter($dataForView->toArray(), function($value) {
                return $value !== null;
            });
        }
        $home_menu_icon = "fa fa-user";
        $title = "Set Unit / Department / Division";
        $title_for_layout = 'Set Unit / Department / Division';

        return view('Users.set_unit', compact('home_menu_icon','title','dataForAudit','title_for_layout','userInfo','dataForView','allDivisionData','allDepartmentData','allGroupData','allSubgroupData','id'));
    }

    public function updateUnit($id = null, Request $request) {
        DB::table('user_units_tmp')->where('user_id', $id)->delete();
        DB::table('model_has_roles_tmp')->where('model_id', $id)->delete();

        $additionalParams = !empty($request->additionalParams) ? $request->additionalParams : "";
        $row = null;
        $unitIds = $request->unit_id ?? [];

        if ($request['type'] == 'Subgroup') {
            $this->validate($request, [
                'subgroup_info_id' => 'required',
                'unit_id' => 'required',
                'unit_id.*' => 'required',
            ]);

            if ($request->subgroup_info_id == 386) {
                $unitIds = array_filter($unitIds, function($value) {
                    return $value !== '1';
                });
            }

            if (!empty($unitIds)) {
                foreach ($unitIds as $unit_id) {
                    DB::table('model_has_roles_tmp')->insert([
                        'role_id' => $unit_id,
                        'model_type' => 'App\User',
                        'model_id' => $id,
                    ]);
                }
            }

            $data = [
                'user_id' => $id,
                'subgroup_info_id' => $request->subgroup_info_id,
                'is_email_allow' => $request->is_email_allow,
                'unit_id' => !empty($unitIds) ? implode(',', $unitIds) : null,
                'is_unit_head' => $request->is_unit_head ? $request->is_unit_head : 0,
                'ip' => $this->getClientIp(),
                'action' => 'Assign',
                'master_id' => $id,
                'form_status' => 0,
                'created_by'=> Auth::user()->user_id,
            ];

            DB::table('user_units_tmp')->insert($data);
            $row = $data;

        } elseif ($request['type'] == 'Group') {
            $groupData = DB::table('units')->where([['is_head', 1], ['status', 1]])->first();
            if (!empty($groupData)) {
                DB::table('model_has_roles_tmp')->insert([
                    'role_id' => $groupData->id,
                    'model_type' => 'App\User',
                    'model_id' => $id,
                ]);
            }


            $data = [
                'user_id' => $id,
                'group_info_id' => $request->group_info_id,
                'is_email_allow' => $request->is_email_allow,
                'unit_id' => 6,
                'is_group_info_head' => $request->group_head ? $request->group_head : 0,
                'ip' => $this->getClientIp(),
                'action' => 'Assign',
                'master_id' => $id,
                'form_status' => 0,
                'created_by'=> Auth::user()->user_id,
            ];


            DB::table('user_units_tmp')->insert($data);
            $row = $data;

        } elseif ($request['type'] == 'Department') {
            $departmentData = DB::table('units')->where([['is_head', 2], ['status', 1]])->first();
            if (!empty($departmentData)) {
                DB::table('model_has_roles_tmp')->insert([
                    'role_id' => $departmentData->id,
                    'model_type' => 'App\User',
                    'model_id' => $id,
                ]);
            }


            $data = [
                'user_id' => $id,
                'department_id' => $request->department_id,
                'is_email_allow' => $request->is_email_allow,
                'unit_id' => 7,
                'is_department_head' => $request->department_head ? $request->department_head : 0,
                'ip' => $this->getClientIp(),
                'action' => 'Assign',
                'master_id' => $id,
                'form_status' => 0,
                'created_by'=> Auth::user()->user_id,
            ];


            DB::table('user_units_tmp')->insert($data);
            $row = $data;

        } elseif ($request['type'] == 'Division') {
            $divisionData = DB::table('units')->where([['is_head', 3], ['status', 1]])->first();
            if (!empty($divisionData)) {
                DB::table('model_has_roles_tmp')->insert([
                    'role_id' => $divisionData->id,
                    'model_type' => 'App\User',
                    'model_id' => $id,
                ]);
            }


            $data = [
                'user_id' => $id,
                'division_id' => $request->division_id,
                'is_email_allow' => $request->is_email_allow,
                'unit_id' => 11,
                'is_division_head' => $request->division_head ? $request->division_head : 0,
                'ip' => $this->getClientIp(),
                'action' => 'Assign',
                'master_id' => $id,
                'form_status' => 0,
                'created_by'=> Auth::user()->user_id,
            ];


            DB::table('user_units_tmp')->insert($data);
            $row = $data;
        }


        if ($row) {
            if (!empty($request->get('oldDataForAudit'))) {
                $audit = new Audit();
                $audit->auditable_type = 'App\User';
                $audit->user_type = 'App\User';
                $audit->user_id = Auth::id();
                $audit->event = 'create';
                $audit->auditable_id = $id;
                $audit->old_values = json_decode($request->get('oldDataForAudit'));
                $audit->save();
            }
            flash('Unit have been set successfully', 'success');
            return redirect('Users' . $additionalParams);
        } else {
            flash('Failed to set Unit', 'danger');
            return redirect('Users/SetUnit/' . $id . $additionalParams);
        }
    }

    public function approveUnit($id)
    {
        $tempData = DB::table('user_units_tmp')->where('user_id', $id)->first();

        if ($tempData) {
            $data = [
                'user_id' => $tempData->user_id,
                'subgroup_info_id' => $tempData->subgroup_info_id ?? null,
                'group_info_id' => $tempData->group_info_id ?? null,
                'department_id' => $tempData->department_id ?? null,
                'division_id' => $tempData->division_id ?? null,
                'is_email_allow' => $tempData->is_email_allow,
                'unit_id' => $tempData->unit_id,
                'is_unit_head' => $tempData->is_unit_head ?? 0,
                'is_group_info_head' => $tempData->is_group_info_head ?? 0,
                'is_department_head' => $tempData->is_department_head ?? 0,
                'is_division_head' => $tempData->is_division_head ?? 0,
                'ip' => $this->getClientIp(),
                'action' => 'Approve',
                'modified_by'=> Auth::user()->user_id,
                'form_status' => 0,
                'created_at' => $tempData->created_at,
                'updated_at' => $tempData->updated_at,
            ];

            // Insert the data into the main user_units table
            DB::table('user_units')->insert($data);

            // Transfer roles from model_has_roles_tmp to model_has_roles
            $tempRoles = DB::table('model_has_roles_tmp')->where('model_id', $id)->get();

            if ($tempRoles->isNotEmpty()) {
                foreach ($tempRoles as $role) {
                    DB::table('model_has_roles')->insert([
                        'role_id' => $role->role_id,
                        'model_type' => 'App\User', // Adjust if necessary
                        'model_id' => $role->model_id,
                    ]);
                }

                // Remove roles from the temporary roles table
                DB::table('model_has_roles_tmp')->where('model_id', $id)->delete();
            }

            // Remove the record from the temporary unit table
            DB::table('user_units_tmp')->where('user_id', $id)->delete();

            // Flash success message and redirect
            flash('Unit approved and stored successfully!', 'success');
            return redirect('Users'); // Redirect to the desired page
        } else {
            // Flash error message if no record found
            flash('No temporary unit data found for approval.', 'danger');
            return redirect('Users'); // Redirect to the desired page
        }
    }



    public function setCallBackUnit($id = null) {
        DB::table('user_units')->where('user_id',$id)->delete();

        $unAssign = (!empty($_GET['unassign'])) ? $_GET['unassign'] : '';
        unset($_GET['unassign']);
        $additionalParams = (!empty($_GET)) ? '?'.http_build_query($_GET) : "";

        if (!empty($unAssign)) {
            flash('Callback Unit have been Un-Assigned', 'danger');
            return redirect('Users'.$additionalParams);
        }

        $userUnitModel = new UserUnit;
        $userUnitModel->user_id = $id;
        $userUnitModel->unit_id = 10;

        if ($userUnitModel->save()) {
            flash('Unit have been set successfully', 'success');
        } else {
            flash('Failed to set Unit', 'danger');
        }
        return redirect('Users'.$additionalParams);
    }


	//Start Zakir

	public function resetPassword()
    {
        $home_menu_icon = "fa fa-gear";
        return view('Users/reset_password',compact("home_menu_icon"));
    }

    public function resetPasswordSubmit(ChangePasswordRequest $request)
    {
		$reqData = $request->all();
		if (!empty($reqData)) {

            $id = Auth::user()->id;
            /*if (!(Hash::check($request->get('password'), Auth::user()->password))) {

                return redirect()->back()->with("error","Your current password does not matches with the password you provided. Please try again.");
            }*/

           /* if(strcmp($request->get('password'), $request->get('password')) == 0){
                //Current password and new password are same
                return redirect()->back()->with("status","New Password cannot be same as your current password. Please choose a different password.");
            }*/


            //Check Password History
            $user = Auth::user();
            $passwordHistories = $user->passwordHistories()->take(env('PASSWORD_HISTORY_NUM'))->get();
            foreach($passwordHistories as $passwordHistory){
                //echo $passwordHistory->password;
                if (Hash::check($request->get('password'), $passwordHistory->password)) {
                    // The passwords matches
                    return redirect()->back()->with("error","Your new password can not be same as any of your recent passwords. Please choose a new password.");
                }
            }
            $data['password'] = Hash::make($reqData['password']);
            $data['status']   = '1';
            $data['password_changed_at']=date('Y-m-d h:m:s' );
            $update = $this->modelName->where([['id', $id]])->first();
			if ($update->update($data)) {
                $passwordHistory = PasswordHistory::create([
                    'user_id' => $id,
                    'password' => bcrypt($request->get('password'))
                ]);
				flash('New Password has been set successfully', 'success');
					Auth::logout();
					flash('Password Change successfully, Please Login Again', 'success');
					return redirect('/');

			} else {
				toast()->error('Password set failed!!!', 'Danger');
			}
        }

        $home_menu_icon = "fa fa-gear";
        return view('Users/reset_password',compact("home_menu_icon"));
    }

	//End Zakir
    public function isBlock($id,$state){
        $row = User::find(decrypt($id));
        $row->update([
           'is_block'=>($state==1)?1:0,
        ]);
        return redirect('Users');
    }

    /*(zihad) user unassign*/
    public function userUnassign($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            flash('Internal Error Occurs!!!', 'danger');
            return redirect()->back();
        }

        // Fetch the roles and user units assigned to the user
        $roles = DB::table('model_has_roles')->where('model_id', $id)->get();
        $user_units = DB::table('user_units')->where('user_id', $id)->get();

        // Store roles in temporary table
        if ($roles->isNotEmpty()) {
            foreach ($roles as $role) {
                DB::table('model_has_roles_tmp')->insert([
                    'role_id' => $role->role_id,
                    'model_type' => 'App\User',
                    'model_id' => $role->model_id,
                ]);
            }
        }

        // Store user units in temporary table
        if ($user_units->isNotEmpty()) {
            foreach ($user_units as $unit) {
                DB::table('user_units_tmp')->insert([
                    'user_id' => $unit->user_id,
                    'subgroup_info_id' => $unit->subgroup_info_id,
                    'is_unit_head' => $unit->is_unit_head,
                    'unit_id' => $unit->unit_id,
                    'is_email_allow' => $unit->is_email_allow,
                    'is_group_info_head' => $unit->is_group_info_head,
                    'is_department_head' => $unit->is_department_head,
                    'is_division_head' => $unit->is_division_head,
                    'group_info_id' => $unit->group_info_id,
                    'department_id' => $unit->department_id,
                    'ip' => $this->getClientIp(),
                    'action' => 'unAssign',
                    'master_id' => $unit->id,
                    'form_status' => 0,
                    'created_by'=> Auth::user()->user_id,
                ]);
            }
        }

        flash('User unassigned request submitted successfully. Please wait for approval.', 'success');
        return redirect()->back();
    }

    public function approveUnassign($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            flash('Internal Error Occurs!!!', 'danger');
            return redirect()->back();
        }

        // Remove roles from the main table
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        // Remove user units from the main table
        DB::table('user_units')->where('user_id', $id)->delete();

        // Optionally, clear the related temporary entries if needed
        DB::table('model_has_roles_tmp')->where('model_id', $id)->delete();
        DB::table('user_units_tmp')->where('user_id', $id)->delete();

        flash('User unassigned successfully.', 'success');
        return redirect()->back();
    }




    public function outgoingSMSEmail($name = "", $user_id = "")
    {
        $msg = array();
        $sms = "";
        $mail = "";

        $smsEmailModel = new SMSEmail();
        $smsEmailData = $smsEmailModel->orderBy('id','DESC')->first();

        if (!empty($smsEmailData)) {
            $sms = $smsEmailData['user_notify_sms'];
            $mail = $smsEmailData['user_notify_email'];
        }

        if (!empty($sms)) {
            $sms = str_replace("{user_name}", $name, $sms);
            $sms = str_replace("{user_id}", $user_id, $sms);
            $msg['sms'] = $sms;
        }

        if (!empty($mail)) {
            $mail = str_replace("{user_name}", $name, $mail);
            $mail = str_replace("{user_id}", $user_id, $mail);
            $msg['mail'] = $mail;
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


    public function sendEMAIL($email_address, $mail, $ref_no = "", $supp_status = NULL)
    {
        date_default_timezone_set('Asia/Dhaka');
        $savedtime = date("Y-m-d H:i:s");
        if ($mail != "") {
            $outgoingEMAILModel = new OutgoingEMAIL;
            $outgoingEMAILModel->subject = 'User Notification';
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
