<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QueryModel;
use App\Models\FreemiumDocumentsModel;
use App\Models\QueryNotesModel;
use App\Models\QueryLineModel;
use App\Models\TrailModel;

class QueryController extends Controller
{
    public function addQuery(Request $request)
    {
        try
        {
            $data = $request->validate([          
            
            'category' => 'required|string|min:2',
            'description' => 'required|string|min:4',
            ]);
            $user = $request->user();           

        $query = QueryModel::create([
            'user_id' => $user->id, 
            'category' => $data['category'],
            'description' => $data['description'],
            'entered_by' => $user->id,          
        ]);
        $lines = json_decode($request->lines,true);
        if(count($lines)>0)
        {
            foreach($lines as $line)
            {
            QueryLineModel::create([
            'query_id' => $query->id, 
            'treatment_date' => $line['treatment_date'],
            'paid_from' => $line['paid_from'],
            'amount_charged' => (double)$line['amount_charged'],
            'amount_paid' => (double)$line['amount_paid'],
            'entered_by' => $user->id,          
        ]);
            }
        } 
        if($request->document!="")
        {
            FreemiumDocumentsModel::create([
                'associated_id' => $query->id, 
                '_type' => "query",               
                'document_name' => $request->document,     
                'document_type' => "pdf", 
                'document_size' => 0, 
                'random_number' => rand(1,100), 
                'entered_by' => $user->id,      
            ]);
            $this->saveTrail($user->id,"Document Uploaded",$user->id);
        }
         return response()->json(['message' => 'New Query Successfully Added','query' => $query,"lines"=>$lines,"document"=>$request->document], 200);
        
        }
    catch(\Exception $e){
        return response()->json(['message' => 'Internal Error : '.$e->getMessage(),], 500);
    }
    }

    public function getQueries(Request $request)
    {
        try
        {
           $user = $request->user();

        $queries = QueryModel::where('user_id','=',$user->id)->get();
         return response()->json(['message' => 'Records Successfully Retrieved','queries' => $queries,"user"=>$user], 200);
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
        $lines = QueryLineModel::where('query_id','=',$data['query_id'])->get();
         return response()->json(['message' => 'Records Successfully Retrieved','query' => $query,'documents'=>$documents,'notes'=>$notes,'lines'=>$lines], 200);
        }
    catch(\Exception $e){
        return response()->json(['message' => 'Internal Error : '.$e->getMessage(),], 500);
    }
    }
    private function saveTrail($user_id,$trail_name,$entered_by,$date_entered=date('Y-m-d H:i:s')):void
    {
     TrailModel::create([
             'user_id' => $user_id,
             'trail_name' => $trail_name,
             'entered_by' => $entered_by,
             'date_entered' => $date_entered,
         ]);
    }
   
}
