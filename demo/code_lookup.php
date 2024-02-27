<?php
session_start();
error_reporting(0);
define("access",true);
?>
<html>
<head>
    <title>Code Lookup</title>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet" integrity="" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <script src="js/bootstrap.bundle.min.js" integrity="" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js" integrity="" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="css/datatables.min.css"/>
    <link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap4.css">
    <script type="text/javascript" src="js/datatables.min.js"></script>
    <link rel="stylesheet" href="css/uikit.min.css" />
    <script src="js/uikit.min.js"></script>
    <script src="js/uikit-icons.min.js"></script>
</head>

<body>
<?php

include_once "dbconn.php";


?>


<?php
$conn2 = connection("cod", "Coding");
function getDescription($cpt4)
{
    global $conn2;
    $stmt2 = $conn2->prepare('SELECT long_description FROM `CPT_Codes` WHERE `CPT_code`=:cpt4');
    $stmt2->bindParam(':cpt4', $cpt4, PDO::PARAM_STR);
    $stmt2->execute();
    return $stmt2->fetchColumn();

}
function getTariff($cpt4)
{
    global $conn2;
    $stmt3 = $conn2->prepare('SELECT clinical_code FROM `ClinicalXref` WHERE `clinical_xref`=:cpt4');
    $stmt3->bindParam(':cpt4', $cpt4, PDO::PARAM_STR);
    $stmt3->execute();
    return $stmt3->fetchAll();

}
function getTariffDescr($rpl)
{
    global $conn2;
    $stmt2 = $conn2->prepare('SELECT Description FROM `TariffMaster` WHERE `Tariff_Code`=:rpl LIMIT 1');
    $stmt2->bindParam(':rpl', $rpl, PDO::PARAM_STR);
    $stmt2->execute();
    return $stmt2->fetchColumn();

}

function getIcd10($cpt4)
{
    global $conn2;
    $stmt3 = $conn2->prepare('SELECT clinical_xref FROM `ClinicalXref` WHERE `clinical_code`=:cpt4');
    $stmt3->bindParam(':cpt4', $cpt4, PDO::PARAM_STR);
    $stmt3->execute();
    return $stmt3->fetchAll();

}
function getIcd10Descr($icd10)
{
    global $conn2;
    $stmt2 = $conn2->prepare('SELECT shortdesc FROM `Diagnosis` WHERE `diag_code`=:rpl LIMIT 1');
    $stmt2->bindParam(':rpl', $icd10, PDO::PARAM_STR);
    $stmt2->execute();
    return $stmt2->fetchColumn();

}
if(!isset($_GET['tariff']))
{
    die("Invalid request");
}
$tariff=$_GET['tariff'];
$icd10=$_GET['icd10'];
$xref = "TRCP";
$xref1 = "CPDI";
echo "<div class=\"container\"><h5 class='uk-text-muted'>Tariff : $tariff<br>ICD10 : $icd10</h5><hr>

    <div class=\"row\">
     ";
try {


    $stmt = $conn2->prepare('SELECT * FROM `ClinicalXref` WHERE `clinical_xref` = :icd10 AND xref_type = :cdpi');
    $stmt->bindParam(':cdpi', $xref1, PDO::PARAM_STR);
    $stmt->bindParam(':icd10', $icd10, PDO::PARAM_STR);
    $stmt->execute();
    echo "   <div class=\"col-md-12\">
            <table class=\"uk-table uk-table-divider\"><thead> <tr><td colspan='2'>Associated RPL Codes</td><td>CPT4 Codes</td><td>CPT4 Description</td></tr></thead>";
    foreach ($stmt->fetchAll() as $row)
    {
        $cpt4=$row[0];
        $cpt4descr=getDescription($cpt4);

        echo "<tr class='uk-card uk-card-default uk-card-body'><td style='width: 50%'><table>";
        foreach (getTariff($cpt4) as $rr)
        {
            $associaterpl=$rr[0];
            $descr=getTariffDescr($associaterpl);
            echo "<tr><td class='uk-text-success'>$associaterpl</td><td>$descr</td></tr>";
        }

        echo"</table></td><td class='uk-text-danger'>$cpt4</td><td>$cpt4descr</td></tr>";
    }
    echo " </table><hr>";
    $stmt1 = $conn2->prepare('SELECT * FROM `ClinicalXref` WHERE `clinical_code` = :tarrif AND xref_type = :cdpi');
    $stmt1->bindParam(':cdpi', $xref, PDO::PARAM_STR);
    $stmt1->bindParam(':tarrif', $tariff, PDO::PARAM_STR);
    $stmt1->execute();

    echo "   <div class=\"col-md-12\">
            <table class=\"uk-table uk-table-divider\"><thead style='background-color: #0d92e1'> <tr><td colspan='2'>Associated Diagnosis Codes</td><td>CPT4 Codes</td><td>CPT4 Description</td></tr></thead>";
    foreach ($stmt1->fetchAll() as $row)
    {

        $cpt4=$row[1];
        $cpt4descr=getDescription($cpt4);

        echo "<tr class='uk-card uk-card-default uk-card-body'><td style='width: 50%'><table>";
        foreach (getIcd10($cpt4) as $rr)
        {
            $associaterpl=$rr[0];
            $descr=getIcd10Descr($associaterpl);
            echo "<tr><td class='uk-text-success'>$associaterpl</td><td>$descr</td></tr>";
        }

        echo"</table></td><td class='uk-text-danger uk-card uk-card-default uk-card-body'>$cpt4</td><td class='uk-text-danger uk-card uk-card-default uk-card-body'>$cpt4descr</td></tr>";
    }
    echo " </table>";
        echo "</div>";
}
catch (Exception $e)
{
    echo $e;
}
?>

    </div>
</div>
<hr>
<?php
include('footer.php');
?>
</body>
</html>