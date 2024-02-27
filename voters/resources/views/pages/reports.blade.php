@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script>
$(function() {

    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
		$("#dat1").val(start.format('YYYY-MM-DD'));
		$("#dat2").val(end.format('YYYY-MM-DD'));
		executeAnalysis();
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);

});
</script>
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Dashboard'])
			   <div id="alert">
        @include('components.alert')
    </div>
    <div class="container-fluid py-4">
        <div class="row mt-4">
            <div class="col-md-4">
			
                <div class="card z-index-2 h-100">
                    <div class="card-header pb-0 pt-3 bg-transparent">
                        <h6 class="text-capitalize">Legislation Trend</h6>
                        <p class="text-sm mb-0">
                            <i class="fa fa-arrow-up text-success"></i>
                        </p>
                    </div>
                    <div class="card-body p-3">
                        <div class="chart">
                            <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
    <div class="card z-index-2 h-100">
                    <div class="card-header pb-0 pt-3 bg-transparent">
                        <h6 class="text-capitalize">Election Trend</h6>
                        <p class="text-sm mb-0">
                            <i class="fa fa-arrow-up text-success"></i>

                        </p>
                    </div>
                    <div class="card-body p-3">
                        <div class="chart">
                            <canvas id="chart-line2" class="chart-canvas" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div> 
			<div class="col-md-4">
    <div class="card z-index-2 h-100">
                    <div class="card-header pb-0 pt-3 bg-transparent">
                        <h6 class="text-capitalize">Topics Trend</h6>
                        <p class="text-sm mb-0">
                            <i class="fa fa-arrow-up text-success"></i>

                        </p>
                    </div>
                    <div class="card-body p-3">
                        <div class="chart">
                            <canvas id="chart-line3" class="chart-canvas" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<div class="row mt-4">
		<div class="col-md-4">
		<div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
    <i class="fa fa-calendar"></i>&nbsp;
    <span></span> <i class="fa fa-caret-down"></i>
</div>
                    <input type="hidden" id="dat1">
                    <input type="hidden" id="dat2">
                   
		</div>
		<div class="col-md-2">	
		<div class="form-group">								
                                    <select class="form-control ochang" type="text" name="category" id="category">
									<option value="">Select Category</option>
									<option value="legislation">Legislation</option>
									<option value="elections">Election</option>
									<option value="topics">Topic</option>
									</select>
									</div>
		</div>
		
		<div class="col-md-2">
		<div class="form-group">			
<div>		
                                 <input type="" class="form-control" placeholder="Type name ..." id="search_term_txt">
								 
									</div>
									 <span id="suggesstion-box" class="et_pb_module et_pb_text et_pb_text_3  et_pb_text_align_left et_pb_bg_layout_light"></span>
									</div>
		</div>
		<div class="col-md-2">
	
				<div class="form-group">								
                                    <select class="form-control ochang" type="text" name="state" id="state">
									<option value="">Select State</option>
									@foreach($states as $state)
									<option value="{{$state['country']}}">{{$state["country"]}}</option>									
									@endforeach
									</select>
									</div>
		</div>
		<div class="col-md-2">
				<div class="form-group">								
                                    <select class="form-control ochang" type="text" name="congressional" id="congressional">
									<option value="">Select Congressional</option>
									@foreach($congressional as $congress)
									<option value="{{$congress['congressional']}}">{{$congress["congressional"]}}</option>									
									@endforeach
									</select>
									</div>
		</div>
	
		</div>
<div class="row">
<div class="col-md-12">

<div class="card card-body" id="result">
<p class="text-danger" align="center">No Results</p>
</div>
</div>
</div>
        @include('layouts.footers.auth.footer')
    </div>
<input type="hidden" id="searched_id">
@endsection

@push('js')
    <script src="./assets/js/plugins/chartjs.min.js"></script>
    <script src="./assets/js/legislation-trend.js"></script>
    <script src="./assets/js/elections-trend.js"></script>
    <script src="./assets/js/topics-trend.js"></script>
  
@endpush
