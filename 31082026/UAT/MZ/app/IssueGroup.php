<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IssueGroup extends Model
{
    protected $table = 'issue_groups';
    protected $fillable = ['unit_item_id'];

    public function issueName()
    {
        return $this->belongsTo(UnitItem::class, 'unit_item_id', 'id');
    }
}
