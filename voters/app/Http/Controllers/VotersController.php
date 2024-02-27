<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Notification;
use Response;
use App\Models\LegislationModel;
use App\Models\VoteModel;
use App\Models\User;
use App\Models\ElectionModel;
use App\Models\ElectionVoteModel;
use App\Models\TopicModel;
use App\Models\TopicVoteModel;
use App\Jobs\EmailsJob;
//use Mail;
//use App\Mail\MailNotify;
use App\Notifications\EmailNotification;
use Artisan;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Console\Command;



class VotersController extends Controller
{
        function showLegislations()
    {
		$user_id=auth()->user()->id;
		$legislations=[];
		$search_term="";
        //$lest1=LegislationModel::all()->sortDesc();
        $lest1=LegislationModel::orderBy('id', 'DESC')->paginate(10);
		foreach($lest1 as $lest)
		{
		array_push($legislations,$this->legiCalc($lest,$user_id));

		}

        return view('voters.legislation',compact(['legislations','search_term','lest1']));
    }
	    function showElections()
    {
		$user_id=auth()->user()->id;
		$elections=[];
		$search_term="";
        $elect1=ElectionModel::orderBy('id', 'DESC')->paginate(10);
		foreach($elect1 as $elect)
		{
		array_push($elections,$this->electCalc($elect,$user_id));

		}

        return view('voters.election',compact(['elections','search_term','elect1']));
    }

		    function showTopics()
    {
		$user_id=auth()->user()->id;
		$topics=[];
		$search_term="";
        $topic1=TopicModel::orderBy('id', 'DESC')->paginate(10);
		foreach($topic1 as $topic)
		{
		array_push($topics,$this->topicCalc($topic,$user_id));

		}

        return view('voters.topic',compact(['topics','search_term','topic1']));
    }

