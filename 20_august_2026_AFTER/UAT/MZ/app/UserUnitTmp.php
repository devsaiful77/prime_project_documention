<?php

namespace App;

use App\UserTmp;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class UserUnitTmp extends Model
{
    // use \OwenIt\Auditing\Auditable;
    protected $table = 'user_units_tmp';
    protected $guarded = [];
    protected $casts = [
        'unit_id' => 'string',
    ];
    // protected $fillable = ['user_id', 'unit_id', 'subgroup_info_id', 'group_info_id', 'is_group_info_head', 'is_department_head', 'is_division_head', 'is_email_allow', 'type', 'department_id', 'division_id', 'is_unit_head', 'created_at', 'updated_at'];
    public $timestamps = false;

    public function user_tmp()
    {
        return $this->belongsTo(UserTmp::class, 'user_id', 'user_id');
    }
}
