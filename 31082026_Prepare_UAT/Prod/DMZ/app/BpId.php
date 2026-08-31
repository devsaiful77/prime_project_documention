<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BpId extends Model
{
	protected $table = 'bp_ids';
    protected $fillable = ['bp_id', 'account_number', 'reference_number'];
}
