<!-- Navbar -->
<nav style="background-color:#ff00c421" class="navbar navbar-main navbar-expand-lg px1-0 shadow-none border-radius-xl
        {{ str_contains(Request::url(), 'virtual-reality') == true ? ' mt-3 mx-3 bg-primary' : '' }}" id="navbarBlur"
        data-scroll="false">
    <div class="">      
       
        <div class="collapse navbar-collapse" id="navbar">
          
            <ul class="navbar-nav  justify-content-end">
             
                <li>
                <a class="" href="{{ route('home') }}"
            target="_blank">
            <img src="{{ asset('img/logo-ct-dark.png')}}" height="500" style="max-width: 100%;max-height: 6rem;" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold" style="background-color:blue; padding:11px; opacity: 6.0;">
            <span class="desktopx" style="color:white !important">The </span><span class="text-primary">Voters</span> 
            <span style="color:red !important">Voices</span></span>
        </a>
                    </li>
            
            
        </div>
        
    </div>
</nav>

<nav class="navbar navbar-main" style="#fb6340 !important">
<div class="row" style="width:100% !important; margin-bottom:10px">
<div class="col-md-6">
<ul class="navbar-nav  justify-content-end">
<li class="nav-item d-flex align-items-center">
                    <form role="form" method="post" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="nav-link text-primary font-weight-bold px-0">
                            <i class="fa fa-user me-sm-1"></i>
                            <span class="d-sm-inline d-none">Log out</span>
                        </a>
                    </form>
                </li>
                <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-primary p-0" id="iconNavbarSidenav">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line bg-primary" style="background-color: #fb6340 !important;"></i>
                            <i class="sidenav-toggler-line bg-primary" style="background-color: #fb6340 !important;"></i>
                            <i class="sidenav-toggler-line bg-primary" style="background-color: #fb6340 !important;"></i>
                        </div>
                    </a>
                </li>
</ul>

</div>
<div class="col-md-6" style="border-top:1px dashed #fb6340 !important">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm"><a class="opacity-5 text-primary" href="javascript:;">Home</a></li>
                <li class="breadcrumb-item text-sm text-primary active" aria-current="page">{{ $title }}</li>
            </ol>
            <h6 class="font-weight-bolder text-primary mb-0">{{ $title }}</h6>
        </nav>
    </div>
    </div>
</nav>
<!-- End Navbar -->
