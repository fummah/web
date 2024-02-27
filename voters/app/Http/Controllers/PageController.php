<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\VoteModel;

class PageController extends Controller
{
    /**
     * Display all the static pages when authenticated
     *
     * @param string $page
     * @return \Illuminate\View\View
     */
    public function index(string $page)
    {
        if (view()->exists("pages.{$page}")) {
            return view("pages.{$page}");
        }

        return abort(404);
    }
    public function userManagement()
    {
       $userslist1=User::paginate(10);
	   $count = User::count();
	   $users=[];
	   $search_term="";
	   foreach($userslist1 as $userslist)
	   {		 
		  	
		array_push($users,$this->userCalc($userslist));
	   }
        return view("pages.user-management",compact(['users','search_term','userslist1','count']));
    }
	  function searchUser(Request $request)
    {
		$count = User::count();
		$search_term=$request->search_term;	
        $users=[];		
        $userslist1=User::where('username','like','%'.$search_term.'%')->orderBy('id', 'DESC')->paginate(10);
  foreach($userslist1 as $userslist)
	   {		 
		  	
		array_push($users,$this->userCalc($userslist));
	   }
        return view("pages.user-management",compact(['users','search_term','userslist1','count']));
    }
	function userCalc($userslist)
	{
		$user_id= $userslist['id'];
		  $username= $userslist['username'];
		  $firstname= $userslist['firstname'];
		  $lastname= $userslist['lastname'];
		  $role= $userslist['role'];
		  $updated_at= $userslist['updated_at'];
		  $status= $userslist['status'];
		  $email= $userslist['email'];
		  $postal= $userslist['postal'];
		  $city= $userslist['city'];
		  $address= $userslist['address'];
		  $country= $userslist['country'];
		  $congressional= $userslist['congressional'];
		  $votes=count(VoteModel::where('user_id', $user_id)->get());
	$arr=array("user_id"=>$user_id,"username"=>$username,"firstname"=>$firstname,"lastname"=>$lastname,"email"=>$email,"address"=>$address,"postal"=>$postal,"country"=>$country,"city"=>$city,"congressional"=>$congressional,"role"=>$role,"created_at"=>$updated_at,"votes"=>$votes,"status"=>$status);
	
	return $arr;
	}
    public function vr()
    {
        return view("pages.virtual-reality");
    }

    public function rtl()
    {
        return view("pages.rtl");
    }

    public function profile()
    {
        return view("pages.profile-static");
    }

    public function signin()
    {
        return view("pages.sign-in-static");
    }

    public function signup()
    {
        return view("pages.sign-up-static");
    }
}
