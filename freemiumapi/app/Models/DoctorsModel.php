<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorsModel extends Model
{
    use HasFactory;
     protected $table = "doctors";
    public $timestamps = false;
    protected $fillable=[
'practice_number',
'claim_id',
'entered_by',
    ];
}
