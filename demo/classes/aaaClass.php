<?php

error_reporting(0);
require_once('../admin/validateAdmin.php');

include_once "../dbconn1.php";
function aaaUsers()
{
    $tt=getCase(5)+getCase(16)+getCase(3)+getCase(9)+getCase(21)+getCase3()+getCase(1)+getCase(15);
    echo "<table cellspacing=\"0\" width=\"100%\">";
    echo "<thead style='background-color: #0f0f0f;font-weight: bolder;color: white'>";
    echo "<tr align='center'>";
    echo "<th>";
    echo "Username ";
    echo "</th>";
    echo "<th>";
    echo "<span class=\"badge btn-info\">$tt</span>";
    echo "</th>";
    echo "<th>";
    echo "<b style=\"color: #0d92e1\">WEST </b><span class=\"badge\" >". getCase(16)."</span>";
    echo "</th>";
    echo "<th>";
    echo "<b style=\"color: #3C510C\">MED </b><span class=\"badge\">". getCase(5)."</span>";
    echo "</th>";
    echo "<th>";
    echo "<b style=\"color: #3f51b5\">KLX </b><span class=\"badge\">". getCase(3)."</span>";
    echo "</th>";
    echo "<th>";
    echo "<b style=\"color: #b21f2d\">SAN</b><span class=\"badge\">". getCase(15)."</span>";
    echo "</th>";

    echo "<th>";
    echo "<b style=\"color: #5a4304\">TBR </b><span class=\"badge\">". getCase(9)."</span>";
    echo "</th>";
    echo "<th>";
    echo "<b style=\"color: blanchedalmond\">INSM </b><span class=\"badge\">". getCase(21)."</span>";
    echo "</th>";
    echo "<th>";
    echo "<b style=\"color: green\">ADM </b><span class=\"badge\">". getCase3()."</span>";
    echo "</th>";
    echo "<th>";
    echo "<b style=\"color: yellow\">ZEST </b><span class=\"badge\">". getCase(1)."</span>";
    echo "</th>";
    echo "<th>";
    echo "Email";
    echo "</th>";
    echo "<th>";
    echo "Last Date Assigned";
    echo "</th>";
    echo "<th>";
    echo "Status";
    echo "</th>";
    echo "</tr>";
    echo "</thead>";

    try {
        $conn = connection("mca", "MCA_admin");
        if ($_SESSION['level'] == "admin") {
            $sql = $conn->prepare('SELECT username,email,status,datetime,id FROM users_information WHERE status=1 ORDER BY datetime DESC');
            $sql->execute();
            $nu = $sql->rowCount();
            if ($nu > 0) {
                foreach ($sql->fetchAll() as $row) {
                    $st = "Deactivate";
                    $xxx = "green";
                    $t = 1;
                    if ($row[2] == 0) {
                        $st = "Activate";
                        $xxx = "pink";
                        $t = 0;
                    }
                    $id = $row[0];

                    $u = $row['4'];
                    $ux = $row[4] . "x";
                    $uy = $row[4] . "y";
                    $tto = getCaseAndUser(16, $id) + getCaseAndUser(5, $id) + getCaseAndUser(3, $id)+getCaseAndUser(15, $id) + getCaseAndUser(9, $id)+ getCaseAndUser(21, $id) + getCaseAndUser3($id)+ getCaseAndUser(1, $id);

                    echo "<tr id='mm'>";
                    echo "<td>";
                    echo $row[0];
                    echo "</td>";
                    echo "<td>";
                    echo "<span class=\"badge btn-info\">" . $tto . "</span>";
                    echo "</td>";
                    echo "<td>";
                    echo " <span class=\"badge\">". getCaseAndUser(16, $id)."</span>";
                    echo "</td>";
                    echo "<td>";
                    echo "<span class=\"badge\">". getCaseAndUser(5, $id)."</span>";
                    echo "</td>";
                    echo "<td>";
                    echo "<span class=\"badge\">". getCaseAndUser(3, $id)."</span>";
                    echo "</td>";
    echo "<td>";
                    echo "<span class=\"badge\">". getCaseAndUser(15, $id)."</span>";
                    echo "</td>";
                    echo "<td>";
                    echo "<span class=\"badge\">". getCaseAndUser(9, $id)."</span>";
                    echo "</td>";
                    echo "<td>";
                    echo "<span class=\"badge\">". getCaseAndUser(21, $id)."</span>";
                    echo "</td>";
                    echo "<td>";
                    echo "<span class=\"badge\">". getCaseAndUser3($id)."</span>";
                    echo "</td>";
                    echo "<td>";
                    echo "<span class=\"badge\">". getCaseAndUser(1, $id)."</span>";
                    echo "</td>";
                    echo "<td>";
                    echo $row[1];
                    echo "</td>";
                    echo "<td>";
                    echo $row[3];
                    echo "</td>";
                    echo "<td style='background-color: $xxx' id='$uy'>";
                    echo "<button id='$u' class='btn btn-info' onclick='action1($u,$t)'>$st</button>";
                    echo "<span id='$ux' style='display: none;color: red'>wait...</span>";
                    echo "</td>";
                    echo "</tr>";
                }
            }
        }
        else{
            echo "There is an error";
        }
    } catch (Exception $re) {
        echo "There is an error";
    }

}
function aaaUsers1()
{
    $tt=getCase(5)+getCase(16)+getCase(3)+getCase(15)+getCase(9)+getCase(21)+getCase3()+getCase(1);
    echo "<table cellspacing=\"0\" width=\"100%\">";

    echo "<thead style='background-color: red;font-weight: bolder;color: white'>";
    echo "<tr align='center'>";
    echo "<th>";
    echo "Username ";
    echo "</th>";
    echo "<th>";
    echo "<span class=\"badge btn-info\">--</span>";
    echo "</th>";
    echo "<th>";
    echo "<b style=\"color: #0d92e1\">WEST </b><span class=\"badge\" >--</span>";
    echo "</th>";
    echo "<th>";
    echo "<b style=\"color: #3C510C\">MED </b><span class=\"badge\">--</span>";
    echo "</th>";
    echo "<th>";
    echo "<b style=\"color: #3f51b5\">KLX </b><span class=\"badge\">--</span>";
    echo "</th>";
   echo "<th>";
    echo "<b style=\"color: #b21f2d\">SAN </b><span class=\"badge\">--</span>";
    echo "</th>";
    echo "<th>";
    echo "<b style=\"color: #5a4304\">TBR </b><span class=\"badge\">--</span>";
    echo "</th>";
    echo "<th>";
    echo "<b style=\"color:blanchedalmond\">INSM </b><span class=\"badge\">--</span>";
    echo "</th>";
    echo "<th>";
    echo "<b style=\"color: green\">ADM </b><span class=\"badge\">--</span>";
    echo "</th>";
    echo "<th>";
    echo "<b style=\"color: yellow\">ZEST </b><span class=\"badge\">--</span>";
    echo "</th>";
    echo "<th>";
    echo "Email";
    echo "</th>";
    echo "<th>";
    echo "Last Date Assigned";
    echo "</th>";
    echo "<th>";
    echo "Status";
    echo "</th>";
    echo "</tr>";
    echo "</thead>";
    try {
        $conn = connection("mca", "MCA_admin");
        if ($_SESSION['level'] == "admin") {
            $sql = $conn->prepare('SELECT username,email,status,datetime,id FROM users_information WHERE status=0 ORDER BY datetime DESC');
            $sql->execute();
            $nu = $sql->rowCount();
            if ($nu > 0) {
                foreach ($sql->fetchAll() as $row) {
                    $st="Deactivate";
                    $xxx="green";
                    $t=1;
                    if($row[2]==0){
                        $st="Activate";
                        $xxx="pink";
                        $t=0;
                    }
                    $idvv = $row[0];
                    $id=$row[4];
                    $idx=$row[4]."x";
                    $idy=$row[4]."y";
                    $tto = getCaseAndUser(16,  $idvv) + getCaseAndUser(5,  $idvv) + getCaseAndUser(3,  $idvv) + getCaseAndUser(15,  $idvv) + getCaseAndUser(9,  $idvv)+ getCaseAndUser(21,  $idvv) + getCaseAndUser3( $idvv);

                    echo "<tr id='mm'>";
                    echo "<td>";
                    echo $row[0];
                    echo "</td>";
                    echo "<td>";
                    echo "<span class=\"badge btn-info\">" . $tto . "</span>";
                    echo "</td>";
                    echo "<td>";
                    echo " <span class=\"badge\">". getCaseAndUser(16,  $idvv)."</span>";
                    echo "</td>";
                    echo "<td>";
                    echo "<span class=\"badge\">". getCaseAndUser(5,  $idvv)."</span>";
                    echo "</td>";
                    echo "<td>";
                    echo "<span class=\"badge\">". getCaseAndUser(3,  $idvv)."</span>";
                    echo "</td>";
   echo "<td>";
                    echo "<span class=\"badge\">". getCaseAndUser(15,  $idvv)."</span>";
                    echo "</td>";
                    echo "<td>";
                    echo "<span class=\"badge\">". getCaseAndUser(9,  $idvv)."</span>";
                    echo "</td>";
                    echo "<td>";
                    echo "<span class=\"badge\">". getCaseAndUser(21,  $idvv)."</span>";
                    echo "</td>";
                    echo "<td>";
                    echo "<span class=\"badge\">". getCaseAndUser3( $idvv)."</span>";
                    echo "</td>";
                    echo "<td>";
                    echo "<span class=\"badge\">". getCaseAndUser(1, $idvv)."</span>";
                    echo "</td>";
                    echo "<td>";
                    echo $row[1];
                    echo "</td>";
                    echo "<td>";
                    echo $row[3];
                    echo "</td>";
                    echo "<td style='background-color: $xxx' id='$idy'>";
                    echo"<button id='$id' class='btn btn-info' onclick='action1(\"$id\",\"$t\")'>$st</button>";
                    echo "<span id='$idx' style='display: none;color: red'>wait...</span>";
                    echo "</td>";
                    echo "</tr>";
                }
            }
        }
        else{
            echo "There is an error";
        }
    } catch (Exception $re) {
        echo "There is an error";
    }

}


