<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimLineModel extends Model
{
    use HasFactory;
     protected $table = "claim_line";
    public $timestamps = false;
   
    protected $fillable = [
        'mca_claim_id', 
        'practice_number', 
        'clmnline_charged_amnt', 
        'clmline_scheme_paid_amnt', 
        'gap', 
        'tariff_code', 
        'createdBy', 
        'treatmentDate', 
        'primaryICDCode',
        'gap_aamount_line',
        'reason_code',
        'reason_description',
        
    ];
}
