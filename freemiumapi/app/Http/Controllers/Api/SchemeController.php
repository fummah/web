<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SchemeModel;

class SchemeController extends Controller
{
   public function getSchemes()
   {
      $schemes = SchemeModel::pluck('name');
    return response()->json(['message' => 'Schemes Retrieved','schemes' => $schemes], 200);
   }
}
