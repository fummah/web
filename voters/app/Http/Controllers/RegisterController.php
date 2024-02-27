<?php

namespace App\Http\Controllers;

// use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EmailNotification;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store()
    {
		try{
        $attributes = request()->validate([
            'username' => 'required|max:255|min:2',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:6|max:255',
            'terms' => 'required',
			'firstname'=>'required|max:255|min:2',
			'lastname'=>'required|max:255|min:2',
			'address'=>'max:255',
			'city'=>'',
			'country'=>'',
			'postal'=>'',
			'congressional'=>''			
        ]);
        $user = User::create($attributes);
        auth()->login($user);
		$recepients=request()->email;
				$data=[
	'category'=>'New Account successfully created ('.$recepients.').',
	'category_name'=>'Click button below to login'
	];
		Notification::route('mail',$recepients)->notify(new EmailNotification($data));

        return redirect('/dashboard')->with('succes', 'Profile succesfully created');
		}
		catch(Exception $ex)
		{
			return redirect('/register')->with('fail', 'There is an error : '.$ex->Message());
		}
    }
	public function creteInternalUser()
    {
		try{
        $attributes = request()->validate([
            'username' => 'required|max:255|min:2',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:6|max:255',
            //'terms' => 'required',
			'firstname'=>'required|max:255|min:2',
			'lastname'=>'required|max:255|min:2',
			'address'=>'max:255',
			'role'=>'min:2',
			'city'=>'',
			'country'=>'',
			'postal'=>'',
			'congressional'=>''			
        ]);
        $user = User::create($attributes);
        auth()->login($user);
		$recepients=request()->email;
				$data=[
	'category'=>'New Account successfully created ('.$recepients.').',
	'category_name'=>'Click button below to login'
	];
		Notification::route('mail',$recepients)->notify(new EmailNotification($data));

        return redirect('/user-management')->with('succes', 'Profile succesfully created');
		}
		catch(Exception $ex)
		{
			return redirect('/user-management')->with('fail', 'There is an error : '.$ex->Message());
		}
    }
	public function saveEdit(Request $request)
    {
		try{
         $user_id=$request->user_id;
		 $user = User::find($user_id);
        $user->firstname=$request->firstname;
        $user->lastname=$request->lastname;
        $user->email=$request->email;
        $user->username=$request->username;
        $user->address=$request->address;
        $user->city=$request->city;
        $user->postal=$request->postal;
        $user->country=$request->country;
        $user->congressional=$request->congressional;
        $user->role=$request->role;
        if((int)$request->passstatus>0)
		{
        $user->password=Hash::make($request->password);
		}
        $user->status=$request->status;
$val=$user->save();        
        

        return $val;
		}
		catch(Exception $ex)
		{
			return 0;
		}
    }
	public function deleteUser(Request $request)
    {
		try{
         $user_id=$request->user_id;
		 $user = User::find($user_id);        
$val=$user->delete(); 
        return $val;
		}
		catch(Exception $ex)
		{
			return 0;
		}
    }
}
