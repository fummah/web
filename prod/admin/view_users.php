<?php
session_start();
define("access",true);
$title="View System Users";
require_once("top.php");
$db=new reportsClass();
if(!$db->isTopLevel()){
   header("Location: ../logout.php");
            die();
}
?>
<body class="crm_body_bg">
<?php
require_once("side.php");
?>

<section class="main_content dashboard_part large_header_bg">
<?php
require_once("top_nav.php");
?>

<div class="main_content_iner overly_inner ">
<div class="container-fluid p-0 ">

<div class="row">
<div class="col-4">
<div class="page_title_box d-flex align-items-center justify-content-between">
<div class="page_title_left">
<h3 class="f_s_30 f_w_700 text_white"><?php echo $title;?></h3>

</div>

</div>
</div>


</div>
<div class="col-lg-12">
<div class="white_card card_height_100 mb_20 ">
<div class="white_card_header">
<div class="box_header m-0">
<div class="main-title">
<h3 class="m-0"><?php echo $title;?></h3>
</div>
</div>
</div>
<div class="white_card_body QA_section" style="padding: 1px 1px 1px !important">
<div class="QA_table ">
<div id="DataTables_Table_1_wrapper" class="dataTables_wrapper no-footer">
<?php
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

  try { 
      foreach ($db->getSystemUsers() as $row) {
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
        echo"<button id='$id' class='btn btn-info' onclick='deactivate($id,$t)'>$st</button>";
        echo "<span id='$idx' style='display: none;color: red'>wait...</span>";
        echo "</td>";
        echo "<td>";
        echo"<button class='btn btn-warning' onclick='pass($row[0])'>Change</button>";
        echo "</td>";
        echo "</tr>";
      } 

  } catch (Exception $re) {
    echo "There is an error";
  }
  echo "</table>"; 
?>
</div>
</div>
</div>
</div>
</div>

</div>
</div>
</div>
</div>

</div>
</div>
</div>
<div id="xsx" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>


      </div>
      <div class="modal-body">
        <h4 align="center" style="color: #0d92e1">ID : <span id="myName"></span></h4>
        <input type="hidden" value="" id="pp">
        <b>Password</b>
        <input type="password" id="password" name="password" class="form-control">
        <b>Confirm Password</b>
        <input type="password" id="passwordC" name="passwordC" class="form-control">
        <p align="center" style="display: none; color:red; font-weight: bolder;" id="modShow">Please wait...</p>

        <div class="modal-footer">
          <p align="center"><button class="btn btn-warning" onclick="submitPass()">Change Password</button><button class="btn btn-danger" onclick="reset()">Reset</button></p>

        </div>
      </div>

    </div>
  </div>
</div>

<?php
require_once("footer.php");
?>
<script src="./js/users.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#example').DataTable();
  } );
</script>