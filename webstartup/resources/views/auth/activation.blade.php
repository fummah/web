@extends('layouts.app', [
    'namePage' => 'Register page',
    'activePage' => 'register',
    'backgroundImage' => asset('assets') . "/img/bg16.jpg",
])

@section('content')
  <div class="content">
    <div class="container">
      <div class="row">
       
        <div class="col-md-10 mr-auto">
          <div class="card card-signup text-center">
            <div class="card-header ">
              <h4 class="card-title">{{ __('Enter Code to Activate your account') }}</h4>
              <p class="card-title text-danger">{{ __('Check your email account and enter the code below') }}</p>
           
            </div>
            <div class="card-body ">
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
              <form method="POST" action="{{ route('activation') }}">
                @csrf          
                           
                <!--Begin input email -->
                <div class="input-group {{ $errors->has('email') ? ' has-danger' : '' }}" hidden>
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      <i class="now-ui-icons ui-1_email-85"></i>
                    </div>
                  </div>
                  <input class="form-control" placeholder="{{ __('Email') }}" type="email" name="email" value="{{$temp_email}}" required readonly>
                 </div>
                <!--Begin input password -->
                <div class="input-group {{ $errors->has('password') ? ' has-danger' : '' }}">
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      <i class="now-ui-icons objects_key-25"></i>
                    </div>
                  </div>
                  <input class="form-control" style="text-align: center;" placeholder="{{ __('Enter code here') }}" type="number" name="code" required>               
                </div>             
                <div class="card-footer ">
                  <button type="submit" class="btn btn-primary btn-round btn-lg">{{__('Send')}}</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('js')
  <script>
    $(document).ready(function() {
      demo.checkFullPageBackgroundImage();
    });
  </script>
@endpush
