<?php

namespace App\Http\Controllers;

use App\User;
use App\UnitItem;
use App\IssueGroup;
use App\IssueGroupWorkflow;
use App\SubgroupInfo;
use App\IssueWorkflow;
use App\IssueGroupMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\IssueGroupMemberRequest;

class IssueGroupController extends Controller
{
    public $checker = false;

    public function __construct()
    {
        $this->middleware(['role_or_permission:superadmin|accessIssueGroup|accessIssueGroupChecker']);
        parent::__construct();
    }

    public function accessIssueGroupChecker()
    {
        

    }

    public function index(Request $request)
    {
        $checker = $this->accessIssueGroupChecker();

        $divisionModelName = new IssueGroup();
        $dataForView = array();
        $tblData = array();
        $dataObj = $divisionModelName->with([
            'issueName:id,name',
        ])->orderBy("id", "DESC")->get();
        if (!empty($dataObj)) {
            $tblData = $dataObj->toArray();
        }

        $searchDataForView = $request->all();
        $title = "Issue Group List";
        $title_for_layout = "Issue Group List";

        return view('Issue-group.index', compact(
            'title',
            'title_for_layout',
            'dataForView',
            'searchDataForView',
            'tblData',
            'checker'
        ));
    }


    public function add()
    {
        $checker = $this->accessIssueGroupChecker();
        if ($checker) {
            abort(403, 'You do not have permission to access this page.');
        }

        $title = "Add Issue Group";
        $title_for_layout = "Add Issue Group";
        $id = 0;
        $tmpId = 0;
        $dataForView = array();


        $issueGroupIds = IssueGroup::pluck('unit_item_id');
        $workflow = IssueWorkflow::with('issue')->whereNotIn('issue_id', $issueGroupIds)->get();

        return view('Issue-group.add', compact('title', 'title_for_layout', 'id', 'dataForView', 'tmpId', 'workflow'));
    }

    public function edit($id)
    {
        if ($this->accessIssueGroupChecker()) {
            abort(403, 'You do not have permission to access this page.');
        }

        $issueGroup = IssueGroup::with('issueName:id,name')->findOrFail($id);
        $issueWorkFlow = IssueWorkflow::where('issue_id', $issueGroup->unit_item_id)->firstOrFail();

        /* ================= Workflow wise group ================= */
        $groupWorkflows = DB::table('issue_group_workflows')
            ->where('issue_workflow_id', $issueWorkFlow->issue_workflow_id)
            // ->where('is_touch_point', 0)
            // ->whereIn('is_touch_point', [0, 1])
            ->get()
            ->keyBy('group_info_id');

        $groupInfoIds = $groupWorkflows->keys()->toArray();

        /* ================= Subgroup + Users ================= */
        $subgroups = SubgroupInfo::with([
            'userUnits:id,user_id,unit_id,subgroup_info_id',
            'userUnits.user:id,name,user_id',
            'userUnits.user.roles:id,display_name',
            'issueGroupMembers' => function ($q) use ($issueGroup) {
                $q->where('issue_group_id', $issueGroup->id)
                    ->select('id', 'subgroup_info_id', 'user_id', 'is_touch_point', 'ordering');
            }
        ])
            ->where('is_active', 1)
            ->whereIn('group_info_id', $groupInfoIds)
            ->get();

        /* ================= Touch config ================= */
        $subgroups->transform(function ($subgroup) use ($groupWorkflows) {
            $workflow = $groupWorkflows[$subgroup->group_info_id] ?? null;
            $subgroup->touch_maker   = $workflow->touch_maker   ?? 0;
            $subgroup->touch_checker = $workflow->touch_checker ?? 0;
            $subgroup->is_touch_point = $workflow->is_touch_point ?? 0;
            return $subgroup;
        });

        // return $subgroups;

        return view('Issue-group.edit', compact('issueGroup', 'subgroups'));
    }


    public function view($id)
    {
        $checker = $this->accessIssueGroupChecker();
        if ($checker) {
            abort(403, 'You do not have permission to access this page.');
        }
        $issueGroup = IssueGroup::with('issueName:id,name')->find($id);
        if (!$issueGroup) {
            return redirect()->back()->with('error', 'Data not found');
        }
        $issueGroupMember = IssueGroupMember::with(['subgroup:id,name', 'user:id,name,user_id'])
            ->where('issue_group_id', $issueGroup->id)
            ->get()
            ->groupBy('subgroup_info_id');
        $title = "View Issue Group";
        $title_for_layout = "View Issue Group";

        return view('Issue-group.view', compact(
            'title',
            'title_for_layout',
            'issueGroup',
            'issueGroupMember'
        ));
    }

    // public function store(IssueGroupMemberRequest $request)
    public function store(Request $request)
    {
        // Validate
        $request->validate([
            'workflow_id' => 'required',
        ]);

        // return $request->all();

        DB::beginTransaction();
        try {
            $workflow = IssueWorkflow::findOrFail($request->workflow_id);
            // Issue group data store
            $issueGroup = IssueGroup::create([
                'unit_item_id' => $workflow->issue_id,
            ]);

            $this->storeIssueGroupMembers(
                $request->input('users', []),
                $issueGroup->id,
                $workflow->issue_workflow_id
            );

            DB::commit();
            flash('Users added successfully!', 'success');
            return redirect('/issue/group');
        } catch (\Exception $e) {
            DB::rollBack();
            flash($e->getMessage(), 'error');
            return back()->withInput();
        }
    }

