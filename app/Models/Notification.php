<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'Notification';
    protected $primaryKey = 'notificationID';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'adminID', 'customerID', 'messageContent', 'subject', 'attachment',
        'filterType', 'filterValue', 'creationDate', 'status',
    ];
}
