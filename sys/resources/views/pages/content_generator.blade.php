@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => 'Generate Content',
    'activePage' => 'content_generator',
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
              <div class="col-md-10"><h4 class="title text-primary">{{__("Generate Content")}}</h4></div>
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
   <form action="{{ route('content_generator') }}" id="formAjax" method="POST" enctype="multipart/form-data">
    @csrf
    </form>
                     <div class="row">
                    <div class="col-md-12 pr-1">
                        <div class="form-group">
                           
                            <h6 class="mb-1 text-sm">Generate Emails, Social Media Ads content</h6>
<span class="text-xs">Getting 3 Social Media captions for : </span>
                              <div style="margin: 10px 10px;">
                                <input name="prompt_name" id="prompt_name" class="form-control" placeholder="seo adverts, web development, etc">                               
                            </div>

                        </div>
                        <button type="submit" class="btn btn-primary ai" data="0">Generate Text</button>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12 pr-1">
                        <div class="form-group">
                           
                            <h6 class="mb-1 text-sm">Generate Images</h6>
<span class="text-xs">Getting best image for your adverting</span>
                              <div style="margin: 10px 10px;">
                                                                                   
                            </div>

                        </div>
                        <button type="submit" class="btn btn-primary ai" data="1">Generate Image</button>
                    </div>
                </div><hr>
                  <div class="row">
                    <div class="col-md-12 pr-1">
                        <div class="form-group">
                         
                              <div style="margin: 10px 10px;">
                                                                                   
                            </div>

                        </div>
                        <button type="submit" style="cursor: pointer; display:none" class="btn-success btn-round ai" id="regenerate"><i class="now-ui-icons loader_refresh"></i> Re-Generate</button>
                    </div>
                </div>
            
          </div>
           <div class="col-md-6 pr-1">
            <div style="padding: 10px; border: 1px dashed #ffc0cb5c;">
              <div class="row">
                    <div class="col-md-12 pr-1">                       
                         
                        <h4 class="mb-1 text-sm">Results Panel</h4><hr>
                        <div class="" style="background-color: black; color: #56DB3A; padding:15px"> 
                       <b>           
             <span id="info">Results shown here</span>
 
           </b>
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
