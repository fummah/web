<?php
session_start();
define("access",true);
include ("classes/controls.php");
$control=new controls();
if (!$control->isTopLevel())
{
    die("Invalid entry");
}
include("header.php");
require_once ("classes/leadClass.php");
$obj=new leadClass();
$pagn=$obj->getclinical();
?>
<html>
<head>
    <title>MCA | Clinical Review</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/simplePagination.css" />
    <script src="js/jquery.simplePagination.js"></script>
</head>

<body>
<?php
echo "<br><br>";

?>
<div class="container">
    <h4 class="uk-text" align="center">Clinical Review claims</h4>
    <table id="example" class="striped" width="100%">
        <thead>
        <tr>
            <th>First Name</th>
            <th>Surname</th>
            <th>Claim Number</th>
            <th>Amount</th>
            <th>Username</th>
            <th>Date</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php

        foreach ($pagn as $row) {
            $claim_id=$row[0];
            $gap=$row[6]>$row[7]?$row[6]:$row[7];
            $gap=$control->moneyformat($gap);
            echo "<tr>";
            echo "<td>$row[1]</td>";
            echo "<td>$row[2]</td>";
            echo "<td>$row[3]</td>";
            echo "<td style='color: green'>$gap</td>";
            echo "<td>$row[4]</td>";
            echo "<td>$row[5]</td>";

            echo "<td><form action='case_details.php' method='post'><input type='hidden' name='claim_id' value='$claim_id'/><button name='btn' uk-icon=\"icon: info\" style='color:#54bc9c'></button></form></td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>

</div>
</body>
</html>
<?php
include "footer.php";
?>

<script type="text/javascript">
    $(document).ready(function() {
        $('#example').DataTable({"order": [ 5, 'asc' ]});
        $('.escl').formSelect();
        //$('#example').DataTable();
    } );
</script>