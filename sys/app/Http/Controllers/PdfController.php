<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
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


class PdfController extends Controller
{
    public function print(Request $request)
    {
        $entity_id=$request->entity_id;
        $entity_name=$request->entity_name;
       $data=$this->editOnItems($entity_id,$entity_name);
        $date = strtotime($data["redit"]["date_entered"]);
        $entity_number=$data["redit"]["item_number"];
        $currentdate=date('d/m/Y', $date);
        $total_amount=0;
        foreach ($data["entity_list"] as $item) {
    $total_amount += $item['price'];
}
       
        //return view("crm.entity_pdf",compact(['data','entity_name','currentdate','total_amount']));
        $pdf = PDF::loadView('crm.entity_pdf', compact(['data','entity_name','currentdate','total_amount']));
        return $pdf->download($entity_number.'.pdf');
    }
        private function editOnItems($entity_id,$entity_name)
      {
        
        if($entity_name=="quote")
        {
         $redit=QuoteModel::find($entity_id);
         $redit=array('item_number'=>$redit["quote_number"],'status'=>$redit["status"],'date_entered'=>$redit["date_entered"],'customer_id'=>$redit["customer_id"]);
         $entity_list=QuoteItemsModel::where('quote_id','=',$entity_id)->get();  
        }
         elseif($entity_name=="order")
      {
        $redit=OrderModel::find($entity_id);
        $redit=array('item_number'=>$redit["order_number"],'status'=>$redit["status"],'date_entered'=>$redit["status"],'customer_id'=>$redit["customer_id"]);
        $entity_list=OrderItemsModel::where('order_id','=',$entity_id)->get();  
      }
       else
      {
        $redit=InvoiceModel::find($entity_id);
        $redit=array('item_number'=>$redit["invoice_number"],'status'=>$redit["status"],'date_entered'=>$redit["status"],'customer_id'=>$redit["customer_id"]);
        $entity_list=InvoiceItemsModel::where('invoice_id','=',$entity_id)->get();  
      }
      $customer_id=(int)$redit["customer_id"];
      $customer=User::select(['id', 'name','email','contact_number','address','company_name'])->find($customer_id);
      $data=array('redit'=>$redit,'entity_list'=>$entity_list,'customer'=>$customer);
      return $data;
  }

}