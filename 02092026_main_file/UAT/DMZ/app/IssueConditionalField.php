<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IssueConditionalField extends Model
{
    protected $fillable = [
        'issue_id',
        'conditional_field',
        'value',
        'dependant_field',
    ];

    
}
