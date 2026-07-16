<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Reward;

class Redemption extends Model
{
    protected $table = 'Redemption';
    protected $primaryKey = 'redemptionID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'redemptionID', 'customerID', 'rewardID', 'redeemedDate',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customerID', 'customerID');
    }

    public function reward()
    {
        return $this->belongsTo(Reward::class, 'rewardID', 'rewardID');
    }
}
