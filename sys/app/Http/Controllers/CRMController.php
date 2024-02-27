<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\ProjectsModel;
use App\Models\ProjectContenModel;
use App\Models\User;
use App\Models\InvoiceItemsModel;
use App\Models\InvoiceModel;
use App\Models\QuoteItemsModel;
use App\Models\QuoteModel;
use App\Models\OrderItemsModel;
use App\Models\OrderModel;
use App\Models\SubscriberModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Redirect;
use Response;

class CRMController extends Controller
{
    public function create_item(Request $request)
    {
     
      $action=$request->action;
      $item=$request->item;
      $myitem_name=$request->item;
      $item_id=(int)$request->item_id;
      $currentdate="";    
      $item_number="";
      $status="";
      $items=[];
      $total_amount=0;
      $customer_name="[Select Customer]";
      $customer_id="";
      $company_name="N/A";
      $address="N/A";
      $contact_number="N/A";
      $email="N/A";
      $linked_quote="";
      $linked_order="";
      $quotes_arr=[];
      $orders_arr=[];
      $statuses=[];
      $lead_id=0;
      $hidden=$myitem_name=="quote" || $myitem_name=="order"?"hidden":"";
      if($action=="create")
      {
       $currentdate=date("d/m/Y"); 
       $item_number=$this->createNewItemNumber($myitem_name);
       $lead_id=isset($request->lead_id)?$request->lead_id:0;
       if($item_id>0)
       {
        $customer_details=$this->singledetails($item_id);
        $customer_name=$customer_details["customer"]["name"];
        $customer_id=$item_id;
        $company_name=$customer_details["customer"]["company_name"];
        $address=$customer_details["customer"]["address"];
        $contact_number=$customer_details["customer"]["contact_number"];
        $email=$customer_details["customer"]["email"];
        $quotes_arr=$customer_details["quotes"];
        $orders_arr=$customer_details["order"];
       }

        $mydet=array("customer_name"=>$customer_name,"customer_id"=>$customer_id,"company_name"=>$company_name,"address"=>$address,"contact_number"=>$contact_number,"email"=>$email,"status"=>$status,"hidden"=>$hidden,"linked_quote"=>$linked_quote,"linked_order"=>$linked_order,"quotes_arr"=>$quotes_arr,"orders_arr"=>$orders_arr);
      }
      elseif($action=="edit")
      {
        $redit=$this->editOnItems($item_id,$myitem_name);
        $statuses=$this->statuses();
        $item_id=$redit["id"];
        $item_number=$redit["item_number"];
        $lead_id=$redit["lead_id"];        
        $status=$redit["status"];
        $items=$redit["items"];
        $currentdate=$redit["date_entered"];
         $linked_quote=$redit["linked_quote"];
      $linked_order=$redit["linked_order"];
        $mycustomer=$redit["details"]["customer"];
      $customer_name=$mycustomer["name"];
      $customer_id=$mycustomer["id"];
      $company_name=$mycustomer["company_name"]=="" || $mycustomer["company_name"]==null?"N/A":$mycustomer["company_name"];
      $address=$mycustomer["address"]=="" || $mycustomer["address"]==null?"N/A":$mycustomer["address"];
      $contact_number=$mycustomer["contact_number"]=="" || $mycustomer["contact_number"]==null?"N/A":$mycustomer["contact_number"];
      $email=$mycustomer["email"]=="" || $mycustomer["email"]==null?"N/A":$mycustomer["email"];
      $quotes_arr=$redit["details"]["quotes"];
      $orders_arr=$redit["details"]["order"];
      $mydet=array("customer_name"=>$customer_name,"customer_id"=>$customer_id,"company_name"=>$company_name,"address"=>$address,"contact_number"=>$contact_number,"email"=>$email,"status"=>$status,"hidden"=>$hidden,"linked_quote"=>$linked_quote,"linked_order"=>$linked_order,"quotes_arr"=>$quotes_arr,"orders_arr"=>$orders_arr);
       
foreach ($items as $item) {
    $total_amount += $item['price'];
}

      }
        return view('crm.create_item',compact(['action','myitem_name','item_id','currentdate','item_number','items','total_amount','mydet','statuses','lead_id']));
    }
    public function getcustomers(Request $request)
    {
      $user_id=auth()->user()->id;
        $customers=SubscriberModel::where('user_id','=',$user_id)->select(['id', DB::raw('CONCAT(first_name, " ", last_name) AS name')])->get();
        return $customers;
    }
    public function getotherdetails(Request $request)
    {
        $customer_id=(int)$request->customer_id;
        $data=$this->singledetails($customer_id);
        return $data;
    }
    private function singledetails($customer_id)
    {
        $customer=SubscriberModel::select(['id', DB::raw('CONCAT(first_name, " ", last_name) AS name'),'email','contact_number','address','company_name'])->find($customer_id);
        $quotes=QuoteModel::where('customer_id','=',$customer_id)->get();
        $order=OrderModel::where('customer_id','=',$customer_id)->get();
        $data=array("customer"=>$customer,"quotes"=>$quotes,"order"=>$order);
        return $data;
    }
    public function itemchange(Request $request)
    {
      $action=$request->action;
      $user_id=auth()->user()->id;
      $entity_name=$request->item;
      $status=$request->status;
      $item_number=$request->item_number;
      $item_id=(int)$request->item_id;
      $lead_id=(int)$request->lead_id;
      $quote=$request->quote;
      $order=$request->order;
      $client_name=$request->client_name;
      $item_obj=$request->item_obj;
      $add=0;
      if($action=="create")
      {
      if($entity_name=="quote")
      {
        $add=$this->addQuote($item_number,$client_name,$user_id,$lead_id);      

      }
      elseif($entity_name=="order")
      {
        $add=$this->addOrder($item_number,$client_name,$user_id,$lead_id);
      } 
      elseif($entity_name=="invoice")
      {
        $add=$this->addInvoice($item_number,$client_name,$quote,$order,$user_id,$lead_id);
      }    
    }
      if($action=="edit")
      {
      if($entity_name=="quote")
      {
        $add=$this->editQuote($item_id,$client_name,$user_id,$status,$item_number,$lead_id);      

      }
        if($entity_name=="order")
      {
        $add=$this->editOrder($item_id,$client_name,$user_id,$status,$item_number,$lead_id);      

      }
      elseif($entity_name=="invoice")
      {
        $add=$this->editInvoice($item_id,$item_number,$client_name,$quote,$order,$status);

      }    
    }
    if($add>0)
        {
            $this->updateLeadStatus($lead_id,['project_status'=>ucfirst($entity_name)]);
            $x=$this->addItems($add,$item_obj,$entity_name,$user_id);
             return redirect("item/edit/".$entity_name."/".$add)->with('success','Changes has successfully saved');
        }
        else
        {
            return Redirect::back()->withErrors(['msg' => 'Failed to commit']);
        }

    }
      private function addInvoice($item_number,$client_name,$quote,$order,$user_id,$lead_id)
      {
        $invoice=new InvoiceModel();               
        $invoice->invoice_number=$item_number;
        $invoice->customer_id=$client_name; 
        $invoice->linked_quote=$quote; 
        $invoice->linked_order=$order; 
        $invoice->lead_id=$lead_id; 
        $invoice->entered_by=$user_id; 
        $invoice->save();
        return $invoice->id;
      }
    private function addQuote($item_number,$client_name,$user_id,$lead_id)
      {
       $quote=new QuoteModel();        
        $quote->quote_number=$item_number;
        $quote->customer_id=$client_name; 
        $quote->lead_id=$lead_id; 
        $quote->entered_by=$user_id; 
        $quote->save();
        return $quote->id;
      }
         private function addOrder($item_number,$client_name,$user_id,$lead_id)
      {
       $order=new OrderModel();        
        $order->order_number=$item_number;
        $order->customer_id=$client_name; 
        $order->lead_id=$lead_id;
        $order->entered_by=$user_id; 
        $order->save();
        return $order->id;
      }
       private function editQuote($item_id,$client_name,$user_id,$status,$item_number,$lead_id)
      {
       $quote=QuoteModel::find($item_id);        
        //$quote->quote_number=$item_number;
        $quote->customer_id=$client_name; 
        $quote->status=$status;
        if($quote->save())
        {
            if($status=="Invoiced")
            {
                $this->updateLeadStatus($lead_id,['project_status'=>"Invoice"]);
                $this->checkLinkedQuote($item_number,$client_name,$user_id,$item_id,$lead_id);
            }               
                
            return $item_id;
        }
        else{
            return 0;
        }        
      }
      private function editOrder($item_id,$client_name,$user_id,$status,$item_number,$lead_id)
      {
       $order=OrderModel::find($item_id);        
        //$quote->quote_number=$item_number;
        $order->customer_id=$client_name; 
        $order->status=$status;
        if($order->save())
        {
            if($status=="Invoiced")
            {
                $this->updateLeadStatus($lead_id,['project_status'=>'Invoice']);
                $this->checkLinkedOrder($item_number,$client_name,$user_id,$item_id,$lead_id);
            }               
                
            return $item_id;
        }
        else{
            return 0;
        }        
      }
      private function editInvoice($item_id,$item_number,$client_name,$quote,$order,$status)
      {
       $invoice=InvoiceModel::find($item_id);        
       //$invoice->invoice_number=$item_number;
        $invoice->customer_id=$client_name; 
        $invoice->linked_quote=$quote; 
        $invoice->linked_order=$order; 
        $invoice->status=$status;
        if($invoice->save())
        {
            return $item_id;
        }
        else{
            return 0;
        }        
      }
      private function checkLinkedQuote($linked_quote,$client_name,$user_id,$quote_id,$lead_id)
      {

       $quote=InvoiceModel::where('linked_quote','=',$linked_quote)->get();    
       if(count($quote)<1)  
       {
        $item_number=$this->createNewItemNumber("invoice");        
        $new_invoice_id=$this->addInvoice($item_number,$client_name,$linked_quote,"",$user_id,$lead_id);
        $quote_list=QuoteItemsModel::where('quote_id','=',$quote_id)->get();       
        foreach($quote_list as $item)    
       {       
         $newobj=new InvoiceItemsModel();
         $newobj->item_name=$item["item_name"];
         $newobj->price=$item["price"];
         $newobj->invoice_id=$new_invoice_id;
         $newobj->entered_by=$user_id; 
         $newobj->save();
       }


       }  
       
      }
      private function checkLinkedOrder($linked_order,$client_name,$user_id,$order_id,$lead_id)
      {
       
       $order=InvoiceModel::where('linked_order','=',$linked_order)->get();    
       if(count($order)<1)  
       {
        $item_number=$this->createNewItemNumber("invoice");        
        $new_invoice_id=$this->addInvoice($item_number,$client_name,$linked_order,"",$user_id,$lead_id);
        $order_list=OrderItemsModel::where('order_id','=',$order_id)->get();       
        foreach($order_list as $item)    
       {       
         $newobj=new InvoiceItemsModel();
         $newobj->item_name=$item["item_name"];
         $newobj->price=$item["price"];
         $newobj->invoice_id=$new_invoice_id;
         $newobj->entered_by=$user_id; 
         $newobj->save();
       }
       }  
       
      }
    private function addItems($item_id,$obj,$entity_name,$user_id)
      {
          if($obj!="")
       {
       $item_obj=json_decode($obj,true);
      
       foreach($item_obj as $item)    
       {
        $item_name=$item["item_name"];
        $price=$item["cost"];
        $newobj=null;
        if($entity_name=="quote")
        {
            $newobj=new QuoteItemsModel();
            $newobj->quote_id=$item_id;
        }
        elseif($entity_name=="order")
        {
           $newobj=new OrderItemsModel();
           $newobj->order_id=$item_id;
        }
             elseif($entity_name=="invoice")
        {
            $newobj=new InvoiceItemsModel();
            $newobj->invoice_id=$item_id;
        }
         $newobj->item_name=$item_name;
         $newobj->price=$price;         
         $newobj->entered_by=$user_id; 
         $newobj->save();
       }
       }
      }
      private function createNewItemNumber($item)
      {
     $alpahabetic="THEQUICKBROWNFOXJUMPSOVERTHELAZYDOG";
     $letter=$alpahabetic[rand(0,25)];
     $randomnum=rand(0,99);   
     $itemcount="";      
      $itemcount=$item=="quote"?:InvoiceModel::all()->count();
      if($item=="quote")
      {
        $itemcount=QuoteModel::all()->count();
      }
      elseif($item=="order")
      {
        $itemcount=OrderModel::all()->count();
      }
       elseif($item=="invoice")
      {
        $itemcount=InvoiceModel::all()->count();
      }  
      $itemcount++;
      $propnum=str_pad($itemcount, 6,'0', STR_PAD_LEFT);
      $temp=$letter.$randomnum.$propnum;
      $item_number="";
      if($item=="quote")
      {
        $item_number="QUO-".$temp;
      }
      elseif($item=="order")
      {
        $item_number="ORD-".$temp;
      }
       elseif($item=="invoice")
      {
        $item_number="INV-".$temp;
      }  
       return $item_number;
      }
    private function editOnItems($item_id,$entity_name)
      {
        $redit="";
        if($entity_name=="quote")
        {
         $redit=QuoteModel::find($item_id);
        }
         elseif($entity_name=="order")
      {
        $redit=OrderModel::find($item_id);
      }
       elseif($entity_name=="invoice")
      {
        $redit=InvoiceModel::find($item_id);
      }
       
        $item_id=$redit["id"];
        $lead_id=$redit["lead_id"];
        $customer_id=(int)$redit["customer_id"];
        $det["id"]=$redit["id"];
        $det["lead_id"]=$lead_id;
        $det["item_number"]="";
        if($entity_name=="quote")
        {
          $det["item_number"]=$redit["quote_number"];
        }
         elseif($entity_name=="order")
      {
        $det["item_number"]=$redit["order_number"];
      }
       elseif($entity_name=="invoice")
      {
        $det["item_number"]=$redit["invoice_number"];
      }        
        $date = strtotime($redit["date_entered"]);
        $det["date_entered"]=date('d/m/Y', $date);
        $det["status"]=$redit["status"];
        if($entity_name=="quote")
        {
        $det["items"]=QuoteItemsModel::where('quote_id','=',$item_id)->get();
        $det["linked_order"]="";
        $det["linked_quote"]="";
        }
          elseif($entity_name=="order")
        {
        $det["items"]=OrderItemsModel::where('order_id','=',$item_id)->get();
        $det["linked_order"]="";
        $det["linked_quote"]="";
        }
        else
        {
$det["items"]=InvoiceItemsModel::where('invoice_id','=',$item_id)->get();
$det["linked_quote"]=$redit["linked_quote"];
$det["linked_order"]=$redit["linked_order"];
        }
       
        $det["details"]=$this->singledetails($customer_id);
        return $det;
      }

