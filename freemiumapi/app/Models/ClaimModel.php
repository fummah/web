<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimModel extends Model
{
    use HasFactory;
     protected $table = "claim";
    public $timestamps = false;
    protected $fillable = [
        'member_id', 
        'claim_number', 
        'charged_amnt', 
        'scheme_paid', 
        'gap', 
        'Open',
        'username',
        'preassessor',
        'Service_Date',
    ];
}
