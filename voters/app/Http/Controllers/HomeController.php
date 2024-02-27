<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LegislationModel;
use App\Models\ElectionModel;
use App\Models\TopicModel;
use App\Models\VoteModel;
use App\Models\User;

class HomeController extends Controller
{
        /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        
		$user_id=auth()->user()->id;
		$role=auth()->user()->role;
		$total_legislations=count(LegislationModel::all());
		if($role=="Admin")
		{
			$total_votes_casted=count(VoteModel::all());
			$positive_votes=count(VoteModel::where('vote','Yes')->get());
		}
		else
		{
			
			$positive_votes=count(VoteModel::where('user_id',$user_id)->where('vote','Yes')->get());
			$total_votes_casted=count(VoteModel::where('user_id',$user_id)->get());
		}
	
		$legislations=[];		
        //$lest1=LegislationModel::offset(0)->limit(2);
		$ccount=0;
        $lest1=LegislationModel::where('id','>',0)->get()->sortDesc();
		foreach($lest1 as $lest)
		{
			if($ccount>1)
			{
				break;
			}
			$checked="--";
			$legislation_id=$lest['id'];
			$votes = VoteModel::where('user_id', $user_id)->where('legislation_id', $legislation_id)->get();
			if(count($votes)>0)
			{
				$checked=$votes[0]['vote'];
			}
			$arr=array("id"=>$legislation_id,"legislation_name"=>$lest['legislation_name'],"legislation_description"=>$lest['legislation_description'],"checked"=>$checked);

					array_push($legislations,$arr);
					$ccount++;
		}
		
		$arr=array('total_legislations'=>$total_legislations,'total_votes_casted'=>$total_votes_casted,'positive_votes'=>$positive_votes);
        if($role=="Admin")
		{
		return view('pages.dashboard',compact(['arr','legislations']));
		}
		else
		{		
			$alllegislations=$this->Legislations();
			$allelections=$this->Elections();
			$alltopics=$this->Topics();
			return view('pages.user-dashboard',compact(['arr','legislations','alllegislations','allelections','alltopics']));
		}
    }
	
	function Legislations()
    {
		$user_id=auth()->user()->id;
		$votersclas=new VotersController();
		$legislations=[];
		$search_term="";
        $lest1=LegislationModel::all()->sortDesc();
		foreach($lest1 as $lest)
		{
		array_push($legislations,$votersclas->legiCalc($lest,$user_id));

		}
		return $legislations;
	}
	
	function Elections()
    {
		$user_id=auth()->user()->id;
		$votersclas=new VotersController();
		$elections=[];
		$search_term="";
        $elect1=ElectionModel::all()->sortDesc();
		foreach($elect1 as $elect)
		{
		array_push($elections,$votersclas->electCalc($elect,$user_id));

		}

        return $elections;
    }

	function Topics()
    {
		$user_id=auth()->user()->id;
		$votersclas=new VotersController();
		$topics=[];
		$search_term="";
        $topic1=TopicModel::all()->sortDesc();
		foreach($topic1 as $topic)
		{
		array_push($topics,$votersclas->topicCalc($topic,$user_id));

		}

        return $topics;
    }

}
