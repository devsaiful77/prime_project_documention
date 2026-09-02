<?php
/**
 * User:Tanay Kumar Roy
 * Email:tanayroy12@gmail.com
 * Created by Tanay Kumar Roy<tanayroy12@gmail.com> on 3/30/2020.
 */

namespace App;


use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Setting extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table='settings';
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
            'file_size_limit',
    ];
}
