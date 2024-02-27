<?php
session_start();
define("access",true);
$title="Add System User";
require_once("top.php");
?>
<body class="crm_body_bg">
<?php
require_once("side.php");
$db=new reportsClass();
if(!$db->isTopLevel()){
   header("Location: ../logout.php");
            die();
}
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
      <div class="row">

        <!--/.col (left) -->
        <!-- right column -->
        <div class="col-md-12">
          <!-- general form elements disabled -->
          <div class="card card">
        
            <!-- /.card-header -->
            <div class="card-body">

              <div class="row">
                <div class="col-sm-6">
                  <!-- text input -->
                  <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="name" id="name" class="form-control">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Surname</label>
                    <input type="text" name="surname" id="surname" class="form-control">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <!-- text input -->
                  <div class="form-group">
                    <label>Email</label>
                    <input type="text" name="email" id="email" class="form-control">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" id="phone" class="form-control">
                  </div>
                </div>
              </div>
              <div class="row">

                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Role</label>
                    <select class="form-control" name="role" id="role">
                      <option value="claims_specialist">Claims Specialist</option>
                      <option value="gap_cover">Gap Cover</option>
                    </select>
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Client Name</label>
                    <select class="form-control" name="role" id="client_name">
                      <option value="MCA">MCA</option>
                      <?php

                      try {

                      foreach ($db->getAllClients() as $row)  {

                          ?>
                          <option value="<?php echo $row['client_name']; ?>"><?php echo $row['client_name']; ?></option>
                          <?php
                        }
                      }
                      catch (Exception $r)
                      {
                        echo "There is an error ".$r;
                      }
                      ?>
                    </select>
                  </div>
                </div>
              </div>


              <div class="row">
                <div class="col-sm-6">
                  <!-- text input --><br>
                  <div class="form-group">
                    <button id="myBtn" class="btn btn-primary">Create User</button>
                  </div>
                </div>
                <div class="col-sm-6"><br>
                  <div class="form-group">

                    <button class="btn btn-danger" id="myClear">Clear</button>
                  </div>
                </div>
              </div>
              <span id="load" style="color: red;display: none">Please wait...</span>
              <span id="details" style="color: red;font-weight: bolder"></span>


            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
          <!-- general form elements disabled -->

          <!-- /.card -->
        </div>
        <!--/.col (right) -->
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
<script src="./js/users.js"></script>
