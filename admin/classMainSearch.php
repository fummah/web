<?php

include_once "dbconn.php";
include_once "function.php";

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
    .dropbtn1 {
        background-color: red;
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
        if (confirm('Do you really want to delete this Case?')) {
            var display1=id+"x";
            document.getElementById(display1).style.display="block";
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {

                    var display2=id+"q";
                    var mess=this.responseText;
                    if(mess.indexOf("Deleted")>-1)
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
        } else {

            return false;
        }
    }
</script>
</head>

<body>

<?php
$r=$_SESSION['user_id'];
echo "<table class=\"table w3-panel w3-card w3-border-blue alert w3-hover-shadow\">";

function default1()
{

    ?>

    <tr style="color: black">
        <th>Name  and Surname</th>
        <th>Policy Number</th>
        <th>Claim Number</th>
        <th>Scheme Number</th>
        <th>Medical Scheme</th>
        <th>Date Entered</th>
        <th>Date Closed</th>
        <th>Client</th>
        <th>Files</th>
        <th>Scheme Savings</th>
        <th>Discount Savings</th>
        <th>Owner</th>
        <th></th>
        <th></th>
    </tr>
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
        $r = htmlspecialchars($clientNameID);
        $condition = "WHERE client_id = :num AND Open<>2";
        if($r==3)
        {
            $condition = "WHERE (client_id=15 OR client_id=27 OR client_id = :num)";
        }
        if($r==21)
        {
            $condition = "WHERE (client_id=26 OR client_id = :num)";
        }
        if($r==32)
        {
            $condition = "WHERE (client_id=26 OR client_id=21 OR client_id=:num)";
        }
        if($r==16)
        {
            $condition = "WHERE (client_id=27 OR client_id = :num)";
        }
    }
    //Search for any case...
    //
    //SELECT b.first_name, b.surname, b.policy_number, a.claim_number, a.savings_scheme, b.medical_scheme, b.scheme_option, a.date_closed, b.client_id, a.date_entered, a.Open, a.claim_id, a.username, a.savings_discount, b.scheme_number,a.sla FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id WHERE client_id = :num AND senderId<>10 AND (claim_type<>'R' OR claim_type is null) ORDER BY a.date_entered DESC LIMIT 0 , 10
    else if ($_SESSION['level'] == "admin" || $_SESSION['level'] == "controller")
    {
        $condition="WHERE 1";
    }
    else
    {
        die("Access Denied");
    }
    $ff="SELECT b.first_name, b.surname, b.policy_number, a.claim_number, a.savings_scheme, b.medical_scheme, b.scheme_option, a.date_closed, b.client_id, a.date_entered, 
a.Open, a.claim_id, a.username, a.savings_discount, b.scheme_number,a.sla FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id 
$condition AND (claim_type<>'R' OR claim_type is null) ORDER BY a.date_entered DESC LIMIT ".$pageLimit." , ".$setLimit;

    $sql = $conn->prepare($ff);
if ($_SESSION['level'] == "claims_specialist" || $_SESSION['level'] == "gap_cover")
{
    $sql->bindParam(':num', $r, PDO::PARAM_STR);
}
    
    $sql->execute();
    $nu=$sql->rowCount();
    if($nu>0)
    {

        foreach ($sql->fetchAll() as $row)
        {
            $name= htmlspecialchars(strtoupper($row[0]." ".$row[1]));
            $policy= htmlspecialchars(strtoupper($row[2]));
            $claimN= htmlspecialchars(strtoupper($row[3]));
            $savings= htmlspecialchars($row[4]);
            $medical= htmlspecialchars($row[5]);
            $date= htmlspecialchars($row[7]);
            $entered= htmlspecialchars($row[9]);
            $client_id=htmlspecialchars($row[8]);
            $option= htmlspecialchars($row[6]);
            $open= htmlspecialchars($row[10]);
            $record_index=htmlspecialchars($row[11]);
            $owner= htmlspecialchars($row[12]);
            $discount_savings=htmlspecialchars($row[13]);
            $scheme_number=htmlspecialchars($row[14]);
            $sla = (int)htmlspecialchars($row[15])==2?1:(int)htmlspecialchars($row[15]);
            $disabled="disabled";
            if($open==1)
            {
                $date="<b style='color:red'>Open</b>";
                $disabled="";
            }
            elseif ($open == 2)
            {
                $date = "<b style='color:orange'>On Hold</b>";
                $disabled = "";
            }
            elseif ($open == 4)
            {
                $date = "<b style='color:darkolivegreen'>Clinical Review</b>";
                $disabled = "";
            }
            elseif ($open == 5)
            {
                $date = "<b style='color:yellowgreen'>Pre-Assessment</b>";
                $disabled = "";
            }
            if($_SESSION['level'] == "admin" || $_SESSION['level'] == "controller")
            {
                $disabled="";
            }
            $deletedRow=$record_index."q";
            echo"<tr id=\"$deletedRow\" class='hig' style='color: dimgrey; font-size: 15px'>"
            ?>


            <td><?php echo $name;?></td>
            <td><?php echo $policy;
                echo "<br>($claimN)";
                ?></td>
            <td>
                <?php
                $path=(int)$client_id==31?"view_aspen.php":"case_detail.php";
                echo "<form action='$path' method='post' />";
                echo "<input type=\"hidden\" name=\"claim_id\" value=\"$record_index\" />";
                echo "<input type=\"hidden\" name=\"sla\" value=\"$sla\" />";
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
                    $dropbtn="dropbtn";
                    $sq = $connD->prepare('SELECT *FROM documents WHERE claim_id=:claim AND additional_doc=1');
                    $sq->bindParam(':claim', $record_index, PDO::PARAM_STR);
                    $sq->execute();
                    if($sq->rowCount()>0)
                    {
                        $dropbtn="dropbtn1";
                    }

                    echo "<div class=\"dropdown\">";
                    echo "<button class=\"$dropbtn\"><span class=\"glyphicon glyphicon-floppy-save\"></span> </button>";
                    echo "<div class=\"dropdown-content\">";
                    foreach ($sqlDoc->fetchAll() as $row1) {
                        $id = htmlspecialchars($row1[0]);
                        $ra=htmlspecialchars($row1[6]);
                        $nname = htmlspecialchars($row1[2]);
                        $desc = "../../mca/documents/" .$ra.$nname;
                        $dd=(int)$row1[9];
                        $ffj="";
                        if($dd==1)
                        {
                            $ffj="style='color: red'";
                        }
                        $size = round($row1[4] / 1024);
                        //echo "<a href='$desc' onclick=\"window.open('$desc','popup','width=800,height=600'); return false;\" title='Click to view'>$nname</a>";
                        echo "<form action='test5.php' method='post' target=\"print_popup\" onsubmit=\"window.open('edit_hospital.php','print_popup','width=1000,height=800');\"/><input type=\"hidden\" name=\"my_doc\" value=\"$desc\" />
<input type=\"hidden\" name=\"my_id\" value=\"$id\" />
<input type=\"submit\" class=\"linkbutton\" name=\"doc\" $ffj  value=\"$nname\">
</form>";
                    }
                    echo"</div>";
                    echo"</div>";
                }
                else
                {
                    echo "<b style='color:grey'>No File</b>";
                }


                ?>

            </td>
            <td class="alert-danger"><?php echo $savings;?></td>
            <td class="alert-info"><?php echo $discount_savings;?></td>
            <td class="alert-success"><?php echo $owner;?></td>

            <td>
                <?php
                if ($_SESSION['level'] == "admin" || $_SESSION['level'] == "claims_specialist" || $_SESSION['level'] == "controller") {
                    echo "<form action='edit_case.php' method='post' />";
                    echo "<input type=\"hidden\" name=\"claim_id\" value=\"$record_index\" />";
                    echo "<button type=\"submit\" class=\"btn btn-info\" name=\"btn\" value=\"\"  $disabled><span class=\"glyphicon glyphicon-pencil\" title='Edit case'></span></button>";
                    echo "</form>";
                }
                if ($_SESSION['level'] == "gap_cover") {
                    echo "<form action='add_documents.php' method='post' />";
                    echo "<input type=\"hidden\" name=\"claim_id\" value=\"$record_index\" />";
                    echo "<button type=\"submit\" class=\"btn btn-info\" name=\"btn\" value=\"\" ><span class=\"glyphicon glyphicon-pencil\"></span></button>";
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
    echo("<b>Results");
    // Call the Pagination Function to load Pagination.

    echo displayPaginationBelow($setLimit,$page);
    echo("<br>");
    echo("<b>Results : ".tot()."</b>");
}




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
    <th>Client</th>
    <th>Files</th>
    <th>Scheme Savings</th>
    <th>Discount Savings</th>
    <th>Owner</th>
    <?php
    $conn=connection("mca","MCA_admin");
    $r=$_SESSION["user_id"];

    $condition="";
    $r=$_SESSION['user_id'];
    if ($_SESSION['level'] == "claims_specialist") {
        $condition = "a.username = :num";
    }
    else if ($_SESSION['level'] == "gap_cover")
    {
        $dbh = connection("mca", "MCA_admin");
        $stmt = $dbh->prepare("SELECT client_id FROM clients WHERE client_name = :name");
        $stmt->bindParam(':name', $r, PDO::PARAM_STR);
        $stmt->execute();
        $clientNameID = $stmt->fetchColumn();
        $r = htmlspecialchars($clientNameID);
        $condition = "c.client_id = :num AND Open<>2";

        if($r==3)
        {
            $condition = "(c.client_id=15 OR c.client_id = :num)";
        }
        if($r==32)
        {
            $condition = "(c.client_id=26 OR c.client_id=21 OR c.client_id = :num)";

        }

    }
    else if ($_SESSION['level'] == "admin" || $_SESSION['level'] == "controller")
    {
        $condition="1";
    }
    else
    {
        die("Access Denied");
    }

    if(!empty($val)) {
        $searched = "%" . $val . "%";

        $sql = $conn->prepare("SELECT DISTINCT c.first_name,c.surname,c.policy_number,a.claim_number,a.savings_scheme,c.medical_scheme,c.scheme_option,a.date_closed,
c.client_id,a.date_entered,a.Open,a.claim_id,a.username,a.savings_discount,c.scheme_number,a.sla FROM claim as a INNER JOIN member as c ON a.member_id=c.member_id INNER JOIN clients as b 
ON c.client_id=b.client_id WHERE $condition AND (claim_type<>'R' OR claim_type is null) AND (c.first_name like :search OR c.surname like :search OR a.claim_number like :search OR a.claim_number1 like :search OR c.medical_scheme like :search OR 
c.policy_number like :search OR b.client_name like :search OR a.username like :search OR c.scheme_number like :search OR a.date_closed LIKE :search) ORDER BY a.date_entered DESC LIMIT 100"); $sql->bindParam(':num', $r, PDO::PARAM_STR);

        $sql->bindParam(':search', $searched, PDO::PARAM_STR);
        $sql->execute();
        $nu = $sql->rowCount();

        if ($nu > 0) {
            echo "<b style='color:green'>Total results found : <span style='color:purple'>" . $nu . "</span></b>";
            foreach ($sql->fetchAll() as $row) {
                $name = htmlspecialchars(strtoupper($row[0] . " " . $row[1]));
                $policy = htmlspecialchars(strtoupper($row[2]));
                $claimN = htmlspecialchars(strtoupper($row[3]));
                $savings = htmlspecialchars($row[4]);
                $medical = htmlspecialchars($row[5]);
                $date = htmlspecialchars($row[7]);
                $entered = htmlspecialchars($row[9]);
                $client_id = htmlspecialchars($row[8]);
                $option = htmlspecialchars($row[6]);
                $open = htmlspecialchars($row[10]);
                $record_index = htmlspecialchars($row[11]);
                $owner = htmlspecialchars($row[12]);
                $discount_savings = htmlspecialchars($row[13]);
                $scheme_number = htmlspecialchars($row[14]);
                $sla = (int)htmlspecialchars($row[15])==2?1:(int)htmlspecialchars($row[15]);
                $disabled = "disabled";

                if ($open == 1) {
                    $date = "<b style='color:red'>Open</b>";
                    $disabled = "";
                }
                elseif ($open == 2)
                {
                    $date = "<b style='color:orange'>On Hold</b>";
                    $disabled = "";
                }
                elseif ($open == 4)
                {
                    $date = "<b style='color:darkolivegreen'>Clinical Review</b>";
                    $disabled = "";
                }
                elseif ($open == 5)
                {
                    $date = "<b style='color:yellowgreen'>Pre-Assessment</b>";
                    $disabled = "";
                }
                if ($_SESSION['level'] == "admin" || $_SESSION['level'] == "controller") {
                    $disabled = "";
                }
                $deletedRow = $record_index . "q";
                echo "<tr id=\"$deletedRow\" class='hig'>"
                ?>


                <td><?php echo $name; ?></td>
                <td><?php echo $policy;
                    echo "<br>($claimN)";
                    ?></td>
                <td><?php
                    $path=(int)$client_id==31?"view_aspen.php":"case_detail.php";
                    echo "<form action='$path' method='post' />";
                    echo "<input type=\"hidden\" name=\"claim_id\" value=\"$record_index\" />";
                    echo "<input type=\"hidden\" name=\"sla\" value=\"$sla\" />";
                    echo "<input type=\"submit\" class=\"linkbutton\" name=\"btn\" value=\"$claimN\">";
                    echo "</form>";
                    ?></td>
                <td><?php echo $scheme_number; ?></td>
                <td><?php echo $medical; ?></td>
                <td><?php echo $entered; ?></td>
                <td><?php echo $date; ?></td>
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
                        $dropbtn="dropbtn";
                        $sq = $connD->prepare('SELECT *FROM documents WHERE claim_id=:claim AND additional_doc=1');
                        $sq->bindParam(':claim', $record_index, PDO::PARAM_STR);
                        $sq->execute();
                        if($sq->rowCount()>0)
                        {
                            $dropbtn="dropbtn1";
                        }
                        echo "<div class=\"dropdown\">";
                        echo "<button class=\"$dropbtn\"><span class=\"glyphicon glyphicon-floppy-save\"></span> </button>";
                        echo "<div class=\"dropdown-content\">";
                        foreach ($sqlDoc->fetchAll() as $row1) {
                            $id = htmlspecialchars($row1[0]);
                            $ra = htmlspecialchars($row1[6]);
                            $nname = htmlspecialchars($row1[2]);
                            $desc = "../../mca/documents/" . $ra . $nname;
                            $dd=(int)$row1[9];
                            $ffj="";
                            if($dd==1)
                            {
                                $ffj="style='color: red'";
                            }

                            $size = round($row1[4] / 1024);
                            //echo "<a href='$desc' onclick=\"window.open('$desc','popup','width=800,height=600'); return false;\" title='Click to view'>$nname</a>";
                            echo "<form action='test5.php' method='post' target=\"print_popup\" onsubmit=\"window.open('edit_hospital.php','print_popup','width=1000,height=800');\"/><input type=\"hidden\" name=\"my_doc\" value=\"$desc\" />
<input type=\"hidden\" name=\"my_id\" value=\"$id\" />
<input type=\"submit\" class=\"linkbutton\" $ffj name=\"doc\" value=\"$nname\">

</form>";
                        }
                        ////
                        ///     $id = htmlspecialchars($row1[0]);
                        //            $ra=htmlspecialchars($row1[6]);
                        //            $nname = htmlspecialchars($row1[2]);
                        //            $desc = "../../mca/test/" . $ra.$nname;
                        //            $type = htmlspecialchars($row1[3]);
                        //            $size = round($row1[4] / 1024);
                        //            $dd=(int)$row1[9];
                        //            $ffj="";
                        //            if($dd==1)
                        //            {
                        //                $ffj="style='color: red'";
                        //            }
                        ///
                        ///
                        ///
                        ///
                        ///
                        ///
                        ///
                        ///
                        echo "</div>";
                        echo "</div>";
                    } else {
                        echo "<b style='color:grey'>No File</b>";
                    }


                    ?>

                </td>
                <td class="alert-danger"><?php echo $savings; ?></td>
                <td class="alert-info"><?php echo $discount_savings; ?></td>
                <td class="alert-success"><?php echo $owner; ?></td>
                <td>
                    <?php
                    if ($_SESSION['level'] == "admin" || $_SESSION['level'] == "claims_specialist" || $_SESSION['level'] == "controller") {
                        echo "<form action='edit_case.php' method='post' />";
                        echo "<input type=\"hidden\" name=\"claim_id\" value=\"$record_index\" />";
                        echo "<button type=\"submit\" class=\"btn btn-info\" name=\"btn\" value=\"\"  $disabled><span class=\"glyphicon glyphicon-pencil\"></span></button>";
                        echo "</form>";
                    }
                    if ($_SESSION['user_id'] == "Western") {
                        echo "<form action='add_documents.php' method='post' />";
                        echo "<input type=\"hidden\" name=\"claim_id\" value=\"$record_index\" />";
                        echo "<button type=\"submit\" class=\"btn btn-info\" name=\"btn\" value=\"\"  ><span class=\"glyphicon glyphicon-pencil\"></span></button>";
                        echo "</form>";
                    }
                    ?>
                </td>
                <?php
                if ($_SESSION['level'] == "admin") {
                    echo "<td>";
                    $myID = "$record_index" . "x";
                    echo "<span style=\"color:purple;display: none\" id=\"$myID\">deleting...</span>";
                    echo "<span style=\"color:red;cursor: pointer\" title='Delete Case' id=\"$record_index\" class=\"glyphicon glyphicon-trash\" onclick=\"delete1('$record_index')\"></span>";
                    echo "</td>";
                }
                ?>
                </tr>
                <?php
            }
        } else {
            echo "<b style='color:red'>No match found</b>";
        }
    }
    else {
        echo "<b style='color:red'>Invalid input</b>";
    }
    echo"</table>";

    // Call the Pagination Function to load Pagination.



    echo("<br>");


}


function allDoctors()
{

    $conn=connection("doc","doctors");
    if(isset($_GET["page"]))
        $page = (int)$_GET["page"];
    else
        $page = 1;

    $setLimit = 10;
    $pageLimit = ($page * $setLimit) - $setLimit;

    $sql = $conn->prepare("SELECT *FROM details");
    $sql->execute();
    $nu=$sql->rowCount();
    if($nu>0)
    {
        foreach ($sql->fetchAll() as $row)
        {
            $name= htmlspecialchars($row[0]." ".$row[1]);
            $policy= htmlspecialchars($row[2]);
            $claimN= htmlspecialchars($row[3]);
            $savings= htmlspecialchars($row[4]);
            $medical= htmlspecialchars($row[5]);
            $date= htmlspecialchars($row[7]);
            $entered= htmlspecialchars($row[9]);
            $client_id=htmlspecialchars($row[8]);
            $option= htmlspecialchars($row[6]);

            ?>
            <tr>

                <td><?php echo $name;?></td>
                <td><?php echo $policy;?></td>
                <td><?php echo $claimN;?></td>
                <td><?php echo $medical;?></td>
                <td><?php echo $option;?></td>
                <td><?php echo $entered;?></td>
                <td><?php echo $date;?></td>
                <td><?php
                    echo getClientName($client_id);
                    ?></td>
                <td><?php echo $savings;?></td>
            </tr>
            <?php
        }
    }
    else
    {
        echo"<b style='color:red'>No Data</b>";
    }

    echo"</table>";

    // Call the Pagination Function to load Pagination.



    echo("<br>");
    echo("<b>Results : <span id='nn'></span></b>");
}

function getClientName($id)
{
    $dbh = connection("mca", "MCA_admin");
    $stmt = $dbh->prepare("SELECT client_name FROM clients WHERE client_id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_STR);
    $stmt->execute();
    $clientName = $stmt->fetchColumn();
    return $clientName;

}
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

