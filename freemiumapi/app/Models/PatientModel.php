<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientModel extends Model
{
    use HasFactory;
    protected $table = "patient";
    public $timestamps = false;
     protected $fillable = [
        'claim_id',
        'patient_name',
     ];
}
