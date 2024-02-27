<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueryNotesModel extends Model
{
    use HasFactory;
    protected $table = "freemium_query_notes";
    public $timestamps = false;

}
