@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => 'Subscribers',
    'activePage' => 'audience',
    'activeNav' => '',
])

@section('content')
<link href="{{ asset('assets') }}/css/dataTables.bootstrap4.css" rel="stylesheet" />
<link href="{{ asset('assets') }}/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
  <div class="panel-header panel-header-sm">
  </div>
  <div class="content">
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
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
              <a class="btn btn-primary btn-round text-white pull-right" id="new_customer" href="#" data-toggle="modal" data-target=".bd-example-modal-lg">Add New Subscriber</a>
                 <a class="btn btn-primary btn-round text-white pull-right" id="edit_customer" href="#" data-toggle="modal" data-target=".bd-example-modal-lg" hidden>Edit</a>
            <h4 class="card-title">Subscribers</h4>
            <div class="col-12 mt-2">
                                        </div>
          </div>
          <div class="card-body td">
            <div class="toolbar">
              <!--        Here you can write extra buttons/actions for the toolbar              -->
            </div>
            <form method="POST" action="#" autocomplete="off">
              @csrf
            </form>
            <input type="hidden" id="page" value="subscribers">
            <table id="subscriberTable" class="table table-striped display dataTable"width="100%">
              <thead>
                <tr>               
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Email</th>
                  <th>Contact Number</th>                  
                  <th class="disabled-sorting text-right"></th>
                </tr>
              </thead>         
            </table>
               
          </div>
          <!-- end content-->
        </div>
        <!--  end card  -->
      </div>
      <!-- end col-md-12 -->
    </div>
  
    <!-- end row -->
  </div>
@include('modals.subscriber_action')
@endsection