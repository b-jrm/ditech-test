<?php

namespace App\Models;


class Token
{
    protected $table = "personal_access_tokens";
    protected $primaryKey = 'id';

    protected $fillable = [
        'tokenable_type',
        'tokenable_id',
        'name',
        'token',
        'abilities',
        'last_used_at',
        'expires_at',
    ];

    public $timestamps = true;
}
