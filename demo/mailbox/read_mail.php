<?php

session_start();
$username=$_SESSION["user_id"];
$_SESSION['start_db'] = true;
if(isset($_POST["email_claim_id"]))
{
  $_SESSION["email_claim_id"] = (int)$_POST["email_claim_id"];
}
include_once ("email.php");
$conn=connection("mca","MCA_admin");
$obj=new email();
if(isset($_POST["subject"]))
{

    $obj->main_email_id=0;
    $title="Read mail";
    require_once ("mail_header.php");
    $subject=$_POST["subject"];
    $body="";
    $date=date("Y-m-d H:i:s");
    $claim_id=(int)$_SESSION["email_claim_id"];
  
    if(isset($_POST["compose"]))
    {
        $subto=$_POST["to"];
        $to="To : ".$subto;
        $email_fromx=$subto;
        $body=$_POST["body"];
        $source="Internal";
        $typm=$_POST["typm"];
        $claim_number=$obj->getMemberEmail($claim_id)[0][1];
        $exsub=" - ".$claim_id;
        $error=0;
        $message_id = $obj->getMessageId($claim_id,$subject);
        $practice_number = "";
        $practice_name = "";
        $claimed_id = "";
        if($typm=="Provider")
        {
            $claimdocArr = $obj->getDoctorByEmail($subto);
            if($claimdocArr)
            {
                $practice_number = $claimdocArr["practice_number"];
                $practice_name = $claimdocArr["name_initials"]." ".$claimdocArr["surname"];
                $claimed_id = $claimdocArr["claimedline_id"];
            }
        }
        else
        {
            $claimdocArr = $obj->getFirstDoctor($claim_id);
            if(count($claimdocArr)>0)
            {
                $practice_number = $claimdocArr[0]["practice_number"];
                $practice_name = $claimdocArr[0]["name_initials"]." ".$claimdocArr[0]["surname"];
                $claimed_id = $claimdocArr[0]["claimedline_id"];
            }
        }
        
        
        
        if(strlen($subject)>2)
        {            
            $subject=empty($message_id)?$subject.$exsub:$subject;
            $filearr=explode(";",$_POST["myfiles"]);

            $emaiarr=explode(";",$subto);
            for($i=0;$i<count($emaiarr);$i++)
            {
                $mmye=$emaiarr[$i];
                if (!filter_var($mmye, FILTER_VALIDATE_EMAIL)) {
                    $error++;
                }

            }
            //print_r($emaiarr);
            if($error<1)
            {
                $usearray=$obj->getUserEmail($username);
                $useremail = $usearray["email"];
                $fullname = $usearray["username"]." ".$usearray["surname"];
                if ($obj->sendMail($subto,$subject,$body,$filearr,$message_id,$fullname))
                {
                    $title="<div class=\"uk-alert-success\" uk-alert>
                    <a class=\"uk-alert-close\" uk-close></a>
                    <p>Message Successfully sent</p>
                </div>";
                    flush();
                    
                    ignore_user_abort(true); // Continue processing even if the user disconnects
    session_write_close(); // Avoid session blocking for the response
                    if($obj->insertEmail($subto,$useremail,$subject,$body,$source,$claim_id,"",$typm)==1)
                    {
                        $db_email_id=$obj->getEmailId($useremail);
                        $obj->main_email_id=$db_email_id;
                        for($i=1;$i<count($filearr);$i++)
                        {
                            $file=$filearr[$i];                            
                            $obj->moveFile($file,$claim_id,$db_email_id);
                        }
                        $body = $obj->cleanHtmlContent($body);
                            $obj->insertNotes($claim_id,$body,$username,"0000-00-00 00:00:00",0,0,$practice_number,$practice_name,$typm);
                            $obj->insertAPILog($claim_id, $practice_number, $body);
                        
         
                    }
                    else{
                        $title="<div class=\"uk-alert-danger\" uk-alert>
    <a class=\"uk-alert-close\" uk-close></a>
    <p>Failed. </p>
</div>";
                    }
                }
                else{
                    $title="<div class=\"uk-alert-danger\" uk-alert>
    <a class=\"uk-alert-close\" uk-close></a>
    <p>Failed to sent. </p>
</div>";
                }
            }
            else
            {
                $title="<div class=\"uk-alert-danger\" uk-alert><a class=\"uk-alert-close\" uk-close></a><p>Invalid email</p></div>";
            }

        }
        else{
            $title="<div class=\"uk-alert-danger\" uk-alert><a class=\"uk-alert-close\" uk-close></a><p>Invalid Subject.</p></div>";
        }



    }
    else if(isset($_POST["sentitems"]))
    {

        $email_id=(int)$_POST["mail_id"];
        $obj->main_email_id=$email_id;

        foreach ($obj->getEmailDetail($email_id) as $row)
        {

            $to="To : ".$row[0];
            $email_fromx=$row[0];
            $subject=$row[2];
            $body=$row[3];
            $date=$row[6];

        }

    }
    else if(isset($_POST["inbox"]))
    {
        $email_id=(int)$_POST["mail_id"];
        $obj->main_email_id=$email_id;
        $to="From : ".$_POST["email_from"];
        $email_fromx=$_POST["email_from"];
        $date="Open";
              
    }
    else{

        $email_id=(int)$_POST["mail_id"];
        $obj->main_email_id=$email_id;
        foreach ($obj->getEmailDetail($email_id) as $row)
        {
            $to="From : ".$row[0];
            $subject=$row[2];
            $body=$row[3];
            $date=$row[6];

        }
    }
   

?>
                    <div class="col-md-10">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title"><?php echo $title;?></h3>

                                <div class="card-tools">
                                    <a href="#" class="btn btn-tool" data-toggle="tooltip" title="Previous"><i class="fas fa-chevron-left"></i></a>
                                    <a href="#" class="btn btn-tool" data-toggle="tooltip" title="Next"><i class="fas fa-chevron-right"></i></a>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body p-0">
                                <div class="mailbox-read-info">
                                    <h5><b><?php echo $subject;?></b></h5>
                                    <h6><?php echo $to;?>
                                        <span class="mailbox-read-time float-right"><?php echo $date;?></span></h6>

                                </div>
                                <div class="card-footer">
                                <div class="float-right">
                                <form action='compose_email.php?sess=<?php echo $_SESSION["email_claim_id"]; ?>' method='post'>
               <input type="hidden" name="seltyp" value="<?php echo $subject;?>" />    
               <input type="hidden" name="coresp" value="<?php echo $email_fromx;?>" />   
               <input type="hidden" name="myclaim_id" value="<?php echo $_SESSION['email_claim_id']?>" />       
               <?php
               if(!empty($email_fromx))
               {
               ?>
               <button type="submit" class="btn btn-default" name="reply"><i class="fas fa-reply"></i> Reply</button>
               <?php
               }
               ?>
                </form>
                                   
                                </div>
                              
                            </div>
                                <!-- /.mailbox-controls -->
                                <div class="mailbox-read-message">
                                
                                    <?php
                                    if($claim_id == 0){
                                        $qarr = $obj->getSingelEmail($email_id);

                                    }
                                    else
                                    {
                                        $obj ->updateEmail($claim_id,$subject);
                                        $qarr = $obj->getEmailTrail($claim_id,$subject);
                                    }
                                
                                        foreach ($qarr as $row)
                                        {  
                                            $body=$row["body"]; 
                                            $email_source=$row["email_source"];
                                            $email_from=$row["email_from"];
                                            $email_id=(int)$row["id"];
                                            $message_id=$row["message_id"];
                                            $date_entered=$obj->formatDate($row["date_entered"]);  
                                            $body_class = $email_source=="Internal"?"note-from":"";                                       
                                            echo "<div class='note-box $body_class'><div class='note-content'>";
                                            $body = str_replace('<o:shapedefaults', '</style><o:shapedefaults', $body);
                                            if($email_source == "External")
                                            {
                                                echo "<form target='_blank' method='POST' action='../emails/read.php'><input type='hidden' name='message_id' value='$message_id'/><button type='submit' class='uk-button uk-button-secondary uk-button-small'>try</button></form>";
                                            }
                                            echo nl2br($body);
                                            foreach ($obj->getEmailDocuments($email_id) as $row)
                                            {
                                                $doc_description=$row[0];
                                                $doc_size=round((int)$row[1]/1024);
                                                $randomNum=$row[2];
                                                $id=(int)$row[3];
                                                $dd =rawurlencode($randomNum.$doc_description);
                                                $link=$encoded_url = "https://s3.us-east-1.wasabisys.com/mcafiles/" .$dd;

                                                echo "<form action='../view_file.php' method='post' target=\"_blank\"/>
    <input type=\"hidden\" name=\"my_doc\" value=\"$link\" /><input type=\"hidden\" name=\"my_id\" value=\"$id\" />";
                                                echo "<p><a href=\"$link\"><i class=\"fas fa-paperclip\"></i> 
                                                <button class=\"linkbutton\" name=\"doc\">$doc_description</button></a></p></form>";

    
                                            }
                                            echo "</div><div class='note-date'>From : $email_from ($date_entered)</div></div>";
                                        }                                   
                                    
                                    ?>
                                </div>
                                <!-- /.mailbox-read-message -->
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
<!-- AdminLTE for demo purposes -->
<script src="../../admin/admin_main/dist/js/demo.js"></script>
</body>
</html>
<?php
}
    ?>