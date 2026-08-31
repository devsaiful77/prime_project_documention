<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssueGroupMember extends Model
{
    use HasFactory;
    protected $table = 'issue_group_members';
    protected $fillable = [
        'issue_group_id',
        'subgroup_info_id',
        'user_id',
        'unit_id',
        'sequence',
        'is_touch_point',
        'ordering'
    ];
    public $timestamps = false;

    public function subgroup()
    {
        return $this->belongsTo(SubgroupInfo::class, 'subgroup_info_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