      public function deleteitemlist(Request $request)
      {
        $item_id=$request->item_id;
        $item=$request->item;
        $del=0; 
        if($item=="quote")
        {
          $del=QuoteItemsModel::find($item_id); 
        }
           elseif($item=="order")
        {
          $del=OrderItemsModel::find($item_id); 
        }
           elseif($item=="invoice")
        {
          $del=InvoiceItemsModel::find($item_id); 
        }
        
        if($del->delete())
        {
            return "deleted";
        }
        else
        {
           return "failed"; 
        }
      }

      private function statuses()
      {
        $data["quote"]=['Open','Invoiced','Cancelled'];
        $data["order"]=['Open','Invoiced','Cancelled'];
        $data["lead"]=['Pending','Quote','Order','Invoice','Cancelled'];
        $data["invoice"]=['Open','Partially Paid','Cancelled','Not Paid',"Fully Paid"];
        return $data;
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
        if($page=="customers")
{
       $all=$this->myclients($row,$rowperpage,$columnIndex,$columnName,$columnSortOrder,$searchValue);
   }
   elseif($page=="quotes")
   {
    $all=$this->getAllQuotes($row,$rowperpage,$columnIndex,$columnName,$columnSortOrder,$searchValue);
   }
     elseif($page=="invoices")
   {
    $all=$this->getAllInvoices($row,$rowperpage,$columnIndex,$columnName,$columnSortOrder,$searchValue);
   }
      elseif($page=="orders")
   {
    $all=$this->getAllOrders($row,$rowperpage,$columnIndex,$columnName,$columnSortOrder,$searchValue);
   }
   

$response = array(
  "draw" => intval($draw),
  "iTotalRecords" => $all["totalRecords"],
  "iTotalDisplayRecords" => $all["totalRecordwithFilter"],
  "aaData" => $all["data"]
);

echo json_encode($response);
      }

