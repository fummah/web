<?php

session_start();
error_reporting(0);

include_once "dbconn.php";
include_once "function.php";
if(!isset($_SESSION['logxged']))
{
    die("There is an error");

}
?>

<style type="text/css">
    .navi {
        width: 500px;
        margin: 5px;
        padding:2px 5px;
        border:1px solid #eee;
    }

    .show {
        color: blue;
        margin: 5px 0;
        padding: 3px 5px;
        cursor: pointer;
        font: 15px/19px Arial,Helvetica,sans-serif;
    }
    .show a {
        text-decoration: none;
    }
    .show:hover {
        text-decoration: underline;
    }


    ul.setPaginate li.setPage{
        padding:15px 10px;
        font-size:14px;
    }

    ul.setPaginate{
        margin:0px;
        padding:0px;
        height:100%;
        overflow:hidden;
        font:12px 'Tahoma';
        list-style-type:none;
    }

    ul.setPaginate li.dot{padding: 3px 0;}

    ul.setPaginate li{
        float:left;
        margin:0px;
        padding:0px;
        margin-left:5px;
    }



    ul.setPaginate li a
    {
        background: none repeat scroll 0 0 #ffffff;
        border: 1px solid #cccccc;
        color: #999999;
        display: inline-block;
        font: 15px/25px Arial,Helvetica,sans-serif;
        margin: 5px 3px 0 0;
        padding: 0 5px;
        text-align: center;
        text-decoration: none;
    }

    ul.setPaginate li a:hover,
    ul.setPaginate li a.current_page
    {
        background: none repeat scroll 0 0 #0d92e1;
        border: 1px solid #000000;
        color: #ffffff;
        text-decoration: none;
    }

    ul.setPaginate li a{
        color:black;
        display:block;
        text-decoration:none;
        padding:5px 8px;
        text-decoration: none;
    }
</style>

</head>

<body>
<table class="table">

    <?php
    $r=$_SESSION['user_id'];





    function searchFunction($val)
    {
        ?>
        <th>Name  and Surname</th>
        <th>Policy Number</th>
        <th>Claim Number</th>
        <th>Scheme Number</th>
        <th>Medical Scheme</th>
        <th>Date Entered</th>
        <th>Date Closed</th>

        <th>Scheme Savings</th>
        <th>Discount Savings</th>
        <th>Owner</th>
        <?php
        $conn=connection("mca","MCA_admin");
        $r=$_SESSION['user_id'];
        if ($_SESSION['level'] == "claims_specialist") {
            $condition = "a.username = :num";
        } else if ($_SESSION['level'] == "gap_cover") {
            $dbh = connection("mca", "MCA_admin");
            $stmt = $dbh->prepare("SELECT client_id FROM clients WHERE client_name = :name");
            $stmt->bindParam(':name', $r, PDO::PARAM_STR);
            $stmt->execute();
            $clientNameID = $stmt->fetchColumn();
            $r = $clientNameID;
            $condition = "c.client_id = :num";
            if($r==3)
            {
                $condition = "(c.client_id=15 OR c.client_id=27 OR c.client_id = :num)";
            }
            if($r==21)
            {
                $condition = "(c.client_id=26 OR c.client_id = :num)";
            }
            if($r==16)
            {
                $condition = "(c.client_id=27 OR c.client_id = :num)";
            }
        } else if ($_SESSION['level'] == "patient") {
            $condition = "patient_name = :num";
        }
        else if($_SESSION['level'] == "admin" || $_SESSION['level'] == "controller")
        {
            $condition="1";
        }
        else
        {
            die("There is an error");
        }
        $searched="%".$val."%";
        $sql = $conn->prepare("SELECT c.first_name,c.surname,c.policy_number,a.claim_number,a.savings_scheme,c.medical_scheme,c.scheme_option,a.date_closed,
c.client_id,a.date_entered,a.Open,a.claim_id,a.username,a.savings_discount,c.scheme_number FROM claim as a INNER JOIN member as c ON a.member_id=c.member_id INNER JOIN clients as b ON c.client_id=b.client_id 
WHERE $condition AND (c.first_name like :search OR c.surname like :search OR a.claim_number like :search OR c.medical_scheme like :search OR c.policy_number like :search 
OR b.client_name like :search OR a.username like :search OR c.scheme_number like :search OR a.date_closed LIKE :search) ORDER BY a.date_entered DESC");
        $sql->bindParam(':num', $r, PDO::PARAM_STR);
        $sql->bindParam(':search', $searched, PDO::PARAM_STR);
        $sql->execute();
        $nu=$sql->rowCount();

        if($nu>0)
        {
            foreach ($sql->fetchAll() as $row)
            {
                $name= $row[0]." ".$row[1];
                $policy= $row[2];
                $claimN= $row[3];
                $savings= $row[4];
                $medical= $row[5];
                $date= $row[7];
                $entered= $row[9];
                $client_id=$row[8];
                $option= $row[6];
                $open= $row[10];
                $record_index=$row[11];
                $owner= $row[12];
                $discount_savings=$row[13];
                $scheme_number=$row[14];
                if($open==1)
                {
                    $date="<b style='color:red'>Open</b>";
                }
                $deletedRow=$record_index."q";
                echo"<tr id=\"$deletedRow\">"
                ?>


                <td><?php echo $name;?></td>
                <td><?php echo $policy;?></td>
                <td><?php
                    echo "<form action='case_detail.php' method='post' />";
                    echo "<input type=\"hidden\" name=\"claim_id\" value=\"$record_index\" />";
                    echo "<input type=\"submit\" class=\"linkbutton\" name=\"btn\" value=\"$claimN\">";
                    echo "</form>";
                    ?></td>
                <td><?php echo $scheme_number;?></td>
                <td><?php echo $medical;?></td>
                <td><?php echo $entered;?></td>
                <td><?php echo $date;?></td>

                <td class="alert-danger"><?php echo $savings;?></td>
                <td class="alert-info"><?php echo $discount_savings;?></td>
                <td class="alert-success"><?php echo $owner;?></td>
                <?php
                if ($_SESSION['level'] == "gap_cover") {

                    echo "<td><form action='add_documents.php' method='post' />";
                    echo "<input type=\"hidden\" name=\"claim_id\" value=\"$record_index\" />";
                    echo "<button type=\"submit\" class=\"btn btn-info\" name=\"btn\" value=\"\"><span class=\"glyphicon glyphicon-pencil\"></span></button>";
                    echo "</form></td>";
                }
                ?>

                </tr>
                <?php
            }
        }
        else
        {
            echo"<b style='color:red'>No match found</b>";
        }

        echo"</table>";

        // Call the Pagination Function to load Pagination.



        echo("<br>");


    }


    ?>


</body>
</html>

