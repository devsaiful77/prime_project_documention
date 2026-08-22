<?php

namespace App\Http\Controllers\CI;

use App\Http\Controllers\Controller;
use App\IssueConditionalField;
use App\IssueConfig;
use App\UnitItem;
use Illuminate\Http\Request;

class ConditionalFieldController extends Controller
{

    public function issueConditional(Request $request, $issue_id)
    {
        $fields = IssueConditionalField::where('issue_conditional_fields.issue_id', $issue_id)
            ->join('issue_config', 'issue_conditional_fields.dependant_field', 'issue_config.id')
            ->select('issue_config.field_name')
            ->get()
            ->toArray();
        return response()->json($fields);
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
