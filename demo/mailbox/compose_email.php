
<?php
session_start();

$username=$_SESSION["user_id"];
$_SESSION['start_db'] = true;
include_once ("email.php");
$conn=connection("mca","MCA_admin");

$obj=new email();
$email="";
$subject="";
$typm="";
$practice_number="";
$template = "";
$disableR="";


if(isset($_POST["myclaim_id"]) || isset($_POST["claim_id_t"]))
{
  
    $_SESSION["email_claim_id"]=isset($_POST["myclaim_id"])?(int)$_POST["myclaim_id"]:$_POST["claim_id_t"];
  
    $coresp=isset($_POST["coresp"])?explode('|',$_POST["coresp"]):explode('|',$_POST["coresp_t"]);
    $temparr = explode('|',$_POST["template_id"]);
    $template_arr=$obj->getEmailTemplate((int)$temparr[1]);    
    $template = $template_arr[0];
    $tags = $template_arr[1];
    $disableR = $template_arr[2];
    $template_name = $template_arr[3];
    $template_client = $template_arr[4];
    $subject = $template_arr[5];

    foreach($_POST as $key => $value){     
        if (is_array($value)) {
            continue;
        }   
        $subject = str_replace('{'.$key.'}', $value,  $subject);
    }
    
    foreach($_POST as $key => $value){
        if (is_array($value)) {
            $value = implode("\n", $value);
        } 
        $value = "<b>".ucfirst($value)."</b>";
        $template = str_replace('{'.$key.'}', $value,  $template);
    }

    if(isset($_POST["fdr"])){
        $fdr = json_decode($_POST["fdr"],true);
        $doctorsd = "";
        $fpractice_name = "";
        $fservice_date = "";
        foreach($fdr as $f)
        {
            $fday = explode("-", $f["practice"]);
            $fpractice_number = $fday[0];
            $fpractice_name = "<b>".$fday[1]."</b>";            
            $fservice_date = "<b>".$f["service_date"]."</b>";
            $doctorsd .= "<br/>- Provider : <b>$fpractice_name</b><br/>";
            $doctorsd .= "- Practice : <b>$fpractice_number</b><br/>";
            if($template_name != "Pre-Auth Request (In-Hospital)")
            {
                $doctorsd .= isset($_POST["patient_name"])?"- Patient : <b>".$_POST["patient_name"]."</b><br/>":"";
                $doctorsd .= "- Service date : <b>$fservice_date</b><br/>";
            }  
            if($template_name == "PMB Referral" && isset($_POST["icd_codes"])) 
            {
                $doctorsd .= "- Diagnosis : <b>".$_POST["icd_codes"]."</b><br/>";
            }         
           
        }
        $template = str_replace('{practice_tag}', $doctorsd,  $template);
        $template = str_replace('{practice_name}', $fpractice_name,  $template);
        $template = str_replace('{service_date}', $fservice_date,  $template);
        $template = str_replace('{admission_date}', $fservice_date,  $template);
    }
    if(isset($_POST["fdr1"])){
        $fdr = json_decode($_POST["fdr1"],true);
        $doctorsd = "";       
        foreach($fdr as $f)
        {
            $fday = explode("-", $f["practice"]);
            $fpractice_number = $fday[0];
            $fpractice_name = "<b>".$fday[1]."</b>";            
            $fservice_date = "<b>".$f["service_date"]."</b>";
            $doctorsd .= "<br/>- Provider : <b>$fpractice_name</b><br/>";
            $doctorsd .= "- Practice : <b>$fpractice_number</b><br/>";              
           
        }
        $template = str_replace('{practice_tag1}', $doctorsd,  $template);
    }

    //$practice_number=$_POST["practice_number"];
    $email = $coresp[0];
    $typm = $coresp[1];
}

$title="Compose New Email";
$disable="";
if(isset($_POST["reply"]))
{
    $title="Reply Email";
    $disable="";
    $subject = $_POST["seltyp"];
          
}

