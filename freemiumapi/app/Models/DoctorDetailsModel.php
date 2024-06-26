<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorDetailsModel extends Model
{
    use HasFactory;
     protected $table = "doctor_details";
    public $timestamps = false;
    protected $fillable = [
         'name_initials',
         'practice_number',
         'entered_by',
    ];
}
