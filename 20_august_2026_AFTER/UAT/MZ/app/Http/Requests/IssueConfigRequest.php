<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IssueConfigRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [];
        if (!empty($this->get('addmore'))) {
            foreach($this->get('addmore') as $key => $val) {
                $rules['addmore.'.$key.'.label_name'] = 'required';
                $rules['addmore.' . $key . '.field_name'] = [
                    'required',
                    'regex:/^[a-z_]+$/'
                ];
            }
        } else {
            // $rules['atleast_config'] = 'required';
        }
        return $rules;
    }

    public function messages()
    {
        if (!empty($this->get('addmore'))) {
            foreach($this->get('addmore') as $key => $val) {
                $messages['addmore.'.$key.'.label_name.required'] = 'Label Name is required';
                $messages['addmore.'.$key.'.field_name.required'] = 'Field Name is required';
                $messages['addmore.'.$key.'.field_name.regex'] = 'Field Name may contain only lowercase letters and underscore';
            }
        } else {
            $messages['atleast_config.required'] = 'Please add at least one field';
        }
        return $messages;
    }
}