    // public function update(IssueGroupMemberRequest $request, $id)
    public function update(Request $request, $id)
    {
        // return $users = collect($request->users)
        // ->filter(fn ($u) => isset($u['id']));

        DB::beginTransaction();
        try {
            $issueGroup = IssueGroup::findOrFail($id);
            $workflow = IssueWorkflow::where('issue_id', $issueGroup->unit_item_id)->firstOrFail();
            // Delete old members
            IssueGroupMember::where('issue_group_id', $issueGroup->id)->delete();
            $this->storeIssueGroupMembers(
                $request->input('users', []),
                $issueGroup->id,
                $workflow->issue_workflow_id
            );
            DB::commit();
            flash('Issue Group updated successfully!', 'success');
            return redirect('/issue/group/view/' . $issueGroup->id);
        } catch (\Exception $e) {
            DB::rollBack();
            flash('Failed to update Issue Group. Please try again.', 'error');
            return back()->withInput();
        }
    }

    // ajax Request
    public function workflowWiseSubgroup(Request $request)
    {
        $groupWorkflows = DB::table('issue_group_workflows')
            ->where('issue_workflow_id', $request->workflowId)
            ->where('is_touch_point', 0)
            ->get()
            ->keyBy('group_info_id');

        $groupInfoIds = $groupWorkflows->keys()->toArray();
        // subgroup with users
        $subgroups = SubgroupInfo::with([
            // userUnits table
            'userUnits:id,user_id,unit_id,subgroup_info_id',
            // users table
            'userUnits.user:id,name,user_id',
            // roles table
            'userUnits.user.roles:id,display_name',
        ])
            ->where('is_active', 1)
            ->whereIn('group_info_id', $groupInfoIds)
            ->get();

        $subgroups->transform(function ($subgroup) use ($groupWorkflows) {
            $workflow = $groupWorkflows[$subgroup->group_info_id] ?? null;
            $subgroup->touch_maker   = $workflow->touch_maker   ?? 0;
            $subgroup->touch_checker = $workflow->touch_checker ?? 0;
            return $subgroup;
        });

        // return $subgroups;

        return view('Issue-group.component.subgroup', compact('subgroups'));
    }

    // Store Issue Member
    private function storeIssueGroupMembers(array $users, int $issueGroupId, int $issueWorkflowId)
    {
        /* ================= Touch group users ================= */
        $groupIds = IssueGroupWorkflow::where('issue_workflow_id', $issueWorkflowId)
            ->where('is_touch_point', 1)
            ->pluck('group_info_id');

        if ($groupIds->isEmpty()) {
            return;
        }

        $subgroupIds = SubgroupInfo::whereIn('group_info_id', $groupIds)
            ->where('is_active', 1)
            ->pluck('id');

        $touchUsers = DB::table('user_units')
            ->whereIn('subgroup_info_id', $subgroupIds)
            ->whereRaw("FIND_IN_SET(2, unit_id)")
            ->get();

        foreach ($touchUsers as $user) {
            DB::table('issue_group_members')->insert([
                'issue_group_id'   => $issueGroupId,
                'subgroup_info_id' => $user->subgroup_info_id,
                'user_id'          => $user->user_id,
                'unit_id'          => 2,
                'is_touch_point'   => 1,
            ]);
        }



        /* ================= Non-touch users ================= */
        $noTouchUsers = collect($users)
            ->filter(fn($data) => isset($data['id']))
            ->map(fn($data) => [
                'id'       => $data['id'],
                'position' => $data['position'] ?? null,
            ]);

        foreach ($noTouchUsers as $item) {

            $userUnit = DB::table('user_units')
                ->where('user_id', $item['id'])
                ->first();

            if (!$userUnit) {
                continue;
            }

            $unitIds = array_map('trim', explode(',', $userUnit->unit_id));

            foreach ($unitIds as $unitId) {

                if (!in_array($unitId, [1, 2])) {
                    continue;
                }

                $checkExist = DB::table('issue_group_members')
                    ->where('user_id', $userUnit->user_id)
                    ->where('unit_id', $unitId)
                    ->first();

                if (empty($checkExist)) {
                    DB::table('issue_group_members')->insert([
                        'issue_group_id'   => $issueGroupId,
                        'subgroup_info_id' => $userUnit->subgroup_info_id,
                        'user_id'          => $userUnit->user_id,
                        'unit_id'          => $unitId,
                        'ordering'         => $item['position'],
                        'is_touch_point'   => 0,
                    ]);
                }
            }



            // DB::table('issue_group_members')->insert([
            //     'issue_group_id'   => $issueGroupId,
            //     'subgroup_info_id' => $userUnit->subgroup_info_id,
            //     'user_id'          => $userUnit->user_id,
            //     'unit_id'          => $userUnit->unit_id,
            //     'ordering'         => $item['position'],
            //     'is_touch_point'   => 0,
            // ]);
        }
    }
}
