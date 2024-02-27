<?php
session_start();
define("access",true);
include ("classes/controls.php");
$control=new controls();
if ($control->isAssessor() || $control->isTopLevel())
{
}
else{
    die("Invalid entry");
}
include("header.php");
require_once ("classes/leadClass.php");
$obj=new leadClass();
$pagn=$obj->getPreassessed($control->myRole(),$control->loggedAs());
?>
    <html>
    <head>
        <title>MCA | Pre-assessment Claims</title>
        <style>
            thead>tr>th{
                color: #54bc9c !important;
            }
        </style>
    </head>
    <body>
    <?php
    echo "<br><br>";
    ?>
    <div class="container">
        <h3 class="uk-text" align="center"><u>Pre-Assessment Claims</u></h3>
        <table id="example" class="uk-table uk-table-striped" width="100%">
            <thead>
            <tr>
                <th>First Name</th>
                <th>Surname</th>
                <th>Claim Number</th>
                <th>Username</th>
                <th>Pre-Assessor</th>
                <th>Client Name</th>
                <th>Claim Lines?</th>
                <th>Date</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($pagn as $row) {
                $claim_id=$row[0];
                $yuser=strlen($row[5])>1?$row[5]:$row[4];
                echo "<tr>";
                echo "<th>$row[1]</th>";
                echo "<th>$row[2]</th>";
                echo "<th>$row[3]</th>";
                echo "<th>$row[4]</th>";
                echo "<th>$yuser</th>";
                echo "<th>$row[6]</th>";

                if($control->viewLatestClaimLine($claim_id)==true)
                {
                    echo "<th style='color: green'>Yes</th>";
                }
                else
                {
                    echo "<th style='color: red'>No</th>";
                }
                echo "<th>$row[7]</th>";
                echo "<th><form action='case_details.php' method='post'><input type='hidden' name='claim_id' value='$claim_id'/><button name='btn' uk-icon=\"icon: info\" style='color:#54bc9c'></button></form></th>";
                echo "<th><form action='edit_case.php' method='post' />";
                echo "<input type=\"hidden\" name=\"claim_id\" value=\"$claim_id\" />";
                echo "<button type=\"submit\" name=\"btn\" uk-icon=\"icon:  pencil\" style='color:#54bc9c'></button>";
                echo "</form></th>";
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
            $('select').formSelect();
        } );
    </script>

