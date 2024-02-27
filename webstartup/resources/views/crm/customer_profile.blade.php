@extends('layouts.app', [
    'namePage' => 'Customer details',
    'class' => 'login-page sidebar-mini ',
    'activePage' => 'users',
    'backgroundImage' => asset('now') . "/img/bg14.jpg",
])

@section('content')
 <div class="panel-header panel-header-sm">
  </div>
  <div class="content">
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
     <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
<div class="row">

      <div class="col-md-2 item-field">Name <hr> <b>{{$customer["name"]}}</b></div>
      <div class="col-md-2 item-field">Email <hr> <b>{{$customer["email"]}}</b></div>
      <div class="col-md-2 item-field">Contact Number <hr> <b>{{$customer["contact_number"]}}</b></div>
      <div class="col-md-2 item-field">Company name <hr> <b>{{$customer["company_name"]}}</b></div>
      <div class="col-md-4 item-field">Address <hr> <b>{{$customer["address"]}}</b></div>
    </div>
         <div class="row" style="background-color: black;padding-bottom:15px !important;padding-top:15px !important;">
        <div class="col-md-12">
             <a href="#"><button class="btn btn-info btn-round edit-customer" data="{{$customer['id']}}"><i class="now-ui-icons design-2_ruler-pencil"></i> {{__('Edit Client')}}</button></a>
             <a type="button" href="{{ url('item/create/order') }}/{{$customer['id']}}" class="btn btn-warning btn-round"><i class="now-ui-icons ui-1_simple-add"></i> {{__('Create Order')}}</a>
<a type="button" href="{{ url('item/create/quote') }}/{{$customer['id']}}" class="btn btn-success btn-round"><i class="now-ui-icons ui-1_simple-add"></i> {{__('Create Quote')}}</a>
<a type="button" href="{{ url('item/create/invoice') }}/{{$customer['id']}}" class="btn btn-secondary btn-round"><i class="now-ui-icons ui-1_simple-add"></i> {{__('Create Invoice')}}</a>
<form action="{{ url('deleteentity/customer') }}/{{$customer['id']}}" method="POST" style="display: inline-block">
  @csrf 
<button type="submit"  class="btn btn-danger btn-round"><i class="now-ui-icons ui-1_simple-remove"></i> {{__('Delete Customer')}}</button>
</form>
  <a class="btn btn-primary btn-round text-white pull-right" id="edit_customer" href="#" data-toggle="modal" data-target=".bd-example-modal-lg" hidden>Edit</a>

        </div>    
    </div>
     </div>
          <div class="card-body">
    <div class="row">
      <div class="col-md-4">
        <div class="card  card-tasks">
          <div class="card-header ">
            <h5 class="card-category">Total ({{count($leads)}})</h5>
            <h4 class="card-title">Leads</h4>
          </div>
          <div class="card-body ">
            <div class="table-full-width table-responsive">
              <table class="table">
                <tbody>
                @if (count($leads) < 1)
 <tr> <td colspan="2"><p class="text-danger">No Leads</p></td></tr>
                @endif
                @foreach($leads as $lead)
                      <tr>                  
                    <td class="text-left">{{$lead["project_name"]}}</td>
                    <td class="td-actions text-right">
                      <a type="button" href="{{ url('create_brief/'.$lead['project_id']) }}" class="btn btn-info btn-round btn-icon btn-icon-mini btn-neutral">
                        <i class="now-ui-icons design_bullet-list-67"></i>
                      </a>                     
                    </td>
                  </tr>
                        @endforeach      
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer ">
            <hr>
            <div class="stats">
              <a  href="{{ url('projects') }}"><i class="now-ui-icons design_bullet-list-67"></i> View All Leads</a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card  card-tasks">
          <div class="card-header ">
            <h5 class="card-category">Total ({{count($quotes)}})</h5>
            <h4 class="card-title">Quotations</h4>
          </div>
          <div class="card-body ">
            <div class="table-full-width table-responsive">
              <table class="table">
                <tbody>
                 @if (count($quotes) < 1)
 <tr> <td colspan="2"><p class="text-danger">No Quotes</p></td></tr>
                @endif
                @foreach($quotes as $quote)
                      <tr>                  
                    <td class="text-left">{{$quote["quote_number"]}}</td>
                    <td class="td-actions text-right">
                      <a type="button" href="{{ url('item/edit/quote/'.$quote['id']) }}" class="btn btn-info btn-round btn-icon btn-icon-mini btn-neutral">
                        <i class="now-ui-icons design_bullet-list-67"></i>
                      </a>                     
                    </td>
                  </tr>
                        @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer ">
            <hr>
            <div class="stats">
              <a  href="{{ url('quotes') }}"><i class="now-ui-icons design_bullet-list-67"></i> View All Quotations</a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card  card-tasks">
          <div class="card-header ">
            <h5 class="card-category">Total ({{count($invoices)}})</h5>
            <h4 class="card-title">Invoices</h4>
          </div>
          <div class="card-body ">
            <div class="table-full-width table-responsive">
              <table class="table">
                <tbody>
                        @if (count($invoices) < 1)
 <tr> <td colspan="2"><p class="text-danger">No Invoices</p></td></tr>
                @endif
                @foreach($invoices as $invoice)
                      <tr>                  
                    <td class="text-left">{{$invoice["invoice_number"]}}</td>
                    <td class="td-actions text-right">
                      <a type="button" href="{{ url('item/edit/invoice/'.$invoice['id']) }}" class="btn btn-info btn-round btn-icon btn-icon-mini btn-neutral">
                        <i class="now-ui-icons design_bullet-list-67"></i>
                      </a>                     
                    </td>
                  </tr>
                        @endforeach
                              
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer ">
            <hr>
            <div class="stats">
              <a  href="{{ url('invoices') }}"><i class="now-ui-icons design_bullet-list-67"></i> View All Invoices</a>
            </div>
          </div>
        </div>
      </div>
  
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card  card-tasks">
          <div class="card-header ">
            <h5 class="card-category">Total ({{count($orders)}})</h5>
            <h4 class="card-title">Orders</h4>
          </div>
          <div class="card-body ">
            <div class="table-full-width table-responsive">
              <table class="table">
                <tbody>
                        @if (count($orders) < 1)
 <tr> <td colspan="2"><p class="text-danger">No Orders</p></td></tr>
                @endif
                @foreach($orders as $order)
                      <tr>                  
                    <td class="text-left">{{$order["order_number"]}}</td>
                    <td class="td-actions text-right">
                      <a type="button" href="{{ url('item/edit/order/'.$order['id']) }}" class="btn btn-info btn-round btn-icon btn-icon-mini btn-neutral">
                        <i class="now-ui-icons design_bullet-list-67"></i>
                      </a>                     
                    </td>
                  </tr>
                        @endforeach
                              
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer ">
            <hr>
            <div class="stats">
              <a  href="{{ url('invoices') }}"><i class="now-ui-icons design_bullet-list-67"></i> View All Orders</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  </div>
  </div>
@include('modals.customer_action')
@endsection

@push('js')
  <script>
    $(document).ready(function() {
      // Javascript method's body can be found in assets/js/demos.js
      demo.initDashboardPageCharts();

    });
  </script>
@endpush