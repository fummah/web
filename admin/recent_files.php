<?php
session_start();
error_reporting(0);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Recent Files</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bootstrap3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <script src="jquery/jquery.min.js"></script>
    <script src="bootstrap3/js/bootstrap.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <link href="css/dataTables.bootstrap.min.css" rel="stylesheet" />
    <script src="js/jquery.dataTables.min.js"></script>
    <style>
        .linkbutton {
            background: none;
            border: none;
            color: #0066ff;
            text-decoration: underline;
            cursor: pointer;

        }
    </style>
</head>
<body onload="openForm(event, 'edited')">
<?php
require_once ('header.php');
require_once ('dbconn.php');
$username=$_SESSION['user_id'];
$my_levels=["admin","claims_specialist","controller"];
if(!in_array($_SESSION["level"],$my_levels))
{
    die("<script>alert('Access Denied');location.href = \"login.html\";</script>");
}
?>
<br><br><br><br><br>
<div class="w3-sidebar w3-bar-block w3-light-grey w3-card" style="width:130px">
    <h5 style="background-color: #00b3ee" class="w3-bar-item">Recent Cases</h5>
    <button class="w3-bar-item w3-button tablink" onclick="openForm(event, 'edited')">Edited</button>
    <button class="w3-bar-item w3-button tablink" onclick="openForm(event, 'opened')">Opened</button>
    <button class="w3-bar-item w3-button tablink" onclick="openForm(event, 'notes')">Notes</button>
</div>

