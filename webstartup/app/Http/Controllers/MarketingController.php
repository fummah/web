<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubscriberModel;
use App\Models\CampaignModel;
use App\Models\CampaignAnalysisModel;
use Redirect;
use Response;
use App\Notifications\EmailNotification;

class MarketingController extends Controller
{
    public $mysearch;
    function campaigns()
    {
        $user_id=auth()->user()->id;
        $data=$this->getMarketingDetails($user_id);
        return view("pages.campaigns",compact(['data']));
    }
     function new_campaign(Request $request)
    {
        $type=$request->mytype;
        $name=auth()->user()->name;
        $user_id=auth()->user()->id;
        $intcount=SubscriberModel::where('user_id','=',$user_id)->where('user_id','=','1')->count();
        
        return view("pages.new_campaign",compact(['name','intcount','type']));
    }
     function view_campaign(Request $request)
    {
        $campaign_id=$request->campaign_id;
        $arr=$this->getCampaign($campaign_id);
        $data=$this->getCampaignDetails($campaign_id);
        return view("pages.view_campaign",compact(['arr','data']));
    }
      function subscribers()
    {
        return view("pages.subscribers");
    }
      function subscriber_details(Request $request)
    {
        $subscriber_id=$request->subscriber_id;
        $arr=$this->subscriber_details_act($subscriber_id);
        return view("pages.subscriber_details",compact(['arr']));
    }
    function generate_content()
    {
        
        return view("pages.content_generator");
    }
    function getsubscribers()
    {
        $user_id=auth()->user()->id;
        $mysubscribers=SubscriberModel::where('user_id','=',$user_id)->where('user_id','=','1')->get();
        return $mysubscribers;
    }
    function sendToSubscribers($campaign_id,$subscriber_id,$user_id)
    {
        $ana=new CampaignAnalysisModel();
        $ana->campaign_id=$campaign_id;
        $ana->subscriber_id=$subscriber_id;
        $ana->user_id=$user_id;    
        $ana->save();
    }
    function getCampaignDetails($campaign_id)
    {
      $data['total_subscribers']=CampaignAnalysisModel::where('campaign_id','=',$campaign_id)->count();
      $data['total_impressions']=CampaignAnalysisModel::where('campaign_id','=',$campaign_id)->where('impression','=','1')->count();
      return $data;
    }
     function getMarketingDetails($user_id)
    {
      $data['total_subscribers']=SubscriberModel::where('user_id','=',$user_id)->count();
      $data['total_campaigns']=CampaignModel::where('user_id','=',$user_id)->where('campaign_type','=',"Email")->count();
       $data['total_campaigns_social']=CampaignModel::where('user_id','=',$user_id)->where('campaign_type','=',"Social Post")->count();
      return $data;
    }
    function add_campaign(Request $request)
    {
        $campaign_type=$request->campaign_type; 
        $subscribers=$request->subscribers;
        if($campaign_type=="0" && $subscribers=="")
        {
            return Redirect::back()->withErrors(['msg' => 'Please select audience']);
            die();
        }
        $user_id=auth()->user()->id;
        $obj=new CampaignModel();
        $obj->campaign_type="Email";
        $obj->campaign_name=$request->campaign_name;
        $obj->campaign_description=$request->editordata;    
        $obj->entered_by=$user_id;
        $obj->user_id=$user_id;
        $add=$obj->save();

        if($add>0)
        {
            $lastInsertId = $obj->id;
            if($campaign_type=="1")
        {
            $subscribers=$this->getsubscribers();
            foreach($subscribers as $sub)
            {
                $subscriber_id=(int)$sub["id"];                
                $this->sendToSubscribers($lastInsertId,$subscriber_id,$user_id);
                $user = SubscriberModel::find($subscriber_id);
                $hid="<a href='https://sys.webstartup.io/unsubscribe'><span style='font-size:8px'>Unsubscribe</span></a><br><img hidden src='https://sys.webstartup.io/checkreceive/$lastInsertId/$subscriber_id'/>";
        $data=[
    'data'=>$obj->campaign_description,
    'hid'=>$hid    
    ];
                $user->notify(new EmailNotification($data));
            }
        }
        else
        {
            $subscribers=$subscribers==""?[]:$subscribers;
              for($i=0;$i<count($subscribers);$i++)
            {
                $subscriber_id=(int)$subscribers[$i];
                $this->sendToSubscribers($lastInsertId,$subscriber_id,$user_id);
                $user = SubscriberModel::find($subscriber_id);
                 $hid="<a href='https://sys.webstartup.io/unsubscribe'><span style='font-size:8px'>Unsubscribe</span></a><br><img hidden src='https://sys.webstartup.io/checkreceive/$lastInsertId/$subscriber_id'/>";
        $data=[
    'data'=>$obj->campaign_description,
    'hid'=>$hid    
    ];
                $user->notify(new EmailNotification($data));
            }
        }
        

           
             return Redirect::back()->with('success','New Campaign Successfully added');
        }
        else
        {
            return Redirect::back()->withErrors(['msg' => 'Failed to add Campaign']);
        }
    }
     function add_subscriber(Request $request)
    {
         $user_id=auth()->user()->id;
        $obj=new SubscriberModel();
        $obj->first_name=$request->first_name;
        $obj->last_name=$request->last_name;
        $obj->email=$request->subscriber_email;
        $obj->contact_number=$request->contact_number;
        $obj->address=$request->subscriber_address;
        $obj->entered_by=$user_id;
        $obj->user_id=$user_id;
        $add=$obj->save();

        if($add>0)
        {
           
             return Redirect::back()->with('success','New Subscriber Successfully added');
        }
        else
        {
            return Redirect::back()->withErrors(['msg' => 'Failed to add Subscriber']);
        }
    }
    public function subscriber_details_act($subscriber_id)
    {
               
        $subscriber=SubscriberModel::find($subscriber_id);
        $emails=CampaignAnalysisModel::join('campaigns','campaigns.id','=','campaign_anlysis.campaign_id')->where('campaign_anlysis.subscriber_id','=',$subscriber_id)->where('campaigns.campaign_type','=','Email')->select(['campaigns.id', 'campaigns.campaign_name','impression','received'])->get();
               $socials=CampaignAnalysisModel::join('campaigns','campaigns.id','=','campaign_anlysis.campaign_id')->where('campaign_anlysis.subscriber_id','=',$subscriber_id)->where('campaigns.campaign_type','=','Social Post')->select(['campaigns.id', 'campaigns.campaign_name','impression','received'])->get();

             $arr=array("subscriber_id"=>$subscriber->id,"first_name"=>$subscriber->first_name,"last_name"=>$subscriber->last_name,"email"=>$subscriber->email,"contact_number"=>$subscriber->contact_number,"date_entered"=>$subscriber->date_entered,"entered_by"=>$subscriber->entered_by,"user_id"=>$subscriber->user_id,"subscribe"=>$subscriber->subscribe,"address"=>$subscriber->address,"emails"=>$emails,"socials"=>$socials);        
        return $arr;
    }
       public function getCampaign($campaign_id)
    {
               
        $campaign=CampaignModel::find($campaign_id);
        $date=date_create($campaign->date_entered);
        $date_entered=date_format($date,"D d F, Y H:i:s");
             $arr=array("campaign_id"=>$campaign->id,"campaign_name"=>$campaign->campaign_name,"campaign_type"=>$campaign->campaign_type,"date_entered"=>$date_entered,"status"=>$campaign->status,"campaign_description"=>$campaign->campaign_description);        
        return $arr;
    }
    public function getfortabledata(Request $request)
      {
        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length; // Rows display per page
        $columnIndex = $request->order[0]['column']; // Column index
        $columnName = $request->columns[$columnIndex]['data']; // Column name
        $columnSortOrder = $request->order[0]['dir']; // asc or desc
        $searchValue = $request->search['value']; // Search value
        $page=$request->page;
        $all=[];
        if($page=="subscribers")
{
       $all=$this->mysubscribers($row,$rowperpage,$columnIndex,$columnName,$columnSortOrder,$searchValue);
   }
   elseif($page=="campaigns")
   {
    $all=$this->mycampaigns($row,$rowperpage,$columnIndex,$columnName,$columnSortOrder,$searchValue);
   }
   $response = array(
  "draw" => intval($draw),
  "iTotalRecords" => $all["totalRecords"],
  "iTotalDisplayRecords" => $all["totalRecordwithFilter"],
  "aaData" => $all["data"]
);
echo json_encode($response);
}

private function mysubscribers($row,$rowperpage,$columnIndex,$columnName,$columnSortOrder,$searchValue)
    {
        $user_id=auth()->user()->id;
      $totalRecords=SubscriberModel::where('user_id','=',$user_id)->get()->count();
      $this->mysearch="%".$searchValue."%";
      if($searchValue != ''){ 
      $totalRecordwithFilter=SubscriberModel::where('user_id','=',$user_id)->where(
        function($query)
        {
            $search=$this->mysearch;
            $query->where('first_name','like',$search)->orWhere('last_name','like',$search)->orWhere('email','like',$search)->orWhere('contact_number','like',$search);
        })->get()->count();      
      $empRecords=SubscriberModel::where('user_id','=',$user_id)->where(
        function($query)
        {
            $search=$this->mysearch;
            $query->where('first_name','like',$search)->orWhere('last_name','like',$search)->orWhere('email','like',$search)->orWhere('contact_number','like',$search);
        })->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
     }

else{
  $totalRecordwithFilter=$totalRecords;
 $empRecords=SubscriberModel::where('user_id','=',$user_id)->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
}
## Fetch records
$data = array();
foreach ($empRecords as $row) {  
$id=(int)$row['id'];        
   $data[] = array( 
      "first_name"=>$row['last_name'],  
      "last_name"=>$row['first_name'],        
      "email"=>$row['email'],
      "contact_number"=>$row['contact_number'],       
      "actions"=>"<a type='button' href='#' data='$id'  class='btn btn-success btn-icon btn-sm edit-customer' title='Edit'><i class='now-ui-icons design-2_ruler-pencil'></i></a>
                  <a type='button' href='subscriber_details/$id' class='btn btn-warning btn-icon btn-sm' title='View Details'><i class='now-ui-icons design_bullet-list-67'></i></a>",     
   );
}
$all["totalRecords"]=$totalRecords;
$all["totalRecordwithFilter"]=$totalRecordwithFilter;
$all["data"]=$data;
return $all;
    }
    private function mycampaigns($row,$rowperpage,$columnIndex,$columnName,$columnSortOrder,$searchValue)
    {
        $user_id=auth()->user()->id;
            $totalRecords=CampaignModel::where('user_id','=',$user_id)->get()->count();
      $this->mysearch="%".$searchValue."%";
      if($searchValue != ''){ 
      $totalRecordwithFilter=CampaignModel::where('user_id','=',$user_id)->where(
        function($query)
        {
            $search=$this->mysearch;
            $query->where('campaign_type','like',$search)->orWhere('campaign_name','like',$search)->orWhere('campaign_description','like',$search)->orWhere('status','like',$search);
        })->get()->count();  


      $empRecords=CampaignModel::where('user_id','=',$user_id)->where(
        function($query)
        {
            $search=$this->mysearch;
            $query->where('campaign_type','like',$search)->orWhere('campaign_name','like',$search)->orWhere('campaign_description','like',$search)->orWhere('status','like',$search);
           
        })->orderBy('id', 'DESC')->offset($row)->limit($rowperpage)->get();
     }

else{
  $totalRecordwithFilter=$totalRecords;
 $empRecords=CampaignModel::where('user_id','=',$user_id)->orderBy('id', 'DESC')->offset($row)->limit($rowperpage)->get();
}
## Fetch records
$data = array();
foreach ($empRecords as $row) {  
$id=(int)$row['id']; 
$campaign_type=$row['campaign_type'];
$campaign_name=$row['campaign_name']; 
$status=$row['status'];       
   $data[] = array(             
      "campaign_name"=>"<div class='card-body p-3'><ul class='list-group'><li class='list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg'><div class='d-flex align-items-center'><div class='icon icon-shape icon-sm me-3 bg-gradient-danger shadow text-center'>      </div><div class='d-flex flex-column'><h6 class='mb-1 text-danger text-sm'><i class='now-ui-icons objects_spaceship desktop'></i> $campaign_name</h6><span class='text-xs'><span style='color:green'>$campaign_type</span> | <span style='color:purple'>$status</span></span></div></div><div class='d-flex'><a href='../view_campaign/$id'><button type='submit' class='btn btn-primary btn-round'><i class='now-ui-icons education_paper desktop'></i> View</button></a>                       
                            </div></li></ul></div>", 
   );
}
$all["totalRecords"]=$totalRecords;
$all["totalRecordwithFilter"]=$totalRecordwithFilter;
$all["data"]=$data;
return $all;
    }
}
