<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\CampaignAnalysisModel;
use App\Notifications\EmailNotification;
use Illuminate\Support\Facades\Session;
use Redirect;

class RegisterController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $temp_code=rand(1000,9999);
        $inar=[
    'data'=>"Welcome to <b>Webstartup.io!</b><br><br>Thank you for registering with Webstartup.io<br><br>Your temporary code : <b>".$temp_code."</b>",
    'hid'=>""    
    ];
       
        $usen=User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'temp_code'=>$temp_code, 
            'recovery_word'=>$data['recovery_word'],        
            'password' => Hash::make($data['password']),

        ]);
        if($usen)
        {
            Session::put('temp_email', $data['email']);
            $mid=User::where('email','=',$data['email'])->get();
            $user = User::find($mid[0]["id"]);
            $user->notify(new EmailNotification($inar));

        }
        return $usen;
    }
    protected function unsubscribe(Request $request)
    {
        return view('auth.unsubscribe');
    }
    protected function checkreceive(Request $request)
    {
        $campaign_id=(int)$request->campaign_id;
        $subscriber_id=(int)$request->subscriber_id;
        $update=CampaignAnalysisModel::where('campaign_id','=',$campaign_id)->where('subscriber_id','=',$subscriber_id)->update(['impression'=>1]);       
        return 0;
    }
      protected function activation(Request $request)
    {
        $temp_email=Session::get('temp_email');
        return view('auth.activation',compact('temp_email'));
    }
      protected function activate(Request $request)
    {
        $email=$request->email;
        $temp_code=(int)$request->code;
        $user=User::where('email','=',$email)->where('temp_code','=',$temp_code)->get();
        if(count($user)>0)
        {
            $hashed=$user[0]["password"];
            $hashed=str_replace("-".$temp_code, "", $hashed);
            User::where('email','=',$email)->update(["status"=>1,"email_verified_at"=>date("Y-m-d H:i:s"),"password"=>$hashed]);          
            return Redirect::route('login')->with('success','Account Successfully Activated');
        }
        else
        {
            return Redirect::back()->withErrors(['msg' => 'Failed to activate Account']);
        }        
        
    }
}
