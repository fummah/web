<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogsModel extends Model
{
    use HasFactory;

     protected $table = "freemium_members_logs";
    public $timestamps = false;

    protected $fillable = [ 
           'first_name',
                'last_name',
                'id_number',
                'email',
                'scheme_name',
                'scheme_number',
    ];

}
