@extends('layouts.app', [
    'namePage' => 'Subscriber details',
    'class' => 'login-page sidebar-mini ',
    'activePage' => 'audiences',
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

      <div class="col-md-2 item-field">Name <hr> <b>{{$arr["first_name"]}} {{$arr["last_name"]}}</b></div>
      <div class="col-md-2 item-field">Email <hr> <b>{{$arr["email"]}}</b></div>
      <div class="col-md-2 item-field">Contact Number <hr> <b>{{$arr["contact_number"]}}</b></div>
      <div class="col-md-2 item-field">Status <hr> <b>
      @if($arr["subscribe"]=="1")
<span class="btn-outline-success btn-round">Subscribed</span>
@else
<span class="btn-outline-danger btn-round">Unsubscribed</span>
      @endif
    </b></div>
      <div class="col-md-4 item-field">Address <hr> <b>{{$arr["address"]}}</b></div>
    </div>
         <div class="row" style="background-color: black;padding-bottom:15px !important;padding-top:15px !important;">
        <div class="col-md-12">
             <a href="#"><button class="btn btn-info btn-round edit-customer" data="{{$arr['subscriber_id']}}"><i class="now-ui-icons design-2_ruler-pencil"></i> {{__('Edit Subscriber')}}</button></a>
           
<form action="" method="POST" style="display: inline-block">
  @csrf 
<button type="submit"  class="btn btn-danger btn-round"><i class="now-ui-icons ui-1_simple-remove"></i> {{__('Delete Subscriber')}}</button>
</form>
  <a class="btn btn-primary btn-round text-white pull-right" id="edit_customer" href="#" data-toggle="modal" data-target=".bd-example-modal-lg" hidden>Edit</a>

        </div>    
    </div>
     </div>
          <div class="card-body">
    <div class="row">
      <div class="col-md-6">
        <div class="card  card-tasks">
          <div class="card-header ">
            <h5 class="card-category">Total ({{count($arr["emails"])}})</h5>
            <h4 class="card-title">Email Compaigns</h4>
          </div>
          <div class="card-body ">
            <div class="table-full-width table-responsive">
              <table class="table">
                <tbody>
               @if(count($arr["emails"])<1)
 <tr> <td colspan="2"><br><p class="text-danger">No Email Campaigns</p></td></tr>
               @endif
@foreach($arr["emails"] as $email)
                      <tr>                  
                    <td class="text-left"><b>{{$email["campaign_name"]}}</b></td>
                    <td class="td-actions text-right">

                      <a type="button" href="{{url('view_campaign')}}/{{$email['id']}}" class="btn btn-info btn-round btn-icon btn-icon-mini btn-neutral">
                        <i class="now-ui-icons media-1_button-play"></i>
                      </a> <br> 
                      @if($email["received"]=="1") 
                      Received | 
                      @else
                      Received |  
                      @endif
                        @if($email["impression"]=="1") 
                      Viewed
                      @else
                      Not Viewed 
                      @endif   

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
              <a  href="{{ url('new_campaign') }}"><i class="now-ui-icons design_bullet-list-67"></i> View Campaigns</a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card  card-tasks">
          <div class="card-header ">
            <h5 class="card-category">Total ({{count($arr["socials"])}})</h5>
            <h4 class="card-title">Social Media</h4>
          </div>
          <div class="card-body ">
            <div class="table-full-width table-responsive">
              <table class="table">
                <tbody>
           @if(count($arr["socials"])<1)
 <tr> <td colspan="2"><br><p class="text-danger">No Social Media Campaigns</p></td></tr>
               @endif
@foreach($arr["socials"] as $social)
                      <tr>                  
                    <td class="text-left"><b>{{$social["campaign_name"]}}</b></td>
                    <td class="td-actions text-right">
                      <a type="button" href="{{url('view_campaign')}}/{{$social['id']}}" class="btn btn-info btn-round btn-icon btn-icon-mini btn-neutral">
                        <i class="now-ui-icons media-1_button-play"></i>
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
             <a  href="{{ url('new_campaign') }}"><i class="now-ui-icons design_bullet-list-67"></i> View Campaigns</a>
            </div>
          </div>
        </div>
      </div>

  
    </div>

  </div>
  </div>
  </div>
  </div>
@include('modals.subscriber_action')
@endsection
