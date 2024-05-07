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
use App\Models\DocumentsModel;
use App\Models\ClaimModel;
use App\Models\ClaimMemberModel;
use App\Models\DoctorDetailsModel;
use App\Models\DoctorsModel;
use App\Models\ClaimLineModel;
use App\Models\FeedbackModel;
use App\Models\PatientModel;
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
        $charged_amnt=0;$scheme_paid=0;$gap=0;$service_date="";
        $dd=null;  
        if((int)$data['claim_id']>0)
        {
            $c = new ClaimsController();  
        $dd = $c->seamLessAPI("https://medclaimassist.co.za/admin/seamless_api_freemium2.php",$data['claim_id']);     
        $charged_amnt=(double)$dd->original->claim->charged_amnt;
        $scheme_paid=(double)$dd->original->claim->scheme_paid;  
        $gap=(double)$dd->original->claim->gap; 
        $service_date=$dd->original->claim->Service_Date; 
        
    }
    $service_date = $this->isValidDate($service_date)?$service_date:date('1970-01-01');
        $category = $data['category'];
        $open=$category=="Others"?4:1;
        $claim = $this->createClaim($user->first_name,$user->last_name,$user->email,$user->id_number,$user->scheme_number,$user->scheme_name,$charged_amnt,$scheme_paid,$gap,$query->id,$open,$service_date,$category);
        $this->createPatient($claim["id"],$user->first_name." ".$user->last_name);
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
            DocumentsModel::create([
                'claim_id'=>$claim["id"],
                'doc_description'=> $request->document,
                'doc_type'=>"pdf",
                'randomNum'=>"",
                'uploaded_by'=>"System", 
                'doc_size'=>"678"    
            ]);
            $this->saveTrail($user->id,"Query Document Loaded",$user->id);
        }
       
        FeedbackModel::create([
            'claim_id'=>$claim["id"],
            'description'=>$data['description'],
            'owner'=>"System",
        ]);
    
        
        if((int)$data['claim_id']>0)
        {       
        foreach($dd->original->doctors as $doctor)
        {
            $this->createDoctor($doctor->practice_number,$doctor->full_name,$claim->id,$doctor->claim_lines);
        }
        
    }
    
         return response()->json(['message' => 'New Query Successfully Added','query' => $query,"lines"=>$dd], 200);
        
        }
    catch(\Exception $e){
        return response()->json(['message' => 'Internal Error : '.$e->getMessage(),], 500);
    }
    }
    private function isValidDate($date, $format = 'Y-m-d') {
        $dateTime = \DateTime::createFromFormat($format, $date);
        return $dateTime && $dateTime->format($format) === $date;
    }
     private function createPatient($claim_id,$patient_name)
    {
        $patient = PatientModel::create([
            "claim_id"=>$claim_id,
            "patient_name"=>$patient_name,
        ]); 
        return $patient;     

    }
    private function createMember($first_name,$surname,$email,$id_number,$scheme_number,$medical_scheme)
    {
        $member = ClaimMemberModel::where('email','=',$email)->where('client_id','=',4)->first();
        if(!$member)
        {
            $member = ClaimMemberModel::create([
                'client_id'=>4, 
                'policy_number'=>'-', 
                'first_name'=>$first_name, 
                'surname'=>$surname, 
                'email'=>$email,
                'id_number'=>$id_number, 
                'scheme_number'=>$scheme_number, 
                'medical_scheme'=>$medical_scheme, 
                'entered_by'=>"System",
            ]);
        }
        return $member;
        
    }
    private function createClaim($first_name,$surname,$email,$id_number,$scheme_number,$medical_scheme,$charged_amnt,$scheme_paid,$gap,$query_id,$open=1,$service_date="",$category="")
    {
        $member=$this->createMember($first_name,$surname,$email,$id_number,$scheme_number,$medical_scheme);
        $claim = ClaimModel::create([
            'member_id'=>$member->member_id, 
            'claim_number'=>$this->createClaimNumber($member->member_id), 
            'charged_amnt'=>$charged_amnt, 
            'scheme_paid'=>$scheme_paid, 
            'gap'=>$gap, 
            'Open'=>$open,
            'username'=>"Faghry",
            'preassessor'=>"Faghry",
            'claim_number1'=>$query_id,
            'Service_Date'=>$service_date,
            'category_type'=>$category,
        ]);
     
       
return $claim;
    }
    private function createClaimNumber($member_id)
    {
        $rand=rand(0,30);
        $claim_number = "FR".$member_id.$rand.date('dHs');
        return $claim_number;

    }
    private function createDoctor($practice_number,$name,$claim_id,$arr)
    {
        $practice_number =str_pad( $practice_number, 7, '0', STR_PAD_LEFT);
        $doctor = DoctorDetailsModel::where('practice_number','=',$practice_number)->first();
        if(!$doctor)
        {
$doctor = DoctorDetailsModel::create([
    'name_initials'=>$name,
    'practice_number'=>$practice_number,
]);        }
        $indoctor = DoctorsModel::create([
            'practice_number'=>$practice_number,
            'claim_id'=>$claim_id,
            'entered_by'=>"System",
            ]);

            foreach($arr as $ar)
            {
                $this->createClaimLine($practice_number,$claim_id,$ar->clmnline_charged_amnt,$ar->clmline_scheme_paid_amnt,$ar->gap,$ar->tariff_code,$ar->treatmentDate,$ar->primaryICDCode,$ar->reason_code,$ar->reason_description);
            }
            return $indoctor;
    }
    private function createClaimLine($practice_number,$claim_id,$charged_amount,$scheme_amount,$gap,$tariff_code,$treatmentDate,$icd10,$reason_code,$reason_description)
    {
        $claim_line = ClaimLineModel::create([
            'mca_claim_id'=>$claim_id, 
            'practice_number'=>$practice_number, 
            'clmnline_charged_amnt'=>$charged_amount, 
            'clmline_scheme_paid_amnt'=>$scheme_amount, 
            'gap'=>$gap, 
            'tariff_code'=>$tariff_code, 
            'createdBy'=>"System", 
            'treatmentDate'=>$treatmentDate, 
            'primaryICDCode'=>$icd10,
            'gap_aamount_line'=>$gap,
            'reason_code'=>$reason_code,
            'reason_description'=>$reason_description,
            'msg_code'=>$reason_code." - ".$reason_description,
        ]);
        return $claim_line;
    }
     private function getFile($qury_id)
    {
        $documents = FreemiumDocumentsModel::where('associated_id','=',$qury_id)->where('_type','=','query')->get();
        return $documents;
    }

    public function getQueries(Request $request)
    {
        try
        {
           $user = $request->user();
           $mainarr=[];

        $queries = QueryModel::where('user_id','=',$user->id)->orderByDesc('id')
        ->join('claim', 'freemium_queries.id', '=', 'claim.claim_number1')
        ->select('freemium_queries.*', 'claim.Open')
        ->get();
        foreach($queries as $query)
        {
$id=$query['id'];
$user_id=$query['user_id'];
$description=$query['description'];
$category=$query['category'];
$source=$query['source'];
$status=(int)$query['Open']>0?"Open":"Closed";
$date_entered=$query['date_entered'];
$isdoc = count($this->getFile($id))>0?"Yes":"No";
array_push($mainarr,array("id"=>$id,"user_id"=>$user_id,"description"=>$description,"category"=>$category,"source"=>$source,"status"=>$status,"date_entered"=>$date_entered,'isdoc'=>$isdoc));
        }

         return response()->json(['message' => 'Records Successfully Retrieved','queries' => $mainarr,"user"=>$user], 200);
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

    public function getClaimDetails($query_id)
    {
        try
        {
        $claim = ClaimModel::where('claim_number1','=',$query_id)->first();
         return $claim;
        }
    catch(\Exception $e){
        return null;
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
        $claim = $this->getClaimDetails((int)$data['query_id']);
        $dd = $c->seamLessAPI("https://medclaimassist.co.za/admin/seamless_api_freemium2.php",$query["switch_claim_id"]);
        $documents = FreemiumDocumentsModel::where('associated_id','=',$data['query_id'])->where('_type','=','query')->get();
        $notes = QueryNotesModel::where('query_id','=',$data['query_id'])->get();
        //$lines = QueryLineModel::where('query_id','=',$data['query_id'])->get();
         return response()->json(['message' => 'Records Successfully Retrieved','query' => $query,'claim'=>$claim,'documents'=>$documents,'notes'=>$notes,'doctors'=>$dd], 200);
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
