<?php

namespace App\Http\Requests;

use Auth;
use App\Setting;
use App\IssueConfig;
use App\IssueCheckListConfig;
use Illuminate\Foundation\Http\FormRequest;

class ComplaintClosingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    protected $fileSizeLimit;

    public function __construct()
    {
        parent::__construct();

        $settings = Setting::first();
        if (!empty($settings) && !empty($settings->file_size_limit)) {
            $this->fileSizeLimit = (int) $settings->file_size_limit;
        } else {
            $this->fileSizeLimit = 10240; // Default to 10 MB in KB
        }
    }
    
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = array();
        
        $rules['complaint_category'] = 'required';
        $rules['complaint_type'] = 'required';
        $rules['subgroup_id'] = 'required';
        $rules['rootcause'] = 'required';
        $rules['actiontaken'] = 'required';
        $rules['justification'] = 'required';
        $rules['closenotification'] = 'required';
        $rules['natureofcomp'] = 'required';
        $rules['fi_id'] = 'required';
        $rules['file_name.*'] = 'nullable|file|max:' . $this->fileSizeLimit;

        return $rules;
    }

    public function messages()
    {
        $messages = array();

        $messages['complaint_category.required'] = 'Required';
        $messages['complaint_type.required'] = 'Required';
        $messages['subgroup_id.required'] = 'Required';
        $messages['rootcause.required'] = 'Required';
        $messages['actiontaken.required'] = 'Required';
        $messages['justification.required'] = 'Required';
        $messages['closenotification.required'] = 'Required';
        $messages['natureofcomp.required'] = 'Required';
        $messages['fi_id.required'] = 'Required';
        
        if (!empty($this->file_name)) {
            foreach($this->file_name as $key => $val) {
                $messages['file_name.'.$key.'.max']  = 'Max uploaded file size is ' . ($this->fileSizeLimit / 1024) . ' MB.';
            }
        }

        return $messages;
    }
}
