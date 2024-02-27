<?php
session_start();
error_reporting(0);
?>
<html>
<head>

    <link rel="stylesheet" href="bootstrap3/css/bootstrap.min.css">
    <script src="jquery/jquery.min.js"></script>
    <script src="bootstrap3/js/bootstrap.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <link href="css/dataTables.bootstrap.min.css" rel="stylesheet" />
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/schemes.js"></script>
    <title>Med ClaimAssist: Schemes</title>
    <script>
        function proc(username,id) {

            var all=id+username;
           $(".all").css("background-color","white");
            $("#"+all).css("background-color","red");


var obj={identity:22,username:username,id:id};
            $.ajax({
                url:"ajaxPhp/deleting.php",
                type:"GET",
                data:obj,
                success:function(data){
                  $("#info").html(data);
                },
                error:function(jqXHR, exception)
                {
                    alert("There is an connection");
                }
            });
        }
    </script>
<style>
    .hh{
        -webkit-box-shadow: -4px -8px 29px 9px rgba(0,0,0,0.75);
        -moz-box-shadow: -4px -8px 29px 9px rgba(0,0,0,0.75);
        box-shadow: -4px -8px 29px 9px rgba(0,0,0,0.75);
    }
    .hh1{
        -webkit-box-shadow: -4px -8px 29px 9px rgba(0,0,0,0.75);
        -moz-box-shadow: -4px -8px 29px 9px rgba(0,0,0,0.75);
        box-shadow: -4px -8px 29px 9px rgba(0,0,0,0.75);
    }
</style>
</head>

<div>


<?php
session_start();
include("header.php");
echo " <br/><br/><div class='hh' style='width: 95%;position: relative;margin-right: auto;margin-left: auto'> ";
$_SESSION['logxged']=true;
$_SESSION['start_db']=true;
require_once('dbconn.php');
$identity = validateXss($_GET['identity']);
$conn = connection("mca", "MCA_admin");
$conn1 = connection("doc", "doctors");
$conn2 = connection("cod", "Coding");

$stmt=$conn->prepare('SELECT username, count(OPEN) as OPEN FROM claim WHERE Open=1 GROUP BY username');
$stmt->execute();
echo "<table width=\"50%\" style='float: left;' border='1'><thead><tr><th>Username</th><th>Total</th><th>Admed</th><th>Zestlife</th></tr></thead>";
echo "<caption><h3 align='center'>Admed/Zestlife Reports</h3></caption>";
foreach ($stmt->fetchAll() as $row) {
    $username=$row[0];
    $total=0;
    $admed=0;
    $zest=0;
    echo "<tr><td>";
    echo $username."</td>";

$std=$conn->prepare('SELECT c.client_name,count(*),b.client_id FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE a.Open=1
  AND a.username=:user GROUP BY c.client_name');
    $std->bindParam(':user', $username, PDO::PARAM_STR);
    $std->execute();
    foreach ($std->fetchAll() as $rw) {
        $nn = $rw[0];
        $rr = $rw[1];



if ($nn == "Admed")
{
    $admed+=$rr;
    $total+=$rr;

}

if($nn=="Zestlife")
{
    $zest+=$rr;
    $total+=$rr;
}
    }
    $myid="6".$username;
    $myid1="1".$username;
echo "<td><b>$total</b></td>
<td id='$myid' class='all'><span onclick='proc(\"$username\",6)' style='color: green;font-weight: bolder;cursor: pointer'>$admed</span></td>
<td id='$myid1' class='all'><span onclick='proc(\"$username\",1)' style='color: deepskyblue; font-weight: bolder;cursor: pointer'>$zest</span></td>";

echo"</tr>";
    }
echo "<tr><td colspan='4'><div class='hh'><hr></div> </td></tr></table><hr>";

?>
<table width="50%" style="float: right" border="1">
   <caption><h3 align='center'>Claims</h3></caption>
    <tr><td id="info"></td></tr></table>

</body>
</html>