      private function myclients($row,$rowperpage,$columnIndex,$columnName,$columnSortOrder,$searchValue)
    {
      $totalRecords=User::all()->count();
      $search="%".$searchValue."%";
      if($searchValue != ''){ 
      $totalRecordwithFilter=User::where('name','like',$search)->orWhere('email','like',$search)->orWhere('contact_number','like',$search)->orWhere('role','like',$search)->get()->count();      
      $empRecords=User::where('name','like',$search)->orWhere('email','like',$search)->orWhere('contact_number','like',$search)->orWhere('role','like',$search)->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
     }

else{
  $totalRecordwithFilter=$totalRecords;
 $empRecords=User::orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
}
## Fetch records
$data = array();
foreach ($empRecords as $row) {  
$customer_id=(int)$row['id'];        
   $data[] = array( 
      "name"=>$row['name'],          
      "email"=>$row['email'],
      "contact_number"=>$row['contact_number'],
      "role"=>$row['role'], 
      "actions"=>"<a type='button' href='#' data='$customer_id'  class='btn btn-success btn-icon btn-sm edit-customer' title='Edit'><i class='now-ui-icons design-2_ruler-pencil'></i></a>",     
   );
}
$all["totalRecords"]=$totalRecords;
$all["totalRecordwithFilter"]=$totalRecordwithFilter;
$all["data"]=$data;
return $all;
    }
    private function getAllQuotes($row,$rowperpage,$columnIndex,$columnName,$columnSortOrder,$searchValue)
    {
      $totalRecords=QuoteModel::all()->count();
      $search="%".$searchValue."%";
      if($searchValue != ''){ 
      $totalRecordwithFilter=QuoteModel::join('users','users.id','=','quotes.customer_id')->where('users.name','like',$search)->orWhere('quotes.date_entered','like',$search)->orWhere('quotes.status','like',$search)->orWhere('users.email','like',$search)->orWhere('quotes.quote_number','like',$search)->get()->count();      
      $empRecords=QuoteModel::join('users','users.id','=','quotes.customer_id')->where('users.name','like',$search)->orWhere('quotes.date_entered','like',$search)->orWhere('quotes.status','like',$search)->orWhere('quotes.quote_number','like',$search)->orWhere('users.email','like',$search)->select(['quotes.id', 'users.name','quotes.date_entered','quotes.status','quotes.quote_number'])->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
     }

else{
  $totalRecordwithFilter=$totalRecords;
 $empRecords=QuoteModel::join('users','users.id','=','quotes.customer_id')->select(['quotes.id', 'users.name','quotes.date_entered','quotes.status','quotes.quote_number'])->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
}
## Fetch records
$data = array();
foreach ($empRecords as $row) { 
$item_id= $row['id'];           
   $data[] = array( 
      "name"=>$row['name'],          
      "item_number"=>$row['quote_number'],
      "date_entered"=>$row['date_entered'],
      "status"=>$row['status'], 
      "actions"=>"<a type='button' href='item/edit/quote/$item_id'  class='btn btn-success btn-icon btn-sm' title='Edit'><i class='now-ui-icons design-2_ruler-pencil'></i></a>
                  <a type='button' href='print/quote/$item_id' class='btn btn-primary btn-icon btn-sm' title='Download'><i class='now-ui-icons arrows-1_cloud-download-93'></i></a>",     
   );
}
$all["totalRecords"]=$totalRecords;
$all["totalRecordwithFilter"]=$totalRecordwithFilter;
$all["data"]=$data;
return $all;
    }
    private function getAllOrders($row,$rowperpage,$columnIndex,$columnName,$columnSortOrder,$searchValue)
    {
      $totalRecords=OrderModel::all()->count();
      $search="%".$searchValue."%";
      if($searchValue != ''){ 
      $totalRecordwithFilter=OrderModel::join('users','users.id','=','orders.customer_id')->where('users.name','like',$search)->orWhere('orders.date_entered','like',$search)->orWhere('orders.status','like',$search)->orWhere('users.email','like',$search)->orWhere('orders.order_number','like',$search)->get()->count();      
      $empRecords=OrderModel::join('users','users.id','=','orders.customer_id')->where('users.name','like',$search)->orWhere('orders.date_entered','like',$search)->orWhere('orders.status','like',$search)->orWhere('orders.order_number','like',$search)->orWhere('users.email','like',$search)->select(['orders.id', 'users.name','orders.date_entered','orders.status','orders.order_number'])->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
     }

else{
  $totalRecordwithFilter=$totalRecords;
 $empRecords=OrderModel::join('users','users.id','=','orders.customer_id')->select(['orders.id', 'users.name','orders.date_entered','orders.status','orders.order_number'])->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
}
## Fetch records
$data = array();
foreach ($empRecords as $row) { 
$item_id= $row['id'];           
   $data[] = array( 
      "name"=>$row['name'],          
      "item_number"=>$row['order_number'],
      "date_entered"=>$row['date_entered'],
      "status"=>$row['status'], 
      "actions"=>"<a type='button' href='item/edit/order/$item_id'  class='btn btn-success btn-icon btn-sm' title='Edit'><i class='now-ui-icons design-2_ruler-pencil'></i></a>
                  <a type='button' href='print/order/$item_id' class='btn btn-primary btn-icon btn-sm' title='Download'><i class='now-ui-icons arrows-1_cloud-download-93'></i></a>",     
   );
}
$all["totalRecords"]=$totalRecords;
$all["totalRecordwithFilter"]=$totalRecordwithFilter;
$all["data"]=$data;
return $all;
    }
    private function getAllInvoices($row,$rowperpage,$columnIndex,$columnName,$columnSortOrder,$searchValue)
    {
      $totalRecords=InvoiceModel::all()->count();
      $search="%".$searchValue."%";
      if($searchValue != ''){ 
      $totalRecordwithFilter=InvoiceModel::join('users','users.id','=','invoices.customer_id')->where('users.name','like',$search)->orWhere('invoices.date_entered','like',$search)->orWhere('invoices.status','like',$search)->orWhere('users.email','like',$search)->orWhere('invoices.invoice_number','like',$search)->get()->count();      
      $empRecords=InvoiceModel::join('users','users.id','=','invoices.customer_id')->where('users.name','like',$search)->orWhere('invoices.date_entered','like',$search)->orWhere('invoices.status','like',$search)->orWhere('invoices.invoice_number','like',$search)->orWhere('users.email','like',$search)->select(['invoices.id', 'users.name','invoices.date_entered','invoices.status','invoices.invoice_number'])->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
     }

else{
  $totalRecordwithFilter=$totalRecords;
 $empRecords=InvoiceModel::join('users','users.id','=','invoices.customer_id')->select(['invoices.id', 'users.name','invoices.date_entered','invoices.status','invoices.invoice_number'])->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
}
## Fetch records
$data = array();
foreach ($empRecords as $row) {  
$item_id= $row['id'];       
   $data[] = array( 
      "name"=>$row['name'],          
      "item_number"=>$row['invoice_number'],
      "date_entered"=>$row['date_entered'],
      "status"=>$row['status'], 
      "actions"=>"<a type='button' href='item/edit/invoice/$item_id'  class='btn btn-success btn-icon btn-sm' title='Edit'><i class='now-ui-icons design-2_ruler-pencil'></i></a>
                  <a type='button' href='print/invoice/$item_id' class='btn btn-primary btn-icon btn-sm' title='Download'><i class='now-ui-icons arrows-1_cloud-download-93'></i></a>",     
   );
}
$all["totalRecords"]=$totalRecords;
$all["totalRecordwithFilter"]=$totalRecordwithFilter;
$all["data"]=$data;
return $all;
    }

