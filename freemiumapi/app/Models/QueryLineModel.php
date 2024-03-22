<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueryLineModel extends Model
{
    use HasFactory;

     protected $table = "freemium_lines";
    public $timestamps = false;

    protected $fillable = [
         'query_id', 
            'treatment_date',
            'paid_from',
            'amount_charged',  
            'amount_paid', 
            'entered_by', 
    ];
}
