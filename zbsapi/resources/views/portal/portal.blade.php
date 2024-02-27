@extends('portal.portalapp', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('portal.navbars.auth.portaltopnav', ['title' => 'ZBS Portal'])
    <style>
        @media only screen and (max-width: 600px) {
          .depd1{
              padding-top: 25px !important;
          }
        }
        @media only screen and (min-width: 600px) {
            .depd{
                padding-top: 25px !important;
            }
        }
        .et_pb_texta{
            font-family: 'Montserrat',Helvetica,Arial,Lucida,sans-serif;
            font-weight: 300;
            line-height: 1.6em;
            font-size: 20px;

        }
        #country-list{
            float:left;
            list-style:none;
            width:190px;
            z-index: 3;
            padding: 2px;
            position: absolute;
            border:#eee 1px solid;
        }
        #country-list li{
            padding: 10px;
            background: #54bf99;
            border-bottom: #E1E1E1 1px solid;
            z-index: 3;
        }
        #country-list li:hover{
            background:lightblue;
            cursor: pointer;
            -webkit-transition: background-color 300ms linear;
            -ms-transition: background-color 300ms linear;
            transition: background-color 300ms linear;
            color: #54bf99;
        }
    </style>
    <button onclick="loadfirst()" id="loadother" data-bs-toggle="modal" data-bs-target="#mymodal" style="display: none">Test</button>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Welcome</p>
                                    <h5 class="font-weight-bolder">
                                        {{ $data["first_name"] }} {{ $data["last_name"] }}
                                    </h5>
                                    <p class="mb-0">
                                        <span class="text-success text-sm font-weight-bolder"><i class="ni ni-mobile-button text-lg opacity-10" aria-hidden="true"></i>({{ $data["contact_number"] }})</span>
                                        {{$data["group"]}}
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                    <i class="ni ni-circle-08 text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Latest Funeral</p>
                                    <h5 class="font-weight-bolder">
                                        {{ $data["funeralname"] }}
                                    </h5>
                                    <p class="mb-0">
                                        @empty($data["paidlast"])
                                            <span class="text-warning text-sm font-weight-bolder">
                                            <i class="ni ni-fat-fat-delete text-lg opacity-10" aria-hidden="true"></i>
                                            Waiting
                                        </span>
                                            @else
                                            @if ($data["paidlast"]["status"]=="paid")
                                                <span class="text-success text-sm font-weight-bolder">
                                            <i class="ni ni-check-bold text-lg opacity-10" aria-hidden="true"></i>
                                            Paid
                                        </span>
                                            @elseif($data["paidlast"]["status"]=="home")
                                                <span class="text-info text-sm font-weight-bolder">
                                            <i class="ni ni-shop text-lg opacity-10" aria-hidden="true"></i>
                                            Home
                                        </span>
                                            @else
                                                <span class="text-danger text-sm font-weight-bolder">
                                            <i class="ni ni-fat-remove text-lg opacity-10" aria-hidden="true"></i>
                                            Unpaid
                                        </span>
                                            @endif
                                        @endempty
                                            ({{ $data["status"] }})

                                        <button class="btn btn-outline-dark btn-sm" onclick="loadfirst()" data-bs-toggle="modal" data-bs-target="#mymodal">View History</button>
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                    <i class="ni ni-diamond text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Accumulated Contribution</p>
                                    <h5 class="font-weight-bolder">
                                        R{{ $data["funeralamounts"][0]["tot_amount"] }}
                                    </h5>
                                    <p class="mb-0">
                                        <span class="text-danger text-sm font-weight-bolder">({{ $data["funeralamounts"][0]["tot_count"] }})</span>
                                        Funerals
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                    <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Current Contribution</p>
                                    <h5 class="font-weight-bolder">
                                        (R{{ $data["amount"] }})
                                    </h5>
                                    <p class="mb-0">
                                        <span class="text-success text-sm font-weight-bolder">R{{ $data["amount"] }}</span> every funeral.
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                    <i class="ni ni-credit-card text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg-7 mb-lg-0 mb-4">
                <div class="card z-index-2 h-100">
                    <div class="card-header pb-0 pt-3 bg-transparent">
                        <h6 class="text-capitalize">Funerals Trend</h6>
                        <p class="text-sm mb-0">
                            <i class="fa fa-arrow-up text-success"></i>
                            <span class="font-weight-bold">Last 6</span> Months
                        </p>
                    </div>
                    <div class="card-body p-3">
                        <div class="chart">
                            <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card card-carousel overflow-hidden h-100 p-0">
                    <div id="carouselExampleCaptions" class="carousel slide h-100" data-bs-ride="carousel">
                        <div class="carousel-inner border-radius-lg h-100">
                            <div class="carousel-item h-100 active" style="background-image: url('./img/1.jpg');
            background-size: cover;">
                                <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                                    <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                                        <i class="ni ni-camera-compact text-dark opacity-10"></i>
                                    </div>
                                    <h5 class="text-white mb-1">ZIMBABWEANS BURIAL SOCIETY</h5>
                                    <p>Excellent service since 2020</p>
                                </div>
                            </div>
                            <div class="carousel-item h-100" style="background-image: url('./img/2.jpg');
            background-size: cover;">
                                <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                                    <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                                        <i class="ni ni-bulb-61 text-dark opacity-10"></i>
                                    </div>
                                    <h5 class="text-white mb-1">Our Promise Is Our Pride</h5>
                                    <p>Nothing makes us more proud than acknowledging the promise which was made in the beginning and which has been kept for years.</p>
                                </div>
                            </div>
                            <div class="carousel-item h-100" style="background-image: url('./img/3.jpg');
            background-size: cover;">
                                <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                                    <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                                        <i class="ni ni-trophy text-dark opacity-10"></i>
                                    </div>
                                    <h5 class="text-white mb-1">Who We Are</h5>
                                    <p>Nothing makes ZBS Burial Society more proud than acknowledging the promise which was made in the beginning and which has been kept for 3 years.</p>
                                </div>
                            </div>
                        </div>
                        <button class="carousel-control-prev w-5 me-3" type="button"
                                data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next w-5 me-3" type="button"
                                data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row depd" >
        <div class="col-md-7">
            <div class="card">
                <div class="card-header pb-0 p-3">
                    <h6 class="mb-0">My Dependencies</h6>
                </div>
                <div class="card-body p-3">
                    @empty($data["dependencies"])
                        <p>No Dependencies</p>
                        @else
                    <ul class="list-group">
                        @foreach($data["dependencies"] as $dependent)
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                    <i class="ni ni-single-02 text-white opacity-10"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 text-dark text-sm">{{ $dependent["first_name"] }} {{ $dependent["surname"] }}</h6>
                                    <span class="text-xs">{{ $dependent["status"] }}</span>
                                </div>
                            </div>
                            <div class="d-flex">
                                <button
                                    class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i
                                        class="ni ni-bold-right" aria-hidden="true"></i></button>
                            </div>
                        </li>
                        @endforeach
                           </ul>
                    @endempty
                </div>
            </div>
        </div>

        <div class="col-md-5 depd1">
            <div class="card">
                <div class="card-header pb-0 p-3">
                    <h6 class="mb-0">My Branch / Coordinator(s)</h6>
                </div>
                <div class="card-body p-3">
                    <ul class="list-group">
                        @foreach($data["branches"] as $branch)
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape icon-sm me-3 bg-gradient-danger shadow text-center">
                                    <i class="ni ni-square-pin text-white opacity-10"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 text-danger text-sm">{{ $branch["first_name"] }} {{ $branch["last_name"] }}</h6>
                                    <span class="text-xs">{{ $branch["location_name"] }} </span>
                                </div>
                            </div>
                            <div class="d-flex">
                                <button
                                    class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i
                                        class="ni ni-bold-right" aria-hidden="true"></i></button>
                            </div>
                        </li>
                        @endforeach

                    </ul>
                </div>
            </div>
        </div>
        </div>
        <input type="hidden" id="member_id" value="{{ $data["member_id"] }}">
        <input type="hidden" id="member_idx" value="{{ $data["member_id"] }}">


        <!-- Modal -->
        <div class="modal fade" id="mymodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title fs-5 text-danger" id="staticBackdropLabel">
                            <i class="ni ni-circle-08 text-lg opacity-10" aria-hidden="true"></i>
                            <span class="num1">
                                {{ $data["first_name"] }} {{ $data["last_name"] }}
                            </span>
                            <span class="submember num2">

                            </span>
                            <br>
                            <span class="text-success text-sm font-weight-bolder">
                                <i class="ni ni-mobile-button text-lg opacity-10" aria-hidden="true"></i>
                                <span class="num1">({{ $data["contact_number"] }})</span>
                                <span class="subphone num2"></span>
                            </span>
                        </h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="info">

                        </div>
                        <p align="center"><button type="button" id="loadmore" onclick="loadMore()" class="btn btn-outline-info">Load More</button></p>
                       </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>

                    </div>
                </div>
            </div>
        </div>
        @include('portal.footers.auth.portalfooter')
    </div>
@endsection

@push('js')
    <script src="./assets/js/jquery-3.2.1.min.js"></script>
    <script src="./assets/js/plugins/chartjs.min.js"></script>
    <script src="./assets/js/portal.js"></script>

@endpush
