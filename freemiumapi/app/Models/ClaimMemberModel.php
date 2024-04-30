<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimMemberModel extends Model
{
    use HasFactory;
     protected $table = "member";
    public $timestamps = false;
    protected $fillable = [
        'client_id', 
        'policy_number', 
        'first_name', 
        'surname', 
        'email', 
        'id_number', 
        'scheme_number', 
        'medical_scheme', 
        'entered_by',
    
    ];
}
