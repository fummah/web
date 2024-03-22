<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentsModel extends Model
{
    use HasFactory;
     protected $table = "documents";
     public $timestamps = false;

    protected $fillable = [
         'associated_id', 
            '_type',
            'document_name',
            'document_type',  
            'document_size', 
            'random_number', 
            'entered_by', 
    ];
}
