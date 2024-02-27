@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => 'Projects / Designs',
    'activePage' => 'projects',
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
            <div class="row">
              <div class="col-md-10"><h5 class="title">{{__(" Projects / Designs")}}</h5></div>
              <div class="col-md-2"><button class="btn btn-outline-info btn-round" style="color:black !important;border-color:black !important" data-toggle="modal" data-target=".bd-example-modal-lg"><b>Add New Project</b></button></div>
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
          </div>
          <div class="card-body">
                                                      @if (count($projects) < 1)
                                                      <hr>
    <h5 align="center" class="text-danger text-center">Currently No Projects / Designs submitted</h5>
@endif
           @foreach($projects as $project)
       
<div class="project-card-summary">
             
                <div class="card-body p-3">
                    <ul class="list-group">
                             <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape icon-sm me-3 bg-gradient-danger shadow text-center">
                                  
                                </div>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 text-danger text-sm"><i class="now-ui-icons objects_spaceship desktop"></i> {{$project["project_name"]}}</h6>
                                    <span class="text-xs">{{$project["project_description"]}} </span>
                                </div>
                            </div>
                            <div class="d-flex">
                              <a href="{{ url('create_brief/'.$project['project_id']) }}"><button type="submit" class="btn btn-primary btn-round"><i class="now-ui-icons education_paper desktop"></i> 
                                @if($role=="Admin" || strpos($role, 'CRM') !== false)
                              View
                              @else
Create a brief
                              @endif
                            </button></a>
                             
                            </div>
                        </li>
                                               
                        
                    </ul>
                </div>
            </div>
            @endforeach 
            {{ $projects->links() }}  
          </div>
      
      </div>
    </div>

    </div>
  </div>



<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
     <div class="modal-header">
        <h5 class="modal-title text-primary" id="exampleModalLongTitle"><b>Add New Project / Design</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form action="{{ route('create_project') }}" method="POST" enctype="multipart/form-data">
      <div class="modal-body">
       

              @csrf 
                <div class="row">
                    <div class="col-md-12 pr-1">
                        <div class="form-group">
               
                            <h6 class="mb-1 text-sm">Select a Customer</h6>
<span class="text-xs">Below is a list of all your customers</span>
                            <div style="margin: 10px 10px;">
                                <select name="customer_id" class="form-control" required>   
                                    <option>[Select your customer]</option>
                                    @foreach($customers as $customer)
                                  <option value="{{$customer['id']}}">{{$customer['name']}}</option>
                                    @endforeach
                                    
                                </select>                            
                            </div>
                        </div>
                    </div>
                </div>
            <div class="row">
                    <div class="col-md-12 pr-1">
                        <div class="form-group">
               
                            <h6 class="mb-1 text-sm">What is the project name?</h6>
<span class="text-xs">This is just the title of the project to kick start everything, should be brief</span>
                            <div style="margin: 10px 10px;">
                                <input name="project_name" class="form-control" placeholder="Project name" required>                               
                            </div>
                        </div>
                    </div>
                </div>
            <div class="row">
                    <div class="col-md-12 pr-1">
                        <div class="form-group">
                           
                            <h6 class="mb-1 text-sm">What is the description of your project?</h6>
<span class="text-xs">Explain everything needed on this project / design.</span>
                            <div style="margin: 10px 10px;">
                                <textarea name="project_description" class="form-control" style="border: 1px solid grey; border-radius: 5px;" placeholder="Description here ..." required></textarea>                               
                            </div>
                        </div>
                    </div>
                </div>
             
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Submit Now</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        
      </div>
       </form>
    </div>
  </div>
</div>
@endsection