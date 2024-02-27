<?php
error_reporting(0);
if(isset($_COOKIE["myMCA"])) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Reports</title>
        <link rel="stylesheet" href="bootstrap3/css/bootstrap.min.css">
        <script src="jquery/jquery.min.js"></script>
        <script src="js/jquery-1.12.4.js"></script>
        <script src="js/highchartsx.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/modules/export-data.js"></script>
        <style>
            #container1 {
                height: 400px;
                min-width: 310px;
                max-width: 800px;
                margin: 0 auto;
            }
            .circle {
                background: purple;
                border-radius: 50%;
                -moz-border-radius: 50%;
                -webkit-border-radius: 50%;
                color: #fff;
                display: inline-block;
                font-weight: bold;
                line-height: 150px;
                margin-right: 5px;
                text-align: center;
                width: 150px;
                font-size: 66px;

            }
            .circle1 {
                background: red;
                border-radius: 50%;
                -moz-border-radius: 50%;
                -webkit-border-radius: 50%;
                color: #fff;
                display: inline-block;
                font-weight: bold;
                line-height: 150px;
                margin-right: 5px;
                text-align: center;
                width: 150px;
                font-size: 66px;

            }
            .circle2 {
                background: orange;
                border-radius: 50%;
                -moz-border-radius: 50%;
                -webkit-border-radius: 50%;
                color: #fff;
                display: inline-block;
                font-weight: bold;
                line-height: 150px;
                margin-right: 5px;
                text-align: center;
                width: 150px;
                font-size: 66px;

            }
            .circle7 {
                background: limegreen;
                border-radius: 50%;
                -moz-border-radius: 50%;
                -webkit-border-radius: 50%;
                color: #fff;
                display: inline-block;
                font-weight: bold;
                line-height: 150px;
                margin-right: 5px;
                text-align: center;
                width: 150px;
                font-size: 66px;

            }
            .circle3 {

                color: lightskyblue;
                display: inline-block;
                font-weight: bold;
                margin-right: 5px;
                text-align: center;
                font-size: 66px;
                padding: 20px;

            }
        </style>

    </head>
    <body onload="startTime()">

    <div class="container alert-info" style="width: 20%; float: left;height:100vh; position: fixed">
        <div class="row">
            <div class="col-sm-12">
                <a href="mca_reports.php"> <img style="width: 100%; height: auto;" id="ximg"
                                                src="images\Med ClaimAssist Logo_1000px.png"></a>

                <b style="font-size: 24px; color: mediumseagreen"><u><span id="dat"></span></u></b>
                <hr>
                <div class="row" style="font-size: 16px">
                    <div class="col-sm-6"><b>Week Number</b></div>
                    <div class="col-sm-6"><b>Savings (Rands)</b></div>
                </div>
                <div class="row" style="font-size: 16px; border-bottom: groove; border-bottom-color: lightgrey">
                    <div class="col-sm-6">1</div>
                    <div class="col-sm-6" id="w0">0</div>
                </div>
                <div class="row" style="font-size: 16px;border-bottom: groove; border-bottom-color: lightgrey">
                    <div class="col-sm-6">2</div>
                    <div class="col-sm-6" id="w1">0</div>
                </div>
                <div class="row" style="font-size: 16px;border-bottom: groove; border-bottom-color: lightgrey">
                    <div class="col-sm-6">3</div>
                    <div class="col-sm-6" id="w2">0</div>
                </div>
                <div class="row" style="font-size: 16px;border-bottom: groove; border-bottom-color: lightgrey">
                    <div class="col-sm-6">4</div>
                    <div class="col-sm-6" id="w3">0</div>
                </div>
                <div class="row" style="font-size: 16px;border-bottom: groove; border-bottom-color: lightgrey">
                    <div class="col-sm-6">5</div>
                    <div class="col-sm-6" id="w4">0</div>
                </div>

                <div class="row" style="font-size: 16px; color: mediumseagreen">
                    <p style="padding-left: 10px"><b>Current total savings</b></p>
               <span style="font-weight: bolder;font-size: 20px; padding-left: 10px">R<span id="sav_act" style="font-weight: bolder;font-size: 40px; padding-left: 10px"></span></span><br>
                    <span style="font-weight: bolder;font-size: 10px; padding-left: 10px">R<span id="sav" style="font-weight: bolder;font-size: 20px; padding-left: 10px; color:red !important;"></span></span>

                <div class="progress">
                    <div class="progress-bar progress-bar-striped active" id="prog" role="progressbar" aria-valuemin="0"
                         aria-valuemax="100">
                        <span id="inf"></span>%
                    </div>
                </div>
                </div>
                <div class="row" style="font-size: 16px; color: mediumseagreen">
                    <p style="padding-left: 10px"><b>Last Month Savings <br>R <span id="sav1"
                                                                                    style="font-weight: bolder;font-size: 20px; "></span></b>
                    </p>


                </div>
                <hr>
                <div class="row">

                    <div id="container3" style="height: 300px; padding-right: 5px"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="container" style="width: 80%; float: right">
        <div class="row">

            <div class="col-sm-12">
                <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
            </div>
        </div>

        <div class="row">

            <div class="col-sm-6">
                <div id="container1" style="height: 400px"></div>
            </div>
            <div class="col-sm-6">
                <div id="container2" style="height: 400px"></div>
            </div>
        </div>

        <div class="row" style="border-top: 2px solid lightgrey; padding-top: 10px">
            <div class="col-sm-4">

                <div class="circle3" id="circle3">0 / </div>

            </div>
            <div class="col-sm-2">

                <div class="circle" id="circle">0</div>

            </div>
            <div class="col-sm-2">
                <div class="circle1" id="circle1">0</div>
            </div>
            <div class="col-sm-2">
                <div class="circle2" id="circle2">0</div>
            </div>
            <div class="col-sm-2">
                <div class="circle7" id="circle7">0</div>
            </div>
        </div>
    </div>


    </body>
    </html>
    <script>
        $(document).ready(function () {
            var interval = setInterval(function () {
                //var momentNow = moment();
                //$('#date-part').html(momentNow.format('MMMM'));

            }, 100);

            if (typeof(EventSource) !== "undefined") {
                var source = new EventSource("ajaxPhp/live_reports.php?id=1");
                var source1 = new EventSource("ajaxPhp/live_reports.php?id=2");
                var source12 = new EventSource("ajaxPhp/live_reports.php?id=3");
                var source13 = new EventSource("ajaxPhp/live_reports.php?id=4");
                //var source15 = new EventSource("ajaxPhp/live_reports.php?id=6");
                var source16 = new EventSource("ajaxPhp/live_reports.php?id=7");
                var clients = [];
                var scheme = [];
                var discount = [];

                source.onmessage = function (event) {

                    clients = [];
                    var scheme = [];
                    var discount = [];
                    var total_scheme = 0;
                    var total_discount = 0;
 var total_scheme1 = 0;
                    var total_discount1 = 0;
                    var jsonData = JSON.parse(event.data);

                    for (var i in jsonData) {
                        clients.push(jsonData[i].client_name);
                        scheme.push(jsonData[i].scheme);
                        discount.push(jsonData[i].discount);
                        total_scheme += jsonData[i].scheme;
                        total_discount += jsonData[i].discount;
total_scheme1 += jsonData[i].scheme_tot;
                        total_discount1 += jsonData[i].discount_tot;
                    }
                    var tot = total_scheme + total_discount;
var tot1 = total_scheme1 + total_discount1;
                    var x = Math.round((tot / 1000000) * 100);
                    if (x > 100) {
                        x = 100;
                    }


                    $("#prog").attr({'style': 'width:' + x + '%'});
                    $("#prog").attr({'aria-valuenow': x});
                    $("#inf").text(x);
                    tot = tot.toLocaleString();
                  tot1 = tot1.toLocaleString();
                    $('#sav_act').text(tot);
                    $('#sav').text("("+tot1+")");
                    Highcharts.chart('container', {
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'Total Savings (' + tot + ')'
                        },
                        xAxis: {
                            categories: clients
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: 'Total Savings'
                            },
                            stackLabels: {
                                enabled: true,
                                style: {
                                    fontWeight: 'bold',
                                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'orangered'
                                }
                            }
                        },
                        legend: {
                            align: 'right',
                            x: -30,
                            verticalAlign: 'top',
                            y: 25,
                            floating: true,
                            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
                            borderColor: 'orangered',
                            borderWidth: 1,
                            shadow: false
                        },
                        tooltip: {
                            headerFormat: '<b>{point.x}</b><br/>',
                            pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
                        },
                        plotOptions: {
                            column: {
                                stacking: 'normal',
                                dataLabels: {
                                    enabled: false,
                                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                                }
                            },
                            line: {
                                animation: false
                            },
                            series: {
                                animation: false
                            }
                        },
                        series: [{
                            name: 'Scheme Savings (' + total_scheme.toLocaleString() + ')',
                            data: scheme
                        }, {
                            name: 'Discount Savings (' + total_discount.toLocaleString() + ')',
                            data: discount
                        }]
                    });
                    $(".highcharts-credits").html("");
                };

                source1.onmessage = function (event1) {

                    var openClients = [];
                    var open_values = [];
                    var open_total = 0;
                    var jsonData1 = JSON.parse(event1.data);

                    for (var j in jsonData1) {
                        openClients.push(jsonData1[j].client_name);
                        open_values.push(jsonData1[j].total);
                        open_total += jsonData1[j].total;
                    }
                    Highcharts.chart('container1', {
                        chart: {
                            type: 'column',
                            options3d: {
                                enabled: true,
                                alpha: 10,
                                beta: 25,
                                depth: 70
                            }
                        },
                        title: {
                            text: 'Total claims for this month (' + open_total + ')'
                        },
                        subtitle: {
                            text: 'Claims per client'
                        },
                        plotOptions: {
                            column: {
                                depth: 25
                            },
                            series: {
                                borderWidth: 0,
                                animation: false,
                                dataLabels: {
                                    enabled: true,
                                    format: '{point.y:0f}'
                                }
                            },
                            line: {
                                animation: false
                            }
                        },
                        xAxis: {
                            categories: openClients,
                            labels: {
                                skew3d: true,
                                style: {
                                    fontSize: '16px'
                                }
                            }
                        },
                        yAxis: {
                            title: {
                                text: 'Claims Number'
                            }
                        },
                        series: [{
                            name: 'Claims',
                            data: open_values
                        }]
                    });
                    $(".highcharts-credits").html("");
                };
                source12.onmessage = function (event2) {

                    var myopenClients;
                    var myopen_total = 0;
                    myopenClients = JSON.parse(event2.data);
                    for (var j in myopenClients) {
                        myopen_total += myopenClients[j].y;
                    }
                    Highcharts.chart('container2', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Total Open Claims (' + myopen_total + ')'
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.0f}%</b>'
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.y:.0f} ',
                                    style: {
                                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                    },
                                    connectorColor: 'silver'
                                }
                            },
                            line: {
                                animation: false
                            },
                            series: {
                                animation: false
                            }
                        },
                        series: [{
                            name: 'Open Claims',
                            data: myopenClients
                        }]
                    });
                    $(".highcharts-credits").html("");
                };
                source13.onmessage = function (event3) {
                    var j = JSON.parse(event3.data);
                    for (var i in j) {
                        var cv = j[i];
                        if (cv == null) {
                            cv = 0;
                        }
                        $("#w" + i).text(cv.toLocaleString());
                    }
                };
                /*source15.onmessage = function (event5) {
                    //console.log("Testtt"+event5);
                    var ddt=event5.data;
                    //console.log("Dziva"+ddt);
                    var dd=ddt.split("--");
                    var purple=dd[0];
                    var red=dd[1];
                    var yellow=dd[2];
                    document.getElementById("circle").innerHTML=purple;
                    document.getElementById("circle1").innerHTML=red;
                    document.getElementById("circle2").innerHTML=yellow;
                };

                 */
                source16.onmessage = function (event6) {

                    document.getElementById("circle3").innerHTML=(event6.data+" /");

                };
            } else {
                alert("Sorry, your browser does not support server-sent events...");
            }
