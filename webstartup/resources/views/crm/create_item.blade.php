@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => 'Entity',
    'activePage' => '',
    'activeNav' => '',
])

@section('content')
  <link href="{{ asset('assets') }}/demo/select2.min.css" rel="stylesheet" />
  <div class="panel-header panel-header-sm">
  </div>
  <div class="content">
 
 <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            

                                 @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if($action=="create")
<h5 class="title">{{__(" New")}} {{ucfirst($myitem_name)}}</h5>
@else
<a type="button" href="{{ url('item/create') }}/{{$myitem_name}}/0" class="btn btn-outline-warning btn-round"><i class="now-ui-icons ui-1_simple-add"></i> Create New {{ucfirst($myitem_name)}}</a>
<a href="{{ url('print') }}/{{$myitem_name}}/{{$item_id}}" type="button" class="btn btn-outline-success btn-round"><i class="now-ui-icons arrows-1_cloud-download-93"></i> View Pdf</a>
<form action="{{ url('deleteentity') }}/{{$myitem_name}}/{{$item_id}}" method="POST" style="display: inline-block">
  @csrf 
<button type="submit"  class="btn btn-outline-danger btn-round"><i class="now-ui-icons ui-1_simple-remove"></i> Delete {{ucfirst($myitem_name)}}</button>
</form>

<span style="float:right;" class="item-field">{{ucfirst($myitem_name)}} Status : 
<select class="statuses" style="font-size:16px; border: 1px solid orange; padding: 5px;">
<option value="{{$mydet['status']}}">{{$mydet["status"]}}</option>
@if($myitem_name=="quote" || $myitem_name=="order")
@foreach($statuses["quote"] as $quo)
{
    <option value="{{$quo}}">{{$quo}}</option>
}
@endforeach
@else
@foreach($statuses["invoice"] as $inv)
{
    <option value="{{$inv}}">{{$inv}}</option>
}
@endforeach
@endif
<select>
    </span>
@endif

          </div>
          <div class="card-body">
               <div class="row" style="background-color: black;padding-bottom:15px !important;padding-top:15px !important;">
        <div class="col-md-6">
             <div class="logo">
   
    <a href="#" class="simple-text logo-normal">
      <img src="{{ asset('assets/img/now-logo.png') }}" height="60" width="auto" alt="">
    </a>
  </div>
        </div>
        <div class="col-md-6 text-white">
            <div class="row"> <div class="col-md-12"><h1 class="text-white">Formal {{ucfirst($myitem_name)}}
        </h1></div></div>
            <div class="row">
                <div class="col-md-6">
                 {{ucfirst($myitem_name)}} No.
                                        <b>{{$item_number}}</b></div>
                <div class="col-md-6">Date : <b>{{$currentdate}}</b></div>
                

            </div>
        </div>
    </div>
    <hr>
            <form method="POST" action="{{ url('item-post') }}" autocomplete="off">
              @csrf
                        
              <div class="row">

                <div class="col-md-6">
                    <input type="hidden" name="action" value="{{$action}}">
                    <input type="hidden" name="item" id="item" value="{{$myitem_name}}">
                    <input type="hidden" name="item_id" value="{{$item_id}}">
                    <input type="hidden" name="item_number" value="{{$item_number}}">
                    <input type="hidden" name="status" id="status" value="{{$mydet['status']}}">
                    <input type="hidden" name="lead_id" id="lead_id" value="{{$lead_id}}">
                    <h3 class="text-white" style="background-color:black; padding:10px !important;">Client Information</h3>
                </div>
                <div class="col-md-3" {{$mydet['hidden']}}>
                      <h6>Related Quote</h6>
                    <div class="form-group">                          
                               
                                <select class="js-example-basic-single form-control" name="quote" id="quote">
                                    @if($mydet["linked_quote"]=="")
                                    <option>[Select Quote]</option>
                                    @else
                                    <option value="{{$mydet['linked_quote']}}">{{$mydet["linked_quote"]}}</option>
                                    @endif
                                    @foreach($mydet["quotes_arr"] as $quote)
<option value="{{$quote['quote_number']}}">{{$quote["quote_number"]}}</option>
 @endforeach 

</select>
                        </div>
                </div>
                 <div class="col-md-3" {{$mydet['hidden']}}>
                    <h6>Related Order</h6>
                    <div class="form-group">                          
                               
                                <select class="js-example-basic-single form-control" name="order" id="order">
                                       @if($mydet["linked_order"]=="")
                                    <option>[Select Order]</option>
                                    @else
                                    <option value="{{$mydet['linked_order']}}">{{$mydet["linked_order"]}}</option>
                                    @endif
                                     @foreach($mydet["orders_arr"] as $order)
