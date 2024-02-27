<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClaimModel;
use App\Models\ClaimMemberModel;
use App\Models\DoctorDetailsModel;
use App\Models\DoctorsModel;
use App\Models\ClaimLineModel;
use App\Models\NotesModel;
use App\Models\DocumentsModel;

class ClaimsController extends Controller
{
     public function getClaims(Request $request)
   {
    try{
        $authuser=$request->user();

     if($request->type == "internal")
    {
     $claims = ClaimModel::join('member','claim.member_id','=','member.member_id')
            ->where('member.email', $authuser->email)->select('claim.claim_id','claim.claim_number','member.medical_scheme','claim.date_entered','claim.charged_amnt','claim.Open')
            ->get();
   
    return response()->json(['message' => 'Records Return','claims'=>$claims], 200);
}
else
{
    return $this->seamLessAPI("https://medclaimassist.co.za/admin/seamless_api_freemium.php",0,$authuser->email,$authuser->scheme_number,$authuser->id_number);
       }
}
    catch(\Exception $e){
        return response()->json(['message' => 'Internal Error : '.$e->getMessage(),], 500);
    }
     }

      public function getClaim(Request $request)
   {
    try{
        
    if($request->type == "internal")
    {
     $claim = ClaimModel::join('member','claim.member_id','=','member.member_id')
            ->where('claim.claim_id', $request->claim_id)
            ->first()->toArray();
            $indoc=array();
             $doctors = DoctorsModel::join('doctor_details','doctor_details.practice_number','=','doctors.practice_number')
            ->where('doctors.claim_id', $request->claim_id)->select('name_initials','surname','doctors.practice_number')
            ->get();

            foreach ($doctors as $row) {
                $practice_number = $row["practice_number"];
                $full_name = $row["name_initials"]." ".$row["surname"];
                $claim_lines = ClaimLineModel::where('mca_claim_id','=',$request->claim_id)->where('practice_number','=',$practice_number)->get();
                $arr = array("practice_number"=>$practice_number,"full_name"=>$full_name,"claim_lines"=>$claim_lines);
                array_push($indoc, $arr);                
            }
            $notes = NotesModel::where('claim_id','=',$request->claim_id)->get();
            $documents = DocumentsModel::where('claim_id','=',$request->claim_id)->get();
    
   
    return response()->json(['message' => 'Records Return','claim'=>$claim,"doctors"=>$indoc,"documents"=>$documents,"notes"=>$notes], 200);
    }
    else
    {
        return $this->seamLessAPI("https://medclaimassist.co.za/admin/seamless_api_freemium2.php",$request->claim_id);
       }
    }
    catch(\Exception $e){
        return response()->json(['message' => 'Internal Error : '.$e->getMessage(),], 500);
    }
     }

     public function seamLessAPI($url,$claim_id=0,$email="",$scheme_number="",$id_number="")
     {
$ch = curl_init();
$data = array(
    "Username" => "Seamless_production",
    "Password" => "mast20seamles",
    "claim_id" => $claim_id,
    "email" =>$email,
    "scheme_number" =>$scheme_number,
    "id_number" =>$id_number
);

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    return response()->json(['message' => 'Internal Error : '], 500);
}
curl_close($ch);
return response()->json(json_decode($result), 200);
     }


}
