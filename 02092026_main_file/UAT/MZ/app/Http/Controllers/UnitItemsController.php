<?php

namespace App\Http\Controllers;

use App\Unit;
use App\CIFApi;
use App\UnitItem;
use App\UserUnit;
use App\IssueConfig;
use App\SubgroupInfo;
use App\Http\Requests;
use App\IssueCategories;
use App\IssueConfigMapping;
use App\issueFieldsetGroup;
use Illuminate\Http\Request;
use App\IssueConditionalField;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UnitItemRequest;
use App\IssueCategoriesTmp;
use App\UnitItemTmp;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class UnitItemsController extends Controller
{
    public $checker = false;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    //    $this->middleware('ability:superadmin|admin,accessIssueCategories')->only('issuesCategory','addCategory','storeCategory','editCategory','updateCategory','statusCategory','getIssueWiseCategory');
        // $this->middleware(['role_or_permission:superadmin|accessIssues|accessIssueCategories|accessIssueCategoriesChecker|adminActivity|true']);

    //    $this->middleware('ability:superadmin|admin,accessIssues')->only('index','add','store','edit','update','status','sms_status','config','checkListConfig');
        // $this->middleware(['role_or_permission:superadmin|admin|accessIssues'])->only('index','add','store','edit','update','status','sms_status','config','checkListConfig');

    //    $this->middleware('ability:superadmin|admin,adminActivity|true')->except('getCategoryWiseComplaint','getCategoryWiseService','getCatWiseServices','issuesCategory','addCategory','storeCategory','editCategory','updateCategory','statusCategory','getIssueWiseCategory','index','add','store','edit','update','status','sms_status','config','checkListConfig');
        // $this->middleware('role_or_permission:superadmin|admin|adminActivity|true')->except('getCategoryWiseComplaint','getCategoryWiseService','getCatWiseServices','issuesCategory','addCategory','storeCategory','editCategory','updateCategory','statusCategory','getIssueWiseCategory','index','add','store','edit','update','status','sms_status','config','checkListConfig','issueDependantFields','issueConditional','issueConditionalFields');

        parent::__construct();
    }

    public function accessIssueCategoriesChecker(){
        if (Auth::check() && Auth::user()->hasPermissionTo('accessIssueCategoriesChecker')) {
            return $this->checker = true;
        }else{
            return $this->checker;
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $unitItemModelName = new UnitItem;
        $dataForView = array();
        $searchDataForView = array();
        $searchDataForView['name'] = $request->name;
        $searchDataForView['issues_from'] = $request->issues_from;
        $tblData = array();
        $dataObj = $unitItemModelName
                        ->select("id", "product_type_id", "issues_from", "unit_id", "auto_unit_id", "name", "status", "issue_categories_id", "is_sent_sms", "is_api", "is_ci", "is_ci_cif",
                            DB::raw("CASE WHEN status = 1 THEN 'Active' WHEN status = 0 THEN 'Inactive' ELSE 'Invalid' END AS status_name"),
                            DB::raw("CASE WHEN is_sent_sms = 1 THEN 'Active' WHEN is_sent_sms = 0 THEN 'Inactive' ELSE 'Invalid' END AS sms_status"))
                       ->with([
                            "unit"=>function($e){
                                $e->select("id","name");

                            },
                            "unit_callback"=>function($e){
                                $e->select("id","name");

                            },'productType'=>function($e){
                                $e->select('id','name');
                            },'IssueCategories'=>function($e){
                                $e->select('id','name');
                            }
                        ]);
        if (!empty($searchDataForView['name'])) {
            $dataObj = $dataObj->where('name','LIKE','%'.$searchDataForView['name'].'%');
        }
        if (!empty($searchDataForView['issues_from'])) {
            $dataObj = $dataObj->where('issues_from',$searchDataForView['issues_from']);
        }

        $dataObj = $dataObj->orderBy("issues_from","DESC")
                        ->orderBy("name","ASC")
                        ->get();

        if (!empty($dataObj)) {
            $tblData = $dataObj->toArray();
        }
		//prd($tblData);
        $title = "Issues List";
        $title_for_layout = "Issues List";
        return view('UnitItems.index',compact('title','title_for_layout','tblData','searchDataForView','dataObj'));
    }

    public function add()
    {
        $checker = $this->accessIssueCategoriesChecker();
        if ($checker) {
            abort(403, 'You do not have permission to access this page.');
        }
        $id = 0;
        $dataForView = array();

        $title = "Add Issues";
        $title_for_layout = "Add Issues";

        $unitModelName = new Unit;

        $allUnitData = $unitModelName
                            ->select('id','name')
                            ->where('status',1)
                            ->orderBy('name','ASC')
                            ->pluck('name', 'id')
                            ->toArray();


        return view('UnitItems.add',compact('title','title_for_layout','id','allUnitData','dataForView'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'product_type_id'=>'required',
            'issues_from'=>'required',
        ]);

        $validator->after(function ($validator) use ($request) {
            $existsInUnitItems = DB::table('unit_items')
                ->where('name', $request->name)
                ->where('product_type_id', $request->product_type_id)
                ->where('issue_categories_id', $request->issue_categories_id)
                ->where('issues_from', $request->issues_from)
                ->exists();

            if ($existsInUnitItems) {
                $validator->errors()->add('name', 'The combination of name, product type, issues from, and issue category has already been taken in unit items table.');
            }

            $existsInUnitItemsTmp = DB::table('unit_items_tmp')
                ->where('name', $request->name)
                ->where('product_type_id', $request->product_type_id)
                ->where('issue_categories_id', $request->issue_categories_id)
                ->where('issues_from', $request->issues_from)
                ->exists();

            if ($existsInUnitItemsTmp) {
                $validator->errors()->add('name', 'The combination of name, product type, issues from, and issue category has already been taken in unit items tmp table.');
            }
        });

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $unitItemsTmp = new UnitItemTmp();
        if ($request->isMethod('post')) {
            $unitItemsTmp->name = $request->name;
            $unitItemsTmp->product_type_id = $request->product_type_id;
            $unitItemsTmp->issues_from = $request->issues_from;
			$unitItemsTmp->issue_categories_id = $request->issue_categories_id;
            $unitItemsTmp->status = 2;
            $unitItemsTmp->action = "Add";
            $unitItemsTmp->created_by = Auth::user()->id;
            $unitItemsTmp->ip = $this->getClientIp();
            $unitItemsTmp->is_api = 0;
            $unitItemsTmp->is_ci = $request->is_ci;
            $unitItemsTmp->is_ci_cif = 0;

            if ($unitItemsTmp->save()) {
                // $lastInsertedId = $unitItemsTmp->id;
                // $unitItemsTmp->master_id = $lastInsertedId;
                // $unitItemsTmp->save();

                flash('Issues has been inserted successfully for review in checker', 'success');
                return redirect('Issues');
            } else {
                flash('Failed to insert data', 'danger');
                return redirect('Issues/add');
            }
        }
    }

    public function edit(Request $req, $id = null)
    {
        $checker = $this->accessIssueCategoriesChecker();
        if ($checker) {
            abort(403, 'You do not have permission to access this page.');
        }
        $unitItemModelName = new UnitItem;
        $title = " Edit Issues";
        $title_for_layout = 'Edit Issues';
        $searchDataForView = $req->all();
        $dataForView = $unitItemModelName->where('id', $id)->first();
        $unitModelName = new Unit;
        $allUnitData = $unitModelName
                            ->select('id','name')
                            ->where('status',1)
                            ->orderBy('name','ASC')
                            ->pluck('name', 'id')
                            ->toArray();
        if ($dataForView->status == 0) {
            abort(403,'Edit Not Allowed !!!');
        }
        return view('UnitItems.add', compact('title', 'title_for_layout', 'dataForView','searchDataForView','allUnitData','id'));
    }

    public function update(Request $request, $id = null)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'product_type_id'=>'required',
            'issues_from'=>'required',
        ]);

        // $validator->after(function ($validator) use ($request, $id) {
        //     $existsInUnitItems = DB::table('unit_items')
        //         ->where('name', $request->name)
        //         ->where('product_type_id', $request->product_type_id)
        //         ->where('issue_categories_id', $request->issue_categories_id)
        //         ->where('issues_from', $request->issues_from)
        //         ->where('id', '!=', $id)
        //         ->exists();

        //     if ($existsInUnitItems) {
        //         $validator->errors()->add('name', 'The combination of name, product type, issues from, and issue category has already been taken in unit items table.');
        //     }

        //     $existsInUnitItemsTmp = DB::table('unit_items_tmp')
        //         ->where('name', $request->name)
        //         ->where('product_type_id', $request->product_type_id)
        //         ->where('issue_categories_id', $request->issue_categories_id)
        //         ->where('issues_from', $request->issues_from)
        //         ->where('master_id', '!=', $id)
        //         ->exists();

        //     if ($existsInUnitItemsTmp) {
        //         $validator->errors()->add('name', 'The combination of name, product type, issues from, and issue category has already been taken in unit items tmp table.');
        //     }
        // });

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $existingTmpRecords = UnitItemTmp::where('master_id', $id)->get();

        $hasInvalidRecord = $existingTmpRecords->contains(function ($record) {
            return $record->form_status != 7 && $record->form_status != -1;
        });

        if ($hasInvalidRecord) {
            flash('An entry already exists, please wait for Checker Approval', 'danger');
            return Redirect::back();
        }

        // $unitItemModelName = new UnitItem;
        $data = $request->all();
        $data['status'] = 2;
        $data['action'] = "Edit";
        $data['created_by'] = Auth::user()->id;
        $data['master_id'] = $id;
        $data['ip'] = $this->getClientIp();
        unset($data['_token']);

        $additionalParams = (!empty($data['additionalParams'])) ? $data['additionalParams'] : "";
        unset($data['additionalParams']);

        // $update = $unitItemModelName->where([['id', $id]])->first();
        $insert = new UnitItemTmp();
        if ($insert->insert($data)) {
            flash('Issues has been updated successfully for review in checker', 'success');
            return redirect('/Issues'.$additionalParams);
        } else {
            flash('Failed to update Issues, Please try again', 'danger');
        }
    }

    public function approveIssue($id)
    {
        DB::beginTransaction();

        try {
            $tmpIssue = DB::table('unit_items_tmp')->where('id', $id)->first();
            // $tmpIssue = DB::table('divisions_tmp')->where('id', $id)->where('status', 0)->first();

            if (!$tmpIssue) {
                flash('Issue not found or not approved yet.', 'danger');
                return redirect()->back();
            }

            $issueData = [
                'product_type_id'      => $tmpIssue->product_type_id,
                'issues_from'          => $tmpIssue->issues_from,
                'name'                 => $tmpIssue->name,
                'issue_categories_id'  => $tmpIssue->issue_categories_id,
                'status'               => $tmpIssue->status ==2? 1: $tmpIssue->status,
                'is_api'               => $tmpIssue->is_api,
                'is_ci'                => $tmpIssue->is_ci,
                'is_ci_cif'            => $tmpIssue->is_ci_cif,
                'created_at'           => now(),
                'updated_at'           => now(),
            ];

            if(!empty($tmpIssue->master_id)){
                $issueData['master_id'] = $tmpIssue->master_id;
            }

            if($tmpIssue->master_id != null && $tmpIssue->action != "Add"){
                DB::table('unit_items')->where('id', $tmpIssue->master_id)->update($issueData);
            }else{
                DB::table('unit_items')->insert($issueData);
                $lastInsertedId = DB::getPdo()->lastInsertId();
                DB::table('unit_items')->where('id', $lastInsertedId)
                ->update(['master_id' => $lastInsertedId]);
            }

            DB::table('unit_items_tmp')->where('id', $tmpIssue->id)->delete();
            DB::commit();
            if($tmpIssue->status == 0){
                flash('Issues has been inActive.', 'danger');
            }elseif($tmpIssue->status == 1){
                flash('Issues has been activated.', 'success');
            }else{
                flash('Issues has been approved.', 'success');
            }
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            flash('An error occurred during the approval process: ' . $e->getMessage(), 'danger');
            return redirect()->back();
        }
    }

    public function issueQueueList(){
        $checker = $this->accessIssueCategoriesChecker();
        if($checker){
            $isChecker = true;
            $issuesTmp = UnitItemTmp::with(['productType:id,name', 'issueCategories:id,name'])
            ->whereNotIn('form_status', [7, -1])
            ->get();
        }else{
            $isChecker = false;
            $issuesTmp = UnitItemTmp::with(['productType:id,name', 'issueCategories:id,name'])
            ->whereIn('form_status', [7, -1])
            ->get();
        }


        return view('UnitItems.index_tmp', compact('issuesTmp', 'isChecker'));
    }

    public function checkerTableDataForIssues($id){
        $tmpData = UnitItemTmp::with(['productType:id,name', 'issueCategories:id,name'])->findOrFail($id);

        if (!empty($tmpData->master_id)) {
            $oldData = UnitItem::with(['productType:id,name', 'issueCategories:id,name'])->findOrFail($tmpData->master_id);
        }

        $tmpData->issue_name = $tmpData->name;
        $tmpData->product_type = $tmpData->productType->name ?? null;
        $tmpData->type = $tmpData->issues_from == "wform" ? "service-request" : $tmpData->issues_from;
        $tmpData->issue_category = $tmpData->issueCategories->name ?? null;
        $tmpData->status = $tmpData->status == 1 ? "Active" : "Inactive";
        $tmpData->api_push = $tmpData->is_api == 1 ? "Yes" : "No";
        $tmpData->ci_issue = $tmpData->is_ci == 1 ? "Yes" : "No";
        $tmpData->ci_cif_api_update = $tmpData->is_ci_cif == 1 ? "Yes" : "No";
        $tmpData->action = $tmpData->action;
        // dd($tmpData);
        // unset($tmpData->department,
        //     $tmpData->department_id,
        //     $tmpData->group_level_id,
        //     $tmpData->is_active
        // );

        if (isset($oldData)) {
            $oldData->issue_name = $oldData->name;
            $oldData->product_type = $oldData->productType->name ?? null;
            $oldData->type = $oldData->issues_from == "wform" ? "service-request" : $oldData->issues_from;
            $oldData->issue_category = $oldData->issueCategories->name ?? null;
            $oldData->status = $oldData->status == 1 ? "Active" : "Inactive";
            $oldData->api_push = $oldData->is_api == 1 ? "Yes" : "No";
            $oldData->ci_issue = $oldData->is_ci == 1 ? "Yes" : "No";
            $oldData->ci_cif_api_update = $oldData->is_ci_cif == 1 ? "Yes" : "No";
            // unset($oldData->dept,
            //     $oldData->department_id,
            //     $oldData->group_level_id,
            //     $oldData->is_active
            // );
        }

        $columnsToSend = ['issue_name', 'product_type', 'type', 'issue_category', 'status', 'api_push', 'ci_issue', 'ci_cif_api_update', 'action'];

        $filteredTmpData = $tmpData->only($columnsToSend);

        $filteredOldData = isset($oldData) ? $oldData->only($columnsToSend) : null;

        $response = [
            'old_data' => $filteredOldData,
            'new_data' => $filteredTmpData
        ];

        return response()->json($response, 200);
    }

    public function issueAssign($id){
        $tmpData = UnitItemTmp::where('id', $id)->first();

        $tmpData->form_status = 1;
        $tmpData->modified_by = Auth::user()->user_id;
        $tmpData->save();

        $data = [
            'message' => 'Assigned Successfully from your End!',
            'id' => $id,
        ];

        return response()->json($data, 200);
    }

    public function issueSendback(Request $request, $id){

        $checker = $this->accessIssueCategoriesChecker();
        if ($checker == false) {
            abort(403, 'You do not have permission to access this page.');
        }

        $request->validate([
            'comments' => 'required'
        ]);

        $tmpData = UnitItemTmp::where('id', $id)->first();
        $tmpData->form_status = 7;
        $tmpData->modified_by = null;
        $tmpData->comments = $request->comments;
        $tmpData->save();

        flash('Send back Successfully!', 'success');
        return redirect()->back();
    }

    public function issueReject(Request $request, $id){
        $checker = $this->accessIssueCategoriesChecker();
        if ($checker == false) {
            abort(403, 'You do not have permission to access this page.');
        }

        $request->validate([
            'comments' => 'required'
        ]);

        $tmpData = UnitItemTmp::where('id', $id)->first();
        $tmpData->form_status = -1;
        $tmpData->modified_by = Auth::user()->user_id;
        $tmpData->comments = $request->comments;
        $tmpData->save();

        flash('Request rejected!!', 'danger');
        return Redirect::back();
    }

    public function buttonControlForIssue($id){
        $tmpData = UnitItemTmp::findOrFail($id);

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

    public function status($id = null, $status = "")
    {
        $unitItemModelName = new UnitItem;
        if ($status != 1 && $status != 0) {
            flash($status.' is not Allowed!!!','danger');
            return Redirect::back();
        }

        $data['status'] = $status;
        $update = $unitItemModelName->where([['id', $id]])->first();

        $update->update($data);

        if ($status == 1) {
            flash('Issues has been Active', 'success');
        } elseif ($status == 0) {
            flash('Issues has been Inactive', 'danger');
        }
        // return redirect('/Issues');
        return Redirect::back();

    }

    public function tmpStatus($id = null, $status = "")
    {
        $checker = $this->accessIssueCategoriesChecker();
        if ($checker) {
            abort(403, 'You do not have permission to access this page.');
        }

        $existingTmpRecords = UnitItemTmp::where('master_id', $id)->get();

        $hasInvalidRecord = $existingTmpRecords->contains(function ($record) {
            return $record->form_status != 7 && $record->form_status != -1;
        });

        if ($hasInvalidRecord) {
            flash('An entry already exists, please wait for Checker Approval', 'danger');
            return Redirect::back();
        }

        $issue = new UnitItem();
        if ($status != 1 && $status != 0) {
            flash($status.' is not Allowed!!!','danger');
            return Redirect::back();
        }

        $update = $issue->where([['id', $id]])->first();

        $issueTmp = new UnitItemTmp();
        if ($status == 0){
            $issueTmp->status = 0;
            $issueTmp->action = "Inactive";
        }else{
            $issueTmp->status = 1;
            $issueTmp->action = "Active";
        }
        $issueTmp->master_id = $update->id;
        $issueTmp->product_type_id  = $update->product_type_id;
        $issueTmp->issues_from  = $update->issues_from;
        $issueTmp->name = $update->name;
        $issueTmp->issue_categories_id  = $update->issue_categories_id;
        $issueTmp->issue_categories_id  = $update->issue_categories_id;
        $issueTmp->is_api = $update->is_api;
        $issueTmp->is_ci = $update->is_ci;
        $issueTmp->is_ci_cif = $update->is_ci_cif;
        $issueTmp->created_by = Auth::user()->id;
        $issueTmp->ip = $this->getClientIp();

        $issueTmp->save();

        if ($status == 1) {
            flash('Issue Active Request Send to Checker', 'success');
        } elseif ($status == 0) {
            flash('Issue Inactive Request Send to Checker', 'danger');
        }
        // return redirect('/Divisions');
        return Redirect::back();
    }

    public function sms_status($id = null, $status = "")
    {
        $unitItemModelName = new UnitItem;
        if ($status != 1 && $status != 0) {
            flash($status.' is not Allowed!!!','danger');
            return Redirect::back();
        }

        $data['is_sent_sms'] = $status;
        $update = $unitItemModelName->where([['id', $id]])->first();

        $update->update($data);

        if ($status == 1) {
            flash('SMS & Email have been Activated', 'success');
        } elseif ($status == 0) {
            flash('SMS & Email have been Inactive', 'danger');
        }
        // return redirect('/Issues');
        return Redirect::back();

    }

    public function config(Request $req, $id)
    {
        $row = UnitItem::find($id);
        $rows = IssueConfig::where('issue_id', $id)->get();
        $pbIssuesCfg = $req->old('addmore'); // If validation failed then curt will not be vanished

        $cif = CIFApi::where('issue_id', $id)
                ->leftJoin('cif_parent_url', 'cif_api.parent_api', 'cif_parent_url.id')
                ->leftJoin('cif_modification_url', 'cif_parent_url.id', 'cif_modification_url.parent_id')
                ->where('cif_api.type', 1)
                ->where('cif_parent_url.status', 1)
                ->where('cif_modification_url.status', 1)
                ->select('cif_modification_url.request')
                ->get();
        $cif = explode(" ",$cif);
        $arr = [];
        foreach($cif as $string){
            if (str_contains($string, 'password')) {
            } else {
                $arr[] = getBetween($string,"#","#");
            }
        }
        $cif = array_filter($arr);

        $issue_config = IssueConfigMapping::where('issue_config_mapping.issue_id',$id)
            ->select('issue_config_mapping.field_name','issue_config_mapping.api_parameter')
            ->where('issue_config_mapping.inquiry_field', 0)
            ->get();
        $inquiry_config = IssueConfigMapping::where('issue_config_mapping.issue_id',$id)
            ->select('issue_config_mapping.field_name','issue_config_mapping.api_parameter')
            ->where('issue_config_mapping.inquiry_field', 1)
            ->get();
        $fieldset_groups = IssueFieldsetGroup::where('issue_id', $id)
            ->select('issue_fieldset_groups.name', 'id')
            ->get();

        return view('UnitItems.config', compact('cif', 'row', 'rows', 'pbIssuesCfg','issue_config','inquiry_config', 'fieldset_groups'));
    }

    public function checkListConfig(Request $req, $id)
    {

        $row = UnitItem::find($id);
        $rows = \App\IssueCheckListConfig::where('issue_id',$id)->get();

        $pbIssuesCfg = $req->old('addmore'); // If validation failed then curt will not be vanished

        return view('UnitItems.check_list_config',['row'=>$row,'rows'=>$rows,'pbIssuesCfg'=>$pbIssuesCfg]);
    }

    public function getCategoryWiseComplaint($product,$category_id)
    {

		$group_info_id = Auth::user()->user_unit->subgroup_info_id;
        $subgroup_id = SubgroupInfo::find($group_info_id);

        $rows = DB::select('SELECT * FROM unit_items
		JOIN issue_workflows ON unit_items.id=issue_workflows.issue_id
		JOIN issue_group_workflows ON issue_workflows.issue_workflow_id=issue_group_workflows.issue_workflow_id
		AND issue_group_workflows.group_info_id='.$subgroup_id->group_info_id.'
		WHERE unit_items.id IN(SELECT issue_id FROM issue_workflows) AND unit_items.status="1"
		AND unit_items.product_type_id = '.$product.' and unit_items.issue_categories_id='.$category_id.' AND issues_from="complaint" order by unit_items.name');

        //return $rows;
        
		/*
		$rows = DB::select('SELECT unit_items.id,unit_items.name FROM unit_items
		JOIN issue_workflows ON unit_items.id=issue_workflows.issue_id where unit_items.product_type_id='.$product.'
		and unit_items.issue_categories_id='.$category_id);
		*/
        //$rows = UnitItem::where('product_type_id',$product)->where('issue_categories_id',$category_id)->get();
        return json_encode($rows);
    }

    public function getCategoryWiseService($product,$category_id)
    {

        $group_info_id = Auth::user()->user_unit->subgroup_info_id;
        $subgroup_id = SubgroupInfo::find($group_info_id);

        $rows = DB::select('SELECT * FROM unit_items
        JOIN issue_workflows ON unit_items.id=issue_workflows.issue_id
        JOIN issue_group_workflows ON issue_workflows.issue_workflow_id=issue_group_workflows.issue_workflow_id
        AND issue_group_workflows.group_info_id='.$subgroup_id->group_info_id.'
        WHERE unit_items.id IN(SELECT issue_id FROM issue_workflows) AND unit_items.status="1"
        AND unit_items.product_type_id = '.$product.' and unit_items.issue_categories_id='.$category_id.' AND issues_from="wform" order by unit_items.name');

        //return $rows;

        /*
        $rows = DB::select('SELECT unit_items.id,unit_items.name FROM unit_items
        JOIN issue_workflows ON unit_items.id=issue_workflows.issue_id where unit_items.product_type_id='.$product.'
        and unit_items.issue_categories_id='.$category_id);
        */
        //$rows = UnitItem::where('product_type_id',$product)->where('issue_categories_id',$category_id)->get();
        return json_encode($rows);
    }

    public function getCatWiseServices($category_id)
    {

        //$group_info_id = Auth::user()->user_unit->subgroup_info_id;
        //$subgroup_id = SubgroupInfo::find($group_info_id);

        $rows = DB::select("SELECT unit_items.id, unit_items.name FROM unit_items
        join issue_categories on unit_items.issue_categories_id = issue_categories.id
        WHERE issue_categories.id= $category_id AND unit_items.status=1 order by unit_items.name");

        return json_encode($rows);
    }

	public function issuesCategory(Request $request)
    {
		$data = array();

		$IssueCategoriesModelName = new IssueCategories;
        // http_build_query($searchDataForView) division
        $dataForView = array();
        $searchDataForView = $request->all();
        $tblData = array();
        $dataObj = $IssueCategoriesModelName
                        ->select("id","product_type_id","issues_from","name","status")
                        ->with([
                                'productType'=>function($e){
                                    $e->select('id','name');
                                }
                            ]);
        $dataObj = $dataObj->orderBy("name","ASC")
                            //->where("status", '=', '1')
                            ->get();
							//->paginate(PAGINATION_NUMBER);

        if (!empty($dataObj)) {
            $tblData = $dataObj->toArray();
        }

		$title = "Issues Category List";
        $title_for_layout = "Issues Category List";
        $checker = $this->accessIssueCategoriesChecker();
        return view('UnitItems.issue_category',compact('title','title_for_layout','tblData','searchDataForView','dataObj','checker'));

    }

	public function addCategory()
    {
        $checker = $this->accessIssueCategoriesChecker();
        if ($checker) {
            abort(403, 'You do not have permission to access this page.');
        }

        $id = 0;
        $tmpId = 0;
        $dataForView = array();


        $title = "Add Issue Category";
        $title_for_layout = "Add Issue Category";

        return view('UnitItems.addcategory',compact('title','title_for_layout','id','dataForView', 'tmpId'));
    }

    public function storeCategory(Request $request)
    {

		// $this->validate($request,[
        //     'name'=>'required',
        //     'issues_from'=>'required',
        //     'product_type_id'=>'required',
        // ]);
        // $existingTmpRecords = IssueCategoriesTmp::where('master_id', $request->id)->get();

        // $hasInvalidRecord = $existingTmpRecords->contains(function ($record) {
        //     return $record->form_status != 7 && $record->form_status != -1;
        // });

        // if ($hasInvalidRecord) {
        //     flash('An entry already exists, please wait for Checker Approval', 'danger');
        //     return Redirect::back();
        // }

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:issue_categories_tmp,name',
            'issues_from' => 'required',
            'product_type_id' => 'required',
        ], [
            'product_type_id.required' => 'The product type field is required.',
        ]);

        $validator->after(function ($validator) use ($request) {
            if (\DB::table('issue_categories')->where('name', $request->name)->exists()) {
                $validator->errors()->add('name', 'The name has already been taken.');
            }

            if (\DB::table('issue_categories_tmp')->where('name', $request->name)->exists()) {
                $validator->errors()->add('name', 'The name has already been taken.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $IssueCategoriesModelName = new IssueCategoriesTmp();
        if ($request->isMethod('post')) {
            $IssueCategoriesModelName->name = $request->name;
            $IssueCategoriesModelName->issues_from = $request->issues_from;
            $IssueCategoriesModelName->product_type_id = $request->product_type_id;
            $IssueCategoriesModelName->status = 1;
            $IssueCategoriesModelName->action = "Add";
            $IssueCategoriesModelName->created_by = Auth::user()->id;

            if ($IssueCategoriesModelName->save()) {
                flash('Issues has been inserted successfully, please wait for Checker Approval', 'success');
                return redirect('Issues-category');
            } else {
                flash('Failed to insert data', 'danger');
                return redirect('Issues-category/addcategory');
            }
        }

    }

    public function editCategory($id = null, Request $req)
    {
        $checker = $this->accessIssueCategoriesChecker();
        if ($checker) {
            abort(403, 'You do not have permission to access this page.');
        }
        $tmpId = 0;
        $IssueCategoriesModelName = new IssueCategories;
        $title = "Edit Issue Category";
        $title_for_layout = 'Edit Issue Category';
        $searchDataForView = $req->all();


        $dataForView = $IssueCategoriesModelName->where
                                            ([
                                                ['id', $id]
                                            ])->first();

        if ($dataForView->status == 0) {
            abort(403,'Edit Not Allowed !!!');
        }
        return view('UnitItems.addcategory', compact('title', 'title_for_layout', 'dataForView','searchDataForView','id', 'tmpId'));
    }

    public function issueCategoryTmpEdit($id = null, Request $req)
    {
        // dd("tmp e ase");
        $checker = $this->accessIssueCategoriesChecker();
        if ($checker) {
            abort(403, 'You do not have permission to access this page.');
        }

        $modelName = new IssueCategoriesTmp;
        $title = "Send Back IssueCategories Edit";
        $title_for_layout = 'Edit IssueCategories';
        $searchDataForView = $req->all();
        $tmpId = $id;

        $dataForView = $modelName->where
                                            ([
                                                ['id', $id]
                                            ])->first();

        return view('UnitItems.addcategory', compact('title', 'title_for_layout', 'dataForView','searchDataForView','id', 'tmpId'));
    }

	public function updateCategory($id = null, Request $request)
    {
        $isTemp = !empty($request->tmpId) && $request->tmpId != 0;

        $tableName = $isTemp ? 'issue_categories_tmp' : 'issue_categories';

        $this->validate($request,[
            'name'=> 'required|unique:'.$tableName.',name,'.$id,
            'issues_from'=>'required',
            'product_type_id'=>'required',
        ]);

        // $IssueCategoriesModelName = new IssueCategories;

        $data = $request->all();
        $existingRecords = $isTemp ? IssueCategoriesTmp::where('id', $id)->get() : IssueCategoriesTmp::where('master_id', $id)->get();
        $hasInvalidRecord = $existingRecords->contains(function ($record) {
            return $record->form_status != 7 && $record->form_status != -1;
        });

        if ($hasInvalidRecord) {
            flash('An entry already exists, please wait for Checker Approval', 'danger');
            return Redirect::back();
        }

        // Check if there's already a record in IssueCategoriesTmp with the same master_id
        // $existingTmpRecords = IssueCategoriesTmp::where('master_id', $id)->get();

        // $hasInvalidRecord = $existingTmpRecords->contains(function ($record) {
        //     return $record->form_status != 7 && $record->form_status != -1;
        // });

        // if ($hasInvalidRecord) {
        //     flash('An entry already exists, please wait for Checker Approval', 'danger');
        //     return Redirect::back();
        // }

        // if ($existingTmpRecord) {
        //     flash('An entry already exists, please wait for Checker Approval', 'danger');
        //     return Redirect::back();
        // }


        $additionalParams = (!empty($data['additionalParams'])) ? $data['additionalParams'] : "";
        unset($data['additionalParams']);

        $data['modified_by'] = Auth::user()->id;
        $data['product_type_id'] = $request->product_type_id;
        // $update = $IssueCategoriesModelName->where([['id', $id]])->first();
        if ($isTemp) {
            $IssueCategoriesModelName = IssueCategoriesTmp::where('id', $id)->first();
        }else{
            $IssueCategoriesModelName = new IssueCategoriesTmp();
            $update = IssueCategories::where('id', $id)->first();
        }

        // $IssueCategoriesModelName = new IssueCategoriesTmp();

        $IssueCategoriesModelName->name = $request->name;
        $IssueCategoriesModelName->issues_from = $request->issues_from;
        $IssueCategoriesModelName->product_type_id = $request->product_type_id;
        // $IssueCategoriesModelName->status = 1;
        $IssueCategoriesModelName->status = 2;
        $IssueCategoriesModelName->form_status = 0;
        $IssueCategoriesModelName->action = "Edit";
        $IssueCategoriesModelName->master_id = $isTemp ? $IssueCategoriesModelName->master_id : $update->id;
        // $IssueCategoriesModelName->master_id = $update->id;
        $IssueCategoriesModelName->created_by = Auth::user()->id;


        if ($IssueCategoriesModelName->save($data)) {
            flash('Issue Category has been updated successfully, please wait for Checker Approval', 'success');
            return redirect('/Issues-category'.$additionalParams);
        } else {
            flash('Failed to update Issue Category, Please try again', 'danger');
        }
    }

	public function statusCategory($id = null, $status = "")
    {
        $checker = $this->accessIssueCategoriesChecker();
        if ($checker) {
            abort(403, 'You do not have permission to access this page.');
        }

        $IssueCategoriesModelName = new IssueCategories;
        if ($status != 1 && $status != 0) {
            flash($status.' is not Allowed!!!','danger');
            return Redirect::back();
        }

        // Check if there's already a record in IssueCategoriesTmp with the same master_id
        $existingTmpRecord = IssueCategoriesTmp::where('master_id', $id)->first();

        if ($existingTmpRecord) {
            flash('An entry already exists, please wait for Checker Approval', 'danger');
            return Redirect::back();
        }

        $data['status'] = $status;
        $update = $IssueCategoriesModelName->where([['id', $id]])->first();

        $IssueCategoriesModelName = new IssueCategoriesTmp();
        if ($status == 0){
           $IssueCategoriesModelName->status = 0;
           $IssueCategoriesModelName->action = "Inactive";
       }else{
           $IssueCategoriesModelName->status = 1;
           $IssueCategoriesModelName->action = "Active";
       }
       $IssueCategoriesModelName->name = $update->name;
       $IssueCategoriesModelName->issues_from = $update->issues_from;
       $IssueCategoriesModelName->product_type_id = $update->product_type_id;
       $IssueCategoriesModelName->master_id = $update->id;
       $IssueCategoriesModelName->created_by = Auth::user()->id;

        $IssueCategoriesModelName->save($data);

        if ($status == 1) {
            flash('Issue Category Active Request Send to Checker', 'success');
        } elseif ($status == 0) {
            flash('Issue Category Inactive Request Send to Checker', 'danger');
        }
        // return redirect('/Groups');
        return Redirect::back();

    }



    //Approved Checker


    public function approveIssueCategory($id)
    {
        $checker = $this->accessIssueCategoriesChecker();
        if ($checker == false) {
            abort(403, 'You do not have permission to access this page.');
        }

        DB::beginTransaction();

        try {
            $tmpIssueCategory = DB::table('issue_categories_tmp')->where('id', $id)->where('status', '!=', 3)->first();
            // dd($tmpIssueCategory);
            // $tmpDepartment = DB::table('divisions_tmp')->where('id', $id)->where('status', 0)->first();

            if (!$tmpIssueCategory) {
                flash('Issue Category not found or not approved yet.', 'danger');
                return redirect()->back();
            }

            $issueCategoryData = [

                'name'        => $tmpIssueCategory->name,
                'issues_from' => $tmpIssueCategory->issues_from,
                'product_type_id' => $tmpIssueCategory->product_type_id,
                'status' =>  $tmpIssueCategory->status ==2? 1: $tmpIssueCategory->status,
                // 'modified_by' => Auth::user()->id,
            ];
            // dd($IssueCategoryData);
            if($tmpIssueCategory->master_id != null && $tmpIssueCategory->action != "Add"){
                DB::table('issue_categories')->where('id', $tmpIssueCategory->master_id)->update($issueCategoryData);
            }else{
                DB::table('issue_categories')->insert($issueCategoryData);
            }

            DB::table('issue_categories_tmp')->where('id', $tmpIssueCategory->id)->delete();
            DB::commit();
            if($tmpIssueCategory->status == 0){
                flash('Issue Category has been inActive.', 'danger');
            }elseif($tmpIssueCategory->status == 1){
                flash('Issue Category has been activated.', 'success');
            }else{
                flash('Issue Category has been approved.', 'success');
            }
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            flash('An error occurred during the approval process: ' . $e->getMessage(), 'danger');
            return redirect()->back();
        }
    }


    public function categoryTmpList()
    {
        $checker = $this->accessIssueCategoriesChecker();
        if ($checker) {
            $isChecker = true;
            $tmpData = IssueCategoriesTmp::with('productType')->whereNotIn('form_status', [7, -1])->get();
        }else{
            $isChecker = false;
            $tmpData = IssueCategoriesTmp::with('productType')->whereIn('form_status', [7, -1])->get();
        }

        return view('UnitItems.issue_category_tmp',compact('tmpData','isChecker'));
    }

    public function categoryAssign($id)
    {
        $tmpData = IssueCategoriesTmp::where('id', $id)->first();

        $tmpData->form_status = 1;
        $tmpData->modified_by = Auth::user()->user_id;
        $tmpData->save();

        $data = [
            'message' => 'Assigned Successfully from your End!',
            'id' => $id,
        ];

        return response()->json($data, 200);
    }

    public function categorySendback(Request $request, $id)
    {
        $checker = $this->accessIssueCategoriesChecker();
        if ($checker == false) {
            abort(403, 'You do not have permission to access this page.');
        }

        $request->validate([
            'comments' => 'required'
        ]);
        $tmpData = IssueCategoriesTmp::where('id', $id)->first();
        $tmpData->form_status = 7;
        $tmpData->modified_by = null;
        $tmpData->comments = $request->comments;
        $tmpData->save();

        flash('Send back Successfully!', 'success');
        return redirect()->back();
    }

    public function categoryReject(Request $request, $id)
    {
        $checker = $this->accessIssueCategoriesChecker();
        if ($checker == false) {
            abort(403, 'You do not have permission to access this page.');
        }

        $request->validate([
            'comments' => 'required'
        ]);
        $tmpData = IssueCategoriesTmp::where('id', $id)->first();
        $tmpData->form_status = -1;
        $tmpData->modified_by = Auth::user()->user_id;
        $tmpData->comments = $request->comments;
        $tmpData->save();

        flash('Rejected !!!', 'danger');
        return redirect()->back();
    }

    public function buttonControl($id){
        $tmpData = IssueCategoriesTmp::findOrFail($id);

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
        $tmpData = IssueCategoriesTmp::with('productType')->findOrFail($id);

        if (!empty($tmpData->master_id)) {
            $oldData = IssueCategories::with('productType')->findOrFail($tmpData->master_id);
        }

        $tmpData->name = $tmpData->name ?? null;
        $tmpData->issues_from = $tmpData->issues_from == "wform" ? "Service Request" : $tmpData->issues_from;
        $tmpData->productType_name = $tmpData->productType->name ?? 'N/A';
        $tmpData->status = $tmpData->status == 1 ? "Active" : "Inactive";

        if (isset($oldData)) {
            $oldData->name = $oldData->name ?? null;
            $oldData->issues_from = $oldData->issues_from == "wform" ? "Service Request" : "N/A";
            $oldData->productType_name = $oldData->productType->name ?? 'N/A';
            $oldData->status = $oldData->status == 1 ? "Active" : "Inactive";
        }

        $columnsToSend = ['name','productType_name','issues_from','status'];

        $filteredTmpData = $tmpData->only($columnsToSend);

        $filteredOldData = isset($oldData) ? $oldData->only($columnsToSend) : null;

        $response = [
            'old_data' => $filteredOldData,
            'new_data' => $filteredTmpData
        ];

        return response()->json($response, 200);
    }




	public function getIssueWiseCategory($issues_from, $product_type)
    {
		//dd($issues_from);
        $rows = IssueCategories::where('issues_from',$issues_from)
        ->where('product_type_id', $product_type)
		->orderBy("name","ASC")
		->get();
        return json_encode($rows);
    }

    public function issueConditionalField(Request $request, $id)
    {
        $issue = UnitItem::find($id);
        $issue_config_conditional = IssueConfig::where('issue_id', $id)
            ->where('field_type', 'dropdown')
            ->orderBy("id", "ASC")
            ->get();
        $issue_config_dependant = IssueConfig::where('issue_id', $id)
            ->orderBy("id", "ASC")
            // ->whereNot('field_type', 'dropdown')
            ->get();
        $exist = IssueConditionalField::where('issue_id', $id)
            ->orderBy("id", "ASC")
            ->get();

        return view('UnitItems.issue_conditional_field', compact('issue', 'issue_config_conditional', 'issue_config_dependant', 'exist'));
    }

    public function issueConditionalFieldStore(Request $request)
    {
        if (!empty($request->issue_id)) {
            // prd($request->issue_id);
            if (!empty($request->new)) {
                // prd($request->new);
                IssueConditionalField::where('issue_id', $request->issue_id)->delete();
                foreach ($request->new as $n) {
                    IssueConditionalField::create([
                        'issue_id' => $request->issue_id,
                        'conditional_field' => $n['conditional_field'],
                        'value' => $n['value'],
                        'dependant_field' => $n['dependant_field'],
                    ]);
                }
                flash('Issue Conditional Field Configuration Successfully Completed.', 'success');
            } else {
                IssueConditionalField::where('issue_id', $request->issue_id)->delete();
                flash('Issue Conditional Fields Configuration Cleared Successfully.', 'success');
            }
        } else {
            flash('Failed to configure issue conditional fields', 'danger');
        }
        return redirect()->back();
    }

    public function issueConditionalFieldValue($id)
    {
        $data = IssueConfig::where('id', $id)
            ->select('options')
            ->first();
        return response()->json($data);
    }

    public function issueConditionalFieldOptions($id)
    {
        $data = IssueConditionalField::where('conditional_field', $id)
            ->select('value')
            ->first();
        return response()->json($data);
    }

    public function issueDependantFields(Request $request, $issue_id, $value, $id)
    {
        $fields = IssueConditionalField::where('issue_conditional_fields.issue_id', $issue_id)
            ->join('issue_config', 'issue_conditional_fields.dependant_field', 'issue_config.id')
            ->select('issue_config.field_name')
            ->where('issue_conditional_fields.conditional_field', $id)
            ->where('issue_conditional_fields.value', $value)
            ->get()
            ->toArray();
        return response()->json($fields);
    }

    public function issueConditional(Request $request, $issue_id)
    {
        $fields = IssueConditionalField::where('issue_conditional_fields.issue_id', $issue_id)
            ->join('issue_config', 'issue_conditional_fields.dependant_field', 'issue_config.id')
            ->select('issue_config.field_name')
            ->get()
            ->toArray();
        return response()->json($fields);
    }

    public function issueConditionalFields(Request $request, $issue_id, $id)
    {
        $fields = IssueConditionalField::where('issue_conditional_fields.issue_id', $issue_id)
            ->join('issue_config', 'issue_conditional_fields.dependant_field', 'issue_config.id')
            ->select('issue_config.field_name')
            ->where('issue_conditional_fields.conditional_field', $id)
            ->get()
            ->toArray();
        return response()->json($fields);
    }

}
