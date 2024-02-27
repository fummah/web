<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use App\Models\User;
use App\Models\ProjectsModel;
use App\Models\InvoiceModel;
use App\Models\QuoteModel;
use App\Models\OrderModel;
use Illuminate\Support\Facades\Auth;
use Redirect;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
       
        $role=auth()->user()->role;
        if($role=="Admin")
        {
            $dashdata=$this->getdashsummary();
        return view('home',compact(['dashdata']));
    }
    else
    {
        $user_id=auth()->user()->id;        
         $projects=ProjectsModel::join('users','users.id','=','projects.user_id')->where('projects.user_id','=',$user_id)->orderBy('project_id', 'DESC')->paginate(10); 
     
       return view('pages.client_dashboard',compact(['projects'])); 
    }
    }
    public function enable()
    {
        return view('enable'); 
    }
public function twoFactorChallenge()
    {
        return view('auth.two-factor-challenge'); 
    }
         private function getdashsummary()
    {  
        $orders=OrderModel::orderBy('id', 'DESC')->offset(0)->limit(5)->get();
        $quotes=QuoteModel::orderBy('id', 'DESC')->offset(0)->limit(5)->get();
        $invoices=InvoiceModel::orderBy('id', 'DESC')->offset(0)->limit(5)->get();

        $invoice_open=InvoiceModel::where('status','=','Open')->get()->count();
        $invoice_partially=InvoiceModel::where('status','=','Partially Paid')->get()->count();
        $invoice_fullypaid=InvoiceModel::where('status','=','Fully Paid')->get()->count();
        $invoice_notpaid=InvoiceModel::where('status','=','Not Paid')->get()->count();
        $invoice_cancelled=InvoiceModel::where('status','=','Cancelled')->get()->count();
        $invoice_status=array('invoice_open' => $invoice_open, 'invoice_partially'=>$invoice_partially,'invoice_fullypaid'=>$invoice_fullypaid,'invoice_notpaid'=>$invoice_notpaid,'invoice_cancelled'=>$invoice_cancelled);
        
        $order_open=OrderModel::where('status','=','Open')->get()->count();
        $order_invoiced=OrderModel::where('status','=','Invoiced')->get()->count();
        $order_cancelled=OrderModel::where('status','=','Cancelled')->get()->count();
        $order_status=array('order_open' => $order_open, 'order_invoiced'=>$order_invoiced,'order_cancelled'=>$order_cancelled);

        $data= array('invoices' => $invoices, 'quotes'=>$quotes,'orders'=>$orders,'invoice_status'=>$invoice_status,'order_status'=>$order_status);
        return $data;
       }
}
