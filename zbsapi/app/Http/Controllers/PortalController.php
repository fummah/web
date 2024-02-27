<?php

namespace App\Http\Controllers;
use App\Models\ZbsMembers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Response;
class PortalController extends Controller
{
    protected $member_id;

    public function getPortalDetails(Request $request)
    {
        $this->member_id=$request->member_id;
        $member_details = ZbsMembers::join('locations','locations.location_id','=','members.location_id')->join('groups','groups.group_id','=','locations.group_id')->where('member_id', $this->member_id)->first();
        $group_id=(int)$member_details["group_id"];
        $parent_groups=$member_details["parent_groups"];
		$funeral=$this->getLatestFuneral($parent_groups);
        $funeralname=$funeral["funeral_name"];
        $funeral_id=(int)$funeral["funeral_id"];
        $first_name=$member_details["first_name"];
        $last_name=$member_details["last_name"];
        $group="Group ".$member_details["group_name"];
        $branch_id=(int)$member_details["location_id"];
        $contact_number=$this->formatPhone($member_details["contact_number"]);
        $funeralamounts=$this->getFuneralAmounts();
        $data=array("member_id"=>$this->member_id,"first_name"=>$first_name,"last_name"=>$last_name,"contact_number"=>$contact_number,"group"=>$group,"funeralname"=>$funeralname,"amount"=>$funeral["amount_paid"],"status"=>$funeral["status"],"funeralamounts"=>$funeralamounts,"dependencies"=>$this->getDependencies(),"branches"=>$this->getCoordinators($branch_id),"paidlast"=>$this->paidLastFuneral($funeral_id));
        //print_r($data);
        return view("portal.portal",compact('data'));
    }
    private function getLatestFuneral($parent_groups)
    {
		$stts = explode(",", $parent_groups);
        $getfuneral=DB::table("funerals")->select("funerals.funeral_id","funerals.funeral_name","funerals.amount_paid","funerals.status")->whereIn("group_id",$stts)->orderBy('funeral_id', 'desc')->first();
        $getfuneral=json_decode(json_encode($getfuneral), true);
        return $getfuneral;
}
    private function paidLastFuneral($funeral_id)
    {
        $paidLast=DB::table("register")->select("register.status")->where("member_id","=",$this->member_id)->where("funeral_id","=",$funeral_id)->first();
        $paidLast=json_decode(json_encode($paidLast), true);
        return $paidLast;
    }
    private function getDependencies()
    {
        $dependencies=DB::table("dependencies")->select("dependencies.first_name","dependencies.surname","dependencies.status")->orderBy('status', 'desc')->where("member_id","=",$this->member_id)->orderBy('status', 'asc')->get();
        return json_decode(json_encode($dependencies), true);
    }
    private function getCoordinators($branch_id)
    {
        $coordinators=DB::table("users")->join('locations', 'users.location_id', '=', 'locations.location_id')->select("users.first_name","users.last_name","users.contact_number","locations.location_name")->where("users.location_id","=",$branch_id)->where("users.status","=",1)->get();
        return json_decode(json_encode($coordinators), true);
    }
    private function getFuneralAmounts()
    {
        $getfuneral=DB::select("SELECT SUM(case when k.status=:status then p.amount_paid else 0 end) as tot_amount,COUNT(*) as tot_count FROM `register` as k INNER JOIN funerals as p ON k.funeral_id=p.funeral_id WHERE k.member_id=:member_id",['member_id' => $this->member_id,'status' => "paid"]);
        return json_decode(json_encode($getfuneral), true);
    }
    public function getFuneralTotals()
    {
        $date = date("Y-m-d");
        $date_from = date('Y-m-01', strtotime($date. '  -5 months'));
        $totalfunerals = DB::select("SELECT DATE_FORMAT(date_entered,'%Y-%m') as months,COUNT(*) as totals FROM `funerals` WHERE date_entered > :date_entered GROUP BY DATE_FORMAT(date_entered,'%Y-%m')",['date_entered' => $date_from]);
        return Response::json($totalfunerals);
    }
    public function getOtherMembers(Request $request)
    {
        $keyword=$request->keyword;
        //$getothermembers = DB::select("SELECT member_id,first_name,last_name,contact_number FROM `members` WHERE first_name LIKE :myword OR last_name LIKE :myword OR contact_number LIKE :myword OR CONCAT(first_name,' ', last_name) LIKE :myword OR CONCAT(last_name,' ', first_name) LIKE :myword LIMIT 5",['myword' => $keyword]);
        $getothermembers = DB::table('members')
            ->select('members.member_id','members.first_name','members.last_name','members.contact_number')
            ->where('members.first_name', 'like', '%' . $keyword . '%')
            ->orWhere('members.last_name', 'like', '%' . $keyword . '%')
            ->orWhere('members.contact_number', 'like', '%' . $keyword . '%')
            ->orWhere(DB::raw('CONCAT(members.first_name,\' \', members.last_name)'), 'like', '%' . $keyword . '%')
            ->orWhere(DB::raw('CONCAT(members.last_name,\' \', members.first_name)'), 'like', '%' . $keyword . '%')
            ->limit(5)
            ->get();
        return Response::json($getothermembers);
    }
    public function getPayments(Request $request)
    {
        $member_id=$request->member_id;
        $start_from=$request->start_from;
        $totalpayments = DB::select("SELECT b.funeral_name,a.entered_by,a.status,b.funeral_id,a.date_entered,b.amount_paid FROM `register` as a INNER JOIN funerals as b ON a.funeral_id=b.funeral_id WHERE a.member_id=:member_id ORDER BY funeral_id DESC LIMIT :start_from,10",['start_from' => $start_from,"member_id"=>$member_id]);
        return Response::json($totalpayments);
    }
    private function formatPhone($input)
    {
        return substr($input, 0, 3)." ".substr($input, 3, 2)  . " " . substr($input, -7, -4) . " " . substr($input, -4);

    }
}