function duplicates()
{


    try {
        $aaa="aaa";
        $conn = connection("mca", "MCA_admin");
        $sql = $conn->prepare('SELECT date,description FROM logs WHERE owner=\'aaa\'ORDER BY date DESC LIMIT 15');
        $sql->execute();
        $nu = $sql->rowCount();

        if ($nu > 0) {
            echo "<table width='40%'>";
            echo "<tr style='font-weight: bolder;background-color: black;color: white'>";
            echo "<td>";
            echo "Date and Time";
            echo "</td>";
            echo "<td>";
            echo "Claim Number";
            echo "</td>";
            echo "</tr>";
            foreach ($sql->fetchAll() as $row) {

                echo "<tr>";
                echo "<td>";
                echo $row[0];
                echo "</td>";
                echo "<td>";
                echo $row[1];
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        else{
            echo "<h5 align='center' style='color: red'>No duplicates detected</h5>";
        }

    } catch (Exception $re)
    {
        echo "There is an error.";
    }

}

function getCase($id)
{
    $dd=date('Y-m-d');
    $conn = connection("mca", "MCA_admin");
    $sql = $conn->prepare('SELECT COUNT(a.claim_id) as cc FROM claim as a inner join member as b on a.member_id=b.member_id WHERE b.client_id=:id AND a.date_entered>:dd AND recordType IS NULL');
    $sql->bindParam(':id', $id, PDO::PARAM_STR);
    $sql->bindParam(':dd', $dd, PDO::PARAM_STR);
    $sql->execute();
    return $sql->fetchColumn();
}
function getCase3()
{

    $dd=date('Y-m-d');
    $date = DateTime::createFromFormat('Y-m-d',$dd);
    $date->modify('-1 day');
    $tt=$date->format('Y-m-d');

    $searched= "%".$tt." 21%";
    $searched1= "%".$dd."%";
    $conn = connection("mca", "MCA_admin");
    $sql = $conn->prepare('SELECT COUNT(a.claim_id) as cc FROM claim as a inner join member as b on a.member_id=b.member_id WHERE b.client_id=6 AND (a.date_entered like :dd OR a.date_entered like :dd1)');

    $sql->bindParam(':dd', $searched, PDO::PARAM_STR);
    $sql->bindParam(':dd1', $searched1, PDO::PARAM_STR);
    $sql->execute();
    return $sql->fetchColumn();
}
function getCaseAndUser($id,$user)
{
    $dd=date('Y-m-d');
    $conn = connection("mca", "MCA_admin");
    $sql = $conn->prepare('SELECT COUNT(a.claim_id) as cc FROM claim as a inner join member as b on a.member_id=b.member_id WHERE a.username=:username AND b.client_id=:id AND a.date_entered>:dd AND recordType IS NULL');
    $sql->bindParam(':id', $id, PDO::PARAM_STR);
    $sql->bindParam(':dd', $dd, PDO::PARAM_STR);
    $sql->bindParam(':username', $user, PDO::PARAM_STR);
    $sql->execute();
    return $sql->fetchColumn();
}

function getCaseAndUser3($user)
{
    $dd=date('Y-m-d');
    $date = DateTime::createFromFormat('Y-m-d',$dd);
    $date->modify('-1 day');
    $tt=$date->format('Y-m-d');

    $searched= "%".$tt." 21%";
    $searched1= "%".$dd."%";
    $conn = connection("mca", "MCA_admin");
    $sql = $conn->prepare('SELECT COUNT(a.claim_id) as cc FROM claim as a inner join member as b on a.member_id=b.member_id WHERE a.username=:username AND b.client_id=6 AND (a.date_entered like :dd OR a.date_entered like :dd1)');
    $sql->bindParam(':dd', $searched, PDO::PARAM_STR);
    $sql->bindParam(':username', $user, PDO::PARAM_STR);
    $sql->bindParam(':dd1', $searched1, PDO::PARAM_STR);
    $sql->execute();
    return $sql->fetchColumn();
}

?>

