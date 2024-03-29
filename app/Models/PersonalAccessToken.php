<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    protected $fillable = [
        'name',
        'token',
        'abilities',
        'plain_text_token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'tokenable_id');
    }
}
