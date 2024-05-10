<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SignUpRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Notifications\EmailNotification;
use App\Models\MemberModel;
use App\Models\TrailModel;
use App\Models\QueryModel;
use App\Http\Controllers\Api\ClaimsController;
use App\Models\SchemeModel;
use App\Models\LogsModel;
use App\Models\QueryDocumentModel;
use App\Models\FaqModel;
use App\Models\BlogModel;

class MemberController extends Controller
{
   public function addUser(SignUpRequest $request)
   {
    try
    {
    $data = $request->validated();
   $temp_code=rand(1000,99999);  
 
     $user = MemberModel::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'id_number' => $data['id_number'],
            'scheme_name' => $data['scheme_name'],
            'scheme_number' => $data['scheme_number'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'temp_code'=>$temp_code,
        ]);

     $token = $user->createToken('main')->plainTextToken;

        $inar=[
    'data'=>"Welcome to <b>MedClaim Assist!</b><br><br>Thank you for registering with MedClaim Assist<br><br>Your temporary code : <b>".$temp_code."</b>",
    'hid'=>""    
    ];
  $user->notify(new EmailNotification($inar));
  
  $this->saveTrail($user->id,"Account Created",$user->id);
        return response()->json(['message' => 'Account created successfully','token'=>$token, 'user' => $user], 200);
        }
    catch(\Exception $e){
        return response()->json(['message' => 'Internal Error',], 500);
    }

   }

   public function login(LoginRequest $request)
   {
    try
    {
    $credentials = $request->validated();
    if(!Auth::attempt($credentials))
    {
        return response()->json(['message' => 'Provided Email / Password is incorrect'], 401);
    }
        $user = Auth::user();
        if($user->status)
        {
            $user->last_logged = date("Y-m-d H:i:s");
            $user->save();
            $token = $user->createToken('api')->plainTextToken;
        return response()->json(['message' => 'Successfully Logged in','token'=>$token, 'user' => $user], 200);
        }
        else
        {
        $token = null;
        return response()->json(['message' => 'Account not Activated','token'=>$token, 'user' => $user], 403);
        }
    }
    catch(\Exception $e){
        return response()->json(['message' => 'Internal Error',], 500);
    }
        
}

