<?php
session_start();
$username=$_SESSION["user_id"];
$_SESSION['start_db'] = true;
$title="Inbox";

require_once ("mail_header.php");
include_once ("email.php");
$conn = connection("mca", "MCA_admin");
$obj=new email();
?>
                <div class="col-md-10">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo $title;?></h3>

                            <div class="card-tools">
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" placeholder="Search Mail">
                                    <div class="input-group-append">
                                        <div class="btn btn-primary">
                                            <i class="fas fa-search"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-tools -->
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <div class="mailbox-controls">
                                <!-- Check all button -->
                                <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
                                </button>
                              
                                <!-- /.float-right -->
                            </div>
                            <div class="table-responsive mailbox-messages">
                                <table class="table table-hover table-striped">
                                    <tbody>
                                    <?php
                                    $source="External";
                                    $claim_id=$_SESSION['email_claim_id'];
                                    $arr=$obj->getInbox($source,$claim_id);
                                    $count=count($arr);
                                    If($count>0) {
                                        foreach ($arr as $row) {
                                            $email_to=$row["email_to"];
                                            $email_from=$row["email_from"];
                                            $subject=$row["subject"];
                                            $id=$row["id"];
                                            $status=(int)$row["status"];
                                            $sb_class = $status==1?"subject":"";
                                            $date=$row["date_entered"];
                                            $ttx=$obj->checkFiles($id)?"<i class=\"fas fa-paperclip\"></i>":"";
                                            echo "<td>
                                        <td>
                                            <div class=\"icheck-primary\">
                                                <input type=\"checkbox\" value=\"\" id=\"check1\">
                                                <label for=\"check1\"></label>
                                            </div>
                                        </td>
                                        <td> <form action='read_mail.php' method='post'>
               <input type=\"hidden\" name=\"mail_id\" value=\"$id\" />
               <input type=\"hidden\" name=\"subject\" value=\"$subject\" />    
               <input type=\"hidden\" name=\"email_from\" value=\"$email_from\" />          
               <input type=\"submit\" class=\"linkbutton\" name=\"inbox\" value=\"$email_from\">
                </form></td>
                                        
                                 
                                        <td class=\"$sb_class\">$subject</td>
                                        <td class=\"mailbox-attachment\"></td>
                                        <td class=\"mailbox-date\">$date</td>
                                        <td class=\"\">$ttx</td>
                                    </tr>";
                                        }
                                    }
                                    else{
                                        echo "<p align='center'>No emails</p>";
                                    }
                                    ?>
                                    </tbody>
                                </table>
                                <!-- /.table -->
                            </div>
                            <!-- /.mail-box-messages -->
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer p-0">
                            <div class="mailbox-controls">
                                <!-- Check all button -->
                                <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
                                </button>
                             
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->


    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../../admin/admin_main/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../admin/admin_main/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../admin/admin_main/dist/js/adminlte.min.js"></script>
<!-- Page Script -->
<script>
    $(function () {
        //Enable check and uncheck all functionality
        $('.checkbox-toggle').click(function () {
            var clicks = $(this).data('clicks')
            if (clicks) {
                //Uncheck all checkboxes
                $('.mailbox-messages input[type=\'checkbox\']').prop('checked', false)
                $('.checkbox-toggle .far.fa-check-square').removeClass('fa-check-square').addClass('fa-square')
            } else {
                //Check all checkboxes
                $('.mailbox-messages input[type=\'checkbox\']').prop('checked', true)
                $('.checkbox-toggle .far.fa-square').removeClass('fa-square').addClass('fa-check-square')
            }
            $(this).data('clicks', !clicks)
        })

        //Handle starring for glyphicon and font awesome
        $('.mailbox-star').click(function (e) {
            e.preventDefault()
            //detect type
            var $this = $(this).find('a > i')
            var glyph = $this.hasClass('glyphicon')
            var fa    = $this.hasClass('fa')

            //Switch states
            if (glyph) {
                $this.toggleClass('glyphicon-star')
                $this.toggleClass('glyphicon-star-empty')
            }

            if (fa) {
                $this.toggleClass('fa-star')
                $this.toggleClass('fa-star-o')
            }
        })
    })
</script>
<!-- AdminLTE for demo purposes -->
<script src="../../admin/admin_main/dist/js/demo.js"></script>
</body>
</html>
