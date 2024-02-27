<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class MemberModel extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

     protected $table = "freemium_members";
    public $timestamps = false;
     protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'id_number',
        'scheme_name',
        'scheme_number',
        'password',
        'temp_code',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


}
