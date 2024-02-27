@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => 'Image Editor',
    'activePage' => 'image_editor',
    'activeNav' => '',
])

@section('content')
<style type="text/css">

  .ScrollBar
{
    overflow-y: scroll;
    max-height: 300px;
    max-width: 100%;
    text-align: center;
    background-color: azure;
}       
</style>
  <div class="panel-header panel-header-sm">
  </div>
  <div class="content">    
    <div class="row">
      <div class="col-md-12">
     

    <div class="col-md-12">
      <div class="card  card-tasks">          
         <div class="card-body">
        <iframe src="https://webstartup.io/editors/index.html" height="700" width="100%"></iframe>
      </div>
     
    </div>
    <hr>
  </div>
</div>
         
      <!-- end col-md-12 -->
    </div>
  
    <!-- end row -->
  </div>
@endsection