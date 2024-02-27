<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;


class ZbsMembers extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;
    protected $table = 'members';
    public $timestamps = false;
   
}
