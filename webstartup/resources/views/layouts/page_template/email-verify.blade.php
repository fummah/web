
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
@include('layouts.navbars.sidebar-email-verify')
<div class="main-panel">
   
    @yield('content')
    @include('layouts.footer')
</div>