	function showPublicLegislations()
    {

        $legislations=LegislationModel::paginate(10);

        return view('auth.public-legislations',compact('legislations'));
    }
	  function showSearchedLegislations(Request $request)
    {
		$search_term=$request->search_term;
        $user_id=auth()->user()->id;
		$legislations=[];
        $lest1=LegislationModel::where('legislation_name','like','%'.$search_term.'%')->orderBy('id', 'DESC')->paginate(10);
		foreach($lest1 as $lest)
		{

					array_push($legislations,$this->legiCalc($lest,$user_id));
		}
        return view('voters.legislation',compact(['legislations','search_term','lest1']));
    }
	 function showSearchedElections(Request $request)
    {
		$search_term=$request->search_term;
        $user_id=auth()->user()->id;
		$elections=[];
        $elect1=ElectionModel::where('election_name','like','%'.$search_term.'%')->orderBy('id', 'DESC')->paginate(10);
		foreach($elect1 as $elect)
		{

					array_push($elections,$this->electCalc($elect,$user_id));
		}
        return view('voters.election',compact(['elections','search_term','elect1']));
    }
	 function showSearchedTopics(Request $request)
    {
		$search_term=$request->search_term;
        $user_id=auth()->user()->id;
		$topics=[];
        $topic1=TopicModel::where('topic_name','like','%'.$search_term.'%')->orderBy('id', 'DESC')->paginate(10);
		foreach($topic1 as $topic)
		{

					array_push($topics,$this->topicCalc($topic,$user_id));
		}
        return view('voters.topic',compact(['topics','search_term','topic1']));
    }
	function legiCalc($lest,$user_id)
	{
		$legislation_id=$lest['id'];
			$votes = VoteModel::where('user_id', $user_id)->where('legislation_id', $legislation_id)->get();
			$checked="--";
				$state=auth()->user()->country;
		$role=auth()->user()->role;
		$congressional=auth()->user()->congressional;
			$yesvotes = $role=="Admin"?VoteModel::where('legislation_id', $legislation_id)->where('vote', 'Yes')->get():VoteModel::join('users','users.id','=','votes.user_id')->where('legislation_id', $legislation_id)->where('vote', 'Yes')->where('country', $state)->where('congressional',$congressional)->get();
			$novotes = $role=="Admin"?VoteModel::where('legislation_id', $legislation_id)->where('vote', 'No')->get():VoteModel::join('users','users.id','=','votes.user_id')->where('legislation_id', $legislation_id)->where('vote', 'No')->where('country', $state)->where('congressional',$congressional)->get();
			$proxyvotes = $role=="Admin"?VoteModel::where('legislation_id', $legislation_id)->where('vote', 'Proxy')->get():VoteModel::join('users','users.id','=','votes.user_id')->where('legislation_id', $legislation_id)->where('vote', 'Proxy')->where('country', $state)->where('congressional',$congressional)->get();

			$yes=count($yesvotes);
			$no=count($novotes);
			$proxy=count($proxyvotes);
			//$total=$yes1+$no1+$proxy1;
			//$yes=$total>0?round(($yes1/$total)*100):0;
			//$no=$total>0?round(($no1/$total)*100):0;
			//$proxy=$total>0?round(($proxy1/$total)*100):0;

			if(count($votes)>0)
			{
				$checked=$votes[0]['vote'];
			}
			$arr=array("id"=>$legislation_id,"legislation_name"=>$lest['legislation_name'],"legislation_description"=>$lest['legislation_description'],"vote_date"=>$lest['vote_date'],"translation"=>$lest['translation'],"checked"=>$checked,"yes"=>$yes,"no"=>$no,"proxy"=>$proxy);
return $arr;
	}
		function electCalc($elect,$user_id)
	{
		$election_id=$elect['id'];
			$votes = ElectionVoteModel::where('user_id', $user_id)->where('election_id', $election_id)->get();
			$checked="--";
			$state=auth()->user()->country;
		$role=auth()->user()->role;
		$congressional=auth()->user()->congressional;
			$yesvotes = $role=="Admin"?ElectionVoteModel::where('election_id', $election_id)->where('vote', 'Yes')->get():ElectionVoteModel::join('users','users.id','=','election_votes.user_id')->where('election_id', $election_id)->where('vote', 'Yes')->where('country', $state)->where('congressional',$congressional)->get();
			$novotes = $role=="Admin"?ElectionVoteModel::where('election_id', $election_id)->where('vote', 'No')->get():ElectionVoteModel::join('users','users.id','=','election_votes.user_id')->where('election_id', $election_id)->where('vote', 'No')->where('country', $state)->where('congressional',$congressional)->get();
			$proxyvotes = $role=="Admin"?ElectionVoteModel::where('election_id', $election_id)->where('vote', 'Proxy')->get():ElectionVoteModel::join('users','users.id','=','election_votes.user_id')->where('election_id', $election_id)->where('vote', 'Proxy')->where('country', $state)->where('congressional',$congressional)->get();

			$yes=count($yesvotes);
			$no=count($novotes);
			$proxy=count($proxyvotes);
			//$total=$yes1+$no1+$proxy1;
			//$yes=$total>0?round(($yes1/$total)*100):0;
			//$no=$total>0?round(($no1/$total)*100):0;
			//$proxy=$total>0?round(($proxy1/$total)*100):0;

			if(count($votes)>0)
			{
				$checked=$votes[0]['vote'];
			}
			$arr=array("id"=>$election_id,"election_name"=>$elect['election_name'],"election_description"=>$elect['election_description'],"translation"=>$elect['translation'],"checked"=>$checked,"yes"=>$yes,"no"=>$no,"proxy"=>$proxy);
return $arr;
	}

