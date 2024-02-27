<?php
if(!defined('access')) {
    die('Access not permited');
}
?>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/highcharts-3d.js"></script>
<div onload="startTime()">
<div class="container" style="width: 100%;">
    <div class="row">
        <div class="col-sm-12">
            <h3>Total Savings/Claims for the previous 9 months</h3>
            <div id="container3" style="height: 300px;"></div>
        </div>
    </div>
</div>
</div>
<script>
    $(document).ready(function () {
        setTimeout(function(){
         
        $(".highcharts-credits").remove();
    }, 5000);
   
//====================================================================
        var months=['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        var m=[];
        var claims_number=[];
        var total_savings=[];
        $.ajax({
            url:"ajax/reports.php",
            type:"GET",
            data:{identityNum:33},
            async: false,
            success:function (data) {
             
                var obj=JSON.parse(data);
              
                for (var i in obj)
                {
                    var full_date=obj[i].claim_date;
                    var mmnth=full_date.split('-');
                    var num=parseInt(mmnth[1]-1);
                    m.push(months[num]);
                    claims_number.push(obj[i].cc);
                }
            },
            error:function (jqXHR, exception) {
                alert(jqXHR.responseText);
            }
        });
        $.ajax({
            url:"ajax/reports.php",
            type:"GET",
            data:{identityNum:34},
            async: false,
            success:function (data) {              
                var obj=JSON.parse(data);              
                for (var i in obj) {
                    total_savings.push(obj[i].savings);
                }
            },
            error:function (jqXHR, exception) {
                alert(jqXHR.responseText);
            }
        });
        m.reverse();
        claims_number.reverse();
        total_savings.reverse();
        $("#sav1").text(total_savings[1].toLocaleString());
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
            colors:["#54bf99","lightgrey"],
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
                },dataLabels: {
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

            },  {
                name: 'Claims',
                type: 'spline',
                data: claims_number,
                tooltip: {
                    valueSuffix: ' '
                }
            }]
        });
//=========================================================

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
       
    });



    ///Functions
    function startTime() {
        var arr=["January","February","March","April","May","June","July","August","September","October","November","December"];
        var today = new Date();
        var h = today.getHours();
        var m = today.getMinutes();
        var s = today.getSeconds();
        var sm = today.getMonth();
        m = checkTime(m);
        s = checkTime(s);
        document.getElementById('dat').innerHTML =arr[sm] + " // "+
            h + ":" + m + ":" + s;
        var t = setTimeout(startTime, 500);
    }
    function checkTime(i) {
        if (i < 3) {i = "0" + i};  // add zero in front of numbers < 10
        return i;
    }
       
</script>