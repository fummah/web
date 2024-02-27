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
</head>

<body>

<?php

include("header.php");
echo"<br><br><br><br><br>";
echo"<h3 align='center'><u>Medical Schemes</u></h3>";
require_once ('dbconn.php');
$my_levels=["admin","claims_specialist","controller"];
if(!in_array($_SESSION["level"],$my_levels))
{
    die("<script>alert('Access Denied');location.href = \"login.html\";</script>");
}
echo "<table id=\"example\" align='center' class=\"table table-striped table-bordered\" cellspacing=\"0\" width=\"95%\">";
echo "<thead style='background-color: #bce8f1'>";
echo "<tr align='center'>";
echo "<th>";
echo "ID";
echo "</th>";
echo "<th>";
echo " Scheme Name";
echo "</th>";
echo "<th>";

echo "</th>";
echo "</tr>";
echo "</thead>";

echo "<tfoot>";
echo "<tr align='center'>";
echo "<th>";
echo "ID";
echo "</th>";
echo "<th>";
echo " Scheme Name";
echo "</th>";
echo "<th>";

echo "</th>";
echo "</tr>";
echo "</tfoot>";
echo "</tbody>";

$conn=connection("mca","MCA_admin");
$stmt = $conn->prepare('SELECT id,name FROM schemes ORDER BY id ASC ');
$stmt->execute();
foreach ($stmt->fetchAll() as $row) {
    $myId=$row[0]."id";
    $mytxt=$row[0]."txt";
    echo "<tr>";
    echo "<td>";
    echo $row[0];
    echo "</td>";
    echo "<td><b>";
    echo $row[1]."</b>";
    $stmt = $conn->prepare('SELECT *FROM scheme_options WHERE scheme_id=:num');
    $stmt->bindParam(':num',$row[0], PDO::PARAM_STR);
    $stmt->execute();
    $num=$stmt->rowCount();
    if($num>0) {

        echo "<details>";
        echo "<summary>Scheme Options($num)</summary>";

        echo "<table class=\"table\">";
  foreach ($stmt->fetchAll() as $row1) {
      echo "<tr>";
      echo " <td width='60%'>$row1[2]</td>";
      echo "<td width='20%'><span class='glyphicon glyphicon-pencil' title='edit' style='color: #00b3ee;cursor: pointer'></span></td>";
      echo "<td width='20%'><span class='glyphicon glyphicon-trash' title='delete' style='color: red;cursor: pointer'></span></td>";
      echo "</tr>";
  }        echo " </table> ";

        echo "</details>";
    }
    echo "</td>";
    echo "<td>";
    echo "<details>";
echo "<summary><span class='glyphicon glyphicon-plus' title='add new scheme option' style='color: #00b3ee;cursor: pointer'></span></summary>";
echo "<br><input type='text' id=\"$myId\"> <button class='btn btn-info' onclick='Schemes(\"$myId\",\"$row[0]\")'>Add</button><br><span id=\"$mytxt\"></span>";
    echo "</details>";
    echo "</td>";
    echo "</tr>";
}
echo "</tbody>";
echo "</table>";

?>
<hr>

<?php
include('footer.php');
?>
</body>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#example').DataTable();
    } );
</script>
<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

</script>
</html>