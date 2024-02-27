<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorsModel extends Model
{
    use HasFactory;
     protected $table = "doctors";
    public $timestamps = false;
}
