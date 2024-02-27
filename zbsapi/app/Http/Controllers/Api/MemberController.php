<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ZbsMembers;
use App\Models\RegisterModel;
use App\Models\FuneralsModel;
use App\Models\DependentsModel;
use App\Models\User;
use App\Models\AccountsModel;
use App\Models\DeceasedModel;
use App\Models\NotificationsModel;
use App\Models\LocationsModel;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\AccountsResource;

class MemberController extends Controller
{
   public function getDetails(Request $request)
   {
      try
      {
         $user = $request->user();
         $memberId=(int)$user['id'];
         $details = $this->getTopDetails($memberId);
         $accumulated = $this->getAccumulated($memberId);
         $groupId=$details["group_id"];
         $register = $this->combineRegister($memberId,$groupId);
         $graph = $this->getGraphProcess($groupId);
         $dd =$details["date_entered"];
         $dateF = \DateTime::createFromFormat('Y-m-d H:i:s', $dd);
         $m = $dd>"2022-10-31"?"since ".$dateF->format('M Y'):"before ".$dateF->format('M Y');

         return response()->json(['message'=>'Done Now','details'=>$details,"accumulated"=>$accumulated,"allregister"=>$register,"graph"=>$graph,"mydate"=>$m],200);

      }
      catch(\Exception $e)
      {
         return response()->json(['message'=> 'There is an error. Please try again later.'],500);
      }
    
   }
   public function getDependents(Request $request)
   {
      try{
         $user = $request->user();
         $memberId=(int)$user['id'];
         $dependents = $this->dependents($memberId);
         return response()->json(['message'=> 'Success','dependents'=>$dependents],200);

      }
      catch(\Exception $e)
      {
         return response()->json(['message'=> 'There is an error. Please try again later.'],500);
      }
   }
   public function getAccounts(Request $request)
   {
      try{
         $user = $request->user();
         $memberId=(int)$user['id'];
         $details = $this->getTopDetails($memberId);
         $balance = number_format($details["account_balance"],2,"."," ");
         $accounts = $this->accounts($memberId);
         return response()->json(['message'=> 'Success','accounts'=>$accounts,'balance'=>$balance],200);

      }
      catch(\Exception $e)
      {
         return response()->json(['message'=> 'There is an error. Please try again later.'],500);
      }
   }
   public function getNotifications(Request $request)
   {
      try{
         $user = $request->user();
         $memberId=(int)$user['id'];
         $notifications = $this->notifications($memberId);
         return response()->json(['message'=> 'Success','notifications'=>$notifications],200);

      }
      catch(\Exception $e)
      {
         return response()->json(['message'=> 'There is an error. Please try again later.'],500);
      }
   }
   public function getAdmins(Request $request)
   {
      try{      
         $user = $request->user();
         $memberId=(int)$user['id'];
         $details = $this->getTopDetails($memberId);   
         $locationId=(int)$details['location_id'];
         $admins = $this->admins($locationId);
         return response()->json(['message'=> 'Success','admins'=>$admins],200);

      }
      catch(\Exception $e)
      {
         return response()->json(['message'=> 'There is an error. Please try again later.'],500);
      }
   }
   public function getProfile(Request $request)
   {
      try{
         $user = $request->user();
         $memberId=(int)$user['id'];
         $details = $this->getTopDetails($memberId);
         $groupId=$details["group_id"];
         $locations = $this->locations($groupId);
         $dd =$details["date_entered"];
         $dateF = \DateTime::createFromFormat('Y-m-d H:i:s', $dd);
         $m = $dateF->format('d M Y');
         return response()->json(['message'=> 'Success','details'=>$details,'locations'=>$locations,'date_registered'=>$m],200);

      }
      catch(\Exception $e)
      {
         return response()->json(['message'=> 'There is an error. Please try again later.'],500);
      }
   }
   public function getRegister(Request $request)
   {
      
      try{      
         $memberId= (int)$request->member_id;   
         $details = $this->getTopDetails($memberId);
         $groupId=$details["group_id"];
         $full_name = $details["first_name"]." ".$details["last_name"];
         $contact_number = $details["contact_number"];
         $register = $this->combineRegister($memberId,$groupId,100);
         return response()->json(['message'=> 'Success','register'=>$register,'full_name'=>$full_name,'contact_number'=>$contact_number],200);

      }
      catch(\Exception $e)
      {
         return response()->json(['message'=> 'There is an error. Please try again later.'],500);
      }
   }
   public function getDeceased(Request $request)
   {
      
      try{ 
         $funeral_id = (int)$request->funeral_id;       
         $deceased = $this->deceased($funeral_id);   
         $funeral = $this->funeral($funeral_id);
         $funeral_name = $funeral["funeral_name"];     
         return response()->json(['message'=> 'Success','deceased'=>$deceased,'funeral_name'=>$funeral_name],200);

      }
      catch(\Exception $e)
      {
         return response()->json(['message'=> 'There is an error. Please try again later.'.$e->getMessage()],500);
      }
   }
   private function deceased($funeral_id)
   {
      $deceased = DeceasedModel::select('deceased.*', 'm.member_id', 'm.first_name', 'm.last_name', 'l.location_name', 'g.group_name')
    ->join('members as m', 'deceased.member_id', '=', 'm.member_id')
    ->join('locations as l', 'm.location_id', '=', 'l.location_id')
    ->join('groups as g', 'l.group_id', '=', 'g.group_id')
    ->where('deceased.funeral_id', '=', $funeral_id)
    ->get();

return $deceased;
   }
   private function getTopDetails($memberId)
   {
$member = ZbsMembers::select('members.*','locations.location_name','groups.group_id','groups.group_name')
    ->join('locations', 'members.location_id', '=', 'locations.location_id')
    ->join('groups', 'locations.group_id', '=', 'groups.group_id')
    ->where('members.member_id', '=', $memberId)
    ->first();
return $member;
   }

