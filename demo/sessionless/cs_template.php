
<?php
include ("sessionLessClass.php");
$conn=connection("mca","MCA_admin");
$username=$_GET["username"];
$temp = new mcaSessionless\sessionLessClass();
$period=date("F Y",strtotime("-1 month"));
$today=date("Y-m-d H:i:s");
$start_date=date("Y-m-01",strtotime("-6 month"));
$end_date=date("Y-m-01");
$currentmonth1=date("Y-m-01",strtotime("-1 month"));
$currentmonth2=date("Y-m-01");
$getlastmonth=$temp->getDetails($username,$currentmonth1,$currentmonth2);

?>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<link rel="stylesheet" href="../css/uikit.min.css" />
<script src="../js/uikit.min.js"></script>
<script src="../js/uikit-icons.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<link href="../css/bootstrap.min.css" rel="stylesheet" integrity="" crossorigin="anonymous">
<script>
    let mcarr_savings=[["Month", "Savings", { role: "style" } ],];
    let mcarr_closed=[["Month", "Closed Claims", { role: "style" } ],];
    let mcarr_qa=[["Month", "QA", { role: "style" } ]];
    let  start_date="<?php echo $start_date;?>";
    let  end_date="<?php echo $end_date;?>";
    let  username="<?php echo $username;?>";
    getValues();
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    google.charts.setOnLoadCallback(drawChart1);
    google.charts.setOnLoadCallback(drawChart2);

    function getValues() {
        let obj={identity_number:1,username:username,start_date:start_date,end_date:end_date};

        $.ajax({
            url: "cs_ajax.php",
            async:false,
            beforeSend:function (xhr)
            {

            },
            type:"POST",
            data:obj,
            success: function(data){
                console.log(data);
                const json=JSON.parse(data);
                console.log(json);
                for(key in json)
                {
                    let month=json[key]["month"];
                    let savings=parseFloat(json[key]["savings"]);
                    let closed=parseFloat(json[key]["closed"]);
                    let qa=parseFloat(json[key]["qa"]);
                    mcarr_savings.push([month,savings,"#64c49c"])
                    mcarr_closed.push([month,closed,"#2ea2cc"])
                    mcarr_qa.push([month,qa,"cadetblue"])
                }


            },
            complete:function (xhr,status) {
                $("#bb").empty();
            },
            error:function (xhr,status,error) {
                alert("There is an error");
            }
        });
    }
    function drawChart() {

        var data = google.visualization.arrayToDataTable(mcarr_savings);
        var view = new google.visualization.DataView(data);
        view.setColumns([0, 1,
            { calc: "stringify",
                sourceColumn: 1,
                type: "string",
                role: "annotation" },
            2]);

        var options = {
            title: "Savings Trend",
            width: 700,
            height: 300,
            bar: {groupWidth: "70%"},
            legend: { position: "none" },
        };

        var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
        chart.draw(view, options);
    }
    function drawChart1() {
        var data = google.visualization.arrayToDataTable(mcarr_closed);

        var view = new google.visualization.DataView(data);
        view.setColumns([0, 1,
            { calc: "stringify",
                sourceColumn: 1,
                type: "string",
                role: "annotation" },
            2]);

        var options = {
            title: "Closed Cases Trend",
            width: 700,
            height: 300,
            bar: {groupWidth: "70%"},
            legend: { position: "none" },
            vAxis: {minValue: 0},
        };
        var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values1"));
        chart.draw(view, options);
    }
    function drawChart2() {
        var data = google.visualization.arrayToDataTable(mcarr_qa);
        var view = new google.visualization.DataView(data);
        view.setColumns([0, 1,
            { calc: "stringify",
                sourceColumn: 1,
                type: "string",
                role: "annotation" },
            2]);

        var options = {
            title: "QA Trend",
            width: 700,
            height: 300,
            bar: {groupWidth: "70%"},
            legend: { position: "none" },
            vAxis: {minValue: 0},
        };
        var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values2"));
        chart.draw(view, options);
    }

