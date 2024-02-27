@extends('layouts.app')

@section('content')
    @include('layouts.navbars.guest.navbar')
    <main class="main-content  mt-0">
        <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg"
            style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/signup-cover.jpg'); background-position: top;">
            <span class="mask bg-gradient-primary opacity-6"></span>
            <div class="container">
                <div class="row justify-content-center">
                   
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
                <div class="col-xl-12 col-lg-12 col-md-12 mx-auto">
                    <div class="card z-index-0">
                        <div class="card-header text-center pt-4">
                            <h3>Legislations</h3>
                        </div>                  
                        <div class="card-body">
                            <table class="table align-items-center uk-table-responsive uk-table-divider">
                            <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Legislation Name</th>

                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Legislation Description</th>
                          

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($legislations as $legislation)
                                <tr>
                                    <td class="w-30">

                                        <div class="d-flex px-2 py-1 align-items-center">
                                            <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                                <i class="ni ni-box-2 text-white opacity-10"></i>
                                            </div>
											<span class="" id="n{{$legislation['id']}}" title="{{$legislation['legislation_description']}}">
                                            <b>{{$legislation['legislation_name']}}</b>
											</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="">
                                           {{$legislation['legislation_description']}}
                                        </div>
                                    </td>
                                    

                                </tr>
                            @endforeach

                            </tbody>
                        </table>
					
                        </div>
						
						 <div class="card-footer text-center pt-0 px-lg-2 px-1" style="border-top:1px dashed red">
                                   <p class="text-sm mt-3 mb-0">Already have an account? <a href="{{ route('login') }}"
                                        class="text-blue font-weight-bolder">Sign in</a></p>
                                </div>
                                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                    <p class="mb-4 text-sm mx-auto">
                                        Don't have an account?
                                        <a href="{{ route('register') }}" class="text-primary text-gradient font-weight-bold">Sign up</a>
                                    </p>
                                </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('layouts.footers.guest.footer')
@endsection