public function verifyEmail(Request $request)
   {
   try
    {
    $user = MemberModel::where('id','=',(int)$request->user_id)->where('temp_code','=',(int)$request->temp_code)->get();
    if(count($user)>0)
    {
        $inuser = MemberModel::find((int)$request->user_id);
        $inuser->status = 1;
        $inuser->date_activated = date("Y-m-d H:i:s");
        $inuser->temp_code = "0000";
        $verified = $inuser->save();
        $this->saveTrail($request->user_id,"Account Verified",$request->user_id);
     return response()->json(['message' => "Your Account was successfully verified. ",'verified'=>$verified,'user'=>$user], 200);
    }
    else
    {
      return response()->json(['message' => 'Failed to verify your email address'], 422);
    }
    }
    catch(\Exception $e){
        return response()->json(['message' => 'Internal Error',], 500);
    }
      
   }


    public function logout(Request $request)
   {
    $user = $request->user()    ;
    $user->currentAccessToken()->delete();
    return response()->json('',204);
   }

   private function saveTrail($user_id,$trail_name,$entered_by):void
   {
    TrailModel::create([
            'user_id' => $user_id,
            'trail_name' => $trail_name,
            'entered_by' => $entered_by,
        ]);
   }

   public function getDashbord(Request $request)
   {
    try{
        $c = new ClaimsController();
        $authuser = $request->user();
    $total_queries = QueryModel::where('user_id','=',$authuser->id)->count();
     $total_faq = FaqModel::count(); 
     $total_blog = BlogModel::count(); 
            
            $doc_count = $this->getQueryDocument($authuser->id);
    $total_switch_claims = $c->seamLessAPI("https://medclaimassist.co.za/admin/seamless_api_freemium.php",0,$authuser->email,$authuser->scheme_number,$authuser->id_number);
    $benefit = $this->countBenefit($authuser->id,$total_switch_claims);
    $trail = TrailModel::where('user_id','=',$authuser->id)->get();
    return response()->json(['message' => 'Records Return','total_query'=>$total_queries,
    'total_faq'=>$total_faq,'total_blog'=>$total_blog,'total_switch_claims'=>$total_switch_claims,'trail'=>$trail,'user'=>$authuser,'benefit'=>$benefit,'doc_count'=>$doc_count], 200);
       }
    catch(\Exception $e){
        return response()->json(['message' => 'Internal Error',], 500);
    }
     }

   private function getQueryDocument($user_id)
   {
    $total = QueryDocumentModel::where('user_id','=',$user_id)->count();
    return $total;
   }
   public function updatePlan(Request $request)
     {
        $email = $request->email;
        $plan = $request->plan;
        $date = date("Y-m-d H:i:s");
        try{
        $user = MemberModel::where('email', '=',$email)->update(['plan' => $plan,'plan_date'=>$date]);
        return response()->json(['message' => 'Successfully Updated','status'=>$user,'email'=>$email,'plan'=>$plan], 200);
        }
    catch(\Exception $e){
        return response()->json(['message' => 'Internal Error',], 500);
    }
     }
     public function getUser(Request $request)
     {
        try{
            $authuser = $request->user();
            $schemes = SchemeModel::pluck('name');
        return response()->json(['message' => 'Successfully Updated','user'=>$authuser,'schemes'=>$schemes], 200);
        }
    catch(\Exception $e){
        return response()->json(['message' => 'Internal Error',], 500);
    }
     }
       private function countBenefit($user_id,$arr=[])
     {
        
     $data['correct'] = 1;
     $data['incorrect'] = 0;
     $data['posscorrect'] = 1;
     $data['possincorrect'] = 0;
 
      return $data;
     }

     public function updateUser(ProfileRequest $request)
     {
        try
        {
        $data = $request->validated();    
        $user = $request->user(); 
        //$user = MemberModel::find($authuser->id);
     /*
         $user->update([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'id_number' => $data['id_number'],
                'scheme_name' => $data['scheme_name'],
                'scheme_number' => $data['scheme_number'],
            ]);
            */
            LogsModel::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'id_number' => $data['id_number'],
                'email'=>$user->email,
                'scheme_name' => $data['scheme_name'],
                'scheme_number' => $data['scheme_number'],
            ]);
        return response()->json(['message' => 'Record successfully saved waiting for verification','user'=>$user], 200);
        }
    catch(\Exception $e){
        return response()->json(['message' => 'Internal Error',], 500);
    }
    
}

public function forgotPassword(Request $request)
    {
       try
       {       
       $email = $request->email;
       $user_email = MemberModel::where('email','=',$email)->first();
       if($user_email)
       {
        $user = MemberModel::find((int)$user_email['id']);
        $temp_code = rand(10000,200000);   
        $user->update([
               'date_password_changed' => date('Y-m-d H:i:s'),
               'temp_code' => $temp_code,               
           ]);

           $inar=[
            'data'=>"Hello<br><br>To reset your password use the temporary code below: <br><br><b>".$temp_code."</b><br><br>If you did not request a password reset from Medclaim Assist, you can safely ignore this email.",
            'hid'=>""    
            ];
          $user->notify(new EmailNotification($inar));
       }
      
      
       return response()->json(['message' => 'Successfully Redirected'], 200);
       }
   catch(\Exception $e){
       return response()->json(['message' => 'Internal Error',], 500);
   }
     }
     public function resetPassword(ForgotPasswordRequest $request)
    {
       try
       {   
       $data = $request->validated();  
       $email = $data['email'];  
       $temp_code = $data['temp_code'];
       $user_email = MemberModel::where('email','=',$email)->where('temp_code','=',$temp_code)->first();
       if($user_email)
       {
        $user = MemberModel::find((int)$user_email['id']);  
        $user->update([
               'date_password_changed' => date('Y-m-d H:i:s'),
               'temp_code' => "0001",  
               'password' => Hash::make($data['password']),           
           ]);
           return response()->json(['message' => 'Password Successfully updated'], 200);
       }
       else
       {
        return response()->json(['message' => 'There is an error, try again',], 500);
       }
      
      
       
       }
   catch(\Exception $e){
       return response()->json(['message' => 'Internal Error',], 500);
   }
     }

}
