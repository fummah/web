<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QueryModel;
use App\Models\FreemiumDocumentsModel;
use App\Models\QueryNotesModel;
use App\Models\QueryLineModel;
use App\Models\QueryDocumentModel;
use App\Models\DocumentLineModel;
use App\Models\FaqModel;
use App\Models\BlogModel;
use App\Models\TrailModel;
use App\Http\Controllers\Api\ClaimsController;

class QueryController extends Controller
{
    public function addQuery(Request $request)
    {
        try
        {
            $data = $request->validate([          
            'claim_id' => 'required',
            'category' => 'required|string|min:2',
            'description' => 'required|string|min:4',
            ]);
            $user = $request->user();           

        $query = QueryModel::create([
            'user_id' => $user->id, 
            'switch_claim_id' => $data['claim_id'],
            'category' => $data['category'],
            'description' => $data['description'],
            'entered_by' => $user->id,          
        ]);
        $this->saveTrail($user->id,"New Query Loaded",$user->id);
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
            $this->saveTrail($user->id,"Query Document Loaded",$user->id);
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
    public function getFaq(Request $request)
    {
        try
        {
        $faqs = FaqModel::all();
         return response()->json(['message' => 'Records Successfully Retrieved','faqs' => $faqs], 200);
        }
    catch(\Exception $e){
        return response()->json(['message' => 'Internal Error : '.$e->getMessage(),], 500);
    }
    }
    public function getBlog(Request $request)
    {
        try
        {
        $blogs = BlogModel::all();
         return response()->json(['message' => 'Records Successfully Retrieved','blogs' => $blogs], 200);
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
            $c = new ClaimsController();
        $query = QueryModel::find($data['query_id']);
        $dd = $c->seamLessAPI("https://medclaimassist.co.za/admin/seamless_api_freemium2.php",$query["switch_claim_id"]);
        $documents = FreemiumDocumentsModel::where('associated_id','=',$data['query_id'])->where('_type','=','query')->get();
        $notes = QueryNotesModel::where('query_id','=',$data['query_id'])->get();
        //$lines = QueryLineModel::where('query_id','=',$data['query_id'])->get();
         return response()->json(['message' => 'Records Successfully Retrieved','query' => $query,'documents'=>$documents,'notes'=>$notes,'doctors'=>$dd], 200);
        }
    catch(\Exception $e){
        return response()->json(['message' => 'Internal Error : '.$e->getMessage(),], 500);
    }
    }

    public function addDocument(Request $request)
    {
        try
        {
            $data = $request->validate([          
            
            'category' => 'required|string|min:2',
            'description' => 'required|string|min:4',
            ]);
            $user = $request->user();          

        $query = QueryDocumentModel::create([
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
            DocumentLineModel::create([
            'doc_query_id' => $query->id, 
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
                '_type' => "doc",               
                'document_name' => $request->document,     
                'document_type' => "pdf", 
                'document_size' => 0, 
                'random_number' => rand(1,100), 
                'entered_by' => $user->id,      
            ]);
            $this->saveTrail($user->id,"Document Loaded",$user->id);
        }
         return response()->json(['message' => 'New Document Successfully Added','query' => $query,"lines"=>$lines,"document"=>$request->document], 200);
        
        }
    catch(\Exception $e){
        return response()->json(['message' => 'Internal Error : '.$e->getMessage(),], 500);
    }
    }

    public function getDocuments(Request $request)
    {
        try
        {
           $user = $request->user();

        $documents = QueryDocumentModel::where('user_id','=',$user->id)->get();
        $arr = array();
        foreach($documents as $doc)
        {
            $doc_id = $doc["id"];
            $doc_description = $doc["description"];
            $date_entered = $doc["date_entered"];
            $actual_documents = FreemiumDocumentsModel::where('associated_id','=',$doc_id)->where('_type','=','doc')->get();
            $lines = DocumentLineModel::where('doc_query_id','=',$doc_id)->get();
            $in_arr = array("doc_id"=>$doc_id,"doc_description"=>$doc_description,'date_entered'=>$date_entered,"actual_documents"=>$actual_documents,"lines"=>$lines);
            array_push($arr,$in_arr);
        }
         return response()->json(['message' => 'Records Successfully Retrieved','docs' => $arr,"user"=>$user], 200);
        }
    catch(\Exception $e){
        return response()->json(['message' => 'Internal Error : '.$e->getMessage(),], 500);
    }
    }
    private function saveTrail($user_id,$trail_name,$entered_by):void
    {
     TrailModel::create([
             'user_id' => $user_id,
             'trail_name' => $trail_name,
             'entered_by' => $entered_by,
         ]);
    }
}
