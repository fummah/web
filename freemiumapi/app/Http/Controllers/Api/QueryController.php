<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QueryModel;
use App\Models\FreemiumDocumentsModel;
use App\Models\QueryNotesModel;

class QueryController extends Controller
{
    public function addQuery(Request $request)
    {
        try
        {
            $authuser = $request->user();
            $data = $request->validate([ 
            'category' => 'required|string|min:2',
            'description' => 'required|string|min:4',
            ]);

        $query = QueryModel::create([
            'user_id' => $authuser->id, 
            'category' => $data['category'],
            'description' => $data['description'],
            'entered_by' => $authuser->id,          
        ]);
         return response()->json(['message' => 'New Query Successfully Added','query' => $query], 200);
        }
    catch(\Exception $e){
        return response()->json(['message' => 'Internal Error : '.$e->getMessage(),], 500);
    }
    }

    public function getQueries(Request $request)
    {
        try
        {
            $authuser = $request->user();           

        $queries = QueryModel::where('user_id','=',$authuser->id)->get();
         return response()->json(['message' => 'Records Successfully Retrieved','queries' => $queries,'user'=>$authuser], 200);
        }
    catch(\Exception $e){
        return response()->json(['message' => 'Internal Error : '.$e->getMessage(),], 500);
    }
    }

     public function getQuery(Request $request)
    {
        try
        {
            $data = $request->validate([            
            'query_id' => 'required|numeric|between:0,999999999999999',
            ]);

        $query = QueryModel::find($data['query_id']);
        $documents = FreemiumDocumentsModel::where('associated_id','=',$data['query_id'])->where('_type','=','query')->get();
        $notes = QueryNotesModel::where('query_id','=',$data['query_id'])->get();
         return response()->json(['message' => 'Records Successfully Retrieved','query' => $query,'documents'=>$documents,'notes'=>$notes], 200);
        }
    catch(\Exception $e){
        return response()->json(['message' => 'Internal Error : '.$e->getMessage(),], 500);
    }
    }
}
