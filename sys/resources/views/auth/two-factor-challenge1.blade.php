@extends('layouts.app', [
    'namePage' => 'Two Factor Authentication',
    'activePage' => 'authentication',
    'backgroundImage' => asset('assets') . "/img/bg16.jpg",
])

@section('content')
  <div class="content">
    <div class="container">
      <div class="row">
       
        <div class="col-md-10 mr-auto">
          <div class="card card-signup text-center">
            <div class="card-header ">
              <h4 class="card-title">{{ __('Please Enter Authentication Code') }}</h4>           
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
              <form method="POST" action="{{ route('two-factor.login') }}">
    @csrf

    <div>
        <label for="code">Authentication Code</label>
        <input id="code" type="text" name="code" required autocomplete="one-time-code">
    </div>

    <div>
        <button type="submit" class="btn btn-primary btn-round btn-lg">
            Authenticate
        </button>
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
