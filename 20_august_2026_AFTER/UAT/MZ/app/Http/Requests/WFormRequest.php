<?php

namespace App\Http\Requests;

use Auth;
use App\Setting;
use App\IssueConfig;
use App\IssueCheckListConfig;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;

class WFormRequest extends FormRequest
{
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

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    
    public function rules()
    {
	
        $requestDt = $this->request;

        //Current year
        Validator::extend('current_year', function ($attribute, $value, $parameters, $validator) {
            $year = date('Y', strtotime($value));
            $currentYear = date('Y');
            return $year == $currentYear;
        });

        //next year
        Validator::extend('next_year', function ($attribute, $value, $parameters, $validator) {
            $year = date('Y', strtotime($value));
            $nextYear = date('Y') + 1;
            return $year == $nextYear;
        });


	// Treasury amount multiple check (sukuk => 10,000 | others => 100,000)
        Validator::extend('treasury_amount_multiple', function ($attribute, $value, $parameters, $validator) use ($requestDt) {
            $treasuryType = $requestDt->get('treasury_type');
            $step = ($treasuryType === 'sukuk') ? 10000 : 100000;

            if (!is_numeric($value) || (int) $value < $step) {
                return false;
            }

            $value = (int) $value;
	    $step = (int) $step;
	    
            return $value % $step === 0;
        });

        Validator::replacer('treasury_amount_multiple', function ($message, $attribute, $rule, $parameters) use ($requestDt) {
            $treasuryType = $requestDt->get('treasury_type');
            $step = ($treasuryType === 'sukuk') ? 10000 : 100000;
            return "Amount must be a multiple of " . number_format($step) . ".";
        });

        $rules = array();
        $rules['w_form_type'] = 'required';
        //$rules['customer_name'] = 'required|max:200';
        //$rules['account_number'] = 'required|max:18';
        $rules['product_type'] = 'required';
        $rules['time_and_ext'] = 'required';
		//$rules['mobile_number'] = 'required';
        if (Auth::user()->user_unit->subgroup_info_id == 3) {
            $rules['caller_id'] = 'required|regex:/^[0-9]+$/|min:1|max:50';
        }
        //$rules['dynamic_question'] = 'required';
        // $rules['address'] = 'required';
        // prd($this->category);

        if (!empty($this->w_form_type)) {
            $w_form_type = $this->w_form_type;
            $issue_config_obj = IssueConfig::where('issue_id', $w_form_type)->get();

            if (!empty($issue_config_obj)) {
                foreach ($issue_config_obj as $key => $value) {
                    $tmp_rules = "";
                    $field_name = $value->field_name;
                    $maximum_length = $value->maximum_length;
                    $minimum_length = $value->minimum_length;
                    $fixed_length = $value->fixed_length;
                    $is_required = $value->is_required;
                    $field_type = $value->field_type;
                    $apiKey = $value->api_key;
                    $fieldSetId = $value->fieldset_group_id;

                    $allFieldNames = array_keys($this->all());
                    $pattern = '/##CI##/';
                    $fieldsNotMatchingPattern = array_filter($allFieldNames, function ($fieldName) use ($pattern) {
                        return preg_match($pattern, $fieldName);
                    });
                    foreach ($fieldsNotMatchingPattern as $key => $value) {
                        $fieldName = str_replace('##CI##', '', $value);
                        if ($field_name == $fieldName) {
                            $is_required = 0;
                        }
                    }

                    if (!empty($this->get('currentYear'))) {
                        $CY = $this->get('currentYear');
                        $keysC = explode(':', $apiKey);
                        if ($fieldSetId == 24){
                            if($CY['request_type'] == 'ADD'){
                                $tmp_rules .= ($keysC[0] == 'limitStartDate' || $keysC[0] == 'limitEndDate') ? '|date|current_year' : '';
                                $is_required = ($keysC[0] == 'unUsagePercentage') ? 0 : $is_required;
                            }else{
                                if($keysC[0] == 'limitUsagePercentage' || $keysC[0] == 'limitStartDate' || $keysC[0] == 'limitEndDate'){
                                    $is_required = 0;
                                }
                            }
                        }
                    }

                    if (!empty($this->get('nextYear'))) {
                        $NX = $this->get('nextYear');
                        $keysN = explode(':', $apiKey);
                        if ($fieldSetId == 25){
                            if($NX['request_type'] == 'ADD'){
                                $tmp_rules .= ($keysN[0] == 'limitStartDate' || $keysN[0] == 'limitEndDate') ? '|date|next_year' : '';
                                $is_required = ($keysN[0] == 'unUsagePercentage') ? 0 : $is_required;
                            }else{
                                if($keysN[0] == 'limitUsagePercentage' || $keysN[0] == 'limitStartDate' || $keysN[0] == 'limitEndDate'){
                                    $is_required = 0;
                                }
                            }
                        }
                    }

                    if (!empty($this->get('medicalQuota'))) {
                        $MQ = $this->get('medicalQuota');
                        $keysM = explode(':', $apiKey);
                        if ($fieldSetId == 28){
                            if($MQ['request_type'] == 'ADD'){
                                $tmp_rules .= ($keysM[0] == 'limitStartDate' || $keysM[0] == 'limitEndDate') ? '|date|current_year' : '';
                                $is_required = ($keysM[0] == 'unUsagePercentage') ? 0 : $is_required;
                            }else{
                                if($keysM[0] == 'limitUsagePercentage' || $keysM[0] == 'limitStartDate' || $keysM[0] == 'limitEndDate'){
                                    $is_required = 0;
                                }
                            }
                        }
                    }

                    if ($is_required == 1) {
                        $tmp_rules .= '|required';
                    }
                    if (!empty($maximum_length) && $field_type !== 'number') {
                        $tmp_rules .= '|max:'.$maximum_length;
                    }
                    if (!empty($minimum_length) && $field_type !== 'number') {
                        $tmp_rules .= '|min:'.$minimum_length;
                    }
                    if (!empty($maximum_length) && $field_type == 'number') {
                        $tmp_rules .= '|max_digits:'.$maximum_length;
                    }
                    if (!empty($minimum_length) && $field_type == 'number') {
                        $tmp_rules .= '|min_digits:'.$minimum_length;
                    }
                    if (!empty($fixed_length)) {
                        $tmp_rules .= '|fixed_len:'.$fixed_length;
                    }
                    if (!empty($field_type) && $field_type == 'number') {
                        $tmp_rules .= '|nullable|numeric';
                    }
                    if (!empty($field_type) && $field_type == 'decimal') {
                        // $tmp_rules .= '|nullable|numeric|regex:/^\d{1,9}+(\.\d{2})?$/';
                        $tmp_rules .= '|nullable|float_twodigit';
                    }
                    if (!empty($field_type) && $field_type == 'date') {
                        $tmp_rules .= '|nullable|date';
                    }
                    if (!empty($field_type) && $field_type == 'ndate') {
                        $tmp_rules .= '|nullable|date|after_or_equal:today';
                    }
                    // Quota field validation
                   if (!empty($this->get('passport'))) {
                        foreach($this->get('passport') as $key => $val) {
                            if ($field_name === $key){
                                $rules['passport.'.$key] = ltrim($tmp_rules, '|');
                            }
                        }
                    }

                    // current year field validation
                    if (!empty($this->get('currentYear'))) {
                        foreach($this->get('currentYear') as $key => $val) {
                            if ($field_name === $key){
                                $rules['currentYear.'.$key] = ltrim($tmp_rules, '|');
                            }
                        }
                    }

                    // next year field validation
                    if (!empty($this->get('nextYear'))) {
                        foreach($this->get('nextYear') as $key => $val) {
                            if ($field_name === $key){
                                $rules['nextYear.'.$key] = ltrim($tmp_rules, '|');
                            }
                        }
                    }

                    // medical Quota field validation
                    if (!empty($this->get('medicalQuota'))) {
                        foreach($this->get('medicalQuota') as $key => $val) {
                            if ($field_name === $key){
                                $rules['medicalQuota.'.$key] = ltrim($tmp_rules, '|');
                            }
                        }
                    }

                    $tmp_rules = ltrim($tmp_rules, '|');

                    // Local TQ Issue ID 1103 UAT 1153 & LIVE null
                    // Local MQ Issue ID 1105 UAT null & LIVE null
                    if ($value->issue_id !== 1103 && $value->issue_id !== 1105){
                        $rules[$field_name] = $tmp_rules;
                    }
                }
            }
            $issue_checklist_config_obj = IssueCheckListConfig::where('issue_id', $w_form_type)->get();
            if (!empty($issue_checklist_config_obj)) {
                foreach ($issue_checklist_config_obj as $key => $value) {
                    $tmp_rules = "";
                    $field_name = $value->field_name;
                    $maximum_length = $value->maximum_length;
                    $is_required = $value->is_required;
                    if ($is_required == 1) {
                        $tmp_rules = 'required';
                    }
                    if (!empty($maximum_length) && $is_required == 1) {
                        $tmp_rules .= '|max:'.$maximum_length;
                    } elseif(!empty($maximum_length) && empty($is_required)) {
                        $tmp_rules .= 'max:'.$maximum_length;
                    }
                    $rules[$field_name] = $tmp_rules;
                }
            }

            /* Check for Nominee Information Minor*/
            if (!empty($this->minor_flg_nominee)) {
                if ($this->minor_flg_nominee == 'Y') {
                    $rules['guardianName'] ='required';
                    $rules['guardina_code_s'] ='required';
                    $rules['guardianAddr1'] ='required';
                    $rules['guardianCity'] ='required';
                    $rules['guardianStateProv'] ='required';
                    $rules['guardianPostalCode'] ='required';
                    $rules['guardianCountry'] ='required';
                    $rules['nominee_dob'] = 'after:'.now()->subYears(18)->toDateString();
                } else {
                    if (!empty($this->nominee_dob)) {
                        $rules['nominee_dob'] = 'before:'.now()->subYears(18)->toDateString();
                    }
                }
            }

        }

        if (!empty($this->file_name)) {
            $rules['file_name.*']  = 'required|mimes_except:exe,bat,cmd|max:' . $this->fileSizeLimit;
        }

	// Treasury type + amount validation
        if (!empty($this->treasury_type)) {
            $rules['treasury_type'] = 'required|in:Bill,sukuk,Bond,frtb';
            $rules['bidding_amount'] = 'required|numeric|treasury_amount_multiple';
        }

        return $rules;
    }

