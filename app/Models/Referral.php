<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    protected $table = 'Referral';
    protected $primaryKey = 'referralID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'referralID', 'customerID', 'pointGranted', 'dateRef',
    ];
}
