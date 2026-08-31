<?php


namespace App\Services\CI;

use App\IssueConfig;
use App\issueFieldsetGroup;

class FieldSetGroupService
{
    public static function getFieldSet($issue_id = '')
    {
        $service_request = $issue_id;
        $rows = IssueConfig::where('issue_id',$service_request)->with('fieldsetGroup')->get();

        $fieldSet = issueFieldsetGroup::where('issue_id', $service_request)->get();
        $fields = [];
        $group = $rows->groupBy('fieldset_group_id');

        foreach ($group as $key => $value) {
            $single['fieldset_title'] = '';
            $single['fieldset_id'] = '';
            if ($key) {
                $fieldSet = issueFieldsetGroup::find($key);
                $single['fieldset_title'] = $fieldSet->name;
                $single['fieldset_id'] = $fieldSet->group_id_name;
            }
            $single['fields'] = $value;
            $fields[] = $single;
        }

        return $fields;
    }
}
