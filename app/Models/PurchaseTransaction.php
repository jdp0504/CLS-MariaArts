<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;

class PurchaseTransaction extends Model
{
    protected $table = 'PurchaseTransaction';
    protected $primaryKey = 'transactionID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'transactionID', 'customerID', 'cashierID',
        'transactionDate', 'totalPrice', 'pointEarned',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customerID', 'customerID');
    }
}
