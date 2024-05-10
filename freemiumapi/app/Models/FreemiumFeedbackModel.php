<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreemiumFeedbackModel extends Model
{
    use HasFactory;

     protected $table = "freemium_feedback";
    public $timestamps = false;

    protected $fillable = [ 
            'description',
            'entered_by',
    ];

}
