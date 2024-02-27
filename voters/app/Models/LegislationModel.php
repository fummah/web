<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class LegislationModel extends Model
{
    use HasFactory;
	protected $table="legislation";
	public $timestamps=false;
	  protected $fillable = [
        'username',
        'legislation_name',
        'legislation_description'     
    ];
}
