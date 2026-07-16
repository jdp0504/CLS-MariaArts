<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'Customer';
    protected $primaryKey = 'customerID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'customerID', 'customerName', 'birthDate', 'phoneNumber',
        'referralCode', 'currentPoints', 'email', 'status', 'archivedAt',
    ];
}
