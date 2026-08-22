<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OSBAPIResponse extends Model
{
	protected $table = 'osb_api_responses';
    
    protected $fillable = [
        'account_number'
        ,'cif_number'
        ,'type'
        ,'url'
        ,'json_node'
        ,'log_user'
        ,'status_code'
        ,'status_msg'
    ];
}
