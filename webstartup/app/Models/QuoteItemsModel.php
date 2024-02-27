<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteItemsModel extends Model
{
    use HasFactory;
     protected $table="quote_items";
    public $timestamps = false;
}
