<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTenant extends Model
{
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];
}