   private function getAccumulated($memberId)
   {
    $totalFunerals = RegisterModel::where('register.member_id', $memberId)
    ->join('funerals as f', 'register.funeral_id', '=', 'f.funeral_id')
    ->selectRaw('COUNT(*) as total_funerals')
    ->selectRaw('SUM(CASE WHEN register.status = "paid" THEN f.amount_paid ELSE 0 END) as total_paid')
    ->first();

// Access the results
$data["total_funerals"] = $totalFunerals->total_funerals;
$data["total_paid"] = number_format($totalFunerals->total_paid,2,"."," ");
return $data;
   }

   private function combineRegister($memberId,$groupId,$limit=9)
   {
      $register_arr = $this->register($memberId,$limit);
      $latest_funeral_arr = $this->getLatestFuneral($groupId);
      $funeral_id = $latest_funeral_arr['funeral_id'];
      $myarr = $register_arr;  
      if(!$this->searchArr($register_arr,$funeral_id))
      {
         $myarr = $register_arr->toArray();
         $new_arr = $this->indivLatest($latest_funeral_arr,$memberId);
         array_unshift($myarr, $new_arr);
         
      }     
      return array("latest_funeral"=>$latest_funeral_arr,"register"=>$myarr);      
   }
   private function getLatestFuneral($groupId)
   {
      $funeral = FuneralsModel::select('funerals.funeral_id', 'funerals.funeral_name', 'funerals.amount_paid', 'funerals.status', 'funerals.date_entered', 'funerals._type', 'funerals.price', 'groups.group_id', 'groups.group_name')
      ->join('groups', 'funerals.group_id', '=', 'groups.group_id')
      ->where('funerals.group_id', '=', $groupId)
      ->latest('funeral_id')->first();
      return $funeral;  
   }

   private function register($memberId,$limit)
   {
      $register = RegisterModel::select('register.*', 'funerals.funeral_name', 'funerals._type', 'funerals.amount_paid', 'funerals.group_id', 'groups.group_name')
      ->join('funerals', 'register.funeral_id', '=', 'funerals.funeral_id')
      ->join('groups', 'funerals.group_id', '=', 'groups.group_id')
      ->where('register.member_id', '=', $memberId)
      ->orderBy('register.funeral_id', 'desc')
      ->limit($limit)
      ->get();

    return $register;
   }

 
   function indivLatest($arr,$memberId)
   {
      $arr_new = array("register_id"=>null, "member_id"=>$memberId, "funeral_id"=>$arr["funeral_id"], "date_entered"=>null, 
      "entered_by"=>null, "status"=>"Waiting","_type"=>$arr['_type'],"funeral_name"=>$arr['funeral_name'],"amount_paid"=>$arr['amount_paid'],
   "group_id"=>$arr['group_id'],"group_name"=>$arr['group_name']);
   return $arr_new;
   }
   private function dependents($memberId)
   {
      $dependents = DependentsModel::where('member_id', '=', $memberId)->get();  
    return $dependents;
   }
   private function admins($locationId)
   {
      $admins = User::join('locations', 'users.location_id', '=', 'locations.location_id')
      ->where('users.location_id', '=', $locationId)
      ->get();
    return $admins;
   }
   private function locations($groupId)
   {
      $locations = LocationsModel::where('group_id', '=', $groupId)->get();  
    return $locations;
   }
   private function accounts($memberId)
   {
      $accounts = AccountsModel::where('member_id', '=', $memberId)
      ->orderBy('id', 'DESC')
      ->limit(50)
      ->get();  
    return AccountsResource::collection($accounts);
   }
   private function notifications($memberId)
   {
      $notifications = NotificationsModel::where('member_id', '=', $memberId)->get();  
    return $notifications;
   }
   private function funeral($funeral_id)
   {
      $funeral = FuneralsModel::where('funeral_id', '=', $funeral_id)->get()->first();  
    return $funeral;
   }

   private function getGraph($groupId)
   {
      $results = FuneralsModel::select(DB::raw('DATE_FORMAT(date_entered,"%Y-%m") as month'), DB::raw('COUNT(*) as total'))
      ->where('group_id', '=', $groupId)
      ->groupBy(DB::raw('DATE_FORMAT(date_entered,"%Y-%m")'))
      ->orderBy('month', 'DESC')
      ->limit(5)
      ->get();
  
  return $results;
   }
   private function getGraphProcess($groupId)
   {
      $months = [];
      $values = [];
      foreach($this->getGraph($groupId) as $row)
      {
         $month = $row['month'];
         $total = $row['total'];
         $date = \DateTime::createFromFormat('Y-m', $month);
         $monthName = $date->format('M');
         array_push($months, $monthName);
         array_push($values, $total);
      }
      $graph["months"]= $months;
      $graph["values"] = $values;
      return $graph;
   }

   private function searchArr($arr,$val)
   {
      $foundIndex = -1;
      foreach ($arr as $index => $item) {
         if ($item['funeral_id'] == $val) {
             $foundIndex = $index;
             break; 
         }
     }     
     if ($foundIndex != -1) {
         return true;
     } else {
      return false;
     }
   }

}
