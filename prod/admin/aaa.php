<?php
session_start();
define("access",true);

$title="AAA";
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

$alltor=$db->getAAATotalAll();
$row=$db->getAAAFormDetails();
   $email=$row[0];
    $password=$row[1];
    $folder=$row[2];
    $imap=$row[4];
    $smtp=$row[3];
    $cc=$row[5];
 $notemail=$row[6];
    $notpass=$row[7];
?>
<style type="text/css">
    .btn-info{
        color:white !important;
    }
</style>
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

</div>

</div>
</div>
<div class="white_card_body QA_section">


<div class="row">
<div class="col-lg-4"><label>Email</label> <input type="text" name="email" id="email" value="<?php echo $email;?>" class="form-control form-control-sm b"></div>
<div class="col-lg-4"><label>Password</label> <input type="password" id="password" value="<?php echo $password;?>" class="form-control form-control-sm b"></div>
<div class="col-lg-4"><label>Copied Email Address </label> <input type="text" id="cc" value="<?php echo $cc;?>" class="form-control form-control-sm b"></div>
</div>
<div class="row">
<div class="col-lg-4"><label>Destination Folder </label> <input type="text" id="folder" value="<?php echo $folder;?>" class="form-control form-control-sm"></div>
<div class="col-lg-4"><label>Incoming Server</label> <input type="text" id="smtp" value="<?php echo $imap;?>" class="form-control form-control-sm v"></div>
<div class="col-lg-4"><label>Outgoing Server </label> <input type="text" id="imap" value="<?php echo $smtp;?>" class="form-control form-control-sm v"></div>
</div>
<div class="row">
<div class="col-lg-4"><label>Notification Email </label> <input type="text" id="notemail" value="<?php echo $notemail;?>" class="form-control form-control-sm b"></div>
<div class="col-lg-4"><label>Password </label> <input type="password" id="notpass" value="<?php echo $notpass;?>" class="form-control form-control-sm b"></div>
</div>
<div class="row">
    <div class="col-lg-4"><br>
<button type="button" id="saveChanges" class="btn btn-secondary">Save Changes</button>
<span id="load" style="color: red;display: none">Please wait...</span>
                <span id="details" style="color: red;font-weight: bolder"></span>
</div>
</div>
<hr>
<div class="main-title">
<h3 class="m-0">Number of Claims Processed Today</h3><br>
</div>
<div class="QA_table ">

<div id="DataTables_Table_1_wrapper" class="dataTables_wrapper no-footer">
<table class="table table-striped table-valign-middle">
              <thead>
              <tr>
                
                <?php
                $holder="x";
                echo "<th><span class='badge bg-danger'>$alltor</span></th>";
                $allclients=$db->allActiveClients();
                $allusers=$db->aaaUsers();
foreach ($allclients as $client) {
    $color=$client["color"];
    $client_name=$client["client_name"];
    $client_id=$client["client_id"];
    $tot=$db->getAAATotalPerClients($client_id);
   echo "<th style='color:$color' title='$client_name'>".$client["client_abrv"]." <span class='badge bg-info'>$tot</span></th>";
}
                ?>
               <th></th>
              </tr>
              </thead>
              <tbody>
                
<?php
foreach ($allusers as $user) {
    $username=$user["username"];
    $datetime=$user["datetime"];
    $id=$user["id"];    
    $tot=$db->getAAATotalPerUser($username);
    echo "<tr> <th title='$datetime'><span class='badge bg-info'>$tot</span> $username</th>";
    foreach ($allclients as $client) {
    $color=$client["color"];
    $client_name=$client["client_name"];
    $client_id=$client["client_id"];
    $count=$db->getAAAClaims($username,$client_id);
   echo "<td style='color:$color' title='$username - $client_name'>$count</td>";
}
echo "<td><button id='$id$holder' class='btn btn-info' onclick='action($id,1)'>Deactivate</button></td>";
echo "</tr>";
}
?>
 <th></th>
              </tbody>
                  <tfoot>
              <tr></tr>
            
              </tfoot>
          </table>
          <h3 class="m-0">Deactivated Users</h3><br>
          <table class="table table-striped table-valign-middle">
              <thead>
              <tr>
                <th></th>
                <?php
                $allclients=$db->allActiveClients();
                $allusers=$db->aaaUsers(0);
foreach ($allclients as $client) {
    $color=$client["color"];
    $client_name=$client["client_name"];
   echo "<th style='color:$color' title='$client_name'>".$client["client_abrv"]."</th>";
}
                ?>
               <th></th>
              </tr>
              </thead>
              <tbody>
                
<?php
foreach ($allusers as $user) {
    $username=$user["username"];
    $datetime=$user["datetime"];
    $id=$user["id"];
    echo "<tr> <th title='$datetime'>$username</th>";
    foreach ($allclients as $client) {
    $color=$client["color"];
    $client_name=$client["client_name"];
    $client_id=$client["client_id"];
    $count=$db->getAAAClaims($username,$client_id);
   echo "<td style='color:$color' title='$username - $client_name'>$count</td>";
}
echo "<td><button id='$id$holder' class='btn btn-danger' onclick='action($id,0)'>Activate</button></td>";
echo "</tr>";
}
?>
 <th></th>
              </tbody>
                  <tfoot>
              <tr></tr>
            
              </tfoot>
          </table>
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
<?php
require_once("footer.php");
?>
<script src="js/users.js"></script>