<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogModel extends Model
{
    use HasFactory;

     protected $table = "freemium_blog";
    public $timestamps = false;

    protected $fillable = [
            'title',
            'description',
            'entered_by',
    ];

}
