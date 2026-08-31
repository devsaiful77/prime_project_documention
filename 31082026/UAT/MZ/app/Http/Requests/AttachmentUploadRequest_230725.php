<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttachmentUploadRequest extends FormRequest
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
        
        if (empty($this->file_name)) {
            $rules['file_name.0']  = 'required';
        } else {
            $rules['file_name.*']  = 'required|mimes:csv,doc,docm,docx,eml,gif,ico,jpe,jpeg,jpg,pdf,png,ppt,pptm,txt,word,xl,xls,xlsx,mp4,mp3,avi,mkv,msg,pptx,amr|max:10240';
        }
        
        return $rules;
    }

    public function messages()
    {
        $messages = array();

        if (!empty($this->file_name)) {
            foreach($this->file_name as $key => $val) {
                $messages['file_name.'.$key.'.required']  = 'File Required';
                $messages['file_name.'.$key.'.mimes_except']  = 'Uploaded file is not supported.';
                $messages['file_name.'.$key.'.max']  = 'Max uploaded file size is 10 MB';
            }
        } else {
            $messages['file_name.0.required']  = 'File Required';
        }
        
        return $messages;
    }
}
