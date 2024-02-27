<script src="../admin_main/plugins/jquery/jquery.min.js"></script>
<script src="../admin_main/plugins/jquery-ui/jquery-ui.min.js"></script>
<link rel="stylesheet" href="../css/uikit.min.css" />
<script src="../js/uikit.min.js"></script>
<script src="../js/uikit-icons.min.js"></script>
<script src="main.js"></script>
<style>
    .linkButton {
        background: none;
        border: none;
        color: #0066ff;
        text-decoration: underline;
        cursor: pointer;
    }
</style>
<?php
if (!isset($_GET["claim_number"])) {
    die("Invalid Entry");
}
session_start();
define("access",true);
require "../classes/controls.php";
include ("../templates/claim_templates.php");
$control=new controls();
$role=$control->myRole();
$username=$control->loggedAs();
$claim_number=$_GET["claim_number"];
echo "<table class='striped uk-table' style='border: 2px solid whitesmoke'>";
            echo "<tr style=\"color: black\">
        <th>Name  and Surname</th>
        <th>Policy Number</th>
        <th>Claim Number</th>
        <th>Scheme Number</th>
        <th>Medical Scheme</th>
        <th>Date Entered</th><tbody>";

foreach($control->viewAllClaims($role,0 ,2,$username,$claim_number,0,0) as $row) {
    $name = htmlspecialchars(strtoupper($row[0] . " " . $row[1]));
    $policy = htmlspecialchars(strtoupper($row[2]));
    $claim_number = htmlspecialchars(strtoupper($row[3]));
    $scheme_savings = htmlspecialchars($row[4]);
    $medical_scheme = htmlspecialchars($row[5]);
    $date_entered = htmlspecialchars($row["date_entered"]);
    $claim_id = htmlspecialchars($row[11]);
    echo "<tr><td>$name</td><td>$policy</td>";
    echo"<td style='color: red'>";
    echo "<form action='../case_details.php' method='post'>";
    echo "<input type=\"hidden\" name=\"claim_id\" value=\"$claim_id\" />";
    echo "<button class='linkButton' name=\"btn\" style=\"color: #0b8278;\">$claim_number</button>";
    echo "</form>";
    echo "</td>";
echo"<td>$scheme_savings</td><td>$medical_scheme</td><td>$date_entered</td></tr>";
}
            echo "</tbody></table>";