//====================================================================startTrend
//====================================================================startTrend
            var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            var m = [];
            var claims_number = [];
            var total_savings = [];
            $.ajax({
                url: "admin/execReport.php",
                type: "GET",
                data: {id: 8},
                async: false,
                success: function (data) {
                    //console.log("Testing" + data);
                    var obj = JSON.parse(data);
                    for (var i in obj) {
                        //console.log("Testing" + data);
                        var full_date = obj[i].claim_date;
                        var mmnth = full_date.split('-');
                        var num = parseInt(mmnth[1] - 1);
                        m.push(months[num]);
                        claims_number.push(obj[i].cc);
                    }
                },
                error: function (jqXHR, exception) {
                    alert(jqXHR.responseText);
                }
            });
            $.ajax({
                url: "admin/execReport.php",
                type: "GET",
                data: {id: 9},
                async: false,
                success: function (data) {
                    //console.log("Testing" + data);
                    var obj = JSON.parse(data);

                    for (var i in obj) {
                        total_savings.push(obj[i].savings);
                    }
                },
                error: function (jqXHR, exception) {
                    alert(jqXHR.responseText);
                }
            });
            m.reverse();
            claims_number.reverse();
            total_savings.reverse();
            $("#sav1").text(total_savings[6].toLocaleString());
            Highcharts.chart('container3', {
                chart: {
                    zoomType: 'xy'
                },
                title: {
                    text: 'Savings/Claims'
                },
                subtitle: {
                    text: ''
                },
                xAxis: [{
                    categories: m,
                    crosshair: true
                }],
                yAxis: [{ // Primary yAxis
                    labels: {
                        format: '{value}',
                        style: {
                            color: Highcharts.getOptions().colors[1]
                        }
                    },
                    title: {
                        text: 'Claims',
                        style: {
                            color: Highcharts.getOptions().colors[1]
                        }
                    },
                    opposite: true

                }, { // Secondary yAxis
                    gridLineWidth: 0,
                    title: {
                        text: 'Total Savings',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                    labels: {
                        format: '{value}',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    }, dataLabels: {
                        enabled: true,
                        format: '{point.y:0f}'
                    }

                }],
                tooltip: {
                    shared: true
                },
                legend: {
                    layout: 'vertical',
                    align: 'left',
                    x: 80,
                    verticalAlign: 'top',
                    y: 55,
                    floating: true,
                    backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
                },
                series: [{
                    name: 'Savings',
                    type: 'column',
                    yAxis: 1,
                    data: total_savings,
                    tooltip: {
                        valueSuffix: ' '
                    }

                }, {
                    name: 'Claims',
                    type: 'spline',
                    data: claims_number,
                    tooltip: {
                        valueSuffix: ' '
                    }
                }]
            });

//=========================================================EndTrend
//=========================================================EndTrend
            // Radialize the colors
            Highcharts.setOptions({
                colors: Highcharts.map(Highcharts.getOptions().colors, function (color) {
                    return {
                        radialGradient: {
                            cx: 0.5,
                            cy: 0.3,
                            r: 0.7
                        },
                        stops: [
                            [0, color],
                            [1, Highcharts.Color(color).brighten(-0.1).get('rgb')] // darken
                        ]
                    };
                })
            });

$("tspan").css("stroke","white !important");
$("tspan").css("fill","white !important");

        });


        ///Functions
        function startTime() {
            var arr = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            var today = new Date();
            var day = today.getDate();
            var h = today.getHours();
            var m = today.getMinutes();
            var s = today.getSeconds();
            var sm = today.getMonth();
            m = checkTime(m);
            s = checkTime(s);
            document.getElementById('dat').innerHTML = day + " " + arr[sm] + " // " +
                h + ":" + m + ":" + s;
            var t = setTimeout(startTime, 500);
        }
        getSLA();
        function checkTime(i) {
            if (i < 10) {
                i = "0" + i
            }
            ;  // add zero in front of numbers < 10
            return i;
        }
        setInterval(function ()
        {
            getSLA();
        }, 20000);
        function getSLA()
        {
            $.ajax({
                url: "../demo/ajax/claims.php",
                type: "POST",
                data: {identity_number: 14,from_live:true},
                async: false,
                success: function (data) {
                    var obj = JSON.parse(data);
                    let purple_total=obj["purple_total"];
                    let red_total=obj["red_total"];
                    let orange_total=obj["orange_total"];
                    let green_total=obj["green_total"];
                    document.getElementById("circle").innerHTML=purple_total;
                    document.getElementById("circle1").innerHTML=red_total;
                    document.getElementById("circle2").innerHTML=orange_total;
                    document.getElementById("circle7").innerHTML=green_total;

                },
                error: function (jqXHR, exception) {
                    console.log(jqXHR.responseText);
                }
            });
        }
        
    </script>

    <?php
}
else{

    echo "<h1 style='color: red'>Unauthorised Access</h1>";
}

?>