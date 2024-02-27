<?php
session_start();
define("access",true);
include ("classes/controls.php");
$control=new controls();
include ("header.php");
$_SESSION["admin_main"]=$_SERVER['REQUEST_URI'];
$pending_records=$control->viewAllSplitClaimsDoctors("pending",1 ,10,"",1);
$completed_records=$control->viewAllSplitClaimsDoctors("completed",1 ,10,"",1);
?>
<title>MCA | Splits</title>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="js/split.js"></script>
<script type="text/javascript">
    let cv=[];
    let cv1=[];
    google.charts.load("current", {packages:["corechart"]});
    google.charts.load('current', {'packages':['corechart']});

    function drawChart() {
        console.log(cv1);
        var data = google.visualization.arrayToDataTable(cv1);

        var options = {
            title: 'Claims per user',
            is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
    }
    function drawVisualization(graph2) {
        // Some raw data (not necessarily accurate)
        console.log(cv);
        var data = google.visualization.arrayToDataTable(cv);

        var options = {
            title : 'Claims Trend',
            vAxis: {title: 'Claims'},
            hAxis: {title: 'Month'},
            seriesType: 'bars',
            series: {5: {type: 'line'}}
        };

        var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }
    $(function() {
        $('input[name="daterange"]').daterangepicker({
            opens: 'left'
        }, function(start, end, label) {
            $("#startdate").val(start.format('YYYY-MM-DD'));
            $("#enddate").val(end.format('YYYY-MM-DD'));
            loadData();

        });

        $('input[type=radio][name=mytype]').change(function() {
            loadData();
        });
        loadData();
    });
    const loadData = () => {
let startdate=$("#startdate").val();
let enddate=$("#enddate").val();
let mytype=$('input[name="mytype"]:checked').val();
        var obj = {identity_number: 3, startdate:startdate,enddate:enddate,val:mytype};
        $.ajax({
            url: "ajax/split_ajax.php",
            beforeSend:function (xhr)
            {

            },
            type: "POST",
            data: obj,
            success: function (data) {
 let json=JSON.parse(data);

 $("#lines").text(json["totals"][0]["tot_lines"]);
 $("#providers").text(json["totals"][0]["tot_hos"]);
 $("#claims").text(json["totals"][0]["tot_claims"]);
                let graph1=json["graph1"];
                const arrgr1=[['Name', 'Claims per Month']];
                for(let key3 in graph1)
                {
                    let mname=graph1[key3]["closed_by"]===null?"Unassigned":graph1[key3]["closed_by"];
                    let mtot=parseInt(graph1[key3]["tot"]);
                    let art=[mname+"("+mtot+")",mtot];
                    arrgr1.push(art);
                }

                let graph2=json["graph2"];
                cv1=arrgr1;
                cv=grapp2(graph2);
                google.charts.setOnLoadCallback(drawChart);
                google.charts.setOnLoadCallback(drawVisualization);
            },
            complete:function (xhr,status) {

            },
            error: function (jqXHR, exception) {
                console.log("Error here");
            }
        });
    }
const grapp2 = (graph2) =>{

    let arr_dates=[];
    let arr_user=[];
    let umarr=[];
    for(let key in graph2)
    {
        let arr1 = graph2[key];

        for(let key1 in arr1) {
            umarr.push(arr1[key1]);
            let closed_by = arr1[key1]["closed_by"];
            let mdat = arr1[key1]["mdat"];
            arr_user.push(closed_by);
            arr_dates.push(mdat);
        }
    }
    arr_user=[...new Set(arr_user)];
    arr_dates=[...new Set(arr_dates)];
    arr_user.unshift("Month");
    let arrmain=[arr_user];
    arr_dates.sort();
    for(let i=0;i<arr_dates.length;i++)
    {
        let rx=[arr_dates[i]];
        for (let j=1;j<arr_user.length;j++)
        {
            let valarr= serachVal(umarr,arr_dates[i],arr_user[j]);
            let val=valarr.length>0?parseInt(valarr[0]["tot"]):0;
            rx.push(val);
        }
        arrmain.push(rx);
    }
    arrmain[0][1]="Unassigned";
    return arrmain;
}
   const serachVal = (data,mdat,muser) => {
        return data.filter(
            function(data){ return data.mdat === mdat && data.closed_by === muser }
        );
    }

</script>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300&display=swap');
    .mybody{
        font-family: 'Montserrat', sans-serif !important;
    }
</style>
<div class="mybody">
    <div class="uk-card uk-card-body" style="width: 100% !important; border:1px dotted black" >
        <div class="row" style="width: 100% !important;">
            <div class="col-md-2 uk-placeholder" style="padding-left:20px;padding-top: 20px; background-color: whitesmoke">
                <label>Select Date Range</label><input type="text" name="daterange">
                <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
                    <label><input class="uk-radio" type="radio" name="mytype" value="all" checked> <span>All Claims</span></label><br>
                    <label><input class="uk-radio" type="radio" name="mytype" value="pending"> <span>Active Claims</span></label>
                    <label><input class="uk-radio" type="radio" name="mytype" value="completed"> <span>Completed</span></label>
                </div>
                <p><span style="color: cadetblue" uk-icon="play-circle"></span> Claim Lines : <span style="float: right" class="uk-badge sub_badge" id="lines">0</span></p>
                <p><span style="color: cadetblue" uk-icon="play-circle"></span> Providers : <span style="float: right" class="uk-badge sub_badge" id="providers">0</span></span></p>
                <p><span style="color: cadetblue"s uk-icon="play-circle"></span> Claims : <span style="float: right" class="uk-badge sub_badge" id="claims">0</span></p>
                <p><span uk-icon="gitter"></span> <a href="splits.php">Active Claims</a></p>
            </div>
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-6"> <div id="piechart_3d" style="width: 600px; height: 350px;"></div></div>
                    <div class="col-md-6"> <div id="chart_div" style="width: 600px; height: 350px;"></div></div>
                </div>
                <div style="border-top:1px dotted #0b8278; padding-top: 5px">
                <table id="example" class="striped" style="width:100%;">

                    <thead>
                    <tr>
                        <th>File ID</th>
                        <th>File Name</th>
                        <th>Total Rows loaded</th>
                        <th>Total Rows</th>
                        <th>Total Claims</th>
                        <th>Date Entered</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($control->viewSplitFiles() as $row)
                    {
                        $id=$row["id"];
                        $file_name=$row["file_name"];
                        $total_claims=$row["total_claims"];
                        $total_loaded=$row["total_loaded"];
                        $date_entered=$row["date_entered"];
                        $total_actclaims=$row["total_actclaims"];
                        $path="file-claims.php?filename=$file_name&fileid=".$id;
                        echo "<tr><td>$id</td><td><a href='$path' onclick=\"window.open('$path','popup','width=1400,height=1000'); return false;\"> $file_name</a></td><td>$total_loaded</td><td>$total_claims</td><td>$total_actclaims</td><td>$date_entered</td></tr>";
                    }

                    ?>
                    </tbody>
                </table>
            </div>
            </div>

        </div>
    </div>

</div>
<input type="hidden" id="startdate">
<input type="hidden" id="enddate">
<!-- This is the modal with the default close button -->
<div class="footer-copyright" style="padding-left: 20px">

</div>
<script>
    $(document).ready(function() {
        $('#example').DataTable();
        $('.escl').formSelect();
    } );
</script>