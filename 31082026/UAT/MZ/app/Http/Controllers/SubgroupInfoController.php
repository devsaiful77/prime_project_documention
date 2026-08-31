<?php


namespace App\Http\Controllers;


use App\GroupInfo;
use App\SubgroupInfo;
use App\SubgroupInfoTmp;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class SubgroupInfoController extends Controller
{
    public $checker = false;

    public function __construct()
    {
        // $this->middleware('auth');
        //$this->middleware('ability:superadmin|admin,accessSubgroup');
        $this->middleware(['role_or_permission:superadmin|admin|accessSubgroup|accessSubgroupChecker']);
        parent::__construct();
    }

    public function accessSubgroupChecker()
    {
        if (Auth::check() && Auth::user()->hasPermissionTo('accessSubgroupChecker')) {
            return $this->checker = true;
        } else {
            return $this->checker;
        }
    }

    public function index()
    {
        $tblData = SubgroupInfo::all();
        $checker = $this->accessSubgroupChecker();
        return view('subgroup_info.index', compact('tblData', 'checker'));
    }
    public function create()
    {
        $checker = $this->accessSubgroupChecker();
        if ($checker) {
            abort(403, 'You do not have permission to access this page.');
        }
        return view('subgroup_info.create');
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
            'name' => 'required|unique:subgroup_info,name',
            'department_id' => 'required',
            'group_info_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    $branchGroup = SubgroupInfo::where('group_info_id', $value)->first();
                    if ($branchGroup && $value != 1) {
                        $fail('One Sub Group already exists in this Group.');
                    }
                }
            ],

        ], [
            'department_id.required' => 'The department field is required.',
            'group_info_id.required' => 'The Group Info field is required.',
        ]);

        $validator->after(function ($validator) use ($request) {
            if (DB::table('subgroup_info')->where('name', $request->name)->exists()) {
                $validator->errors()->add('name', 'The name has already been taken in sub group info table.');
            }

            if (DB::table('subgroup_info_tmps')->where('name', $request->name)->exists()) {
                $validator->errors()->add('name', 'The name has already been taken, please wait for Checker Approval.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $subGroupInfoTmp = new SubgroupInfoTmp();
        $subGroupInfoTmp->name = $request->name;
        $subGroupInfoTmp->department_id = $request->department_id;
        $subGroupInfoTmp->description = $request->description;
        $subGroupInfoTmp->address = $request->address;
        $subGroupInfoTmp->group_info_id = $request->group_info_id;
        $subGroupInfoTmp->action = "Add";
        $subGroupInfoTmp->status = 2;
        $subGroupInfoTmp->created_by = Auth::user()->id;
        $subGroupInfoTmp->ip = $this->getClientIp();

        if ($subGroupInfoTmp->save()) {
            flash('Subgroup info has been inserted For Review in Checker', 'success');
            return redirect('subgroup-info');
        } else {
            flash('Failed to insert data', 'danger');
            return redirect('subgroup-info/create');
        }
    }



    public function approveSubGroupInfo($id)
    {
        $checker = $this->accessSubgroupChecker();
        if ($checker == false) {
            abort(403, 'You do not have permission to access this page.');
        }
        DB::beginTransaction();

        try {
            // $subGroupInfoTmp = DB::table('subgroup_info_tmps')->where('status', '!=', 3)->first();

            $subGroupInfoTmp = DB::table('subgroup_info_tmps')->where('id', $id)->first();
            if (!$subGroupInfoTmp) {
                flash('Subgroup info not found or not approved yet.', 'danger');
                return redirect()->back();
            }

            $subGroupInfoData = [
                'name'           => $subGroupInfoTmp->name,
                'description'    => $subGroupInfoTmp->description,
                'address'    => $subGroupInfoTmp->address,
                'department_id'  => $subGroupInfoTmp->department_id,
                'group_info_id' => $subGroupInfoTmp->group_info_id,
                'is_active'      => $subGroupInfoTmp->status == 2 ? 1 : $subGroupInfoTmp->status,
                'created_at'     => now(),
                'updated_at'     => now(),
            ];
            if ($subGroupInfoTmp->master_id != null && $subGroupInfoTmp->action != "Add") {
                DB::table('subgroup_info')->where('id', $subGroupInfoTmp->master_id)->update($subGroupInfoData);
            } else {
                DB::table('subgroup_info')->insert($subGroupInfoData);
            }

            DB::table('subgroup_info_tmps')->where('id', $subGroupInfoTmp->id)->delete();
            DB::commit();

            if ($subGroupInfoTmp->status == 0) {
                flash('Subgroup info has been inActive.', 'danger');
            } elseif ($subGroupInfoTmp->status == 1) {
                flash('Subgroup info has been activated.', 'success');
            } else {
                flash('Subgroup info has been approved.', 'success');
            }
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            flash('An error occurred during the approval process: ' . $e->getMessage(), 'danger');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $checker = $this->accessSubgroupChecker();
        if ($checker) {
            abort(403, 'You do not have permission to access this page.');
        }
        $tmpId = 0;
        $row = SubgroupInfo::find(decrypt($id));
        return view('subgroup_info.edit', compact('row', 'tmpId'));
    }

    public function subGroupInfoTmpEdit(Request $request, $id = null)
    {
        $checker = $this->accessSubgroupChecker();
        if ($checker) {
            abort(403, 'You do not have permission to access this page.');
        }

        $subGroupInfoTmp = SubgroupInfoTmp::findOrFail($id);
        $row = $subGroupInfoTmp;
        $tmpId = $id;

        return view('subgroup_info.edit', compact('row', 'tmpId'));
    }


    public function update(Request $request, $id)
    {
        $isTemp = !empty($request->tmpId) && $request->tmpId != 0;

        $columnArr = ['name'];
        $charArr = ['<', '>', '"', "'", '|', '=', '#', '%', '&', '*', '!'];
        $validationResult = validateSpecialChars($request->all(), $columnArr, $charArr);
        if ($validationResult !== true) {
            return back()->withErrors($validationResult)->withInput();
        }

        $tableName = $isTemp ? 'subgroup_info_tmps' : 'subgroup_info';

        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                Rule::unique($tableName, 'name')->ignore($id, 'id')
            ],
            'department_id' => 'required',
            'group_info_id' => 'required',
        ], [
            'department_id.required' => 'The department field is required.',
            'group_info_id.required' => 'The group information field is required.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $existingRecords = $isTemp ? SubgroupInfoTmp::where('id', $id)->get() : SubgroupInfoTmp::where('master_id', $id)->get();

        $hasInvalidRecord = $existingRecords->contains(function ($record) {
            return $record->form_status != 7 && $record->form_status != -1;
        });

        if ($hasInvalidRecord) {
            flash('An entry already exists, please wait for Checker Approval', 'danger');
            return Redirect::back();
        }

        // $existingTmpRecords = DB::table('subgroup_info_tmps')
        //     ->where('master_id', $id)
        //     ->get();


        // $hasInvalidRecord = $existingTmpRecords->contains(function ($record) {
        //     return $record->form_status != 7 && $record->form_status != -1;
        // });

        // $validator->after(function ($validator) use ($request, $id, $hasInvalidRecord) {
        //     $nameExistsInSubgroup = DB::table('subgroup_info')
        //         ->where('name', $request->name)
        //         ->where('id', '!=', $id)
        //         ->exists();

        //     $nameExistsInTmp = DB::table('subgroup_info_tmps')
        //         ->where('name', $request->name)
        //         ->where('id', '!=', $id)
        //         ->where('form_status', '!=', 7)
        //         ->where('form_status', '!=', -1)
        //         ->exists();

        //     if ($nameExistsInSubgroup) {
        //         $validator->errors()->add('name', 'The name has already been taken in the subgroup_info table.');
        //     }

        //     if ($nameExistsInTmp) {
        //         $validator->errors()->add('name', 'This name is currently under review by Checker. Please wait for approval before proceeding.');
        //     }

        //     if ($hasInvalidRecord) {
        //         $validator->errors()->add('name', 'There are invalid records under review in the subgroup_info_tmps table. Please wait for the Checker to approve or reject.');
        //     }
        // });

        // if ($validator->fails()) {
        //     return redirect()->back()->withErrors($validator)->withInput();
        // }


        $subGroupInfo = new SubgroupInfo();
        $data = $request->all();

        $additionalParams = (!empty($data['additionalParams'])) ? $data['additionalParams'] : "";
        unset($data['additionalParams']);

        $data['modified_by'] = Auth::user()->id;

        if ($isTemp) {
            $subGroupInfoTmp = SubgroupInfoTmp::findOrFail($id);
        } else {
            $subGroupInfoTmp = new SubgroupInfoTmp();
            $update = $subGroupInfo->where('id', $id)->first();
        }

        $subGroupInfoTmp->name = $request->name;
        $subGroupInfoTmp->department_id = $request->department_id;
        $subGroupInfoTmp->group_info_id = $request->group_info_id;
        $subGroupInfoTmp->description = $request->description;
        $subGroupInfoTmp->address = $request->address;
        $subGroupInfoTmp->status = 2;
        $subGroupInfoTmp->form_status = 0;
        $subGroupInfoTmp->action = "Edit";
        $subGroupInfoTmp->master_id = $isTemp ? $subGroupInfoTmp->master_id : $update->id;
        $subGroupInfoTmp->created_by = Auth::user()->id;
        $subGroupInfoTmp->ip = $this->getClientIp();

        if ($subGroupInfoTmp->save()) {
            flash('Subgroup info has been updated For Review in Checker', 'success');
            if ($isTemp) {
                flash("Subgroup info temporary data updated successfully.", "success");
                return redirect('subgroup-info/action-queue-list');
            }
            return redirect('/subgroup-info' . $additionalParams);
        } else {
            flash('Failed to update group info, Please try again', 'danger');
        }
    }

    public function status($id = null, $status = "")
    {
        $checker = $this->accessSubgroupChecker();
        if ($checker) {
            abort(403, 'You do not have permission to access this page.');
        }

        $existingTmpRecords = SubgroupInfoTmp::where('master_id', decrypt($id))->get();

        $hasInvalidRecord = $existingTmpRecords->contains(function ($record) {
            return $record->form_status != 7 && $record->form_status != -1;
        });

        if ($hasInvalidRecord) {
            flash('An entry already exists, please wait for Checker Approval', 'danger');
            return Redirect::back();
        }

        $subGroupInfo = new SubgroupInfo();
        if ($status != 1 && $status != 0) {
            flash($status . ' is not Allowed!!!', 'danger');
            return Redirect::back();
        }

        $update = $subGroupInfo->where([['id', decrypt($id)]])->first();

        $subGroupInfoTmp = new SubgroupInfoTmp();
        if ($status == 0) {
            $subGroupInfoTmp->status = 0;
            $subGroupInfoTmp->action = "Inactive";
        } else {
            $subGroupInfoTmp->status = 1;
            $subGroupInfoTmp->action = "Active";
        }
        $subGroupInfoTmp->master_id = $update->id;
        $subGroupInfoTmp->name = $update->name;
        $subGroupInfoTmp->department_id = $update->department_id;
        $subGroupInfoTmp->group_info_id = $update->group_info_id;
        $subGroupInfoTmp->description = $update->description;
        $subGroupInfoTmp->address = $update->address;
        $subGroupInfoTmp->created_by = Auth::user()->id;
        $subGroupInfoTmp->ip = $this->getClientIp();

        $subGroupInfoTmp->save();

        if ($status == 1) {
            flash('Subgroup info Active Request Send to Checker', 'success');
        } elseif ($status == 0) {
            flash('Subgroup info Inactive Request Send to Checker', 'danger');
        }
        // return redirect('/Divisions');
        return Redirect::back();
    }
    public function destroy($id)
    {
        $row = SubgroupInfo::find(decrypt($id));
        $row->delete();
        return redirect()->back();
    }
    public function activate($id, $state)
    {
        $row = SubgroupInfo::find(decrypt($id));
        $row->update([
            'is_active' => $state
        ]);
        return redirect()->back();
    }
    public function subgroupList($group_id)
    {
        $rows = SubgroupInfo::where('is_active', true)->where('group_info_id', $group_id)->get();
        return json_encode($rows);
    }

    public function subGroupInfoQueueList()
    {
        $checker = $this->accessSubgroupChecker();
        if ($checker) {
            $isChecker = true;
            $subGroupInfoTmp = SubgroupInfoTmp::with(['department', 'groupInfo'])->whereNotIn('form_status', [7, -1])->get();
        } else {
            $isChecker = false;
            $subGroupInfoTmp = SubgroupInfoTmp::with(['department', 'groupInfo'])->whereIn('form_status', [7, -1])->get();
        }
        return view('subgroup_info.index_tmp', compact('subGroupInfoTmp', 'isChecker'));
    }

    public function subGroupInfoAssign($id)
    {
        $tmpData = SubgroupInfoTmp::where('id', $id)->first();
        $tmpData->form_status = 1;
        $tmpData->modified_by = Auth::user()->user_id;
        $tmpData->save();

        $data = [
            'message' => 'Assigned Successfully from your End!',
            'id' => $id,
        ];

        return response()->json($data, 200);
    }

    public function buttonControl($id)
    {
        $tmpData = SubgroupInfoTmp::findOrFail($id);

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
        $tmpData = SubgroupInfoTmp::with(['department', 'groupInfo'])->findOrFail($id);

        if (!empty($tmpData->master_id)) {
            $oldData = SubgroupInfo::with(['dept', 'groupInfo'])->findOrFail($tmpData->master_id);
        }

        $tmpData->department_name = $tmpData->department->name ?? null;
        $tmpData->group_name = $tmpData->groupInfo->name ?? null;
        $tmpData->status = $tmpData->status == 1 ? "Active" : "Inactive";

        unset(
            $tmpData->department,
            $tmpData->department_id,
            $tmpData->group_info_id,
            $tmpData->is_active
        );

        if (isset($oldData)) {
            $oldData->department_name = $oldData->dept->name ?? null;
            $oldData->group_name = $oldData->groupInfo->name ?? null;
            $oldData->status = $oldData->is_active == 1 ? "Active" : "Inactive";
            unset(
                $oldData->dept,
                $oldData->department_id,
                $oldData->group_info_id,
                $oldData->is_active
            );
        }

        $columnsToSend = ['name', 'group_name', 'department_name', 'description', 'address', 'status'];

        $filteredTmpData = $tmpData->only($columnsToSend);

        $filteredOldData = isset($oldData) ? $oldData->only($columnsToSend) : null;

        $response = [
            'old_data' => $filteredOldData,
            'new_data' => $filteredTmpData
        ];

        return response()->json($response, 200);
    }

    public function subGroupInfoSendback(Request $request, $id)
    {
        $checker = $this->accessSubgroupChecker();
        if ($checker == false) {
            abort(403, 'You do not have permission to access this page.');
        }
        $request->validate([
            'comments' => 'required'
        ]);

        $tmpData = SubgroupInfoTmp::where('id', $id)->first();
        $tmpData->form_status = 7;
        $tmpData->modified_by = null;
        $tmpData->comments = $request->comments;
        $tmpData->save();

        flash('Send back Successfully!', 'success');
        return redirect()->back();
    }

    public function subgroupInfoReject(Request $request, $id)
    {
        $checker = $this->accessSubgroupChecker();
        if ($checker == false) {
            abort(403, 'You do not have permission to access this page.');
        }

        $request->validate([
            'comments' => 'required'
        ]);

        $tmpData = SubgroupInfoTmp::findOrFail($id);
        $tmpData->form_status = -1;
        $tmpData->modified_by = Auth::user()->user_id;
        $tmpData->comments = $request->comments;
        $tmpData->save();

        flash('Request rejected!!', 'danger');
        return Redirect::back();
    }
}
