<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'Notification';
    protected $primaryKey = 'notificationID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'adminID', 'customerID', 'messageContent', 'subject', 'attachment',
        'filterType', 'filterValue', 'creationDate', 'status',
    ];
}
