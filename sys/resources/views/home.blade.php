@extends('layouts.app', [
    'namePage' => 'Admin Dashboard',
    'class' => 'login-page sidebar-mini ',
    'activePage' => 'home',
    'backgroundImage' => asset('now') . "/img/bg14.jpg",
])

@section('content')
  <div class="panel-header panel-header-lg">
    <canvas id="bigDashboardChart"></canvas>
  </div>
  <div class="content">
    <div class="row">
      <div class="col-lg-4">
        <div class="card card-chart">
          <div class="card-header">
            <h5 class="card-category">Monthly</h5>
            <h4 class="card-title">Invoices</h4>
            <div class="dropdown">
              <button type="button" class="btn btn-round btn-outline-default dropdown-toggle btn-simple btn-icon no-caret" data-toggle="dropdown">
                <i class="now-ui-icons loader_gear"></i>
              </button>              
              <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="#">Open ({{$dashdata["invoice_status"]["invoice_open"]}})</a>
                <a class="dropdown-item" href="#">Partially Paid ({{$dashdata["invoice_status"]["invoice_partially"]}})</a>
                <a class="dropdown-item" href="#">Not Paid ({{$dashdata["invoice_status"]["invoice_notpaid"]}})</a>
                <a class="dropdown-item" href="#">Fully Paid ({{$dashdata["invoice_status"]["invoice_fullypaid"]}})</a>
                <a class="dropdown-item text-danger" href="#">Cancelled ({{$dashdata["invoice_status"]["invoice_cancelled"]}})</a>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="chart-area">
              <canvas id="lineChartExample"></canvas>
            </div>
          </div>
          <div class="card-footer">
            <div class="stats">
             <a  href="{{ url('invoices') }}"><i class="now-ui-icons design_bullet-list-67"></i> View All Invoices</a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="card card-chart">
          <div class="card-header">
            <h5 class="card-category">Monthly</h5>
            <h4 class="card-title">Orders</h4>
            <div class="dropdown">
              <button type="button" class="btn btn-round btn-outline-default dropdown-toggle btn-simple btn-icon no-caret" data-toggle="dropdown">
                <i class="now-ui-icons loader_gear"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-right">
               <a class="dropdown-item" href="#">Open ({{$dashdata["order_status"]["order_open"]}})</a>
                <a class="dropdown-item" href="#">Invoiced ({{$dashdata["order_status"]["order_invoiced"]}})</a>                
                <a class="dropdown-item text-danger" href="#">Cancelled ({{$dashdata["order_status"]["order_cancelled"]}})</a>                
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="chart-area">
              <canvas id="lineChartExampleWithNumbersAndGrid"></canvas>
            </div>
          </div>
          <div class="card-footer">
            <div class="stats">
             <a  href="{{ url('orders') }}"><i class="now-ui-icons design_bullet-list-67"></i> View All Orders</a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="card card-chart">
          <div class="card-header">
            <h5 class="card-category">Monthly</h5>
            <h4 class="card-title">Quotations</h4>
          </div>
          <div class="card-body">
            <div class="chart-area">
              <canvas id="barChartSimpleGradientsNumbers"></canvas>
            </div>
          </div>
          <div class="card-footer">
            <div class="stats">
              <a  href="{{ url('quotes') }}"><i class="now-ui-icons design_bullet-list-67"></i> View All Quotations</a>
            </div>
          </div>
        </div>
      </div>
    </div>
          <div class="card-body">
    <div class="row">
      <div class="col-md-4">
        <div class="card  card-tasks">
          <div class="card-header ">
            <h5 class="card-category">Recent </h5>
            <h4 class="card-title">Invoices</h4>
          </div>
          <div class="card-body ">
            <div class="table-full-width table-responsive">
              <table class="table">
                <tbody>
                @if(count($dashdata["invoices"])<1)
 <tr> <td colspan="2"><p class="text-danger">No Invoices</p></td></tr>
              @endif
              @foreach($dashdata["invoices"] as $invoice)
                        <tr>                  
                    <td class="text-left">{{$invoice["invoice_number"]}}</td>
                    <td class="td-actions text-right">
                      <a type="button" href="{{ url('item/edit/invoice/'.$invoice['id']) }}" class="btn btn-info btn-round btn-icon btn-icon-mini btn-neutral">
                        <i class="now-ui-icons design_bullet-list-67"></i>
                      </a>                     
                    </td>
                  </tr>
                        @endforeach
                             
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer ">
            <hr>
            <div class="stats">
              <a  href="{{ url('invoices') }}"><i class="now-ui-icons design_bullet-list-67"></i> View All Invoices</a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card  card-tasks">
          <div class="card-header ">
            <h5 class="card-category">Recent</h5>
            <h4 class="card-title">Orders</h4>
          </div>
          <div class="card-body ">
            <div class="table-full-width table-responsive">
              <table class="table">
                <tbody>
               @if(count($dashdata["orders"])<1)
 <tr> <td colspan="2"><p class="text-danger">No Orders</p></td></tr>
              @endif
              @foreach($dashdata["orders"] as $order)
                        <tr>                  
                    <td class="text-left">{{$order["order_number"]}}</td>
                    <td class="td-actions text-right">
                      <a type="button" href="{{ url('item/edit/order/'.$order['id']) }}" class="btn btn-info btn-round btn-icon btn-icon-mini btn-neutral">
                        <i class="now-ui-icons design_bullet-list-67"></i>
                      </a>                     
                    </td>
                  </tr>
                        @endforeach
                     
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer ">
            <hr>
            <div class="stats">
              <a  href="{{ url('orders') }}"><i class="now-ui-icons design_bullet-list-67"></i> View All Orders</a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card  card-tasks">
          <div class="card-header ">
            <h5 class="card-category">Recent</h5>
            <h4 class="card-title">Quotations</h4>
          </div>
          <div class="card-body ">
            <div class="table-full-width table-responsive">
              <table class="table">
                <tbody>
                @if(count($dashdata["quotes"])<1)
 <tr> <td colspan="2"><p class="text-danger">No Quotes</p></td></tr>
              @endif
              @foreach($dashdata["quotes"] as $quote)
                        <tr>                  
                    <td class="text-left">{{$quote["quote_number"]}}</td>
                    <td class="td-actions text-right">
                      <a type="button" href="{{ url('item/edit/quote/'.$quote['id']) }}" class="btn btn-info btn-round btn-icon btn-icon-mini btn-neutral">
                        <i class="now-ui-icons design_bullet-list-67"></i>
                      </a>                     
                    </td>
                  </tr>
                        @endforeach
                      
                              
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer ">
            <hr>
            <div class="stats">
              <a  href="{{ url('quotes') }}"><i class="now-ui-icons design_bullet-list-67"></i> View All Quotations</a>
            </div>
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
      // Javascript method's body can be found in assets/js/demos.js
      demo.initDashboardPageCharts();

    });
  </script>
@endpush