require_once ("mail_header.php");
?>
    <!-- /.col -->

        <div class="col-md-10">
            <form action="read_mail.php" method="post" id="pform">
                <input type="hidden" name="typm" value="<?php echo $typm; ?>"/>
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><?php echo $title;?></h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="form-group">
                      <input type="hidden" name="email_claim_id" value="<?php echo $_SESSION["email_claim_id"]; ?>"/>
                        <input type="text" class="form-control" placeholder="To:" name="to" id="to" value="<?php echo $email; ?>" <?php echo $disable;?> REQUIRED>
                    </div>
                    <div class="form-group">
                        <input class="form-control" placeholder="Subject:" name="subject" id="subject" value="<?php echo $subject; ?>" <?php echo $disable;?> REQUIRED>
                    </div>
                    <div class="form-group">
                    <textarea id="compose-textarea" class="form-control" name="body" REQUIRED>
<?php echo nl2br($template);?>
                    </textarea>
                        <textarea name="myfiles" id="myfiles" hidden>

                        </textarea>
                    </div>
                    <span id="status"></span>
                    <p id="demo"></p>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <div class="float-right">
<span id="iffo"></span>
                        <button type="submit" name="compose" id="compose" class="btn btn-primary" onclick="pleaseWait()" <?php echo $disableR;?>><i class="far fa-envelope"></i> Send</button>
                    </div>
                 <span class="text-danger">NB : For multiple email recipients use semi colon <b><i>(;)</i></b> to seperate emails.</span>
                 <!--
                 <p class="help-block">
                    <label><input type="checkbox" name="checkNotes" id="checkNotes"/> Include the email in the notes</label>
                </p>
                -->
                </div>
                <!-- /.card-footer -->
            </div>
            <!-- /.card -->
            </form>
            <form id="formAjax" action="filehandle.php" method="POST">
            <div class="form-group">
                <div class="btn btn-default btn-file">
                    <i class="fas fa-paperclip"></i> Attachment (Max. 15MB)
                    <input type="file" id="fileAjax" name="fileAjax[]" multiple="multiple" onchange="upload()"/>
                    <input type="submit" id="submit" name="submit" value="Upload" hidden/>
                </div>               
            </div>
          
            </form>
        </div>
<!-- /.col -->
</div>
<!-- /.row -->
</div><!-- /.container-fluid -->
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
<!-- Summernote -->
<script src="../../admin/admin_main/plugins/summernote/summernote-bs4.min.js"></script>
<!-- Page Script -->
<script type="text/javascript">
    var myForm = document.getElementById('formAjax');  // Our HTML form's ID
    var myFile = document.getElementById('fileAjax');  // Our HTML files' ID
    var statusP = document.getElementById('status');
    function upload()
    {
        var myPlayer = document.getElementById("submit");
        myPlayer.click();
    }
    function pleaseWait()
    {
        sendDataToParent();
        document.getElementById("iffo").innerHTML="please wait...";
        $("#compose").hide();
        //document.getElementById("compose").disabled = true;

    }
    function sendDataToParent() {
            var inputValue = document.getElementById("compose-textarea").value;
            if (window.opener && !window.opener.closed) {
                window.opener.receiveDataFromPopup(inputValue);
            }
        }
        var ff="";
    myForm.onsubmit = function(event) {
        event.preventDefault();

        statusP.innerHTML = 'Please wait...';

        // Get the files from the form input
        var files = myFile.files;
        // Create a FormData object
        var formData = new FormData();

        // Select only the first file from the input array


        for(i=0;i<files.length;i++)
        {
            var file = files[i];
            // Add the file to the AJAX request
            formData.append('fileAjax', file, file.name);
            let hh = file.name;
            
            // Set up the request
            var xhr = new XMLHttpRequest();

            // Open the connection
            xhr.open('POST', 'filehandle.php', true);

            // Set up a handler for when the task for the request is complete
            xhr.onload = function () {
                if (xhr.status == 200) {
                    statusP.innerHTML = '';
                    document.getElementById("compose").disabled = false;
                    
                } else {
                    statusP.innerHTML = 'Upload error. Try again. '+xhr.status;
                }
                if(this.responseText.indexOf(' been uploaded')>0)
                {
                    let myTextArea = $("#myfiles").val();
                    myTextArea += ";"+hh;
                    $("#myfiles").val(myTextArea);
                }
                $("#demo").append("<hr>"+this.responseText);

            };

            // Send the data.
            xhr.send(formData);

        }

       
    }
</script>
<script>
    $(function () {
        //Add text editor
        $('#compose-textarea').summernote({
            tabsize: 2,
            height: 1000
        });
        

    });
    $(document).ready(function (){
        $('.note-editable').css("height","200px");

    });
</script>
</body>
</html>
