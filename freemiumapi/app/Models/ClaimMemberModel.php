<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimMemberModel extends Model
{
    use HasFactory;
     protected $table = "member";
    public $timestamps = false;
}