<option value="{{$order['order_number']}}">{{$order["order_number"]}}</option>
 @endforeach 

</select>
                        </div>
                </div>
              </div>
              <div class="row">
                 <div class="col-md-6 pr-1">
                    <h6><b>Client Name</b></h6>
                 </div>
                 <div class="col-md-6 pr-1">
                     <div class="form-group">                          
                               
                                <select class="js-example-basic-single form-control" name="client_name" id="client_name" required>
                                    <option value="{{$mydet['customer_id']}}">{{$mydet["customer_name"]}}</option>

</select>
                        </div>
                 </div>
              </div>
                  <div class="row">
                 <div class="col-md-6 pr-1">
                    <h6><b>Company Name</b></h6>
                 </div>
                 <div class="col-md-6 pr-1">
                     <div class="form-group item-field">
                     <h6 id="company_name">
                         {{$mydet["company_name"]}}
                     </h6>                 
                        </div>
                 </div>
              </div>
                  <div class="row">
                 <div class="col-md-6 pr-1">
                    <h6><b>Address</b></h6>
                 </div>
                 <div class="col-md-6 pr-1">
                     <div class="form-group item-field">                          
                                <h6 id="address">
                         {{$mydet["address"]}}
                     </h6>                              
                        </div>
                 </div>
              </div>
                  <div class="row">
                 <div class="col-md-6 pr-1">
                    <h6><b>Contact Number</b></h6>
                 </div>
                 <div class="col-md-6 pr-1">
                     <div class="form-group item-field">  
                       <h6 id="contact_number">
                         {{$mydet["contact_number"]}}
                     </h6>                        
                        </div>
                 </div>
              </div>
                   <div class="row">
                 <div class="col-md-6 pr-1">
                    <h6><b>Email Address</b></h6>
                 </div>
                 <div class="col-md-6 pr-1">
                     <div class="form-group item-field">    
                       <h6 id="email">
                         {{$mydet["email"]}}
                     </h6>                     
                        </div>
                 </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                    <h3 class="text-white" style="background-color:black; padding:10px !important;">Services</h3>
                </div>
                   <div class="col-md-6">
                    <button type="button" class="btn btn-secondary btn-round" data-toggle="modal" data-target="#exampleModal"><i class="now-ui-icons ui-1_simple-add"></i> {{__('Add Items')}}</button>
                </div>
              </div>
              <textarea id="item_obj" name="item_obj" hidden></textarea>
              <input type="hidden" id="tot" value="{{count($items)}}">
              <div class="row">
                  <div class="col-md-12">
                     <div class="table-responsive">
              <table class="table">
                       <thead class=" text-primary">
                  <th>
                    Item No.
                  </th>
                  <th>
                    Description
                  </th>
                  <th>
                   Cost
                  </th>
                
                </thead>
                <tbody id="items">
 @foreach($items as $item)
<tr id="${{$item['id']}}"><td><i class='now-ui-icons ui-1_simple-remove remove-db' title="delete" cost='${{$item["price"]}}' data='${{$item["id"]}}' style='color:red; cursor:pointer'></i> {{ $loop->iteration }}.</td><td>{{$item["item_name"]}}</td><td>${{$item["price"]}}</td></tr>
 @endforeach
                  
                </tbody>
                       <tfoot class=" text-primary">
                  <th colspan="2">
                    Total Costs
                  </th>
                
                  <th>
                   $<span id="total_amount">{{$total_amount}}.00</span>
                  </th>
                    
                </tfoot>
              </table>
                  </div>
              </div>
 </div>
 <hr class="half-rule"/>
              <div class="card-footer ">
                <button type="submit" class="btn btn-primary btn-round"><i class="now-ui-icons ui-1_check"></i> {{__('Save Now')}}</button>
              </div>
              
            </form>
          </div>
       
      </div>
    </div>
</div>
  </div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Item</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <label>Item Description</label>
         <textarea name="item_name" id="item_name" class="form-control" style="border: 1px solid grey; border-radius: 5px; padding: 10px;" placeholder="Item Name / Description"></textarea> <br>
            <label>Cost / Price ($)</label>
        <div class="form-group">                          
                          <input type="number" name="cost" min="0" id="cost" class="form-control" value="0" style="border: 1px solid grey;" value=""> 
                        </div>
      </div>
      <div class="modal-footer">
         <button type="button" class="btn btn-primary" onclick="addItem()">Add Now</button>
        <button type="button" id="close" class="btn btn-secondary" data-dismiss="modal">Close</button>
       
      </div>
    </div>
  </div>
</div>

@endsection
