@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => 'View Campaign',
    'activePage' => 'campaigns',
    'activeNav' => 'marketing',
])

@section('content')
<style>
  .btn, .navbar .navbar-nav>a.btn {
    margin: 1px 1px !important;
    background-color: #f96332 !important;
  }
  input[type='radio']:after {
        width: 15px;
        height: 15px;
        border-radius: 15px;
        top: -2px;
        left: -1px;
        position: relative;
        background-color: lightgrey;
        border: 1px solid #f96332;
        content: '';
        display: inline-block;
        visibility: visible;
        border: 2px solid white;
    }

    input[type='radio']:checked:after {
        width: 15px;
        height: 15px;
        border-radius: 15px;
        top: -2px;
        left: -1px;
        position: relative;
        background-color: #f96332;
        content: '';
        display: inline-block;
        visibility: visible;
        border: 2px solid white;
    }
    .myrow{
      margin-bottom: 10px;
    }
    .cv{
      padding-bottom: 7px;
      padding-top: 7px;
    }
</style>
  <div class="panel-header panel-header-sm">
  </div>
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col-md-10"><h4 class="title text-primary">
                 @if($arr["campaign_type"]=="Email")
              {{__("View Campaign")}}
              @else
{{__("View Post")}}
              @endif
            </h4></div>
              <div class="col-md-2">               
                </div>
            </div>      
            <hr>
            <input type="hidden" id="campaign_type" value="{{$arr['campaign_type']}}">
            <input type="hidden" id="campaign_id" value="{{$arr['campaign_id']}}">
   <div class="row">
    <div class="col-md-6 pr-1" style="padding: 10px; border: 1px dashed #ffc0cb5c;"> 
              <h6 class="mb-1 text-sm"></h6>
                 <div class="row myrow">
                    <div class="col-md-4 pr-1">
                                 <div class="form-group">                          
                            <span class="text-danger"><b>{{$arr["date_entered"]}}</b></span>                       
                        </div>   
                        </div>
                         <div class="col-md-4 pr-1" style="border-right: grey;">
                                <span class="btn-info btn-round cv">{{$arr["status"]}}</span>
                        </div>
                         <div class="col-md-4 pr-1">
                                 <div class="form-group">                          
                            <span class="btn-success btn-round cv">{{$arr["campaign_type"]}}</span>  

                        </div>   
                        </div>
                        </div>
                        <hr>
             
                <div class="row myrow">
                    <div class="col-md-12 pr-1">
                        <div class="form-group">
                          <h6 class="mb-1 text-sm">Campaign Name</h6>
                            <span>{{$arr["campaign_name"]}}</span>
                       
                        </div>
                    </div>
                </div>
                 <div class="row myrow">
                    <div class="col-md-12 pr-1">
                        <div class="form-group">
                          <h6 class="mb-1 text-sm">Compaign Content</h6>
                            <span>{!! $arr["campaign_description"] !!}</span>
                          
                        </div>
                    </div>
                </div>
                               
              <hr class="half-rule"/>
            </form>
          </div>
           <div class="col-md-6 pr-1">
            <div style="padding: 10px; border: 1px dashed #ffc0cb5c;">
              <div class="row">
                    <div class="col-md-12 pr-1">                       
                         
                        <h4 class="mb-1 text-sm">Analytics</h4><hr>
                        @if($arr["campaign_type"]=="Email")    
                                           
                      <div class="row">
                        <div class="col-md-6">
                            <div class="card  card-tasks">
          <div class="card-header ">
          <h6 class="card-title">Audience Received</h6>
          </div>
          <div class="card-body ">
            <div class="table-full-width table-responsive">
              <table class="table">
                <tbody>
             
 <tr> <td colspan="2"><br><span class="btn-outline-danger btn-round">{{$data["total_subscribers"]}}</span></td></tr>          
               
                     
                </tbody>
              </table>
            </div>
          </div>         
        </div>
                        </div>
                          <div class="col-md-6">
                            <div class="card  card-tasks">
          <div class="card-header ">
          <h6 class="card-title">Impressions</h6>
          </div>
          <div class="card-body ">
            <div class="table-full-width table-responsive">
              <table class="table">
                <tbody>
             
 <tr> <td colspan="2"><br><span class="btn-outline-success btn-round">{{$data["total_impressions"]}}</span></td></tr>          
               
                     
                </tbody>
              </table>
            </div>
          </div>         
        </div>
                        </div>
                      </div>
@else
                      <div class="row">
                        <div class="col-md-6">
                           <span id="info"></span>
                            <div class="card  card-tasks">
          <div class="card-header ">
          <h6 class="card-title">Likes</h6>
          </div>
          <div class="card-body ">
            <div class="table-full-width table-responsive">
              <table class="table">
                <tbody>             
 <tr> <td colspan="2"><br><span class="btn-outline-danger btn-round" id="likes">0</span></td></tr>        
               
                     
                </tbody>
              </table>
            </div>
          </div>         
        </div>
                        </div>
                          <div class="col-md-6">
                            <div class="card  card-tasks">
          <div class="card-header ">
          <h6 class="card-title">Engagement</h6>
          </div>
          <div class="card-body ">
            <div class="table-full-width table-responsive">
              <table class="table">
                <tbody>
             
 <tr> <td colspan="2"><br><span class="btn-outline-success btn-round" id="engagements">0</span></td></tr>          
               
                     
                </tbody>
              </table>
            </div>
          </div>         
        </div>
                        </div>
                      </div>


 <div class="row">
                        <div class="col-md-6">
                            <div class="card  card-tasks">
          <div class="card-header ">
          <h6 class="card-title">Comments</h6>
          </div>
          <div class="card-body ">
            <div class="table-full-width table-responsive">
              <table class="table">
                <tbody>
             
 <tr> <td colspan="2"><br><span class="btn-outline-danger btn-round" id="comments">0</span></td></tr>          
               
                     
                </tbody>
              </table>
            </div>
          </div>         
        </div>
                        </div>
                          <div class="col-md-6">
                            <div class="card  card-tasks">
          <div class="card-header ">
          <h6 class="card-title">Shares</h6>
          </div>
          <div class="card-body ">
            <div class="table-full-width table-responsive">
              <table class="table">
                <tbody>
             
 <tr> <td colspan="2"><br><span class="btn-outline-success btn-round" id="shares">0</span></td></tr>          
               
                     
                </tbody>
              </table>
            </div>
          </div>         
        </div>
                        </div>
                      </div>

@endif

                    </div>
                </div>
                </div>
           </div>
         </div>


          </div>
          <div class="card-body">
                                               
          </div>
      
      </div>
    </div>

    </div>
  </div>
@endsection
  