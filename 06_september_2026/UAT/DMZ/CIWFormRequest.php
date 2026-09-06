<?php

namespace App\Http\Requests;

use Auth;
use App\IssueConfig;
use App\IssueCheckListConfig;
use App\Rules\AlphaNumericOnly;
use App\Rules\SpecialCharacterFilter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class CIWFormRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        if ($this->has('treasury_type') && !is_null($this->treasury_type)) {
            $this->merge([
                'treasury_type' => strtolower(trim($this->treasury_type)),
            ]);
        }
        $this->clearIrrelevantTreasuryFields();
    }

    protected function clearIrrelevantTreasuryFields()
    {
        $fieldMap = [
            'bill'  => 'treasury_bills',
            'sukuk' => 'treasury_sukuk',
            'bond'  => 'treasury_bonds',
            'frtb'  => 'treasury_frtb',
        ];

        $treasuryType = strtolower(trim((string) $this->input('treasury_type')));

        if (!array_key_exists($treasuryType, $fieldMap)) {
            return;
        }

        $keepField = $fieldMap[$treasuryType];

        $nullify = [];
        foreach ($fieldMap as $type => $field) {
            if ($field !== $keepField) {
                $nullify[$field] = null;
            }
        }

        if (!empty($nullify)) {
            $this->merge($nullify);
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
        $rules = array();
        $rules['w_form_type'] = 'required';
        $rules['account_number'] = 'required|max:18';
        $rules['product_type'] = 'required';

        $isUpdateRequest = $this->isMethod('PUT')
            || $this->isMethod('PATCH')
            || ($this->has('_method') && in_array(strtoupper($this->input('_method')), ['PUT', 'PATCH']))
            || Str::contains(strtolower($this->path()), 'update');

        if ($this->file_name) {
            foreach ($this->file_name as $index => $image) {
                if ($isUpdateRequest) {
                    $rules["file_name.$index.file"] = 'nullable|mimes:jpeg,png,jpg,pdf,heif,heic|max:3072';
                } else {
                    if (isset($image['is_required']) && $image['is_required'] == 1) {
                        $rules["file_name.$index.file"] = 'required|mimes:jpeg,png,jpg,pdf,heif,heic|max:3072';
                    } else {
                        $rules["file_name.$index.file"] = 'nullable|mimes:jpeg,png,jpg,pdf,heif,heic|max:3072';
                    }
                }
            }
        }

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

        if (!empty($this->w_form_type)) {
            $w_form_type = $this->w_form_type;
            $issue_config_obj = IssueConfig::where('issue_id', $w_form_type)->get();

            if ($issue_config_obj->isNotEmpty()) {
                foreach ($issue_config_obj as $key => $value) {
                    $tmp_rules = "";
                    $field_name = $value->field_name;
                    $maximum_length = $value->maximum_length;
                    $minimum_length = $value->minimum_length;
                    $fixed_length = $value->fixed_length;
                    $field_type = $value->field_type;
                    $is_required = $value->is_required;

                    $allFieldNames = array_keys($this->all());
                    $pattern = '/##CI##/';
                    $fieldsNotMatchingPattern = array_filter($allFieldNames, function ($fieldName) use ($pattern) {
                        return preg_match($pattern, $fieldName);
                    });
                    foreach ($fieldsNotMatchingPattern as $k => $v) {
                        $fieldName = str_replace('##CI##', '', $v);
                        if ($field_name == $fieldName) {
                            $is_required = 0;
                        }
                    }

                    if ($is_required == 1) {
                        if ($field_type == 'file' && $isUpdateRequest) {
                            $tmp_rules .= '|nullable';
                        } else {
                            $tmp_rules .= '|required';
                        }
                    } else {
                        $tmp_rules .= '|nullable';
                    }
                    if (!empty($maximum_length) && $field_type !== 'number') {
                        $tmp_rules .= '|max:' . $maximum_length;
                    }
                    if (!empty($minimum_length) && $field_type !== 'number') {
                        $tmp_rules .= '|min:' . $minimum_length;
                    }
                    if (!empty($maximum_length) && $field_type == 'number') {
                        $tmp_rules .= '|max_digits:' . $maximum_length;
                    }
                    if (!empty($minimum_length) && $field_type == 'number') {
                        $tmp_rules .= '|min_digits:' . $minimum_length;
                    }
                    if (!empty($fixed_length)) {
                        $tmp_rules .= '|fixed_len:' . $fixed_length;
                    }
                    if (!empty($field_type) && $field_type == 'file') {
                        $tmp_rules .= '|mimes:jpeg,png,jpg,pdf,heif,heic|max:3072';
                    }
                    if (!empty($field_type) && $field_type == 'number') {
                        $tmp_rules .= '|nullable|numeric';
                    }
                    if (!empty($field_type) && $field_type == 'decimal') {
                        $tmp_rules .= '|float_twodigit';
                    }
                    if (!empty($field_type) && $field_type == 'date') {
                        $tmp_rules .= '|date';
                    }

                    $tmp_rules = ltrim($tmp_rules, '|');

                    if (!empty($field_type)) {
                        $ruleArray = explode('|', $tmp_rules);

                        if ($field_type == 'text' || $field_type == 'address') {
                            $rules[$field_name] = array_merge($ruleArray, [new SpecialCharacterFilter()]);
                        } elseif ($field_type == 'alphanumeric') {
                            $rules[$field_name] = array_merge($ruleArray, [new AlphaNumericOnly()]);
                        } else {
                            $rules[$field_name] = $ruleArray;
                        }

                        $rules[$field_name] = array_filter($rules[$field_name]);
                    }
                }
            }

            if (!empty($this->minor_flg_nominee)) {
                if ($this->minor_flg_nominee == 'Y') {
                    $rules['guardianName'] = 'required';
                    $rules['guardina_code_s'] = 'required';
                    $rules['guardianAddr1'] = 'required';
                    $rules['guardianCity'] = 'required';
                    $rules['guardianStateProv'] = 'required';
                    $rules['guardianPostalCode'] = 'required';
                    $rules['guardianCountry'] = 'required';
                    $rules['nominee_dob'] = 'after:' . now()->subYears(18)->toDateString();
                } else {
                    if (!empty($this->nominee_dob)) {
                        $rules['nominee_dob'] = 'before:' . now()->subYears(18)->toDateString();
                    }
                }
            }
        }

        // Treasury type + amount validation
        if (!empty($this->treasury_type)) {
            $rules['treasury_type'] = 'required|in:bill,sukuk,bond,frtb';
            $rules['bidding_amount'] = 'required|numeric|treasury_amount_multiple';
        }

        return $rules;
    }

    public function messages()
    {
        $messages = array();
        $messages['w_form_type.required']       = 'Required';
        $messages['account_number.required']    = 'Required';
        $messages['product_type.required']      = 'Required';
        $messages['time_and_ext.required']      = 'Required';

        if ($this->file_name) {
            foreach ($this->file_name as $index => $image) {

                if ($image['is_required'] == 1) {
                    $messages["file_name.$index.file.required"] = "Image is required.";
                    $messages["file_name.$index.file.mimes"] = "This file is not supported; The supported formats are JPG, JPEG, PNG, PDF, HEIF, HEIC";
                    $messages["file_name.$index.file.max"] = "Max uploaded file size is 3 MB";
                } else {
                    $messages["file_name.$index.file.mimes"] = "This file is not supported; The supported formats are JPG, JPEG, PNG, PDF, HEIF, HEIC";
                    $messages["file_name.$index.file.max"] = "Max uploaded file size is 3 MB";
                };
            }
        }


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
                    $messages[$field_name . '.required'] = 'Required';
                }
                if (!empty($maximum_length)) {
                    $messages[$field_name . '.max'] = 'Max Length ' . $maximum_length;
                }
                if (!empty($minimum_length)) {
                    $messages[$field_name . '.min'] = 'Min Length ' . $minimum_length;
                }
                if (!empty($fixed_length)) {
                    $messages[$field_name . '.fixed_len'] = 'Input length should be ' . $fixed_length;
                }
                $messages[$field_name . '.float_twodigit'] = 'Must be two digit after decimal number is required.';
            }

            $issue_checklist_config_obj = IssueCheckListConfig::where('issue_id', $w_form_type)->get();
            foreach ($issue_checklist_config_obj as $key => $value) {
                $tmp_rules = "";
                $field_name = $value->field_name;
                $maximum_length = $value->maximum_length;
                $is_required = $value->is_required;
                if ($is_required == 1) {
                    $messages[$field_name . '.required'] = 'Required';
                }
                if (!empty($maximum_length) && $is_required == 1) {
                    $tmp_rules .= '|max:' . $maximum_length;
                    $messages[$field_name . '.max'] = 'Max Length ' . $maximum_length;
                } else {
                    $tmp_rules .= 'max:' . $maximum_length;
                    $messages[$field_name . '.max'] = 'Max Length ' . $maximum_length;
                }
            }

            /* Check for Nominee Information Minor*/
            if (!empty($this->minor_flg_nominee)) {
                if ($this->minor_flg_nominee == 'Y') {
                    $messages['guardianName.required'] = 'Required';
                    $messages['guardina_code_s.required'] = 'Required';
                    $messages['guardianAddr1.required'] = 'Required';
                    $messages['guardianCity.required'] = 'Required';
                    $messages['guardianStateProv.required'] = 'Required';
                    $messages['guardianPostalCode.required'] = 'Required';
                    $messages['guardianCountry.required'] = 'Required';
                    $messages['nominee_dob.after'] = 'Please check Nominee Minor Flag / Nominee Birth Date';
                } else {
                    $messages['nominee_dob.before'] = 'Please check Nominee Minor Flag / Nominee Birth Date';
                }
            }
        }

        return $messages;
    }
}
