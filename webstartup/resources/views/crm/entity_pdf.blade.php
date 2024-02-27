<!DOCTYPE html>
<html lang="en">
    <head>
        <title> $invoice->name </title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

        <style type="text/css" media="screen">
            html {
                font-family: sans-serif;
                line-height: 1.15;
                margin: 0;
            }

            body {
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
                font-weight: 400;
                line-height: 1.5;
                color: #212529;
                text-align: left;
                background-color: #fff;
                font-size: 12px;
                margin: 36pt;
            }

            h4 {
                margin-top: 0;
                margin-bottom: 0.5rem;
            }

            p {
                margin-top: 0;
                margin-bottom: 1rem;
            }

            strong {
                font-weight: bolder;
            }

            img {
                vertical-align: middle;
                border-style: none;
            }

            table {
                border-collapse: collapse;
            }

            th {
                text-align: inherit;
            }

            h4, .h4 {
                margin-bottom: 0.5rem;
                font-weight: 500;
                line-height: 1.2;
            }

            h4, .h4 {
                font-size: 1.5rem;
            }

            .table {
                width: 100%;
                margin-bottom: 1rem;
                color: #212529;
            }

            .table th,
            .table td {
                padding: 0.75rem;
                vertical-align: top;
            }

            .table.table-items td {
                border-top: 1px solid #dee2e6;
            }

            .table thead th {
                vertical-align: bottom;
                border-bottom: 2px solid #dee2e6;
            }

            .mt-5 {
                
            }

            .pr-0,
            .px-0 {
                padding-right: 0 !important;
            }

            .pl-0,
            .px-0 {
                padding-left: 0 !important;
            }

            .text-right {
                text-align: right !important;
            }

            .text-center {
                text-align: center !important;
            }

            .text-uppercase {
                text-transform: uppercase !important;
                color: #f47e20;
            }
            * {
                font-family: "DejaVu Sans";
            }
            body, h1, h2, h3, h4, h5, h6, table, th, tr, td, p, div {
                line-height: 1.1;
            }
            .party-header {
                font-size: 1.5rem;
                font-weight: 400;
            }
            .total-amount {
                font-size: 12px;
                font-weight: 700;
            }
            .border-0 {
                border: none !important;
            }
            .cool-gray {
                color: #6B7280;
            }
            .pad{
                padding-left: 10px !important;padding-right: 5px !important;
            }
        </style>
    </head>

    <body>
      <p style="background-color: black"><img src="{{ public_path('storage\now-logo.png') }}" height="60" width="auto" alt=""></p>

        <table class="table mt-5">
            <tbody>
                <tr>
                    <td class="border-0 pl-0" width="70%">
                        <h4 class="text-uppercase">
                            {{ucfirst($entity_name)}} #<strong>{{$data["redit"]["item_number"]}}</strong>
                        </h4>
                    </td>
                    <td class="border-0 pl-0">
                       
                            <h4 class="text-uppercase cool-gray">
                                <strong>{{$data["redit"]["status"]}}</strong>
                            </h4>
                       
                        <p class="text-uppercase"><strong>{{$currentdate}}</strong></p>
                    </td>
                </tr>
            </tbody>
        </table>

        <table class="table" style="border: 1px dashed lightgrey; ">
            <thead>
                <tr>
                    <th class="border-0 pl-0 party-header pad" width="48.5%">
                      SELLER
                    </th>
                    <th class="border-0" width="3%"></th>
                    <th class="border-0 pl-0 party-header pad">
                       BUYER
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-0">
                     
                            <p class="seller-name pad">
                                <strong>WEBSTARTUP</strong>
                            </p>
                       

                     
                            <p class="seller-address pad">
                              1315 Aspen Lake Dr San Jose 
                            </p>
                     
                            <p class="seller-code pad">
                                CA 95113
                            </p>
                       

                      
                           
                      
                    </td>
                    <td class="border-0"></td>
                    <td class="px-0">
                      
                            <p class="buyer-name pad">
                                <strong  style="text-transform: uppercase !important;">{{$data["customer"]["name"]}}</strong>
                            </p>
                            <p class="buyer-address pad">
                                @if($data["customer"]["company"]=="")
                                N/A
                                @else
                                {{$data["customer"]["company"]}}
                                @endif
                            </p>
                        

                            
                      
                    </td>
                </tr>
            </tbody>
        </table>

        <table class="table table-items">
            <thead>
                <tr>
                    <th scope="col" class="border-0 pl-0"> Item No.</th>
                                     
                        <th scope="col" class="text-right border-0"> Service Name </th>
                    
                    <th scope="col" class="text-right border-0 pr-0"> Cost </th>
                </tr>
            </thead>
            <tbody>
             
            @foreach($data["entity_list"] as $list)             
             
                    <tr>
                        <td class="text-right pl-0"> {{ $loop->iteration }}. </td>
                        <td class="text-right pl-0"> {{$list["item_name"]}}</td>
                        <td class="text-right pr-0">
                             ${{$list["price"]}}
                        </td>
                    </tr>
                
             @endforeach
                                
           
                    
                
                    <tr>
                        <td colspan=" $invoice->table_columns - 2 " class="border-0"></td>
                        <td class="text-right pl-0"> <h4>Total Cost</h4> </td>
                        <td class="text-right pr-0 total-amount">
                            <h4 class="text-uppercase"> ${{$total_amount}}.00 </h4>
                        </td>
                    </tr>
            </tbody>
        </table>


    </body>
</html>