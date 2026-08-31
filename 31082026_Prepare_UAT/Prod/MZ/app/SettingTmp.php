<?php
/**
 * User:Tanay Kumar Roy
 * Email:tanayroy12@gmail.com
 * Created by Tanay Kumar Roy<tanayroy12@gmail.com> on 3/30/2020.
 */

namespace App;


use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SettingTmp extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table='settings_tmp';
    protected $primaryKey = 'setting_id';
    protected $fillable = [
            'session_lifetime',
            'ci_session_time',
            'ci_otp_sms_email',
            'term_condition',
            'term_condition_url',
            'password_change_time',
            'allow_ip_restriction',
            'sla_blink',
            'sla_email_time',
            'forward_time',
	    'noncustomersms',
	    'action',
	    'form_status',
	    'master_id',
	    'created_by',
	    'modified_by',
            'file_size_limit',
	    'comments'
    ];
}
