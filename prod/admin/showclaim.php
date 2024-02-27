<?php
session_start();
define("access",true);
$title="Claim Dashboard";
require_once("top.php");
$db=new reportsClass();
if(!$db->isTopLevel()){
   header("Location: ../logout.php");
            die();
}
$_SESSION["admin_main"]=true;
$_SESSION['start_db']=true;
$holidays=$db->getHolidays();
$role=$db->myRole();
$username=$db->loggedAs();
$limit = 10;
$page = 1;
if (isset($_GET["page"])) {
  $page = (int)$_GET["page"];
} else {
  $page = 1;
};
$start_from = ($page - 1) * $limit;
$sql=$_GET['sql']. " LIMIT $start_from, $limit";
$sql1=$_GET['sql'];
$username=$_GET['name'];
$dat=$_GET['dat'];
$today = date('Y-m-d H:i:s');
echo $username."<hr>";
?>

  <link rel="stylesheet" href="../uikit/css/uikit.min.css" />
  <script src="../uikit/js/uikit.min.js"></script>
  <script src="../uikit/js/uikit-icons.min.js"></script>
<table class="table table-striped" id="example" width="100%">
  <thead>
  <tr>
    <th>Claim Number</th>
    <th>Username</th>
    <th>Date and Time</th>
    <th>SLA Started On</th>
    <th>Days</th>
    <th></th>
  </tr>
  </thead>
  <tbody>
  <?php
  try {
     $total_records=$db->showProdClaim1($sql1,$username,$dat);
     $p2=$db->showProdClaim2($sql,$username,$dat);
    $total_records1=count($p2);
    if($total_records1>0) {
      foreach ($p2 as $row) {
        $claim_id=$row[0];
        $from_date=$row[3];
        $pre_date=$row[6];
        $present="no";
        $p3=$db->showProdClaim3($claim_id,$pre_date);        
        if($p3==true)
        {
          $from_date=$p3["date_entered"];
          $present="yes";
        }
        $claim_number=$row[1];
        $open=(int)$row[2];
        $user=$row[4];
        $starx="";
        if($open==0){
          $starx="(Closed)";
        }
        $date_number=$db->getWorkingDays($from_date,$pre_date,$holidays);
        $star="";
        if($date_number<2){$star="<span style='color: darkseagreen'>*</span>";}
        elseif($date_number>=2 && $present=="no"){$star="<span style='color: purple'>*</span>";}
        elseif($date_number==2){$star="<span style='color: orange'>*</span>";}
        elseif($date_number>2){$star="<span style='color: red'>*</span>";}
        echo "<td>$claim_number $star $starx</td>";
        echo "<td>$user</td>";
        echo "<td>$pre_date</td>";
        echo "<td>$from_date</td>";
        echo "<td>$date_number</td>";
        echo "<td>";
        echo "<form action='../case_details.php' method='post' target='_blank' />";
        echo "<input type=\"hidden\" name=\"claim_id\" value=\"$claim_id\" />";
        echo "<input type=\"hidden\" name=\"sla\" value=\"\" />";
        echo "<input type=\"submit\" class=\"linkbutton\" name=\"btn\" value=\"View\">";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
      }
    }
    else{
      echo "<tr style='background-color: white'><td colspan='7' class='uk-text-light'>No records</td></tr>";
    }

  } catch (Exception $e) {
    return "There is an error.";
  }

  ?>
  </tbody>
</table>
<?php

$total_pages = ceil($total_records / $limit);
$pagLink = "<nav><ul class='pagination'>";
for ($i=1; $i<=$total_pages; $i++) {
  $pagLink .= "<li><a href='showclaim.php?sql=".$sql1."&name=".$username."&dat=".$dat."&page=".$i."'>".$i."</a></li>";
};
echo $pagLink . "</ul></nav>";
?>

</div>
<?php
require_once("footer.php");
?>

<script type="text/javascript">
  $(document).ready(function(){
    $('.pagination').pagination({
      items: <?php echo $total_records;?>,
      itemsOnPage: <?php echo $limit;?>,
      cssStyle: 'light-theme',
      currentPage : <?php echo $page;?>,
      hrefTextPrefix : 'showclaim.php?sql=<?php echo $sql1;?>&name=<?php echo $username;?>&dat=<?php echo $dat;?>&page='
    });
  });
</script>