</script>
<style>
    h1,h2,h3,h4,h5{
        margin-top: 1px !important;

    }
    p{
        margin-bottom: 1px !important;
    }

</style>
<div style="padding: 5px; ">
<p align="center"><img src="../images/newlogo.png" width="50" height="50"></p>
<h2 style="color:red !important;" class="" align="center"><?php echo $temp->getFullname($username); ?> - Performance</h2>
<h4 align="center">Period Covered : <span style="color: darkgreen !important;"><?php echo $period; ?></span></h4>
<h5 align="center">Date Issued : <span style="color: darkgreen !important;"><?php echo $today; ?></span></h5>
</div>
<div style="width: 100%;border: 5px solid #81c7df;border-radius: 7px; padding 15px ">
    <div id="columnchart_values" style="width: 50% !important;"></div>
    <div id="columnchart_values1" style="width: 50% !important;"></div>
    <div id="columnchart_values2" style="width: 50% !important;"></div>

</div>
<div style="border: 5px solid #81c7df; border-radius: 7px; padding 15px">
<h3 align="center" style="color:#64c49c !important; padding-top: 5px !important;">Incentive Model</h3>
<?php
$savings=(double)$getlastmonth[0]["savings"];
$claim_value=(double)$getlastmonth[0]["claim_value"];
$closed_claims=(double)$getlastmonth[0]["closed"];
$qa=(double)$getlastmonth[0]["qa"];
$savings_perc=(double)$getlastmonth[0]["savings_perc"];
$descr_savings="-";
$descr_claims="-";
$descr_qa="-";
$savings1="-";
$savings_color="";
$closed_color="";
$qa_color="";
$pers=0;
if($savings_perc<10){ $savings1="5";$pers = 0;$descr_savings="Less than 10 perc";$savings_color="red !important";}
elseif ($savings_perc>=10 && $savings_perc<=12){$savings1="4";$pers = 6.25;$descr_savings="10 to 12 perc"; $savings_color="gold !important";}
elseif ($savings_perc>12 && $savings_perc<=14){$savings1="3";$pers = 8.33;$descr_savings="13 to 14 perc"; $savings_color="gold !important";}
elseif ($savings_perc>14 && $savings_perc<=16){$savings1="2";$pers = 12.50;$descr_savings="15 to 16 perc";$savings_color="gold !important";}
elseif ($savings_perc>16){$savings_perc="1";$pers = 25;$descr_savings="17 and Above"; $savings_color="lawngreen !important";}

// QA
$qa1="-";
$perq=0;

if($qa<80){ $qa1="5";$perq = 0;$descr_qa="Less than 80";$qa_color="red !important";}
elseif ($qa>=80 && $qa<=84){$qa1="4";$perq = 12.50;$descr_qa="80 to 84";$qa_color="gold !important";}
elseif ($qa>84 && $qa<=89){$qa1="3";$perq = 16.67;$descr_qa="85 to 90";$qa_color="gold !important";}
elseif ($qa>89 && $qa<=94){$qa1="2";$perq = 25;$descr_qa="90 to 94";$qa_color="gold !important";}
elseif ($qa>94){$qa1="1";$perq = 50;$descr_qa="95 and Above";$qa_color="lawngreen !important";}

//Closed claims
$closed_claims1="-";
$perc=0;
if($closed_claims<180){ $closed_claims1="5";$perc = 0;$descr_claims="Less than 180";$closed_color="red !important";}
elseif ($closed_claims>=180 && $closed_claims<=184){$closed_claims1="4";$perc = 6.25;$descr_claims="180";$closed_color="gold !important";}
elseif ($closed_claims>184 && $closed_claims<=189){$closed_claims1="3";$perc = 8.33;$descr_qa="-";$descr_claims="185";$closed_color="gold !important";}
elseif ($closed_claims==190){$closed_claims1="2";$perc = 12.50;$descr_claims="190";$closed_color="gold !important";}
elseif ($closed_claims>190){$closed_claims1="1";$perc = 25;$descr_claims="190 and Above";$closed_color="lawngreen !important";}

