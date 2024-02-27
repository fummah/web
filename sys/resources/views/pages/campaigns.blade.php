@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => 'Campaigns',
    'activePage' => 'campaigns',
    'activeNav' => 'marketing',
])

@section('content')
  <div class="panel-header panel-header-sm">
  </div>
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col-md-10"><h4 class="title text-primary">
             
                <img width="64" height="56" src="{{asset('assets')}}/img/conversion.svg"> 
              {{__("My Campaigns")}}</h4></div>
              <div class="col-md-2">
    <a href="{{url('content_generator')}}" class="btn btn-primary btn-round"><i class="now-ui-icons business_bulb-63"></i> Generate Content</a>
              </div>
            </div>           
             @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

          </div>
          <div class="card-body">
            <hr>
            <a href="{{url('new_campaign/email')}}" type="button" class="btn btn-outline-success btn-round"><i class="now-ui-icons ui-1_email-85"></i> Emails</a>
            <a href="{{url('new_campaign/social_media')}}" type="button" class="btn btn-outline-primary btn-round"><i class="now-ui-icons ui-2_chat-round"></i> Social Media</a>
            <hr>
                <div class="row">
                       <div class="col-md-4">
        <div class="card  card-tasks">
          <div class="card-header ">
           
            <h4 class="card-title">Total Subscribers</h4>
          </div>
          <div class="card-body ">
            <div class="table-full-width table-responsive">
              <table class="table">
                <tbody>
          
 <tr> <td colspan="2"><br><span class="btn-outline-info btn-round">{{$data["total_subscribers"]}}</span></td></tr>          
                  
                     
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer ">
            <hr>
            <div class="stats">
              <a  href="{{url('audience')}}"><i class="now-ui-icons design_bullet-list-67"></i> View Subscribers</a>
            </div>
          </div>
        </div>
      </div>
           <div class="col-md-4">
        <div class="card  card-tasks">
          <div class="card-header ">
          <h4 class="card-title">Email Compaigns</h4>
          </div>
          <div class="card-body ">
            <div class="table-full-width table-responsive">
              <table class="table">
                <tbody>
             
 <tr> <td colspan="2"><br><span class="btn-outline-danger btn-round">{{$data["total_campaigns"]}}</span></td></tr>            
               
                     
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer ">
            <hr>
            <div class="stats">
              <a  href="{{ url('new_campaign/email') }}"><i class="now-ui-icons design_bullet-list-67"></i> View Email Campaigns</a>
            </div>
          </div>
        </div>
      </div>
       <div class="col-md-4">
        <div class="card  card-tasks">
          <div class="card-header ">
      <h4 class="card-title">Social Media Campaigns</h4>
          </div>
          <div class="card-body ">
            <div class="table-full-width table-responsive">
              <table class="table">
                <tbody>
             
 <tr> <td colspan="2"><br><span class="btn-outline-success btn-round">{{$data["total_campaigns_social"]}}</span></td></tr>            
                     
               
                     
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer ">
            <hr>
            <div class="stats">
              <a  href="{{ url('new_campaign/social_media') }}"><i class="now-ui-icons design_bullet-list-67"></i> View Social Media Campaigns</a>
            </div>
          </div>
        </div>
      </div>

                </div>                               
          </div>
      
      </div>
    </div>

    </div>
  </div>
@endsection