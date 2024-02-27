<div class="container position-sticky z-index-sticky top-0">
    <div class="row">
        <div class="col-12">
            <!-- Navbar -->
            <nav
                class="navbar navbar-expand-lg blur border-radius-lg top-0 z-index-3 shadow position-absolute mt-4 py-2 start-0 end-0">
                <div class="container-fluid">
             <div class="sidenav-header pad-left">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{ route('home') }}"
            target="_blank">
            <img src="./img/logo-ct-dark.png" height="500" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold"><span class="desktop">The </span><span class="text-primary">Voters</span> <span style="color:blue !important">Voices</span></span>
        </a>
    </div>
                    <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon mt-2">
                            <span class="navbar-toggler-bar bar1"></span>
                            <span class="navbar-toggler-bar bar2"></span>
                            <span class="navbar-toggler-bar bar3"></span>
                        </span>
                    </button>
                    <div class="collapse navbar-collapse background-red pad-header" id="navigation">
                        <ul class="navbar-nav mx-auto">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center me-2 font-white active" aria-current="page"
                                    href="{{ route('home') }}">
                                    <i class="fa fa-chart-pie opacity-6 text-dark me-1 font-white"></i>
                                    Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link me-2 font-white" href="{{ route('register') }}">
                                    <i class="fas fa-user-circle opacity-6 text-dark me-1 font-white"></i>
                                    Sign Up
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link me-2 font-white" href="{{ route('login') }}">
                                    <i class="fas fa-key opacity-6 text-dark me-1 font-white"></i>
                                    Sign In
                                </a>
                            </li>
                        </ul>
                        <ul class="navbar-nav d-lg-block d-none">
                            <li class="nav-item">
                                <a href="{{ route('public-legislations') }}" target=""
                                    class="btn btn-sm mb-0 me-1 btn-primary">Legislations</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- End Navbar -->
        </div>
    </div>
</div>
