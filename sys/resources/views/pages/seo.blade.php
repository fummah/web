@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => 'SEO',
    'activePage' => 'seo',
    'activeNav' => '',
])

@section('content')
<style type="text/css">
  .numpoint{
    color: #fd7e14 !important; 
    font-size: 12px !important;
    font-weight: 600 !important;
  }
  .numpoint>.span{
    border: 1px solid #fd7e14 !important;
    padding: 10px;
    border-radius:50%;
  }
   .numpoint>span{
    border: 1px solid #fd7e14 !important;
    padding: 10px;    
  }
  .numdesc{
    font-size: 12px !important;
    font-weight: 600 !important;
    color: #000 !important;
  }
    .r4{
    background-color: purple !important;
    color: white !important;
  }
    .r3{
    background-color: red !important;
    color: white !important;
  }
  .r2{
    background-color: grey !important;
    color: white !important;
  }
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
        <div class="card">
          <div class="card-header">
            <form method="GET" action="" autocomplete="off">
              @csrf
              <div class="row">
                <div class="col-md-4">
                    <div class="form-group" style="margin-top: 15px;">
                            <input type="url" class="form-control" placeholder="Enter domain or URL e.g https://example.com" name="search_url" id="search_url" value="{{$search_url}}" required>
                        </div>
                </div>
                <div class="col-md-4">
                  <button class="btn btn-primary wait">Search</button>
                  <h5 id="wait" style="color:red; display: none;">please wait...</h5>
                </div>
              </div>
            </form>
          </div>
          @if($search_url!=="")
          <div class="card-body td"> 
          <div class="card-body all-icons">    
                <div class="row">
              <div class="font-icon-list col-lg-2 col-md-3 col-sm-4 col-xs-6 col-xs-6">
                <div class="font-icon-detail">
                  <p class="numpoint" align="center"><span class="span">{{$obj["quickfacts"]["loadtime"]}}</span> sec</p>
                  <p class="numdesc" align="center">Load Time</p>
                </div>
              </div>
              <div class="font-icon-list col-lg-2 col-md-3 col-sm-4 col-xs-6 col-xs-6">
                <div class="font-icon-detail">
                <p class="numpoint" align="center"><span class="span">{{$obj["quickfacts"]["filesize"]}}</span> MB</p>
                  <p class="numdesc" align="center">Files Size</p>
                </div>
              </div>
              <div class="font-icon-list col-lg-2 col-md-3 col-sm-4 col-xs-6 col-xs-6">
                <div class="font-icon-detail">
                 <p class="numpoint" align="center"><span>{{$obj["quickfacts"]["words"]}}</span></p>
                  <p class="numdesc" align="center">Total Words</p>
                </div>
              </div>
              <div class="font-icon-list col-lg-2 col-md-3 col-sm-4 col-xs-6 col-xs-6">
                <div class="font-icon-detail">
                <p class="numpoint" align="center"><span>{{$obj["quickfacts"]["mediafiles"]}}</span></p>
                  <p class="numdesc" align="center">Media Files</p>
                </div>
              </div>
              <div class="font-icon-list col-lg-2 col-md-3 col-sm-4 col-xs-6 col-xs-6">
                <div class="font-icon-detail">
                 <p class="numpoint" align="center"><span>{{$obj["quickfacts"]["internallinks"]}}</span></p>
                  <p class="numdesc" align="center">Internal Links</p>
                </div>
              </div>
              <div class="font-icon-list col-lg-2 col-md-3 col-sm-4 col-xs-6 col-xs-6">
                <div class="font-icon-detail">
                <p class="numpoint" align="center"><span>{{$obj["quickfacts"]["externallinks"]}}</span></p>
                  <p class="numdesc" align="center">External Links</p>
                </div>
              </div>
            </div>
          </div>
      
