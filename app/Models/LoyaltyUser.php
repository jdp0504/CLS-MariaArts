<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyUser extends Model
{
    protected $table = 'User';
    protected $primaryKey = 'userID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'userID',
        'username',
        'role',
        'createdDate',
        'password',
    ];

    protected $hidden = [
        'password',
    ];
}
