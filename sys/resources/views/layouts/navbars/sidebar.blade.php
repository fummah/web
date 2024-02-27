<div class="sidebar" data-color="orange">
  <!--
    Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red | yellow"
-->
  <div class="logo">
   
    <a href="https://webstartup.io" class="simple-text logo-normal">
     <img src="{{ asset('assets/img/'.auth()->user()->logo) }}" height="60" width="auto" alt="">
    </a>
  </div>
  <div class="sidebar-wrapper" id="sidebar-wrapper">
    <ul class="nav">
      <li class="@if ($activePage == 'home') active @endif">
        <a href="{{ route('home') }}">
          <i class="now-ui-icons design_app"></i>
          <p><b>{{ __('Dashboard') }}</b></p>
        </a>
      </li>
       @if(auth()->user()->role=="Admin" || strpos(auth()->user()->role, 'CRM') !== false)
      <li>
       
        <a data-toggle="collapse" href="#laravelExamples">
            <i class="now-ui-icons location_world"></i>
          <p>
           <b> {{ __("CRM") }}</b>
            <b class="caret"></b>
          </p>
        </a>        
        <div class="collapse show" id="laravelExamples">
          <ul class="nav">             
              
              <li class="@if ($activePage == 'projects') active @endif">
              <a href="{{ route('projects') }}">
                <i class="now-ui-icons business_badge"></i>
                <p> {{ __("Leads") }} </p>
              </a>
            </li>
              <li class="@if ($activePage == 'quotes') active @endif">
              <a href="{{ route('quotes') }}">
                <i class="now-ui-icons education_paper"></i>
                <p> {{ __("Quotes") }} </p>
              </a>
            </li>
              <li class="@if ($activePage == 'orders') active @endif">
              <a href="{{ route('orders') }}">
                <i class="now-ui-icons business_money-coins"></i>
                <p> {{ __("Orders") }} </p>
              </a>
            </li>
              <li class="@if ($activePage == 'invoices') active @endif">
              <a href="{{ route('invoices') }}">
                <i class="now-ui-icons shopping_credit-card"></i>
                <p> {{ __("Invoices") }} </p>
              </a>
            </li>
            <li class="@if ($activePage == 'audience') active @endif">
              <a href="{{ route('audience') }}">
                <i class="now-ui-icons users_single-02"></i>
                <p> {{ __("Customers") }} </p>
              </a>
            </li>
            @if(auth()->user()->role=="Admin")
            <li class="@if ($activePage == 'users') active @endif">
              <a href="{{ route('system_users') }}">
                <i class="now-ui-icons users_circle-08"></i>
                <p> {{ __("System Users") }} </p>
              </a>
            </li>
                   @endif
          </ul>
        </div>
      </li>
      @endif
      @if(auth()->user()->role=="Admin" || strpos(auth()->user()->role, 'Ordinary') !== false)
      <li>
        <a data-toggle="collapse" href="#marketing">
            <i class="now-ui-icons ui-1_bell-53"></i>
          <p>
           <b> {{ __("Marketing") }}</b>
            <b class="caret"></b>
          </p>
        </a>
        <div class="show" id="marketing">
          <ul class="nav">
              
               <li class="@if ($activePage == 'campaigns') active @endif">
              <a href="{{ route('campaigns') }}">
                <i class="now-ui-icons ui-1_send"></i>
                <p> {{ __("Marketing Campaigns") }} </p>
              </a>
            </li>
             <li class="@if ($activePage == 'content-management') active @endif">
              <a href="{{ route('content-management') }}">
                <i class="now-ui-icons media-2_sound-wave"></i>
                <p> {{ __("Content Management") }} </p>
              </a>
            </li>
              <li class="@if ($activePage == 'seo') active @endif">
              <a href="{{ route('seo') }}">
                <i class="now-ui-icons design_palette"></i>
                <p> {{ __("SEO") }} </p>
              </a>
            </li>
          
               <li class="@if ($activePage == 'audience') active @endif">
              <a href="{{ route('audience') }}">
                <i class="now-ui-icons users_single-02"></i>
                <p> {{ __("Audience") }} </p>
              </a>
            </li>
             
          </ul>
        </div>
      </li>
      @endif
        <li class="@if ($activePage == 'profile') active @endif">
              <a href="{{ route('profile.edit') }}" class="bg-info" style="background-color: black !important;">
                <i class="now-ui-icons users_circle-08"></i>
                <p> {{ __("My Profile") }} </p>
              </a>
            </li>
            <li class="">
              <a href="#" id="shareButton" class="bg-info" style="background-color: black !important;">
                <i class="now-ui-icons ui-1_send"></i>
                <p> {{ __("Share") }} </p>
              </a>
            </li>
               <li class="@if ($activePage == 'enable2fa') active @endif">
              <a href="{{ route('enable2fa') }}" class="bg-info" style="background-color: green !important;">
                <i class="now-ui-icons users_circle-08"></i>
                <p> {{ __("Multi-Auth") }} </p>
              </a>
            </li>
   
    </ul>
  </div>
</div>
