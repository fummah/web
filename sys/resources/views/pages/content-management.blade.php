@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => 'Content Management',
    'activePage' => 'content-management',
    'activeNav' => '',
])

@section('content')
<style type="text/css">
  
</style>
  <div class="panel-header panel-header-sm">
  </div>
  <div class="content">    
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
     <h5 class="title text-primary">{{__(" Content Management")}}</h5>
          </div>      
          <div class="card-body td"> 
          <div class="card-body all-icons">    
                <div class="row">
              <div class="font-icon-list col-lg-6 col-md-6 col-sm-6 col-xs-6 col-xs-6">
                <a href="{{ route('content_generator') }}">
                <div class="font-icon-detail">
                  <h3 class="text-primary"><i class="now-ui-icons loader_gear"></i> Content Generator</h3>
                </div>
              </a>
              </div>
              <div class="font-icon-list col-lg-6 col-md-6 col-sm-6 col-xs-6 col-xs-6">
                <a href="{{ route('keyword_suggestion') }}">
                <div class="font-icon-detail">
              <h3 class="text-primary"><i class="now-ui-icons objects_diamond"></i> SEO Keyword Suggestion</h3>
                </div>
              </a>
              </div>            
            </div>
              <div class="row">
              <div class="font-icon-list col-lg-6 col-md-6 col-sm-6 col-xs-6 col-xs-6">
                <a href="{{ route('image_editor') }}">
                <div class="font-icon-detail">
                  <h3 class="text-primary"><i class="now-ui-icons media-1_album"></i> Image Editor</h3>
                </div>
              </a>
              </div>
              <div class="font-icon-list col-lg-6 col-md-6 col-sm-6 col-xs-6 col-xs-6">
                <a href="{{ route('seo') }}">
                <div class="font-icon-detail">
              <h3 class="text-primary"><i class="now-ui-icons design_palette"></i> SEO</h3>
                </div>
              </a>
              </div>            
            </div>
          </div>
          </div>
          <!-- end content-->
        </div>
        <!--  end card  -->
      </div>
      <!-- end col-md-12 -->
    </div>
  
    <!-- end row -->
  </div>
@endsection