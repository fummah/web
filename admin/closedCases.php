<?php
session_start();
include_once "dbconn.php";
include_once "function1.php";
if(!isset($_SESSION['logxged']))
{
    header("Location:login.html");

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
    .dropbtn {
        background-color:#0D3349;
        color: white;
        padding: 10px;
        font-size: 16px;
        border: none;
        cursor: pointer;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {background-color: #0d92e1}

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .dropdown:hover .dropbtn {
        background-color: red;
    }
    .hig:hover{
        background-color: #e8f6ff
    }
</style>
<script>
    function delete1(id)
    {
        var display1=id+"x";
        document.getElementById(display1).style.display="block";
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {

                var display2=id+"q";
                var mess=this.responseText;
                if(mess=="Deleted")
                {
                    document.getElementById(display1).innerHTML = this.responseText;
                    document.getElementById(display2).style.backgroundColor="pink";
                    document.getElementById(display1).style.color="Green";
                    document.getElementById(id).style.display="none";
                }
                else
                {
                    document.getElementById(display1).innerHTML = this.responseText;
                }

            }
        };
        xhttp.open("GET", "ajaxPhp/deleting.php?id="+id+"&identity=1", true);
        xhttp.send();
    }
</script>
</head>

<body>
<table class="table">

    <?php
    $r=$_SESSION['user_id'];

    error_reporting(0);
    function default1()
    {
        ?>
        <th>Name  and Surname</th>
        <th>Policy Number</th>
        <th>Claim Number</th>
        <th>Scheme Number</th>
        <th>Medical Scheme</th>
        <th>Date Entered</th>
        <th style="border: double; border-color: #00b3ee">Date Closed</th>
        <th>Client</th>
        <th>Files</th>
        <th>Scheme Savings</th>
        <th>Discount Savings</th>
        <th>Owner</th>
        <?php
        $conn=connection("mca","MCA_admin");
        if(isset($_GET["page"]))
            $page = (int)$_GET["page"];
        else
            $page = 1;

        $setLimit = 10;
        $pageLimit = ($page * $setLimit) - $setLimit;
        $r=$_SESSION['user_id'];
        $condition="";
        $r=$_SESSION['user_id'];
        if ($_SESSION['level'] == "claims_specialist") {
            $condition = "WHERE username = :num";
        } else if ($_SESSION['level'] == "gap_cover") {
            $dbh = connection("mca", "MCA_admin");
            $stmt = $dbh->prepare("SELECT client_id FROM clients WHERE client_name = :name");
            $stmt->bindParam(':name', $r, PDO::PARAM_STR);
            $stmt->execute();
            $clientNameID = $stmt->fetchColumn();
            $r = $clientNameID;
            $condition = "WHERE client_id = :num";
        } else if ($_SESSION['level'] == "patient") {
            $condition = "WHERE patient_name = :num";
        }
        else if($_SESSION['level'] == "admin" || $_SESSION['level'] == "controller")
        {
            $condition="WHERE 1";
        }
        else
        {
            die("There is an error");
        }
        $ff="SELECT member_name,member_surname,policy_number,claim_number,savings_scheme,medical_scheme,scheme_option,date_closed,client_id,date_entered,Open,claim_id,username,savings_discount,scheme_number FROM claim $condition AND Open=0 ORDER BY date_closed DESC LIMIT ".$pageLimit." , ".$setLimit;

        $sql = $conn->prepare($ff);
        $sql->bindParam(':num', $r, PDO::PARAM_STR);
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
                $disabled="disabled";
                if($open==1)
                {
                    $date="<b style='color:red'>Open</b>";
                    $disabled="";
                }
                if($_SESSION['level'] == "admin")
                {
                    $disabled="";
                }
                $deletedRow=$record_index."q";
                echo"<tr id=\"$deletedRow\" class='hig'>"
                ?>


                <td><span  data-toggle="tooltip" data-placement="top"><?php echo $name;?></span></td>
                <td><?php echo $policy;

                    echo "<br>($claimN)";
                    ?>
                </td>
                <td>
                    <?php
                    echo "<form action='case_detail.php' method='post' />";
                    echo "<input type=\"hidden\" name=\"claim_id\" value=\"$record_index\" />";
                    echo "<input type=\"submit\" class=\"linkbutton\" name=\"btn\" value=\"$claimN\">";
                    echo "</form>";

                    ?>
                </td>

                <td><?php echo $scheme_number;?></td>
                <td><?php echo $medical;?></td>

                <td><?php echo $entered;?></td>
                <td><?php echo $date;?></td>
                <td><?php
                    echo getClientName($client_id);
                    ?></td>
                <td>

                    <?php

                    $connD = connection("mca", "MCA_admin");
                    $sqlDoc = $connD->prepare('SELECT *FROM documents WHERE claim_id=:claim');
                    $sqlDoc->bindParam(':claim', $record_index, PDO::PARAM_STR);
                    $sqlDoc->execute();
                    $nu8 = $sqlDoc->rowCount();

                    if ($nu8 > 0) {
                        echo "<div class=\"dropdown\">";
                        echo "<button class=\"dropbtn\"><span class=\"glyphicon glyphicon-floppy-save\"></span> </button>";
                        echo "<div class=\"dropdown-content\">";
                        foreach ($sqlDoc->fetchAll() as $row1) {
                            $id = $row1[0];
                            $ra=$row1[6];
                            $nname = $row1[2];
                            $desc = "documents/" .$ra.$nname;
                            $type = $row1[3];

                            $size = round($row1[4] / 1024);
                            echo "<a href='$desc' onclick=\"window.open('$desc','popup','width=800,height=600'); return false;\" title='Click to view'>$nname</a>";
                        }
                        echo"</div>";
                        echo"</div>";
                    }
                    else
                    {
                        echo "<b style='color:rebeccapurple'>No File</b>";
                    }


                    ?>

                </td>
                <td class="alert-danger"><?php echo $savings;?></td>
                <td class="alert-info"><?php echo $discount_savings;?></td>
                <td class="alert-success"><?php echo $owner;?></td>

                <td>
                    <?php
                    if ($_SESSION['level'] == "admin" || $_SESSION['level'] == "claims_specialist") {
                        echo "<form action='edit_case.php' method='post' />";
                        echo "<input type=\"hidden\" name=\"claim_id\" value=\"$record_index\" />";
                        echo "<button type=\"submit\" class=\"btn btn-info\" name=\"btn\" value=\"\"  $disabled><span class=\"glyphicon glyphicon-pencil\"> Edit</span></button>";
                        echo "</form>";
                    }
                    ?>
                </td>
                <?php
                if($_SESSION['level']=="admin") {
                    echo "<td>";
                    $myID="$record_index"."x";
                    echo "<span style=\"color:purple;display: none\" id=\"$myID\">deleting...</span>";
                    echo "<span style=\"color:red;cursor: pointer\" title='Delete Case' id=\"$record_index\" class=\"glyphicon glyphicon-trash\" onclick=\"delete1('$record_index')\"></span>";
                    echo "</td>";
                }
                ?>
                </tr>
                <?php
            }
        }
        else
        {
            echo"No result found";
        }

        echo"</table>";

        // Call the Pagination Function to load Pagination.

        echo displayPaginationBelow($setLimit,$page);
        echo("<br>");
        echo("<b>Results : ".tot()."</b>");
    }
    default1();
    function getClientName($id)
    {
        $dbh = connection("mca", "MCA_admin");
        $stmt = $dbh->prepare("SELECT client_name FROM clients WHERE client_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        $clientName = $stmt->fetchColumn();
        return $clientName;

    }


    ////// Search withn date Function
    ///
    ///
    ///

    //////////////////////////////////////////////////////////////End
    ?>


    <script type="text/javascript">
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-38304687-1']);
        _gaq.push(['_trackPageview']);

        (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();

    </script>

    <!--********************************************

      For More Detail please Visit:

      http://www.discussdesk.com/download-pagination-in-php-and-mysql-with-example.htm

      ************************************************-->

</body>
</html>