	function topicCalc($topic,$user_id)
	{
		$topic_id=$topic['id'];
			$votes = TopicVoteModel::where('user_id', $user_id)->where('topic_id', $topic_id)->get();
			$checked="--";
			$state=auth()->user()->country;
		$role=auth()->user()->role;
		$congressional=auth()->user()->congressional;
			$yesvotes = $role=="Admin"?TopicVoteModel::where('topic_id', $topic_id)->where('vote', 'Yes')->get():TopicVoteModel::join('users','users.id','=','topic_votes.user_id')->where('topic_id', $topic_id)->where('vote', 'Yes')->where('country', $state)->where('congressional',$congressional)->get();
			$novotes = $role=="Admin"?TopicVoteModel::where('topic_id', $topic_id)->where('vote', 'No')->get():TopicVoteModel::join('users','users.id','=','topic_votes.user_id')->where('topic_id', $topic_id)->where('vote', 'No')->where('country', $state)->where('congressional',$congressional)->get();
			$proxyvotes = $role=="Admin"?TopicVoteModel::where('topic_id', $topic_id)->where('vote', 'Proxy')->get():TopicVoteModel::join('users','users.id','=','topic_votes.user_id')->where('topic_id', $topic_id)->where('vote', 'Proxy')->where('country', $state)->where('congressional',$congressional)->get();

		$yes=count($yesvotes);
			$no=count($novotes);
			$proxy=count($proxyvotes);
			//$total=$yes1+$no1+$proxy1;
			//$yes=$total>0?round(($yes1/$total)*100):0;
			//$no=$total>0?round(($no1/$total)*100):0;
			//$proxy=$total>0?round(($proxy1/$total)*100):0;

			if(count($votes)>0)
			{
				$checked=$votes[0]['vote'];
			}
			$arr=array("id"=>$topic_id,"topic_name"=>$topic['topic_name'],"topic_description"=>$topic['topic_description'],"translation"=>$topic['translation'],"checked"=>$checked,"yes"=>$yes,"no"=>$no,"proxy"=>$proxy);
return $arr;
	}
	function singleCategory(Request $request)
    {

		$user_id=$role=auth()->user()->id;
		$state=auth()->user()->country;
		$role=auth()->user()->role;
		$congressional=auth()->user()->congressional;
       $category_name=ucfirst($request->category_name);
	   $id=$request->id;
	   $content_type=$request->content_type;
	   if($category_name=="Topic"){	 	
	   $single =TopicModel::where('id', $id)->first();
	   $getvote =TopicVoteModel::where('topic_id', $id)->where('user_id', $user_id)->first();
	   $vote=$getvote==true?$getvote['vote']:"";
	$yesvotes = $role=="Admin"?TopicVoteModel::where('topic_id', $id)->where('vote', 'Yes')->get():TopicVoteModel::join('users','users.id','=','topic_votes.user_id')->where('topic_id', $id)->where('vote', 'Yes')->where('country', $state)->where('congressional',$congressional)->get();
			$novotes = $role=="Admin"?TopicVoteModel::where('topic_id', $id)->where('vote', 'No')->get():TopicVoteModel::join('users','users.id','=','topic_votes.user_id')->where('topic_id', $id)->where('vote', 'No')->where('country', $state)->where('congressional',$congressional)->get();
			$proxyvotes = $role=="Admin"?TopicVoteModel::where('topic_id', $id)->where('vote', 'Proxy')->get():TopicVoteModel::join('users','users.id','=','topic_votes.user_id')->where('topic_id', $id)->where('vote', 'Proxy')->where('country', $state)->where('congressional',$congressional)->get();

		$yes=count($yesvotes);
			$no=count($novotes);
			$proxy=count($proxyvotes);
			 $arr=array("id"=>$single['id'],"name"=>$single['topic_name'],"description"=>$single['topic_description'],"vote_date"=>$single['vote_date'],"translation"=>$single['translation'],"vote"=>$vote,"yes"=>$yes,"no"=>$no,"proxy"=>$proxy,"page"=>$request->category_name,"typep"=>$request->content_type);

	   }
	   else if($category_name=="Election"){
		   	   $single =ElectionModel::where('id', $id)->first();
	   $getvote =ElectionVoteModel::where('election_id', $id)->where('user_id', $user_id)->first();
	   $vote=$getvote==true?$getvote['vote']:"";
				$yesvotes = $role=="Admin"?ElectionVoteModel::where('election_id', $id)->where('vote', 'Yes')->get():ElectionVoteModel::join('users','users.id','=','election_votes.user_id')->where('election_id', $id)->where('vote', 'Yes')->where('country', $state)->where('congressional',$congressional)->get();
			$novotes = $role=="Admin"?ElectionVoteModel::where('election_id', $id)->where('vote', 'No')->get():ElectionVoteModel::join('users','users.id','=','election_votes.user_id')->where('election_id', $id)->where('vote', 'No')->where('country', $state)->where('congressional',$congressional)->get();
			$proxyvotes = $role=="Admin"?ElectionVoteModel::where('election_id', $id)->where('vote', 'Proxy')->get():ElectionVoteModel::join('users','users.id','=','election_votes.user_id')->where('election_id', $id)->where('vote', 'Proxy')->where('country', $state)->where('congressional',$congressional)->get();

			$yes=count($yesvotes);
			$no=count($novotes);
			$proxy=count($proxyvotes);
			 $arr=array("id"=>$single['id'],"name"=>$single['election_name'],"description"=>$single['election_description'],"vote_date"=>$single['vote_date'],"translation"=>$single['translation'],"vote"=>$vote,"yes"=>$yes,"no"=>$no,"proxy"=>$proxy,"page"=>$request->category_name,"typep"=>$request->content_type);

	   }
	   else{
		   	   $single =LegislationModel::where('id', $id)->first();
	   $getvote =VoteModel::where('legislation_id', $id)->where('user_id', $user_id)->first();
	   $vote=$getvote==true?$getvote['vote']:"";
		$yesvotes = $role=="Admin"?VoteModel::where('legislation_id', $id)->where('vote', 'Yes')->get():VoteModel::join('users','users.id','=','votes.user_id')->where('legislation_id', $id)->where('vote', 'Yes')->where('country', $state)->where('congressional',$congressional)->get();
			$novotes = $role=="Admin"?VoteModel::where('legislation_id', $id)->where('vote', 'No')->get():VoteModel::join('users','users.id','=','votes.user_id')->where('legislation_id', $id)->where('vote', 'No')->where('country', $state)->where('congressional',$congressional)->get();
			$proxyvotes = $role=="Admin"?VoteModel::where('legislation_id', $id)->where('vote', 'Proxy')->get():VoteModel::join('users','users.id','=','votes.user_id')->where('legislation_id', $id)->where('vote', 'Proxy')->where('country', $state)->where('congressional',$congressional)->get();

			$yes=count($yesvotes);
			$no=count($novotes);
			$proxy=count($proxyvotes);
		    $arr=array("id"=>$single['id'],"name"=>$single['legislation_name'],"description"=>$single['legislation_description'],"vote_date"=>$single['vote_date'],"translation"=>$single['translation'],"vote"=>$vote,"yes"=>$yes,"no"=>$no,"proxy"=>$proxy,"page"=>$request->category_name,"typep"=>$request->content_type);

	   }
			
	 // print_r($arr);
return view('pages.details',compact(['arr','category_name','content_type']));

    }
	 function createLegislation(Request $request)
    {

         $attributes = $request->validate([
            'username' => 'required|max:255|min:2',
            'legislation_name' => 'required|max:255|min:2',
            'legislation_description' => 'required|min:3'
        ]);

		$leg=new LegislationModel;
		$leg->legislation_name=$request->legislation_name;
		$leg->legislation_description=$request->legislation_description;
		$leg->translation=$request->translation;
		$leg->vote_date=$request->vote_date;
		$leg->entered_by=$request->username;
		$leg->save();
$this->basic_email("Legislation",$request->legislation_name);
		return back()->with('succes', 'New Legislation succesfully created');
    }
		 function createElection(Request $request)
    {

         $attributes = $request->validate([
            'username' => 'required|max:255|min:2',
            'election_name' => 'required|max:255|min:2',
            'election_description' => 'required|min:3'
        ]);

		$leg=new ElectionModel;
		$leg->election_name=$request->election_name;
		$leg->election_description=$request->election_description;
		$leg->translation=$request->translation;
		$leg->entered_by=$request->username;
		$leg->save();
$this->basic_email("Election",$request->election_name);
		return back()->with('succes', 'New Election succesfully created');
    }
		 function createTopic(Request $request)
    {

         $attributes = $request->validate([
            'username' => 'required|max:255|min:2',
            'topic_name' => 'required|max:255|min:2',
            'topic_description' => 'required|min:3'
        ]);

		$leg=new TopicModel;
		$leg->topic_name=$request->topic_name;
		$leg->topic_description=$request->topic_description;
		$leg->translation=$request->translation;
		$leg->entered_by=$request->username;
		$leg->save();
$this->basic_email("Topic",$request->topic_name);
		return back()->with('succes', 'New Topic succesfully created');
    }
	function voteNow(Request $request)
    {
	$user_id=auth()->user()->id;
	$legislation_id=(int)$request->legislation_id;

	$oldvotes = VoteModel::where('user_id', $user_id)->where('legislation_id', $legislation_id)->get();
	foreach($oldvotes as $oldvote)
	{
	$jj = VoteModel::find($oldvote['id']);
$jj->delete();
	}

		$vote=new VoteModel;
		$vote->user_id=$user_id;
		$vote->legislation_id=(int)$request->legislation_id;
		$vote->vote=$request->vote;
		$val=$vote->save();
		return ($val);

    }
	function voteElectionNow(Request $request)
    {
	$user_id=auth()->user()->id;
	$election_id=(int)$request->election_id;

	$oldvotes = ElectionVoteModel::where('user_id', $user_id)->where('election_id', $election_id)->get();
	foreach($oldvotes as $oldvote)
	{
	$jj = ElectionVoteModel::find($oldvote['id']);
$jj->delete();
	}

		$vote=new ElectionVoteModel;
		$vote->user_id=$user_id;
		$vote->election_id=(int)$request->election_id;
		$vote->vote=$request->vote;
		$val=$vote->save();
		return ($val);

    }
		function voteTopicNow(Request $request)
    {
	$user_id=auth()->user()->id;
	$topic_id=(int)$request->topic_id;

	$oldvotes = TopicVoteModel::where('user_id', $user_id)->where('topic_id', $topic_id)->get();
	foreach($oldvotes as $oldvote)
	{
	$jj = TopicVoteModel::find($oldvote['id']);
$jj->delete();
	}

		$vote=new TopicVoteModel;
		$vote->user_id=$user_id;
		$vote->topic_id=(int)$request->topic_id;
		$vote->vote=$request->vote;
		$val=$vote->save();
		return ($val);

    }

