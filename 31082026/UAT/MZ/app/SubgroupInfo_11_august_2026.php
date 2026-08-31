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
}
