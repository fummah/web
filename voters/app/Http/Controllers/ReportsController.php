<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\LegislationModel;
use App\Models\VoteModel;
use App\Models\User;
use App\Models\ElectionModel;
use App\Models\ElectionVoteModel;
use App\Models\TopicModel;
use App\Models\TopicVoteModel;
use Illuminate\Support\Facades\DB;
use Response;

class ReportsController extends Controller
{
    //
	public function show()	
	{
		$states=User::where('id','>',1)->distinct()->get(['country']);		
		$congressional=User::where('id','>',1)->distinct()->get(['congressional']);
		return view('pages.reports',compact(['states','congressional']));
		
	}	
	public function searched(Request $request)	
	{
		$category=$request->category;
		$keyword=$request->keyword;
		if($category=="legislation")
		{
		$getlists = DB::table('legislation')
            ->select('legislation.id','legislation.legislation_name as name','legislation.legislation_description as description')
            ->where('legislation.legislation_name', 'like', '%' . $keyword . '%')
            ->orWhere('legislation.legislation_description', 'like', '%' . $keyword . '%')
            ->limit(2)
            ->get();
		}
		elseif($category=="elections")
		{
			$getlists = DB::table('elections')
           ->select('elections.id','elections.election_name as name','elections.election_description as description')
            ->where('elections.election_name', 'like', '%' . $keyword . '%')
            ->orWhere('elections.election_description', 'like', '%' . $keyword . '%')
            ->limit(2)
            ->get();
		}
		else
		{
			$getlists = DB::table('topics')
            ->select('topics.id','topics.topic_name as name','topics.topic_description as description')
            ->where('topics.topic_name', 'like', '%' . $keyword . '%')
            ->orWhere('topics.topic_description', 'like', '%' . $keyword . '%')
            ->limit(2)
            ->get();
		}
        return Response::json($getlists);
		
	}
	public function analysis(Request $request)	
	{
		$dat1=$request->dat1;
		$dat2=$request->dat2." 23:59:59";
		$category=$request->category;
		$searched_id=(int)$request->searched_id;
		$state=$request->state;
		$congressional=$request->congressional;
			$votes_table="";
		$join_field="";
		if($category=="legislation")
		{
			$votes_table="votes";
			$join_field="a.legislation_id";
		}
		elseif($category=="elections")
		{
			$votes_table="election_votes";
			$join_field="a.election_id";
		}	
		elseif($category=="topics")
		{
			$votes_table="election_votes";
			$join_field="a.election_id";
		}
		if(!empty($category))
		{
		$condition="a.date_entered>='".$dat1."' AND a.date_entered<='".$dat2."'";
		$searched_id_con=$searched_id>0?" AND $join_field=".$searched_id:"";
		$state_con=!empty($state)?" AND country='".$state."'":"";
		$congressional_con=!empty($congressional)?" AND congressional='".$congressional."'":"";
		$condition.=$searched_id_con.$state_con.$congressional_con;			
		$sql="SELECT DISTINCT c.id,c.firstname,c.lastname FROM $votes_table as a INNER JOIN $category as b ON $join_field=b.id INNER JOIN users as c ON a.user_id=c.id WHERE $condition";
		}
		else
		{
			$condition="c.created_at>='".$dat1."' AND c.created_at<='".$dat2."'";
			$state_con=!empty($state)?" AND country='".$state."'":"";
		$congressional_con=!empty($congressional)?" AND congressional='".$congressional."'":"";
		$condition.=$state_con.$congressional_con;	
			$sql="SELECT DISTINCT c.id,c.firstname,c.lastname FROM users as c WHERE $condition";
		}
		$getlists = DB::select($sql);
		return json_decode(json_encode($getlists), true);
	}
	public function trends(Request $request)	
	{
		$category=$request->trend;
		$txt="votes";
		if($category=="topics")
		{
			$txt="topic_votes";
		}
		elseif($category=="elections")
		{
			$txt="election_votes";
		}
		$date = date("Y-m-d");
        $date_from = date('Y-m-01', strtotime($date. '  -5 months'));
        $totalfunerals = DB::select("SELECT DATE_FORMAT(date_entered,'%Y-%m') as months,COUNT(*) as totals FROM $txt WHERE date_entered > :date_entered GROUP BY DATE_FORMAT(date_entered,'%Y-%m')",['date_entered' => $date_from]);
        return Response::json($totalfunerals);
		
	}
	 
}
