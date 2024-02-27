<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrailModel extends Model
{
    use HasFactory;
    protected $table = "freemium_trail";
    public $timestamps = false;
     protected $fillable = [
        'user_id',
        'trail_name',
        'entered_by',
    ];
}
