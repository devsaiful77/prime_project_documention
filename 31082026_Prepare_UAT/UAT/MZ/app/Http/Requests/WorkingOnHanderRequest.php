<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkingOnHanderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = array();
        $rules['comments'] = 'required';

        if (!empty($this->submit)) {
            if ($this->submit === "resolve") {
                $rules['com_summary'] = 'required';
                $rules['com_root_cause'] = 'required';
                $rules['action_taken'] = 'required';
                $rules['action_date'] = 'required';
            }
            if ($this->submit == "forward") {
                $rules['unit_id'] = 'required';
                $rules['group_id'] = 'required';
            }

            if ($this->has('bpid')) {
                $rules['bpid'] = 'nullable|max:9';
            }

            if (!empty($this->is_subflow_available) && ($this->submit == "approved")) {
                $rules['subflow_type_group_id'] = 'required';
            }
        }
        return $rules;
    }

    public function messages()
    {
        $messages = array();
        $messages['comments.required'] = 'Please Type Comment';
        $messages['com_summary.required'] = 'Please Type Complaint Summary';
        $messages['com_root_cause.required'] = 'Please Type Complaint Root Cause';
        $messages['action_taken.required'] = 'Please Type Action Taken';
        $messages['action_date.required'] = 'Please Type Action Date';
        $messages['unit_id.required'] = 'Please Select Unit';
        $messages['group_id.required'] = 'Please Select Forward Group';
        $messages['subflow_type_group_id.required'] = 'Subflow is Required';
        $messages['bpid.required'] = 'BP ID is required';
        return $messages;
    }
}
