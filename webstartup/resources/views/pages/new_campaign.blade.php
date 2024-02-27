@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => 'New Campaign',
    'activePage' => 'campaigns',
    'activeNav' => 'marketing',
])

@section('content')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
  <link href="{{ asset('assets') }}/demo/select2.min.css" rel="stylesheet" />
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
    .row{
      margin-bottom: 15px !important;
    }
    select[name='subscriberTable_length'],#subscriberTable_length{
display: none;
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
                @if($type=="email")
              {{__("New Email Campaign")}}
              @else
{{__("New Social Media Post on WebstartUp Page")}}
              @endif
            </h4></div>
              <div class="col-md-2">               
                </div>
            </div>      
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
    <div class="col-md-6 pr-1">
      @if($type=="email")
   <form action="{{ route('add_campaign') }}" id="formAjax" method="POST" enctype="multipart/form-data">
              @csrf
              <h6 class="mb-1 text-sm">Select Audience ({{$intcount}})</h6>
                 <div class="row" style="margin-bottom: 5px;margin-top: 10px;">
                    <div class="col-md-4 pr-1">
                                 <div class="form-group">                          
                            <label><input type="radio" name="campaign_type" id="campaign_type" value="1" checked> <b>All Audiences</b></label>                        
                        </div>   
                        </div>
                         <div class="col-md-1 pr-1" style="border-right: grey;">
                                
                        </div>
                         <div class="col-md-4 pr-1">
                                 <div class="form-group">                          
                            <label><input type="radio" name="campaign_type" id="campaign_type" value="0"> <b>Selected Audiences</b></label>  
<div id="eem" style="display: none;">
                            <select class="js-example-basic-single form-control" name="subscribers[]" id="subscribers" style="width:100%" multiple="multiple">
                                    <option value="">Select</option>

</select>
</div>
                        </div>   
                        </div>
                        </div>
             
                <div class="row">
                    <div class="col-md-12 pr-1">
                        <div class="form-group">
                          <h6 class="mb-1 text-sm">Campaign Name</h6>
                            <input type="text" class="form-control" name="campaign_name" id="campaign_name">
<span class="text-xs">For Internal Use Only</span>                          
                        </div>
                    </div>
                </div>           
                   <div class="row">
                    <div class="col-md-12 pr-1">
                        <div class="form-group">
                          <h6 class="mb-1 text-sm">From Name</h6>
                            <input type="text" class="form-control" name="from_name" id="from_name" value="{{$name}}" readonly>
                         
                        </div>
                    </div>
                </div>
                  <div class="row">
                    <div class="col-md-12 pr-1">
                        <div class="form-group">
                          <h6 class="mb-1 text-sm">From Email</h6>
                            <input type="text" class="form-control" name="campaign_email" id="campaign_email" value="marketing@webstartup.io" readonly>
                         
                        </div>
                    </div>
                </div>
                  <div class="row">
                    <div class="col-md-12 pr-1">
                        <div class="form-group">
                          <h6 class="mb-1 text-sm">Create Message / Template</h6>
                            <textarea id="summernote" name="editordata" id="editordata" placeholder="Compose Message ..."></textarea>
                        </div>
                    </div>
                </div>
              <div class="card-footer ">
                <button type="submit" class="btn btn-primary btn-round"><i class="now-ui-icons arrows-1_cloud-upload-94"></i> {{__('Add New Campaign')}}</button>
              </div>
              <hr class="half-rule"/>
              <input type="hidden" name="page" id="page" value="campaigns">
            </form>
            @else
   <form action="{{ route('add_socialpost') }}" id="formAjax" method="POST" enctype="multipart/form-data">
              @csrf
                     
             
                <div class="row">
                    <div class="col-md-12 pr-1">
                        <div class="form-group">
                          <h6 class="mb-1 text-sm">Post Name</h6>
                            <input type="text" class="form-control" name="campaign_name" id="campaign_name">
<span class="text-xs">For Internal Use Only</span>                          
                        </div>
                    </div>
                </div>           
                   <div class="row">
                    <div class="col-md-12 pr-1">
                        <div class="form-group">
                          <h6 class="mb-1 text-sm">From Name</h6>
                            <input type="text" class="form-control" name="from_name" id="from_name" value="Webstartup API" readonly>
                         
                        </div>
                    </div>
                </div>                
                  <div class="row">
                    <div class="col-md-12 pr-1">
                        <div class="form-group">
                          <h6 class="mb-1 text-sm">Create a Post</h6>
                            <textarea id="api_text" name="api_text" class="form-control" style="border: 1px solid grey; border-radius: 5px;" placeholder="Compose Post ..."></textarea>
                        </div>
                    </div>
                </div>
              <div class="card-footer ">
                <button type="submit" class="btn btn-primary btn-round"><i class="now-ui-icons arrows-1_cloud-upload-94"></i> {{__('Post On Facebook')}}</button>
              </div>
              <hr class="half-rule"/>
              <input type="hidden" name="page" id="page" value="campaigns">
            </form>
                <br>
              
            @endif
          </div>

           <div class="col-md-6 pr-1">
            <div style="padding: 10px; border: 1px dashed #ffc0cb5c;">
              <div class="row">
                    <div class="col-md-12 pr-1">                       
                         
                        <h4 class="mb-1 text-sm">All My Campaigns</h4><hr>
                        <div class="" style="border-bottom: 1px solid #f96332a8;"> 
                         <table id="subscriberTable" class="table table-striped display dataTable"width="100%">
                       <thead>
                <tr>               
               
                  </tr>
              </thead>         
            </table>            
             
            </div>
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
  