<?php

namespace App\Http\Requests;

use App\Setting;
use Illuminate\Foundation\Http\FormRequest;

class AttachmentUploadRequest extends FormRequest
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

    public function rules()
    {
        $rules = array();
        
        if (empty($this->file_name)) {
            $rules['file_name.0']  = 'required';
        } else {
            $rules['file_name.*']  = 'required|mimes:csv,doc,docm,docx,eml,gif,ico,jpe,jpeg,jpg,pdf,png,ppt,pptm,txt,word,xl,xls,xlsx,mp4,mp3,avi,mkv,msg,pptx,amr|max:' . $this->fileSizeLimit;
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
                $messages['file_name.'.$key.'.max']  = 'Max uploaded file size is ' . ($this->fileSizeLimit / 1024) . ' MB.';
            }
        } else {
            $messages['file_name.0.required']  = 'File Required';
        }
        
        return $messages;
    }
}
