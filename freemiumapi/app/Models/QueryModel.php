<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueryModel extends Model
{
    use HasFactory;

     protected $table = "freemium_queries";
    public $timestamps = false;

    protected $fillable = [
         'user_id', 
            'category',
            'description',
            'entered_by',  
            'assigned_to', 
    ];

}