<div style="margin-left:130px">
    <div class="w3-padding" style="color:deepskyblue">View recent cases</div>

    <div id="edited" class="w3-container city" style="display:none">
        <h2>Edited Cases</h2>
        <table class="table">
            <thead>
            <tr>
                <th>First Name</th>
                <th>Surname</th>
                <th>Claim Number</th>
                <th>Policy Number</th>
                <th>Client Name</th>
                <th>Date and Time</th>
                <th>Owner</th>
            </tr>
            </thead>

            <tbody>
            <?php
            $query="select a.claim_id, max(a.id) as lastClaim,a.member_name,a.member_surname,a.claim_number,a.policy_number,a.date,a.client_id,a.owner from logs as a 
 WHERE a.owner=:owner group by a.claim_id ORDER by date DESC LIMIT 10";
            if($_SESSION['level']=="admin")
            {
                $query="select a.claim_id, max(a.id) as lastClaim,a.member_name,a.member_surname,a.claim_number,a.policy_number,a.date,a.client_id,a.owner from logs as a 
 group by a.claim_id ORDER by date DESC LIMIT 15";

            }

            $conn=connection("mca","MCA_admin");
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':owner', $username, PDO::PARAM_STR);
            $stmt->execute();

            foreach ($stmt->fetchAll() as $row) {
                ?>
                <tr>
                    <td><?php echo $row[2]?></td>
                    <td><?php echo $row[3]?></td>
                    <td><?php
                        echo "<form action='case_detail.php' method='post' />";
                        echo "<input type=\"hidden\" name=\"claim_id\" value=\"$row[0]\" />";
                        echo "<input type=\"submit\" class=\"linkbutton\" name=\"btn\" value=\"$row[4]\">";
                        echo "</form>";
                        ?></td>
                    <td><?php echo $row[5]?></td>
                    <td><?php echo $row[7]?></td>
                    <td><?php echo $row[6]?></td>
                    <td><?php echo $row[8]?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
            <tfooter>
                <tr>
                    <th>First Name</th>
                    <th>Surname</th>
                    <th>Claim Number</th>
                    <th>Policy Number</th>
                    <th>Client Name</th>
                    <th>Date and Time</th>
                    <th>Owner</th>
                </tr>
            </tfooter>

        </table>
    </div>

    <div id="opened" class="w3-container city" style="display:none">
        <h2>Opened Cases</h2>
        <table class="table">
            <thead>
            <tr>
                <th>First Name</th>
                <th>Surname</th>
                <th>Claim Number</th>
                <th>Policy Number</th>
                <th>Client Name</th>
                <th>Date and Time</th>
                <th>Owner</th>
            </tr>
            </thead>

            <tbody>
            <?php
            $query="select a.claim_id,a.gap,b.first_name,b.surname,a.claim_number,b.policy_number,a.recent_date_time,c.client_name,a.username from claim as a
INNER  JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE a.username=:owner ORDER by a.recent_date_time DESC LIMIT 10";
            if($_SESSION['level']=="admin")
            {
                $query="select a.claim_id,a.gap,b.first_name,b.surname,a.claim_number,b.policy_number,a.recent_date_time,c.client_name,a.username from claim as a
INNER  JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id ORDER by a.recent_date_time DESC LIMIT 15";

            }

            $conn=connection("mca","MCA_admin");
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':owner', $username, PDO::PARAM_STR);
            $stmt->execute();

            foreach ($stmt->fetchAll() as $row) {
                ?>
                <tr>
                    <td><?php echo $row[2]?></td>
                    <td><?php echo $row[3]?></td>
                    <td><?php
                        echo "<form action='case_detail.php' method='post' />";
                        echo "<input type=\"hidden\" name=\"claim_id\" value=\"$row[0]\" />";
                        echo "<input type=\"submit\" class=\"linkbutton\" name=\"btn\" value=\"$row[4]\">";
                        echo "</form>";
                        ?></td>
                    <td><?php echo $row[5]?></td>
                    <td><?php echo $row[7]?></td>
                    <td><?php echo $row[6]?></td>
                    <td><?php echo $row[8]?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
            </tbody>
            <tfooter>
                <tr>
                    <th>First Name</th>
                    <th>Surname</th>
                    <th>Claim Number</th>
                    <th>Policy Number</th>
                    <th>Client Name</th>
                    <th>Date and Time</th>
                    <th>Owner</th>
                </tr>
            </tfooter>

        </table>
    </div>

    <div id="notes" class="w3-container city" style="display:none">
        <h2>Recent Notes</h2>
        <table class="table">
            <thead>
            <tr>
                <th>First Name</th>
                <th>Surname</th>
                <th>Claim Number</th>
                <th>Policy Number</th>
                <th>Client Name</th>
                <th>Date and Time</th>
                <th>Owner</th>
            </tr>
            </thead>

            <tbody>
            <?php
            $query="select c.claim_id, max(c.intervention_id) as lastNote,b.first_name,b.surname,a.claim_number,b.policy_number,c.date_entered,b.client_id,a.username from intervention as c 
INNER JOIN claim as a ON c.claim_id=a.claim_id INNER  JOIN member as b ON a.member_id=b.member_id WHERE a.username=:owner group by c.claim_id ORDER by c.date_entered DESC LIMIT 10";
            if($_SESSION['level']=="admin")
            {

                $query="select c.claim_id, max(c.intervention_id) as lastNote,b.first_name,b.surname,a.claim_number,b.policy_number,c.date_entered,b.client_id,a.username from intervention as c 
INNER JOIN claim as a ON c.claim_id=a.claim_id INNER  JOIN member as b ON a.member_id=b.member_id group by c.claim_id ORDER by c.date_entered DESC LIMIT 15";
            }

            $conn=connection("mca","MCA_admin");
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':owner', $username, PDO::PARAM_STR);
            $stmt->execute();

            foreach ($stmt->fetchAll() as $row) {
                ?>
                <tr>
                    <td><?php echo $row[2]?></td>
                    <td><?php echo $row[3]?></td>
                    <td><?php
                        echo "<form action='case_detail.php' method='post' />";
                        echo "<input type=\"hidden\" name=\"claim_id\" value=\"$row[0]\" />";
                        echo "<input type=\"submit\" class=\"linkbutton\" name=\"btn\" value=\"$row[4]\">";
                        echo "</form>";
                        ?></td>
                    <td><?php echo $row[5]?></td>
                    <td><?php echo $row[7]?></td>
                    <td><?php echo $row[6]?></td>
                    <td><?php echo $row[8]?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
            <tfooter>
                <tr>
                    <th>First Name</th>
                    <th>Surname</th>
                    <th>Claim Number</th>
                    <th>Policy Number</th>
                    <th>Client Name</th>
                    <th>Date and Time</th>
                    <th>Owner</th>
                </tr>
            </tfooter>

        </table>
    </div>

</div>

<script>
    function openForm(evt, formName) {
        var i, x, tablinks;
        x = document.getElementsByClassName("city");
        for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablink");
        for (i = 0; i < x.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" w3-red", "");
        }
        document.getElementById(formName).style.display = "block";
        evt.currentTarget.className += " w3-red";
    }
</script>
<?php
require_once ('footer.php');
?>
</body>
</html>