	function whoVoted(Request $request)
    {
	$user_id=auth()->user()->id;
	$role=auth()->user()->role;
	$state=auth()->user()->country;
	$congressional=auth()->user()->congressional;
	$id=(int)$request->id;
	$vote=$request->vote;
	$page=$request->page;
	if($page=="legislation")
	{
if($role=="Admin")
{
	$arr = VoteModel::join('users','users.id','=','votes.user_id')->where('legislation_id', $id)->where('vote', $vote)->get();
}
else
{
	$arr = VoteModel::join('users','users.id','=','votes.user_id')->where('legislation_id', $id)->where('vote', $vote)->where('country',$state)->where('congressional',$congressional)->get();
}
	}

		if($page=="election")
	{
if($role=="Admin")
{
	$arr = ElectionVoteModel::join('users','users.id','=','election_votes.user_id')->where('election_id', $id)->where('vote', $vote)->get();
}
else
{
	$arr = ElectionVoteModel::join('users','users.id','=','election_votes.user_id')->where('election_id', $id)->where('vote', $vote)->where('country',$state)->where('congressional',$congressional)->get();
}
	}

		if($page=="topic")
	{
if($role=="Admin")
{
	$arr = TopicVoteModel::join('users','users.id','=','topic_votes.user_id')->where('topic_id', $id)->where('vote', $vote)->get();
}
else
{
	$arr = TopicVoteModel::join('users','users.id','=','topic_votes.user_id')->where('topic_id', $id)->where('vote', $vote)->where('country',$state)->where('congressional',$congressional)->get();
}
	}
	$xarr=[];
	foreach($arr as $row)

	{

		$inarr=array("firstname"=>$row['firstname'],"lastname"=>$row['lastname'],"date_entered"=>$row['date_entered'],"state"=>$row['country']." - ".$row['congressional']);
		array_push($xarr,$inarr);
	}
		return $xarr;

    }



