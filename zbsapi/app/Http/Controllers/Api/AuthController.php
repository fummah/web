<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ZbsMembers;
use App\Models\UsersMemberModel;
use Illuminate\Support\Facades\Auth;
use App\Models\NotificationsModel;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function Signup(Request $request)
    {
     try
     {
    
     $member_id = (int)$request->member_id;
     $full_name = $request->full_name;
     $contact_number = "+27".$request->contact_number;
     $contact_number = str_replace('+270','+27', $contact_number);
  
      $user = ZbsMembers::where('member_id', $member_id)
      ->where('contact_number',$contact_number)
      ->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%'.$full_name.'%'])
      ->first();
      if (!$user) {
        return response()->json(['message' => 'Incorrect Details','user' => $user], 401);
      } else {
        $account = UsersMemberModel::where('id', $member_id)->first();
        if(!$account)
        {
            $member_id = (int)$user["member_id"];
            $password = Hash::make($contact_number);
            $newuser = $this->registerUser($member_id,$password);
            $this->addNotifiction($member_id,"Account has been created","System","Sign Up");
            $this->addNotifiction($member_id,"Welcome to ZBS App, contact us for any questions","System","Welcome!!!");
            return response()->json(['message' => 'Account created successfully','user' => $user,"updatedmember"=> $newuser], 200);
        } else {
            return response()->json(['message' => 'Account already exist','user' => $user], 401);
        }
      }         
         }
     catch(\Exception $e){
         return response()->json(['message' => 'Internal Error'.$e->getMessage(),], 500);
     }
 
    }
    public function Login(Request $request)
   {
    try
    {

 
    $member_id = (int)$request->member_id;
    $contact_number = "+27".$request->contact_number;
    $contact_number = str_replace('+270','+27', $contact_number);

    $credentials = [
        'id'=> $member_id,
        'password'=> $contact_number
    ];

    if(!Auth::attempt($credentials))
    {
        return response()->json(['message' => 'Invalid Login'], 401);
    }
        $user = Auth::user();       
            $user->last_logged = date("Y-m-d H:i:s");
            $user->save();
            $token = $user->createToken('api')->plainTextToken;
        return response()->json(['message' => 'Successfully Logged in','token'=>$token, 'user' => $user], 200);
       
    }
    catch(\Exception $e){
        return response()->json(['message' => 'Internal Error : '.$e->getMessage(),], 500);
    }
        
}
public function logout(Request $request)
{
 $user = $request->user()    ;
 $user->currentAccessToken()->delete();
 return response()->json('',204);
}
private function registerUser($member_id,$password)
{
    $usermember = UsersMemberModel::create(['id'=> $member_id,'password'=> $password]);
    return $usermember;
}
private function addNotifiction($member_id,$message,$entered_by,$title)
{
    $new_notification = NotificationsModel::create(['member_id'=> $member_id,'message'=> $message,'entered_by'=> $entered_by,'title'=> $title]);
    return $new_notification;
}
}
