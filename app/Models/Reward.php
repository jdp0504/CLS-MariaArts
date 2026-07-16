<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    protected $table = 'Reward';
    protected $primaryKey = 'rewardID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'rewardID', 'rewardName', 'description', 'pointRequired', 'stock', 'status',
    ];
}