	public function basic_email($category="Legislation",$category_name="") {
			$data=[
	'category'=>'New '.$category.' was added, you may login and check',
	'category_name'=>'Description : '.$category_name.'.'
	];

	try{
	$users=User::chunk(10,function($users) use($data){
		$recepients=$users->pluck('email');
        //dispatch(new EmailsJob($data));
        Notification::route('mail',$recepients)->notify(new EmailNotification($data));
	});
		//Mail::to('tendai@medclaimassistz.co.za')->send(new MailNotify($data));
		//return Response::json(['Great checkyourmail box']);
		return 0;
	}
	catch(Exception $ex)
	{
		return Response::json(['Sorry something wrong '.$ex]);
	}
   }

    public function editCategory(Request $request) {
	$id=$request->id;
	$page=$request->page;
	$typep=$request->typep;
	$dname=$request->dname;
	$dvote_date=$request->dvote_date;
	$ddescription=$request->ddescription;
	if($page=="topic")
	{
	$cat=TopicModel::find($id);
	$cat->topic_name=$request->dname;
	if($typep=="writtenas")
	{
	$cat->topic_description=$request->ddescription;
	}
	else{
		$cat->translation=$request->ddescription;
	}
	}
		elseif($page=="legislation")
	{
	$cat=LegislationModel::find($id);
	$cat->legislation_name=$request->dname;
	$cat->vote_date=$request->dvote_date;
	if($typep=="writtenas")
	{
	$cat->legislation_description=$request->ddescription;
	}
	else{
		$cat->translation=$request->ddescription;
	}
	}
			else
	{
	$cat=ElectionModel::find($id);
	$cat->election_name=$request->dname;
	if($typep=="writtenas")
	{
	$cat->election_description=$request->ddescription;
	}
	else{
		$cat->translation=$request->ddescription;
	}
	}
	$val=$cat->save();
		return ($val);
        
    }
	 public function deleteCategory(Request $request) {
	$id=$request->id;
	$page=$request->page;
	if($page=="topic")
	{
	$cat=TopicModel::where('id',$id);	
	$del=TopicVoteModel::where('topic_id',$id);	
	$del->delete();
	}
	elseif($page=="legislation")
	{
	$cat=LegislationModel::where('id',$id);
	$del=VoteModel::where('legislation_id',$id);		
	$del->delete();
	}
	else{
		$cat=ElectionModel::where('id',$id);
		$del=ElectionVoteModel::where('election_id',$id);		
	$del->delete();
	}
	$val=$cat->delete();
	return ($val);
	 }
}
