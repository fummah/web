<?php

//require_once('validateAdmin.php');

include_once "../dbconn1.php";
function usersView()
{
  echo "<table id=\"example\" class=\"table table-striped \" cellspacing=\"0\" width=\"100%\">";
  echo "<thead>";
  echo "<tr align='center'>";
  echo "<th>";
  echo " User ID ";
  echo "</th>";
  echo "<th>";
  echo "Full Name";
  echo "</th>";
  echo "<th>";
  echo "Username";
  echo "</th>";
  echo "<th>";
  echo "Email";
  echo "</th>";
  echo "<th>";
  echo "Phone";
  echo "</th>";
  echo "<th>";
  echo "Role";
  echo "</th>";
  echo "<th>";
  echo "Status";
  echo "</th>";
  echo "<th>";
  echo "Password";
  echo "</th>";
  echo "</tr>";
  echo "</thead>";

  echo "<tfoot>";
  echo "<tr align='center'>";
  echo "<th>";
  echo " User ID ";
  echo "</th>";
  echo "<th>";
  echo "Full Name";
  echo "</th>";
  echo "<th>";
  echo "Username";
  echo "</th>";
  echo "<th>";
  echo "Email";
  echo "</th>";
  echo "<th>";
  echo "Phone";
  echo "</th>";
  echo "<th>";
  echo "Role";
  echo "</th>";
  echo "<th>";
  echo "Status";
  echo "</th>";
  echo "<th>";
  echo "Password";
  echo "</th>";
  echo "</tr>";
  echo "</tfoot>";
  try {
    $conn = connection("doc", "doctors");
    $sql = $conn->prepare('SELECT user_id,username,role,state,email,phone,fullName FROM staff_users');
    $sql->execute();
    $nu = $sql->rowCount();
    if ($nu > 0) {
      foreach ($sql->fetchAll() as $row) {
        $st="Deactivate";
        $xxx="green";
        $t=1;
        if($row[3]==0){
          $st="Activate";
          $xxx="pink";
          $t=0;
        }
        $id=$row[0];
        $idx=$row[0]."x";
        $idy=$row[0]."y";
        echo "<tr>";
        echo "<td>";
        echo $row[0];
        echo "</td>";
        echo "<td>";
        echo $row[6];
        echo "</td>";
        echo "<td>";
        echo $row[1];
        echo "</td>";
        echo "<td>";
        echo $row[4];
        echo "</td>";
        echo "<td>";
        echo $row[5];
        echo "</td>";
        echo "<td>";
        echo $row[2];
        echo "</td>";
        echo "<td style='background-color: $xxx' id='$idy'>";
        echo"<button id='$id' class='btn btn-info' onclick='action($id,$t)'>$st</button>";
        echo "<span id='$idx' style='display: none;color: red'>wait...</span>";
        echo "</td>";
        echo "<td>";
        echo"<button class='btn btn-warning' onclick='pass($row[0])'>Change</button>";
        echo "</td>";
        echo "</tr>";
      }
    }
  } catch (Exception $re) {
    echo "There is an error";
  }
}
?>

