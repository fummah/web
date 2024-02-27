<?php
session_start();
include("validateAdmin.php");
$cookie_name = "myMCA";
$cookie_value = "admin";
setcookie($cookie_name, $cookie_value, time() + (86400 * 7000), "/"); // 86400 = 1 day
?>
<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">

    <title>Reports</title>

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/bootstrap-theme.min.css">
    <script src="../js/jquery-1.12.4.js"></script>

    <!-- Custom CSS -->
    <link href="../css/sb-admin.css" rel="stylesheet">
    <!-- Morris Charts CSS -->

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <script type="text/javascript">
        var d = new Date();
        var month = new Array();
        month[0] = "January";
        month[1] = "February";
        month[2] = "March";
        month[3] = "April";
        month[4] = "May";
        month[5] = "June";
        month[6] = "July";
        month[7] = "August";
        month[8] = "September";
        month[9] = "October";
        month[10] = "November";
        month[11] = "December";
        var n = month[d.getMonth()];
        window.onload = function () {
//open Cases variables
            var openCasesUserDetails;
            var openCasesRaw=[];
            var  openCasesRawSecond=[];
            var countOpenCases=0;
//end
            //open Cases variables
            var schemeCasesUserDetails;
            var schemeCasesRaw=[];
            var  schemeCasesRawSecond=[];
            var countSchemeCases=0;
//end
            //open Cases variables
            var discountCasesUserDetails;
            var discountCasesRaw=[];
            var  discountCasesRawSecond=[];
            var countdiscountCases=0;
//end

            //open Cases variables
            var mOpenCasesUserDetails;
            var mOpenCasesRaw=[];
            var  mOpenCasesRawSecond=[];
            var countmOpenCases=0;
//end
            //closed Cases variables
            var mClosedCasesUserDetails;
            var mClosedCasesRaw=[];
            var  mClosedCasesRawSecond=[];
            var countmClosedCases=0;
//end
            //closed Cases variables
            var cSavingsUserDetails;
            var cSavingsCasesRaw=[];
            var  cSavingsCasesRawSecond=[];
            var countcSavingsCases=0;
            //end
            var cSavingsUserDetails1;
            var cSavingsCasesRaw1=[];
            var  cSavingsCasesRawSecond1=[];
            var countcSavingsCases1=0;
            //savings variables

            //Ajax code for Open Cases
            $.ajax({
                url:"execReport.php",
                type:"GET",
                data:{id:1},
                async: false,
                success:function (data) {
                    console.log(data);
                    openCasesRaw=data.replace(/\"/g,"");
                    openCasesRawSecond=openCasesRaw.replace(/\~/g,'"');
                    var xx=JSON.stringify(openCasesRawSecond);
                    var json2= JSON.parse(xx);
                    openCasesUserDetails = JSON.parse(json2);

                },
                error:function (jqXHR, exception) {
                    alert(jqXHR.responseText);
                }
            });
            $.each(openCasesUserDetails, function(key, value){
                countOpenCases+=openCasesUserDetails[key].y;
            });

            //Ajax code for Schem Savings
            $.ajax({
                url:"execReport.php",
                type:"GET",
                data:{id:2},
                async: false,
                success:function (scheme) {
                    schemeCasesRaw=scheme.replace(/\"/g,"");
                    schemeCasesRawSecond=schemeCasesRaw.replace(/\~/g,'"');
                    var xx1=JSON.stringify(schemeCasesRawSecond);
                    var json2= JSON.parse(xx1);
                    schemeCasesUserDetails = JSON.parse(json2);

                },
                error:function (jqXHR, exception) {
                    alert(jqXHR.responseText);
                }
            });
            $.each(schemeCasesUserDetails, function(key, value){
                countSchemeCases+=schemeCasesUserDetails[key].y;
            });
            //end
            //ajax code for discount savings
            $.ajax({
                url:"execReport.php",
                type:"GET",
                data:{id:3},
                async: false,
                success:function (discount) {
                    discountCasesRaw=discount.replace(/\"/g,"");
                    discountCasesRawSecond=discountCasesRaw.replace(/\~/g,'"');
                    var xx2=JSON.stringify(discountCasesRawSecond);
                    var json2= JSON.parse(xx2);
                    discountCasesUserDetails = JSON.parse(json2);

                },
                error:function (jqXHR, exception) {
                    alert(jqXHR.responseText);
                }
            });
            $.each(discountCasesUserDetails, function(key, value){
                countdiscountCases+=discountCasesUserDetails[key].y;
            });
            //ajaxend
            //Cases
            $.ajax({
                url:"execReport.php",
                type:"GET",
                data:{id:4},
                async: false,
                success:function (open) {
                    mOpenCasesRaw=open.replace(/\"/g,"");
                    mOpenCasesRawSecond=mOpenCasesRaw.replace(/\~/g,'"');
                    var xx1=JSON.stringify(mOpenCasesRawSecond);
                    var json2= JSON.parse(xx1);
                    mOpenCasesUserDetails = JSON.parse(json2);

                },
                error:function (jqXHR, exception) {
                    alert(jqXHR.responseText);
                }
            });
            $.each(mOpenCasesUserDetails, function(key, value){
                countmOpenCases+=mOpenCasesUserDetails[key].y;
            });

            //end

            $.ajax({
                url:"execReport.php",
                type:"GET",
                data:{id:5},
                async: false,
                success:function (closed) {
                    mClosedCasesRaw=closed.replace(/\"/g,"");
                    mClosedCasesRawSecond=mClosedCasesRaw.replace(/\~/g,'"');
                    var xx2=JSON.stringify(mClosedCasesRawSecond);
                    var json2= JSON.parse(xx2);
                    mClosedCasesUserDetails = JSON.parse(json2);

                },
                error:function (jqXHR, exception) {
                    alert(jqXHR.responseText);
                }
            });
            $.each(mClosedCasesUserDetails, function(key, value){
                countmClosedCases+=mClosedCasesUserDetails[key].y;
            });
            //end

            $.ajax({
                url:"execReport.php",
                type:"GET",
                data:{id:6},
                async: false,
                success:function (cSavings) {
                    cSavingsCasesRaw=cSavings.replace(/\"/g,"");
                    cSavingsCasesRawSecond=cSavingsCasesRaw.replace(/\~/g,'"');
                    var xx6=JSON.stringify(cSavingsCasesRawSecond);
                    var json6= JSON.parse(xx6);
                    cSavingsUserDetails = JSON.parse(json6);

                },
                error:function (jqXHR, exception) {
                    alert(jqXHR.responseText);
                }
            });
            $.each(cSavingsUserDetails, function(key, value){
                countcSavingsCases+=cSavingsUserDetails[key].y;
            });

            //end
            $.ajax({
                url:"execReport.php",
                type:"GET",
                data:{id:7},
                async: false,
                success:function (cSavings) {
                    cSavingsCasesRaw1=cSavings.replace(/\"/g,"");
                    cSavingsCasesRawSecond1=cSavingsCasesRaw1.replace(/\~/g,'"');
                    var xx7=JSON.stringify(cSavingsCasesRawSecond1);
                    var json7= JSON.parse(xx7);
                    cSavingsUserDetails1 = JSON.parse(json7);

                },
                error:function (jqXHR, exception) {
                    alert(jqXHR.responseText);
                }
            });
            $.each(cSavingsUserDetails1, function(key, value){
                countcSavingsCases1+=cSavingsUserDetails1[key].y;
            });
            var tot=countdiscountCases+countSchemeCases;
            var tot7=countcSavingsCases1+countcSavingsCases;
            countcSavingsCases1=parseFloat(countcSavingsCases1).toFixed(2);
            countcSavingsCases=parseFloat(countcSavingsCases).toFixed(2);
            tot=parseFloat(tot).toFixed(2);
            tot7=parseFloat(tot7).toFixed(2);
            countSchemeCases=parseFloat(countSchemeCases).toFixed(2);
            countdiscountCases=parseFloat(countdiscountCases).toFixed(2);
            /*var arr=[
                {  "y":4181563, indexLabel:'Nomluleko(100)' },
                {  "y":2175498, indexLabel:'Shakila(700)' },
                {  "y":3125844, indexLabel:'Jenine' },
                {  "y":1176121, indexLabel:"Naomi"},
                {  "y":1727161, indexLabel:"Shirley" },
                {  "y":4303364, indexLabel:"Lizelle"}
            ];*/

            var chart1 = new CanvasJS.Chart("chartContainer1",
                {
                    theme: "theme2",
                    title:{
                        text: countOpenCases+" Total Open Cases"
                    },
                    data: [
                        {
                            type: "pie",
                            showInLegend: true,
                            toolTipContent: "{y} Cases - #percent %",
                            // yValueFormatString: "#,##0,,.## Million",
                            legendText: "{indexLabel}",
                            dataPoints: openCasesUserDetails
                        }
                    ]
                });
            //End of pie code
            var chart2 = new CanvasJS.Chart("chartContainer2",
                {
                    title:{
                        text: "Total Savings for "+n+"("+tot+")"
                    },
                    axisY:{
                        title:"Total Savings (in thousands)",
                        valueFormatString: "#0.#,.",
                    },
                    data: [
                        {
                            type: "stackedColumn",
                            legendText: "Scheme Savings("+countSchemeCases+")",
                            showInLegend: "true",
                            dataPoints: schemeCasesUserDetails
                        },  {
                            type: "stackedColumn",
                            legendText: "Discount Savings("+countdiscountCases+")",
                            showInLegend: "true",
                            indexLabel: "#total",
                            //yValueFormatString: "#0.#,.",
                            indexLabelPlacement: "outside",
                            dataPoints: discountCasesUserDetails
                        }
                    ]
                });
            //end


            var chart3 = new CanvasJS.Chart("chartContainer3",
                {
                    title:{
                        text: "Total Cases for "+n+"("+countmOpenCases+")"
                    },
                    data: [

                        {
                            dataPoints: mOpenCasesUserDetails
                        }
                    ]
                });
            var chart4 = new CanvasJS.Chart("chartContainer4",
                {
                    title:{
                        text: "Clients Total Cases for "+n+"("+countmClosedCases+")"
                    },
                    data: [

                        {
                            dataPoints: mClosedCasesUserDetails
                        }
                    ]
                });
            //end
            var chart5 = new CanvasJS.Chart("chartContainer5",
                {
                    title:{
                        text: "Clients Total Savings for "+n+"("+tot7+")"
                    },
                    axisY:{
                        title:"Total Savings (in thousands)",
                        valueFormatString: "#0.#,.",
                    },
                    data: [
                        {
                            type: "stackedColumn",
                            legendText: "Scheme Savings("+countcSavingsCases+")",
                            showInLegend: "true",
                            dataPoints: cSavingsUserDetails
                        },  {
                            type: "stackedColumn",
                            legendText: "Discount Savings("+countcSavingsCases1+")",
                            showInLegend: "true",
                            indexLabel: "#total",
                            //yValueFormatString: "#0.#,.",
                            indexLabelPlacement: "outside",
                            dataPoints: cSavingsUserDetails1
                        }
                    ]
                });

            chart1.render();
            chart2.render();
            chart3.render();
            chart4.render();
            chart5.render();


        }

    </script>
    <script type="text/javascript" src="../js/canvas.js">
        $('a').text('x');
    </script>
</head>
<body>

<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="navbar-header">
            <a class="navbar-brand" href="../index.php" style="color:#00ffff;">Med Claim Assist</a>
        </div>

        <?php
        require_once ('admin_header.php');
        ?>
        <!-- /.navbar-collapse -->
    </nav>

    <div id="page-wrapper">

        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">


                <div id="chartContainer1" style="height: 300px; width: 100%;">
                    loading...
                </div>
                <div id="chartContainer2" style="height: 300px; width: 100%;">
                    loading...
                </div>
                <div id="chartContainer5" style="height: 300px; width: 100%;">
                    loading...
                </div>

                <table width="100%"><tr>
                        <td>
                            <div id="chartContainer3" style="height: 300px; width: 100%;">
                                loading...
                            </div>
                        </td>
                        <td>
                            <div id="chartContainer4" style="height: 300px; width: 100%;">
                                loading...
                            </div>
                        </td>
                    </tr></table>
                <hr>

                <hr>
                <?php
                include('footer.php');
                ?>
            </div>
        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->

<!-- jQuery -->


</body>
</html>
