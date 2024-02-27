@extends('layouts.app')

@section('content')
  @include('layouts.navbars.guest.navbar')
    <main class="main-content  mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="row" >
					<div class="col-md-4">
					</div>
                        <div class="col-md-4 d-flex flex-column mx-lg-0 mx-auto content-center" >
					<div class="archivo pad pad-desktop" style="margin-bottom:10px">The Voters voice is where we come together to be heard by our elected officials. 
								You can tell them how you want them to vote on new piece of legislation or campaign issues.</div>	
                            <div class="card card-plain"> 
						
                                <div class="card-body background-blue border-rad">								
								 <h4 class="font-weight-bolder font-white archivo" align="center">Sign In</h4>
                                    <form role="form" method="POST" action="{{ route('login.perform') }}">
                                        @csrf
                                        @method('post')
                                        <div class="flex flex-col mb-3">
                                            <input type="email" name="email" class="form-control form-control-lg" value="" placeholder="Username" aria-label="Email">
                                            @error('email') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                                        </div>
                                        <div class="flex flex-col mb-3">
                                            <input type="password" name="password" class="form-control form-control-lg" aria-label="Password" placeholder="Password" value="" >
                                            @error('password') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" name="remember" type="checkbox" id="rememberMe">
                                            <label class="form-check-label font-white archivo" for="rememberMe">Remember me</label>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">Sign in</button>
                                        </div>
                                          <div class="text-center">
                                   <a href="{{ route('register') }}" <button type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2">Sign up</button></a>
                                </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                    <p class="mb-1 text-sm mx-auto">
                                    
                                        <a href="{{ route('reset-password') }}" class="text-primary text-gradient font-weight-bold archivo">Forgot you password?</a>
                                    </p>
                                </div>
								
                                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                    <p class="mb-4 text-sm mx-auto archivo">
                                        They work for us, tell them what you want and hold them accountable. Don't have an account?
                                        <a href="{{ route('register') }}" class="text-primary text-gradient font-weight-bold">Sign up</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                       	<div class="col-md-4 >
					</div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
