<?php
session_start();
require_once "dbconn.php";
$conn=connection("mca","MCA_admin");
require_once ("classes/leadClass.php");
$obj=new leadClass();
$pagn=$obj->getclinical();


?>
<html>
<head>

    <title>MCA : Clinical Review</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bootstrap3/css/bootstrap.min.css">
    <script src="jquery/jquery.min.js"></script>
    <script src="bootstrap3/js/bootstrap.min.js"></script>
    <script src="jquery/jquery.min.js"></script>
    <script src="js/jquery-1.12.4.js"></script>
    <link rel="stylesheet" href="admin_main/plugins/datatables-bs4/css/dataTables.bootstrap4.css">  <!-- Theme style -->
    <script src="admin_main/plugins/datatables/jquery.dataTables.js"></script>
    <script src="admin_main/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
    <link href="w3/w3.css" rel="stylesheet" />
    <link rel="stylesheet" href="uikit/css/uikit.min.css" />
    <script src="uikit/js/uikit.min.js"></script>
    <script src="uikit/js/uikit-icons.min.js"></script>
    <link rel="stylesheet" href="js/simplePagination.css" />
    <script src="js/jquery.simplePagination.js"></script>


</head>

<body>
<?php

include("header.php");
echo "<br><br><br><br>";

?>
<div class="container"><hr>
    <h4 class="uk-text" align="center">Clinical Review claims</h4>
    <table id="example" class="uk-table uk-table-striped" width="100%">
        <thead>
        <tr>
            <th>First Name</th>
            <th>Surname</th>
            <th>Claim Number</th>
            <th>Username</th>
            <th>Date</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php

        foreach ($pagn as $row) {
            $claim_id=$row[0];
            echo "<tr>";
            echo "<td>$row[1]</td>";
            echo "<td>$row[2]</td>";
            echo "<td>$row[3]</td>";
            echo "<td>$row[4]</td>";
            echo "<td>$row[5]</td>";
            echo "<td><form action='case_detail.php' method='post'><input type='hidden' name='claim_id' value='$claim_id'/><button name='btn' uk-icon=\"icon: info\"></button></form></td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>

</div>
</body>
</html>
<script type="text/javascript">
    $(document).ready(function() {
        $('#example').DataTable({"order": [ 4, 'asc' ]});
        //$('#example').DataTable();
    } );
</script>