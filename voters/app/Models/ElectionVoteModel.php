<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectionVoteModel extends Model
{
    use HasFactory;
		protected $table="election_votes";
	public $timestamps=false;
	  protected $fillable = [
        'user_id',
        'election_id',
        'vote',
		'entered_by'		
    ];
}
