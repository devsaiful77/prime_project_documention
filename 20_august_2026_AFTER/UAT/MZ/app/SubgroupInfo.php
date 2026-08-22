<?php


namespace App;


use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SubgroupInfo extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'subgroup_info';
    protected $primaryKey = 'id';
    protected $fillable = [
        'group_info_id',
        'department_id',
        'name',
        'description',
        'is_active'
    ];
    public function dept()
    {
        return $this->belongsTo('App\Department', 'department_id');
    }
    public function groupInfo()
    {
        return $this->belongsTo('App\GroupInfo', 'group_info_id');
    }

    public function userUnits()
    {
        return $this->hasMany(\App\UserUnit::class, 'subgroup_info_id')
            ->where(function ($q) {
                $q->whereRaw("FIND_IN_SET(1, unit_id)")
                    ->orWhereRaw("FIND_IN_SET(2, unit_id)");
            });
    }

    public function issueGroupMembers()
    {
        return $this->hasMany(IssueGroupMember::class, 'subgroup_info_id');
    }
}
