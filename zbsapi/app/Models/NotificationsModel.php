<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationsModel extends Model
{
    use HasFactory;
    protected $table = 'notifications';
    public $timestamps = false;
    protected $fillable = [
        'member_id', 
        'message',     
        'entered_by',
        'title',  
    ];
}