$overal=$pers+$perq+$perc;
$color1="";
$color2="";
$color3="";
if ($savings<250000){}elseif ($savings>=250000 && $savings<400000){}else{$color1="lawngreen !important";}
if ($closed_claims<150){}elseif ($closed_claims>=150 && $closed_claims<240){}else{$color2="lawngreen !important";}
if ($qa<50){}elseif ($qa>=50 && $qa<90){}else{$color3="lawngreen !important";}
?>
    <div>
<table class="uk-table uk-table-striped">
    <thead>
    <tr style="color: #64c49c !important; font-weight: bolder !important;"><td></td><td>Target</td><td>Weighting</td><td>Overall</td><td>Grade</td></tr>
    </thead>
    <tbody>
    <?php
    echo"<tr><td style='color: #64c49c !important; font-weight: bolder !important;'>Savings</td><td>$descr_savings</td><td>$savings1</td><td>$pers%</td><td style='print-color-adjust: exact;-webkit-print-color-adjust: exact;background-color: $savings_color'></td></tr>
    <tr><td style='color: #64c49c !important; font-weight: bolder !important;'>Closed Claims</td><td>$descr_claims</td><td>$closed_claims1</td><td>$perc%</td><td style='print-color-adjust: exact;-webkit-print-color-adjust: exact;background-color: $closed_color'></td></tr>
    <tr><td style='color: #64c49c !important; font-weight: bolder !important;'>QA</td><td>$descr_qa</td><td>$qa1</td><td>$perq%</td><td style='print-color-adjust: exact;-webkit-print-color-adjust: exact;background-color: $qa_color'></td></tr>
    <tr style='color: darkred !important; font-weight: bolder !important;'><td></td><td></td><td></td><td>$overal%</td><td></td></tr>";
    ?>
    </tbody>
</table>
    </div>
<div style="padding-top:10px;border-top: 1px dashed lightgrey;">
<h3 align="center" style="color:#64c49c !important;">Claims Analysis and QA</h3>
<table class="uk-table uk-table-striped">
    <thead>
    <tr style="font-weight: bolder !important;"><th>Savings<th>Total Closed</th><th>Claims Value</th><th>QA</th></tr>
    </thead>
    <tbody>
    <?php
    $savings=$temp->format($savings);
    $claim_value=$temp->format($claim_value);
        echo"<tr style='color: #64c49c !important; font-weight: bolder !important;'><td style='print-color-adjust: exact;-webkit-print-color-adjust: exact;background-color: $color1'>$savings</td><td style='print-color-adjust: exact;-webkit-print-color-adjust: exact;background-color: $color2'>$closed_claims</td><td>$claim_value</td><td style='print-color-adjust: exact;-webkit-print-color-adjust: exact;background-color: $color3'>$qa%</td></tr>";
    ?>
    </tbody>
</table>
</div>
<h5 align="center" style="color: darkred !important;">Areas of Improvement</h5>
<table class="uk-table table-bordered"><thead><tr>
        <td style="font-weight: bolder !important;">Grade</td>
        <td style="font-weight: bolder !important;">Description</td></tr></thead><tbody>
<?php
$aa=$temp->aiQA($username,$currentmonth1,$currentmonth2);
foreach ($aa["data"] as $rr)
{
    $descr=$rr["descr"];
    $total=$rr["total"];
    $clas="";
    if($total>0 && $total<2){$clas="lightgrey !important";}
    else if($total>=2 && $total < 5){$clas="gold !important";}
    else if($total>=5){$clas="red !important";}
    echo "<tr ><td style='print-color-adjust: exact;-webkit-print-color-adjust: exact;background-color: $clas'></td><td>$descr</td></tr>";
}
?>
    </tbody></table>

</div>
