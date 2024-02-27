<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchemeModel extends Model
{
    use HasFactory;
    protected $table = "schemes";
    public $timestamps = false;
}
