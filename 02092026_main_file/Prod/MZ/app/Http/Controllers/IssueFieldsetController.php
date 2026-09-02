<?php

namespace App\Http\Controllers;

use App\Http\Requests\IssueAttachmentConfigRequest;
use App\IssueAttachmentConfig;
use App\IssueConfig;
use App\issueFieldsetGroup;
use App\UnitItem;
use Illuminate\Http\Request;

class IssueFieldsetController extends Controller
{
    public function fieldsetGroup(Request $req, $id)
    {
        $issue = UnitItem::find($id);
        $fieldsetGroup = IssueFieldsetGroup::where('issue_id', $id)->get();
        $pbfieldsetGroup = $req->old('addmore');
        return view('UnitItems.fieldset_group', compact('fieldsetGroup', 'issue', 'pbfieldsetGroup'));
    }

    public function fieldsetGroupStore(Request $request)
    {
        // return $request->all();
        if (!empty($request->issue_id)) {
            $fields = IssueFieldsetGroup::where('issue_id', $request->issue_id);
            if (!empty($request->addmore)) {
                foreach ($request->addmore as $key => $r) {
                    if (array_key_exists("group_name_old", $r)) {
                        foreach ($r["group_name_old"] as $key => $name) {
                            if (!empty($name)) {
                                $oldFgroup = IssueFieldsetGroup::find($key);
                                $oldFgroup->name = $name;


                                // update group_id if exists
                                if (isset($r["group_id_old"][$key])) {
                                    $oldFgroup->group_id_name = $r["group_id_old"][$key];
                                }

                                $oldFgroup->update();
                            }
                        }
                    } else {
                        // return $r["group_id"];
                        if (!empty($r['group_name'])) {

                            $group_id_name = '';
                            if (isset($r["group_id"][$key])) {
                                $group_id_name = $r["group_id"];
                            }
                            IssueFieldsetGroup::create([
                                'issue_id' => $request->issue_id,
                                'name' => $r['group_name'],
                                'group_id_name' => $group_id_name,
                            ]);
                        }
                    }
                }
            }
            flash('Fieldset Group have been configured successfully.', 'success');
        } else {
            flash('Failed to configure Fieldset Group.', 'danger');
        }
        return redirect()->back();
    }

    public function fieldsetGroupDelete($id = null)
    {
        $data = issueFieldsetGroup::find($id);
        if (!empty($data)) {
            $data->delete();
            $issue = IssueConfig::where('fieldset_group_id', $id);
            $issue->update(['fieldset_group_id' => null]);
            flash('Fieldset Group has been deleted', 'danger');
            return redirect()->back();
        }
        return redirect()->back();
    }

    public function issueAttachmentConfig(Request $req, $id)
    {
        $issue = UnitItem::find($id);
        $attachmentConfig = IssueAttachmentConfig::where('issue_id', $id)->get();
        $pbfieldsetGroup = $req->old('addmore');

        return view('UnitItems.attachment_config', compact('attachmentConfig', 'issue', 'pbfieldsetGroup'));
    }

    public function issueAttachment(Request $request)
    {
        $issueAttachment = IssueAttachmentConfig::where('issue_id', $request->issue_id)
            ->orderBy('order_by', "ASC")
            ->get();
        return view('partials.issue_attachment_item', ['attachment_item' => $issueAttachment, 'type' => $request->type]);
    }
    public function issueAttachmentStore(IssueAttachmentConfigRequest $request)
    {
        if (!empty($request->issue_id)) {
            IssueAttachmentConfig::where('issue_id', $request->issue_id)->delete();
            if (!empty($request->addmore)) {
                foreach ($request->addmore as $key => $r) {
                    IssueAttachmentConfig::create([
                        'issue_id' => $request->issue_id,
                        'name' => $r['name'],
                        'is_required' => $r['is_required'],
                        'order_by' => $r['order_by'],
                    ]);
                }
            }
            flash('Attachment have been configured successfully.', 'success');
        } else {
            flash('Failed to configure Attachment Group.', 'danger');
        }
        return redirect()->back();
    }
}
