<?php

namespace App\Http\Controllers;

use App\BpId;
use App\CIFApi;
use App\Treasury;
use App\UnitItem;
use App\WFormType;
use App\IssueConfig;
use App\TreasuryBond;
use App\ApiCommonConfig;
use App\ComplaintFormType;
use App\IssueConfigMapping;
use Illuminate\Http\Request;
use App\IssueCheckListConfig;
use Illuminate\Support\Facades\DB;
use App\Services\FieldSetGroupService;
use App\Http\Requests\IssueConfigRequest;

class IssueConfigController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth');
        parent::__construct();
        //$this->middleware('role:superadmin|admin')->except('issueFormField','issueCheckList','editIssueFormField','editIssueCheckList','store');
        //$this->middleware(['role:superadmin|admin'])->except('issueFormField', 'issueCheckList', 'editIssueFormField', 'editIssueCheckList', 'store', 'issueAttachment', 'CIissueAttachment', 'getBpIdWithTreasury');
    }

    public function store(IssueConfigRequest $request)
    {
        //return $request->all();

        if (!empty($request->issue_id)) {
            IssueConfigMapping::where('issue_id', $request->issue_id)->delete();
            if (!empty($request->addmore)) {
                $idArray = [];
                foreach ($request->addmore as $key => $r) {
                    if (!empty($r['id']) && !empty($exist = IssueConfig::where('id', $r['id'])->first())) {
                        $idArray[] = $r["id"];
                        $exist->label_name = $r['label_name'];
                        $exist->field_type = $r['field_type'];
                        $exist->field_name = $r['field_name'];
                        $exist->api_key = $r['api_key'];
                        $exist->options = $r['options'];
                        $exist->placeholder = $r['placeholder'];
                        $exist->maximum_length = $r['maximum_length'];
                        $exist->minimum_length = $r['minimum_length'];
                        $exist->fixed_length = $r['fixed_length'];
                        $exist->is_required = $r['is_required'];
                        $exist->is_readonly = $r['is_readonly'];
                        $exist->fieldset_group_id = $r['group_id'];
                        $exist->save();
                    } else {
                        $new_issue = IssueConfig::create([
                            'issue_id' => $request->issue_id,
                            'label_name' => $r['label_name'],
                            'field_type' => $r['field_type'],
                            'field_name' => $r['field_name'],
                            'api_key' => $r['api_key'],
                            'options' => $r['options'],
                            'placeholder' => $r['placeholder'],
                            'maximum_length' => $r['maximum_length'],
                            'minimum_length' => $r['minimum_length'],
                            'fixed_length' => $r['fixed_length'],
                            'is_required' => $r['is_required'],
                            'is_readonly' => $r['is_readonly'],
                            'fieldset_group_id' => $r['group_id'],
                        ]);
                        $idArray[] = $new_issue->id;
                    }
                    if (!empty($r['api'])) {
                        foreach ($r['api'] as $ke => $va) {
                            IssueConfigMapping::create([
                                'issue_id' => $request->issue_id,
                                'field_name' => $r['field_name'],
                                'api_parameter' => $va,
                                'inquiry_field' => 0,
                            ]);
                        }
                    }
                }
                IssueConfig::where('issue_id', $request->issue_id)->whereNotIn('id', $idArray)->delete();
            }
            flash('Issue have been configured successfully.', 'success');
        } else {
            flash('Failed to configure issue.', 'danger');
        }
        return redirect()->back();
        //return redirect('Issues');
    }

    public function issueFormField(Request $request)
    {
        $fields = FieldSetGroupService::getFieldSet($request->issue_id);
        $rows = IssueConfig::where('issue_id', $request->issue_id)->with('fieldsetGroup')->get();


        $iris_fields = $rows->groupBy('fieldset_group_id')->toArray();

        // Local TQ Issue ID 1103 UAT 1153 & LIVE null
        // Local MQ Issue ID 1105 UAT null & LIVE null
        if ($request->issue_id == "1103") {
            return view('partials.quota_fields', ['iris_fields' => $iris_fields, 'acc_number' => $request->acc_number, 'account_number' => $request->account_number, 'issue_id' => $request->issue_id]);
        } elseif ($request->issue_id == "1105") {
            return view('partials.m_quota_fields', ['iris_fields' => $iris_fields, 'acc_number' => $request->acc_number, 'account_number' => $request->account_number, 'issue_id' => $request->issue_id]);
        }
        return view('partials.extra_form_field_with_group', ['issue_fields' => $fields, 'issue_id' => $request->issue_id]);
    }

    public function issueCheckList(Request $request)
    {
        //dd($request);
        $service_request = $request->issue_id;
        $rows = IssueCheckListConfig::where('issue_id', $service_request)->get();

        return view('partials.issue_check_list', ['check_lists' => $rows]);
    }

    public function editIssueFormField(Request $request)
    {
         //dd($request->all());
        $exits_data = "";
        $service_request = $request->issue_id;
        $rows = IssueConfig::where('issue_id', $service_request)->get();
        if ($service_request == "1103" || $service_request == "1105") {
            $iris_fields = $rows->groupBy('fieldset_group_id')->toArray();
            $exits_data = WFormType::where('reference_number', $request->reference_number)->first();
            $exits_data = (array)json_decode($exits_data->extra_field, true);
            return view('partials.quota_fields_edit', ['iris_fields' => $iris_fields, 'exits_data' => $exits_data, 'acc_number' => $request->acc_number, 'account_number' => $request->account_number]);
        }
        if ($request->form_type == "wform") {
            $exits_data = WFormType::where('reference_number', $request->reference_number)->first();
        } else {
            $exits_data = ComplaintFormType::where('reference_number', $request->reference_number)->first();
        }

        return view('partials.edit_extra_form_field', ['issue_fields' => $rows, 'exits_data' => $exits_data]);
    }

    public function editIssueCheckList(Request $request)
    {
        //dd($request);
        $exits_data = "";
        $service_request = $request->issue_id;
        $rows = IssueCheckListConfig::where('issue_id', $service_request)->get();
        if ($request->form_type == "wform") {
            $exits_data = WFormType::where('reference_number', $request->reference_number)->first();
        } else {
            $exits_data = ComplaintFormType::where('reference_number', $request->reference_number)->first();
        }
        return view('partials.edit_issue_check_list', ['check_lists' => $rows, 'exits_data' => $exits_data]);
    }

    public function commonConfig(Request $req, $id)
    {
        $issue = UnitItem::find($id);

        $cif = CIFApi::where('issue_id', $id)
            ->leftJoin('cif_parent_url', 'cif_api.parent_api', 'cif_parent_url.id')
            ->leftJoin('cif_modification_url', 'cif_parent_url.id', 'cif_modification_url.parent_id')
            ->where('cif_api.type', 1)
            ->where('cif_parent_url.status', 1)
            ->where('cif_modification_url.status', 1)
            ->select('cif_modification_url.request')
            ->get();
        $cif = explode(" ", $cif);
        $arr = [];
        foreach ($cif as $string) {
            if (str_contains($string, 'password')) {
                //do nothing
            } elseif (preg_match('#[~](.+?)[~]#', $string)) {
                $arr[] = '~' . getBetween($string, "~", "~") . '~';
                $arr = array_filter($arr);
            } elseif (preg_match('~[|](.+?)[|]~', $string)) {
                $arr[] = '|' . getBetween($string, "|", "|") . '|';
                $arr = array_filter($arr);
            }
        }
        $cif = $arr;

        $exist = ApiCommonConfig::where('issue_id', $id)->where('type', 1)->get();
        $exist_inq = ApiCommonConfig::where('issue_id', $id)->where('type', 2)->get();

        $active_tab = !empty($req->active_tab) ? $req->active_tab : 'api_update';

        return view('UnitItems.common_config', compact('cif', 'issue', 'exist', 'active_tab', 'exist_inq'));
    }

    public function commonConfigStore(Request $request)
    {
        //dd($request->all());
        if (!empty($request->issue_id) && !empty($request->active_tab)) {
            if ($request->active_tab == 'api_update') {
                $active_tab = 'api_update';
                ApiCommonConfig::where('issue_id', $request->issue_id)->where('type', 1)->delete();
                if (!empty($request->new)) {
                    foreach ($request->new as $n) {
                        ApiCommonConfig::create([
                            'issue_id' => $request->issue_id,
                            'api_parameter' => $n['api_parameter'],
                            'value' => $n['value'],
                            'type' => 1,
                        ]);
                    }
                }
            } elseif ($request->active_tab == 'inquiry_api') {
                $active_tab = 'inquiry_api';
                ApiCommonConfig::where('issue_id', $request->issue_id)->where('type', 2)->delete();
                if (!empty($request->new2)) {
                    foreach ($request->new2 as $n) {
                        ApiCommonConfig::create([
                            'issue_id' => $request->issue_id,
                            'api_parameter' => $n['api_parameter'],
                            'value' => $n['value'],
                            'type' => 2,
                        ]);
                    }
                }
            }
            flash('API Common Config have been successful.', 'success');
            return redirect()->back();
        } else {
            flash('Failed to configure issue.', 'danger');
            return redirect()->back();
        }
    }

    public function inquiryConfig(Request $req, $id)
    {
        $issue = UnitItem::find($id);
        $tblData = DB::table('api_common_config_inquiry')
            ->join('cif_modification_url', 'api_common_config_inquiry.cif_modification_url_id', 'cif_modification_url.id')
            ->select('cif_modification_url.name as url', 'api_common_config_inquiry.*', DB::raw("CASE WHEN api_common_config_inquiry.status = 1 THEN 'Active' WHEN api_common_config_inquiry.status = 0 THEN 'Inactive' ELSE 'Invalid' END AS status_name"))
            ->where('api_common_config_inquiry.issue_id', $id)
            ->where('api_common_config_inquiry.parent_id', 0)
            ->get();
        return view('UnitItems.commonConfig.inquiry_config', compact('issue', 'id', 'tblData'));
    }

    public function inquiryConfigAdd(Request $req, $id)
    {
        $dataForView = array();
        $title = "Add Inquiry Node";
        $title_for_layout = "Add Inquiry Node";
        $issue = UnitItem::find($id);
        $parent = DB::table('api_common_config_inquiry')
            ->where('issue_id', $id)
            ->where('parent_id', 0)
            ->select('id', 'node_idx', 'search_idx', 'node_value')
            ->get();
        $inquiry = CIFApi::where('cif_api.issue_id', $id)
            ->leftJoin('cif_parent_url', 'cif_api.parent_api', 'cif_parent_url.id')
            ->leftJoin('cif_modification_url', 'cif_parent_url.id', 'cif_modification_url.parent_id')
            ->where('cif_api.type', 2)
            ->where('cif_parent_url.status', 1)
            ->where('cif_modification_url.status', 1)
            ->select('cif_modification_url.id', 'cif_modification_url.name')
            ->get();
        $id = null;

        return view('UnitItems.commonConfig.create', compact('parent', 'id', 'inquiry', 'issue', 'dataForView', 'title', 'title_for_layout'));
    }

    public function inquiryConfigstore(Request $request)
    {
        $this->validate($request, [
            'cif_modification_url_id'   =>  'required',
            'node_idx'      =>  'required',
            'node_value'    =>  'required',
        ]);
        if ($request->isMethod('post')) {
            DB::table('api_common_config_inquiry')->insert([
                'parent_id'      => (!empty($request->parent_id)) ? $request->parent_id : 0,
                'issue_id'       => (!empty($request->issue_id)) ? $request->issue_id : 0,
                'cif_modification_url_id'     => (!empty($request->cif_modification_url_id)) ? $request->cif_modification_url_id : 0,
                'search_idx'     => (!empty($request->search_idx)) ? $request->search_idx : '',
                'node_idx'       => (!empty($request->node_idx)) ? $request->node_idx : '',
                'node_value'     => (!empty($request->node_value)) ? $request->node_value : '',
            ]);
            flash('Inquiry Api node has been inserted successfully', 'success');
        }
        return redirect('issues/inquiry/config/' . $request->issue_id);
    }

    public function inquiryConfigEdit(Request $req, $id)
    {
        $dataForView = array();
        $title = "Edit Inquiry Node";
        $title_for_layout = "Edit Inquiry Node";

        $data = DB::table('api_common_config_inquiry')->where('id', $id)->first();

        $issue = UnitItem::find($data->issue_id);
        $inquiry = CIFApi::where('cif_api.issue_id', $data->issue_id)
            ->leftJoin('cif_parent_url', 'cif_api.parent_api', 'cif_parent_url.id')
            ->leftJoin('cif_modification_url', 'cif_parent_url.id', 'cif_modification_url.parent_id')
            ->where('cif_api.type', 2)
            ->where('cif_parent_url.status', 1)
            ->where('cif_modification_url.status', 1)
            ->select('cif_modification_url.id', 'cif_modification_url.name')
            ->get();
        $parent = DB::table('api_common_config_inquiry')
            ->where('issue_id', $data->issue_id)
            ->where('parent_id', 0)
            ->select('node_idx', 'id', 'search_idx', 'node_value')
            ->get();

        return view('UnitItems.commonConfig.edit', compact(
            'data',
            'parent',
            'id',
            'inquiry',
            'issue',
            'dataForView',
            'title',
            'title_for_layout'
        ));
    }

    public function inquiryConfigUpdate(Request $request, $id)
    {
        $this->validate($request, [
            'cif_modification_url_id'   =>  'required',
            'node_idx'      =>  'required',
            'node_value'    =>  'required',
        ]);
        if ($request->isMethod('post')) {
            $data = DB::table('api_common_config_inquiry')->where('id', $id)->first();
            DB::table('api_common_config_inquiry')->where('id', $id)
                ->update([
                    'parent_id'      => (!empty($request->parent_id)) ? $request->parent_id : 0,
                    'cif_modification_url_id' => (!empty($request->cif_modification_url_id)) ? $request->cif_modification_url_id : 0,
                    'search_idx'     => (!empty($request->search_idx)) ? $request->search_idx : '',
                    'node_idx'       => (!empty($request->node_idx)) ? $request->node_idx : '',
                    'node_value'     => (!empty($request->node_value)) ? $request->node_value : '',
                ]);
            flash('Inquiry Api node has been updated successfully', 'success');
        }
        return redirect('issues/inquiry/config/' . $data->issue_id);
    }

    public function inquiryConfigStatus(Request $request, $id, $status)
    {
        $data = DB::table('api_common_config_inquiry')->where('id', $id)->first();
        if (!empty($data)) {
            if ($status == 1) {
                DB::table('api_common_config_inquiry')->where('id', $id)->update(['status' => 1]);
                flash('Inquiry Api node has been activated successfully', 'success');
            } else {
                DB::table('api_common_config_inquiry')->where('id', $id)->update(['status' => 0]);
                flash('Inquiry Api node has been inactivated', 'warning');
            }
        } else {
            flash('Inquiry Api node not found', 'warning');
        }
        return redirect()->back();
    }

    public function inquiryConfigChild(Request $request, $id)
    {
        $parent = DB::table('api_common_config_inquiry')
            ->join('cif_modification_url', 'api_common_config_inquiry.cif_modification_url_id', 'cif_modification_url.id')
            ->select('cif_modification_url.name as url', 'api_common_config_inquiry.*')
            ->where('api_common_config_inquiry.id', $id)
            ->first();
        $childs = DB::table('api_common_config_inquiry')->where('parent_id', $id)->get();
        return view('UnitItems.commonConfig.set_child', compact('parent', 'childs'));
    }

    public function inquiryConfigChildStore(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            DB::table('api_common_config_inquiry')->where('parent_id', $id)->delete();
            $parent = DB::table('api_common_config_inquiry')->where('id', $id)->first();
            if (!empty($request->node)) {
                foreach ($request->node as $n) {
                    DB::table('api_common_config_inquiry')->insert([
                        'parent_id' => $parent->id,
                        'issue_id' => $parent->issue_id,
                        'cif_modification_url_id' => $parent->cif_modification_url_id,
                        'node_idx' => $n['index'],
                        'node_value' => $n['label'],
                    ]);
                }
            }
            flash('Inquiry Api node has been updated successfully', 'success');
        }
        return redirect()->action('IssueConfigController@inquiryConfig', ['id' => $parent->issue_id]);
    }
}