    public function messages()
    {
        $messages = array();
        $messages['w_form_type.required']       = 'Required';
        //$messages['customer_name.required'] = 'Required';
        //$messages['account_number.required']    = 'Required';
        $messages['product_type.required']      = 'Required';
        //$messages['item_type.required']         = 'Required';
        $messages['time_and_ext.required']      = 'Required';
        //$messages['caller_id.required']         = 'Required';
        //$messages['dynamic_question.required']  = 'Required';
        //$messages['address.required']           = 'Required';

        if (!empty($this->w_form_type)) {
            $w_form_type = $this->w_form_type;
            $issue_config_obj = IssueConfig::where('issue_id', $w_form_type)->get();
            foreach ($issue_config_obj as $key => $value) {
                $field_name = $value->field_name;
                $maximum_length = $value->maximum_length;
                $minimum_length = $value->minimum_length;
                $fixed_length = $value->fixed_length;
                $is_required = $value->is_required;
                $field_type = $value->field_type;

                $allFieldNames = array_keys($this->all());
                $pattern = '/##CICF##/';
                $fieldsNotMatchingPattern = array_filter($allFieldNames, function ($fieldName) use ($pattern) {
                    return preg_match($pattern, $fieldName);
                });
                foreach ($fieldsNotMatchingPattern as $key => $value) {
                    $fieldName = str_replace('##CICF##', '', $value);
                    if ($field_name == $fieldName) {
                        $is_required = 0;
                    }
                }
                if ($is_required == 1) {
                    $messages[$field_name.'.required'] = 'Required';
                }
                if (!empty($maximum_length)) {
                    $messages[$field_name.'.max'] = 'Max Length '.$maximum_length;
                }
                if (!empty($minimum_length)) {
                    $messages[$field_name.'.min'] = 'Min Length '.$minimum_length;
                }
                if (!empty($fixed_length)) {
                    $messages[$field_name.'.fixed_len'] = 'Input length should be '.$fixed_length;
                }
                $messages[$field_name.'.float_twodigit'] = 'Must be two digit after decimal number is required.';

                // For Quota field validation
                if (!empty($this->get('passport'))) {
                    foreach($this->get('passport') as $key => $val) {
                        if ($field_name === $key){
                            if ($is_required == 1) {
                                $messages['passport.'.$key.'.required'] = 'Required';
                            }
                            if (!empty($maximum_length)) {
                                $messages['passport.'.$key.'.max'] = 'Max Length '.$maximum_length;
                            }
                            if (!empty($minimum_length)) {
                                $messages['passport.'.$key.'.min'] = 'Min Length '.$minimum_length;
                            }
                            if (!empty($fixed_length)) {
                                $messages['passport.'.$key.'.fixed_len'] = 'Input length should be '.$fixed_length;
                            }
                            $messages['passport.'.$key.'.float_twodigit'] = 'Must be two digit after decimal number is required.';
                            $messages['passport.'.$key.'.after_or_equal'] = 'Must be greater than today date';
                        }
                    }
                }

                if (!empty($this->get('nextYear'))) {
                    foreach($this->get('nextYear') as $key => $val) {
                        if ($field_name === $key){
                            if ($is_required == 1) {
                                $messages['nextYear.'.$key.'.required'] = 'Required';
                            }
                            if (!empty($maximum_length)) {
                                $messages['nextYear.'.$key.'.max'] = 'Max Length '.$maximum_length;
                            }
                            if (!empty($minimum_length)) {
                                $messages['nextYear.'.$key.'.min'] = 'Min Length '.$minimum_length;
                            }
                            if (!empty($fixed_length)) {
                                $messages['nextYear.'.$key.'.fixed_len'] = 'Input length should be '.$fixed_length;
                            }
                            $messages['nextYear.'.$key.'.float_twodigit'] = 'Must be two digit after decimal number is required.';
                            $messages['nextYear.'.$key.'.next_year'] = 'Date Should be Next Year';
                            $messages['nextYear.'.$key.'.after_or_equal'] = 'Must be greater than today date';
                        }
                    }
                }

                if (!empty($this->get('currentYear'))) {
                    foreach($this->get('currentYear') as $key => $val) {
                        if ($field_name === $key){
                            if ($is_required == 1) {
                                $messages['currentYear.'.$key.'.required'] = 'Required';
                            }
                            if (!empty($maximum_length)) {
                                $messages['currentYear.'.$key.'.max'] = 'Max Length '.$maximum_length;
                            }
                            if (!empty($minimum_length)) {
                                $messages['currentYear.'.$key.'.min'] = 'Min Length '.$minimum_length;
                            }
                            if (!empty($fixed_length)) {
                                $messages['currentYear.'.$key.'.fixed_len'] = 'Input length should be '.$fixed_length;
                            }
                            $messages['currentYear.'.$key.'.float_twodigit'] = 'Must be two digit after decimal number is required.';
                            $messages['currentYear.'.$key.'.current_year'] = 'Date Should be Current Year';
                            $messages['currentYear.'.$key.'.after_or_equal'] = 'Must be greater than today date';
                        }
                    }
                }

                if (!empty($this->get('medicalQuota'))) {
                    foreach($this->get('medicalQuota') as $key => $val) {
                        if ($field_name === $key){
                            if ($is_required == 1) {
                                $messages['medicalQuota.'.$key.'.required'] = 'Required';
                            }
                            if (!empty($maximum_length)) {
                                $messages['medicalQuota.'.$key.'.max'] = 'Max Length '.$maximum_length;
                            }
                            if (!empty($minimum_length)) {
                                $messages['medicalQuota.'.$key.'.min'] = 'Min Length '.$minimum_length;
                            }
                            if (!empty($fixed_length)) {
                                $messages['medicalQuota.'.$key.'.fixed_len'] = 'Input length should be '.$fixed_length;
                            }
                            $messages['medicalQuota.'.$key.'.float_twodigit'] = 'Must be two digit after decimal number is required.';
                            $messages['medicalQuota.'.$key.'.current_year'] = 'Date Should be Current Year';
                            $messages['medicalQuota.'.$key.'.after_or_equal'] = 'Must be greater than today date';
                        }
                    }
                }
            }

            $issue_checklist_config_obj = IssueCheckListConfig::where('issue_id',$w_form_type)->get();
            foreach ($issue_checklist_config_obj as $key => $value) {
                $tmp_rules = "";
                $field_name = $value->field_name;
                $maximum_length = $value->maximum_length;
                $is_required = $value->is_required;
                if ($is_required == 1) {
                    $messages[$field_name.'.required'] = 'Required';
                }
                if (!empty($maximum_length) && $is_required == 1) {
                    $tmp_rules .= '|max:'.$maximum_length;
                    $messages[$field_name.'.max'] = 'Max Length '.$maximum_length;
                } else {
                    $tmp_rules .= 'max:'.$maximum_length;
                    $messages[$field_name.'.max'] = 'Max Length '.$maximum_length;
                }
            }

            /* Check for Nominee Information Minor*/
            if (!empty($this->minor_flg_nominee)) {
                if ($this->minor_flg_nominee == 'Y') {
                    $messages['guardianName.required'] ='Required';
                    $messages['guardina_code_s.required'] ='Required';
                    $messages['guardianAddr1.required'] ='Required';
                    $messages['guardianCity.required'] ='Required';
                    $messages['guardianStateProv.required'] ='Required';
                    $messages['guardianPostalCode.required'] ='Required';
                    $messages['guardianCountry.required'] ='Required';
                    $messages['nominee_dob.after'] = 'Please check Nominee Minor Flag / Nominee Birth Date';
                } else {
                    $messages['nominee_dob.before'] = 'Please check Nominee Minor Flag / Nominee Birth Date';
                }
            }

        }


        if (!empty($this->file_name)) {
            foreach($this->file_name as $key => $val) {
                $messages['file_name.'.$key.'.required']  = 'File Required';
                $messages['file_name.'.$key.'.mimes_except']  = 'Uploaded file is not supported.';
                $messages['file_name.'.$key.'.max']  = 'Max uploaded file size is ' . ($this->fileSizeLimit / 1024) . ' MB.';
            }
        }
        return $messages;
    }
}