    public function quotes()
    {
        return view('crm.quotes');
    }
     public function invoices()
    {
        return view('crm.invoices');
    }
      public function orders()
    {
        return view('crm.orders');
    }
       public function customer_details(Request $request)
    {
        $customer_id=(int)$request->customer_id;        
        $customer=SubscriberModel::find($customer_id);
        $leads=ProjectsModel::where('user_id','=',$customer_id)->get();
        $quotes=QuoteModel::where('customer_id','=',$customer_id)->get();
        $orders=OrderModel::where('customer_id','=',$customer_id)->get();
        $invoices=InvoiceModel::where('customer_id','=',$customer_id)->get();
        return view('crm.customer_profile',compact(['customer','leads','quotes','invoices','orders']));
    }
    public function action_customer(Request $request)
    {       
        $customer_id = (int)$request->customer_id;
        $change_pass = $request->change_pass;
        $password = $request->password;
        $confirm_password = $request->confirm_password;
        if($customer_id>0)
        {
            $customer= User::find($customer_id);
            if($change_pass=="change_pass")
            {
                if(strlen($password)>5 && $confirm_password==$password)
                {
                   $customer->password = Hash::make($password); 
                }
                else
                {
                    return Redirect::back()->withErrors(['msg' => 'Incorrect Password please try again']);
                }
            }
           

           }
           else
           {
            $customer= New User();
            $customer->password = "-"; 
           }       
        $customer->name = $request->customer_name;
        $customer->email = $request->customer_email;
        $customer->contact_number = $request->contact_number;
        $customer->company_name = $request->company_name;
        $customer->address = $request->customer_address; 
        if($customer_id<1)
        {

        }
        if($customer->save())
        {
            return redirect()->back()->with('success','Changes has successfully saved');
        }
        else
        {
            return Redirect::back()->withErrors(['msg' => 'Failed to commit']);
            
        }
    }
    public function deleteentity(Request $request)
    {
        $entity_id=(int)$request->entity_id;
        $entity=$request->entity; 
        $remove=0;
        $link="";
        if($entity=="customer")  
        {
            $del = SubscriberModel::find($entity_id);
            $remove=$del->delete();
            $link="audience";
        } 
        elseif($entity=="quote")  
        {
             $del = QuoteModel::find($entity_id);
            $remove=$del->delete();
            $link="quotes";
        } 
        elseif($entity=="order")  
        {
             $del = OrderModel::find($entity_id);
            $remove=$del->delete();
            $link="orders";
        } 
           elseif($entity=="invoice")  
        {
             $del = InvoiceModel::find($entity_id);
            $remove=$del->delete();
            $link="invoices";
        } 
        elseif($entity=="lead")  
        {
             $del = ProjectsModel::where('project_id','=',$entity_id);
            $remove=$del->delete();
            $link="projects";
        } 
        if($remove>0)
        {
            return redirect($link)->with('success','Record successfully deleted');
        }
        else
        {
            return Redirect::back()->withErrors(['msg' => 'Failed to delete']);
            
        }    
        $customer=SubscriberModel::find($customer_id);
        $leads=ProjectsModel::where('user_id','=',$customer_id)->get();
        $quotes=QuoteModel::where('customer_id','=',$customer_id)->get();
        $invoices=InvoiceModel::where('customer_id','=',$customer_id)->get();
        return view('crm.customer_profile',compact(['customer','leads','quotes','invoices']));
    }
    public function getdashrecords(Request $request)
    {  
        $date = date("Y-m-d");
        $date_from = date('Y-m-01', strtotime($date. '  -12 months'));
        $leads_get = DB::select("SELECT DATE_FORMAT(date_entered,'%Y-%m') as months,COUNT(*) as totals FROM `projects` WHERE date_entered > :date_entered GROUP BY DATE_FORMAT(date_entered,'%Y-%m')",['date_entered' => $date_from]);        
         $quotes_get = DB::select("SELECT DATE_FORMAT(date_entered,'%Y-%m') as months,COUNT(*) as totals FROM `quotes` WHERE date_entered > :date_entered GROUP BY DATE_FORMAT(date_entered,'%Y-%m')",['date_entered' => $date_from]);        
         $invoices_get = DB::select("SELECT DATE_FORMAT(date_entered,'%Y-%m') as months,COUNT(*) as totals FROM `invoices` WHERE date_entered > :date_entered GROUP BY DATE_FORMAT(date_entered,'%Y-%m')",['date_entered' => $date_from]);
           $orders_get = DB::select("SELECT DATE_FORMAT(date_entered,'%Y-%m') as months,COUNT(*) as totals FROM `orders` WHERE date_entered > :date_entered GROUP BY DATE_FORMAT(date_entered,'%Y-%m')",['date_entered' => $date_from]);
        $leads=Response::json($leads_get);
        $orders=Response::json($orders_get);
        $quotes=Response::json($quotes_get);
        $invoices=Response::json($invoices_get);      
        $data= array('invoices' => $invoices, 'quotes'=>$quotes,'leads'=>$leads,'orders'=>$orders);
        return $data;
    }
    public function updateLeadStatus($project_id,$arr)
    { 
      ProjectsModel::where('project_id','=',$project_id)->update($arr);
     
    }
     public function cancellead(Request $request)
    { 
        $lead_id=(int)$request->lead_id;
        $this->updateLeadStatus($lead_id,['project_status'=>"Cancelled"]);    
        return redirect()->back()->with('success','Changes has successfully saved'); 
    }

    public function subscribers()
    {
        $subscribers=SubscriberModel::paginate(10);
        return view('crm.subscribers', compact(['subscribers' ]));
    }


}
