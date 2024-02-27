@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => 'Project Requirements',
    'activePage' => 'create_brief',
    'activeNav' => '',
])

@section('content')
  <div class="panel-header panel-header-sm">
  </div>
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h5 class="title">{{__(" Project Requirements")}}</h5>
          </div>
          <div class="card-body">
                @if($role=="Admin")
<div class="row">

      <div class="col-md-3 item-field">Name <hr> <b>{{$project["name"]}}</b></div>
      <div class="col-md-3 item-field">Email <hr> <b>{{$project["email"]}}</b></div>
      <div class="col-md-3 item-field">Contact Number <hr> <b>{{$project["contact_number"]}}</b></div>
      <div class="col-md-3 item-field">
           <a type="button" href="{{url('customer_details')}}/{{$project['user_id']}}" class="btn btn-warning btn-round" title="View Customer Details"><i class="now-ui-icons design_bullet-list-67"></i> View Customer Details</a>
      </div>
    
    </div>
    @endif
                 <ul class="list-group">
                             <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
             <div class="d-flex flex-column">
                                    <h5 class="mb-1 text-danger text-sm"><i class="now-ui-icons objects_spaceship desktop"></i> <b>{{$project['project_name']}}</b></h5>
                                    <span class="text-xs">{{$project['project_description']}}</span>

                                </div>
                                <div class="d-flex" >
                                    <button type="submit" class="btn btn-outline-primary btn-round"> {{$project['project_status']}}</button>
                                </div>
                            </li>
                        </ul>

                                <hr>
                                  @if($role=="Ordinary")
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

                                @if (count($contenttext) > 0)
    <h5 class="text-success">Further Projection Requirements</h5>
@endif
                                   @foreach($contenttext as $txt)
<div class="alert alert-primary alert-with-icon" data-notify="container">
              <button type="button" aria-hidden="true" class="close">
                <i class="now-ui-icons education_paper"></i>
              </button>
              <span data-notify="icon" class="now-ui-icons design_vector"></span>
           
              <span data-notify="message">{{ $txt["content_name"] }}</span>

            </div>
            @endforeach
                                          @if (count($contenttext) > 0)
    <h5 class="text-success">Files</h5>
@endif
            @foreach($contentfiles as $files)
<a href=""><button class="btn btn-outline-success btn-round">  <i class="now-ui-icons files_paper"></i> {{$files["content_name"]}}</button></a>
            @endforeach
                                                    @if (count($contenttext) > 0)
       <hr>
@endif
                            
                              
           <form action="{{ route('upload_file') }}" id="formAjax" method="POST" enctype="multipart/form-data">

              @csrf            
            
              <div class="row">
              </div>
                <div class="row">
                    <div class="col-md-12 pr-1">
                        <div class="form-group">
                            <input type="hidden" name="project_id" id="project_id" value="{{$project['project_id']}}">
                            <h6 class="mb-1 text-sm">What are your further requirements?</h6>
<span class="text-xs">This will help to get the project done accurately.</span>
                            <div style="margin: 10px 10px;">
                                <textarea name="project_description" id="project_description" class="form-control" style="border: 1px solid grey; border-radius: 5px;" placeholder="Description here (optional) ..."></textarea>                               
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                  <div class="col-md-12 pr-1">
                    <div class="">
                            <h6 class="mb-1 text-sm">What files/documents you want to submit?</h6>
<span class="text-xs">Submit all your files to support project requirents</span>
                            <div style="margin: 10px 10px;">
                            <div class="file-drop-area" style="border:1px dashed dimgrey;">
  <span class="fake-btn">Choose files</span>
  <span class="file-msg">or drag and drop files here</span>
  <input class="file-input" type="file" id="fileAjax" name="files[]" multiple>
</div>
                            </div>
                        </div>
                      
                 
                  </div>
                </div>
              <div class="card-footer ">
                <button type="submit" class="btn btn-primary btn-round"><i class="now-ui-icons arrows-1_cloud-upload-94"></i> {{__('Save')}}</button>
              </div>
              <hr class="half-rule"/>
            </form>
            @endif
              @if($role=="Admin")
              <form action="{{ url('deleteentity/lead') }}/{{$project['project_id']}}" method="POST" style="display: inline-block">
  @csrf 
<button type="submit"  class="btn btn-danger btn-round"><i class="now-ui-icons ui-1_simple-delete"></i> Delete Record</button>
</form>
@if(count($myids["myorder"])>0)
<form action="{{ url('item/edit/order') }}/{{$myids['myorder'][0]['id']}}" method="GET" style="display: inline-block">
  @csrf 
  <input type="hidden" name="lead_id" value="{{$project['project_id']}}">
<button type="submit" title="View order"  class="btn btn-info btn-round">{{$myids['myorder'][0]['order_number']}}</button>
</form>
@else
<form action="{{ url('item/create/quote') }}/{{$project['user_id']}}" method="GET" style="display: inline-block">
  @csrf 
  <input type="hidden" name="lead_id" value="{{$project['project_id']}}">
<button type="submit"  class="btn btn-info btn-round"><i class="now-ui-icons ui-1_simple-add"></i> {{__('Create New Order')}}</button>
</form>
@endif
@if(count($myids["myquote"])>0)
<form action="{{ url('item/edit/quote') }}/{{$myids['myquote'][0]['id']}}" method="GET" style="display: inline-block">
  @csrf 
  <input type="hidden" name="lead_id" value="{{$project['project_id']}}">
<button type="submit" title="View quote"  class="btn btn-primary btn-round">{{$myids['myquote'][0]['quote_number']}}</button>
</form>
@else
<form action="{{ url('item/create/quote') }}/{{$project['user_id']}}" method="GET" style="display: inline-block">
  @csrf 
  <input type="hidden" name="lead_id" value="{{$project['project_id']}}">
<button type="submit"  class="btn btn-primary btn-round"><i class="now-ui-icons ui-1_simple-add"></i> {{__('Create New Quote')}}</button>
</form>
@endif
@if(count($myids["myinvoice"])>0)
<form action="{{ url('item/edit/invoice') }}/{{$myids['myinvoice'][0]['id']}}" method="GET" style="display: inline-block">
  @csrf 
  <input type="hidden" name="lead_id" value="{{$project['project_id']}}">
<button type="submit" title="View invoice"  class="btn btn-secondary btn-round">{{$myids['myinvoice'][0]['invoice_number']}}</button>
</form>
@else
<form action="{{ url('item/create/invoice') }}/{{$project['user_id']}}" method="GET" style="display: inline-block">
  @csrf 
  <input type="hidden" name="lead_id" value="{{$project['project_id']}}">
<button type="submit"  class="btn btn-secondary btn-round"><i class="now-ui-icons ui-1_simple-add"></i> {{__('Create New Invoice')}}</button>
</form>
@endif
<a type="button" href="{{ url('cancellead/') }}/{{$project['project_id']}}" class="btn btn-danger btn-round"><i class="now-ui-icons ui-1_simple-remove"></i> {{__('Cancel')}}</a>

      @endif
          </div>
          <div style="margin-left:20px !important">
 <p id="status"></p>
  <p id="iffo"></p>
    <p id="demo"></p>
  <input type="hidden" id="myfiles" name="myfiles">
  </div>
      </div>

    </div>
    
    </div>
 
  </div>


@endsection