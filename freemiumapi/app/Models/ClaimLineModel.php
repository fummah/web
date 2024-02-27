<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimLineModel extends Model
{
    use HasFactory;
     protected $table = "claim_line";
    public $timestamps = false;
}
