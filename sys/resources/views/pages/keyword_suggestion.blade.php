@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => 'Keyword Suggestion',
    'activePage' => 'content_management',
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
    max-height: 500px;
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
                            <input type="text" class="form-control" placeholder="Enter your keyword" name="keyword" id="keyword" value="{{$keyword}}" REQUIRED>
                        </div>
                </div>
                 <div class="col-md-4">
                    <div class="form-group" style="margin-top: 15px;">
                     
                            <select class="form-control" name="location" id="location" required>
                              <option>Select Location</option>
                              @foreach($countries as $country)
                              <option value="{{$country['code']}}">{{$country["name"]}}</option>
                              @endforeach
                            </select>
                        </div>
                </div>
                <div class="col-md-4">
                  <button class="btn btn-primary wait">Search</button>
                  <h5 id="wait" style="color:red; display: none;">please wait...</h5>
                </div>
              </div>
            </form>
          </div>
          @if($keyword!=="")
          <div class="card-body td">         
      <hr>
<div class="row" style="border-top:1px dashed lightgrey; padding: 10px;">

    <div class="col-md-12">
      <div class="card  card-tasks">
          <div class="card-header ">
          <h6 class="card-title">Suggested Keywords</h6>
          </div>
         <div class="card-body ">
            <div class="table-full-width table-responsive ScrollBar">
              <table class="table">
                <thead><tr><th>Keyword</th><th>Score</th><th>Competition</th></tr></thead>
                <tbody> 
                @foreach($response as $res)            
 <tr> <td>{{$res["text"]}}</td><td style="background-color: black !important; color: white !important;">{{$res["score"]}}</td><td style="background-color: grey !important; color: white !important;">{{$res["competition"]}}</td></tr>        
               @endforeach
                     
                </tbody>
              </table>
            </div>
          </div>
        </div>     
    <hr>
  </div>
</div>
          </div>
          @else
          <h5 style="padding-left:30px">
Discover new keywords.
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