<div class="row" style="border-top:1px dashed lightgrey; padding: 10px;">
  <div class="col-md-6">
    <div class="row">
    <div class="col-md-4">
      <div class="card  card-tasks">
          <div class="card-header ">
          <h6 class="card-title">Images</h6>
          </div>
         <div class="card-body ">
            <div class="table-full-width table-responsive">
              <table class="table">
                <tbody>             
 <tr> <td colspan="2"><br><span class="btn-outline-danger btn-round" id="likes">{{count($obj["medialist"]["images"])}}</span></td></tr>        
               
                     
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
        <div class="col-md-4">
      <div class="card  card-tasks">
          <div class="card-header ">
          <h6 class="card-title">Audios</h6>
          </div>
         <div class="card-body ">
            <div class="table-full-width table-responsive">
              <table class="table">
                <tbody>             
 <tr> <td colspan="2"><br><span class="btn-outline-danger btn-round" id="likes">{{count($obj["medialist"]["audio"])}}</span></td></tr>        
               
                     
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
        <div class="col-md-4">
      <div class="card  card-tasks">
          <div class="card-header ">
          <h6 class="card-title">Videos</h6>
          </div>
         <div class="card-body ">
            <div class="table-full-width table-responsive">
              <table class="table">
                <tbody>             
 <tr> <td colspan="2"><br><span class="btn-outline-danger btn-round" id="likes">{{count($obj["medialist"]["videos"])}}</span></td></tr>        
               
                     
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <hr>

    <div class="row">
           <div class="col-md-12">
      <div class="card  card-tasks">
          <div class="card-header ">
          <h6 class="card-title">Quality Results</h6>
          </div>
         <div class="card-body ">
            <div class="table-full-width table-responsive">
              <table class="table">
                <thead><tr><th>Name</th><th>Score</th><th>Points</th><th>Max-Points</th></tr></thead>
                <tbody>           
       <tr style="padding-top: 10px !important;"><td>Structure</td><td><span class="btn" id="likes">{{$obj["groupresults"]["structure"]["score"]}}%</span></td><td><span class="btn-outline-secondary btn-round" id="likes">{{$obj["groupresults"]["structure"]["points"]}}</span></td><td><span class="btn-outline-secondary btn-round" id="likes">{{$obj["groupresults"]["structure"]["maxpoints"]}}</span></td></tr>
           <tr style="padding-top: 10px !important;"><td>Page Quality</td><td><span class="btn" id="likes">{{$obj["groupresults"]["pagequality"]["score"]}}%</span></td><td><span class="btn-outline-secondary btn-round" id="likes">{{$obj["groupresults"]["pagequality"]["points"]}}</span></td><td><span class="btn-outline-secondary btn-round" id="likes">{{$obj["groupresults"]["pagequality"]["maxpoints"]}}</span></td></tr>
               <tr style="padding-top: 10px !important;"><td>Links</td><td><span class="btn" id="likes">{{$obj["groupresults"]["links"]["score"]}}%</span></td><td><span class="btn-outline-secondary btn-round" id="likes">{{$obj["groupresults"]["links"]["points"]}}</span></td><td><span class="btn-outline-secondary btn-round" id="likes">{{$obj["groupresults"]["links"]["maxpoints"]}}</span></td></tr>
                   <tr style="padding-top: 10px !important;"><td>Server</td><td><span class="btn" id="likes">{{$obj["groupresults"]["server"]["score"]}}%</span></td><td><span class="btn-outline-secondary btn-round" id="likes">{{$obj["groupresults"]["server"]["points"]}}</span></td><td><span class="btn-outline-secondary btn-round" id="likes">{{$obj["groupresults"]["server"]["maxpoints"]}}</span></td></tr>

                </tbody>
              </table>             

            </div>
            <hr><h6 class="card-title">Hints</h6>
                <div class="table-full-width table-responsive">
              <table class="table">
                <thead><tr><th>Priority</th><th>Hint</th></tr></thead>
                <tbody> 
                          @foreach($obj["hints"] as $hint)            
 <tr style="padding-top: 10px !important;" class="r{{$hint['priority']}}"><td>{{$hint['priority']}}</td><td>{{$hint['text']}}</td></tr>
 @endforeach           

                </tbody>
              </table>            

            </div>          
          </div>
          </div>
        </div>
    </div>
  </div>
   <div class="col-md-6">
    <div class="row">
       <div class="col-md-12">
      <div class="card  card-tasks">
          <div class="card-header ">
          <h6 class="card-title"><span class="btn-outline-success btn-round" id="likes">{{count($obj["linklist"])}}</span> Total Links</h6>
          </div>
         <div class="card-body ">
            <div class="table-full-width table-responsive ScrollBar">
              <table class="table">
                <tbody> 
                @foreach($obj["linklist"] as $link)            
 <tr> <td colspan="2"><br>{{$link["link"]}}</td></tr>  
 @endforeach               
                </tbody>
              </table>
            </div>
               <hr><h6 class="card-title">Search Keywords</h6>
                <div class="table-full-width table-responsive ScrollBar">
              <table class="table">
                <thead><tr><th>Keyword</th><th>Score</th></tr></thead>
                <tbody>  
                       @foreach($obj["keywords"] as $keyword)            
 <tr><td>{{$keyword["keyword"]}}</td><td><br><span class="btn-outline-success btn-round" id="likes">{{$keyword["score"]}}%</span></td></tr>
 @endforeach       
                </tbody>
              </table>             

            </div>

          </div>
          </div>
        </div>
      </div>
   </div>
</div>

          </div>
          @else
          <h5 style="padding-left:30px">
Discover the performance of your site.
          </h5>
          @endif
          <!-- end content-->
        </div>
        <!--  end card  -->
      </div>
      <!-- end col-md-12 -->
    </div>
  
    <!-- end row -->
  </div>
@endsection