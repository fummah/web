<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoteModel extends Model
{
    use HasFactory;
		protected $table="votes";
	public $timestamps=false;
	  protected $fillable = [
        'user_id',
        'legislation_id',
        'vote',
		'entered_by'		
    ];
}
