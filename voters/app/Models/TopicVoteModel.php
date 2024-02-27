<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopicVoteModel extends Model
{
    use HasFactory;
		protected $table="topic_votes";
	public $timestamps=false;
	  protected $fillable = [
        'user_id',
        'topic_id',
        'vote',
		'entered_by'		
    ];
}
