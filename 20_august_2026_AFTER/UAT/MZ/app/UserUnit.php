<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class UserUnit extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['user_id','unit_id','department_id','division_id'];
    public $timestamps = false;

    public function subgroup()
    {
        return $this->belongsTo(SubgroupInfo::class, 'subgroup_info_id');
    }

    public function group()
    {
        return $this->belongsTo(GroupInfo::class, 'group_info_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
