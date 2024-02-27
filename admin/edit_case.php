<?php
header('Cache-Control: no cache'); //no cache
session_cache_limiter('private_no_expire'); // works
session_start();
error_reporting(0);
?>
<html>
<head>

    <title>Med ClaimAssist: Edit Case</title>
    <link rel="stylesheet" href="bootstrap3/css/bootstrap.min.css">
    <script src="jquery/jquery.min.js"></script>
    <script src="bootstrap3/js/bootstrap.min.js"></script>
    <script src="jquery/jquery.js"></script>
    <link href="w3/w3.css" rel="stylesheet" />
    <link href="w3/custom.css" rel="stylesheet" />
    <script src="js/newCase.js"></script>
    <link rel="stylesheet" type="text/css" href="css/newCase.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAxEPQ45jn7zzBxD2eUfzxqAkFDio7p_6Q&callback=initialize"></script>

    <script type="text/javascript">


        function resizeIframe(obj) {
            obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
        }

        function generateClaimNomber() {
            var client = document.getElementById("client_id").value;
            checkAspen(client);
        }
        function checkAspen(client)
        {
            if(client == 31)
            {
                $(".showcl").show();
                $(".hidecl").hide();
                $(".claimn").text("MCA Request Number");
                $(".incid").text("(Infusion Date)");

            }
            else {
                $(".showcl").hide();
                $(".hidecl").show();
                $(".claimn").text("Claim Number");
                $(".incid").text("(Incident Date)");
            }
        }
        $(document).ready(function ()
        {
            var client = document.getElementById("client_id").value;
            checkAspen(client);
        });
    </script>
    <style>
        input[type=text],input[type=number],input[type=email],input[type=date],select{
            width:250px;

            font-weight: bolder;
            color: #3C510C;
        }
        #ss
        {
            margin-left: 20px;
            display: none;;
        }


        .xx{
            font-weight: bolder;
        }
        #tb{
            width: 69%;
            margin-left: 20px;
        }
        #advanced{
            margin-left: 20px;
        }
        #notifications{
            float: right;
            width: auto;
            right: 10px;
            position: fixed;
            z-index: 1;

        }
        #view{
            margin-left: 20px;
        }
        #nnb{
            display: block;
            width: 70%;
            margin-left:20px;
        }

        #view1{
            margin-left: 50px;
        }
        #myInput{
            width:100%;
            background-image:none;
        }
        #myDiv{
            z-index: 1;
        }
        #blink {

            animation:1s blinker linear infinite;
            -webkit-animation:1s blinker linear infinite;
            -moz-animation:1s blinker linear infinite;

            color: red;
        }

        @-moz-keyframes blinker {
            0% { opacity: 1.0; }
            50% { opacity: 0.0; }
            100% { opacity: 1.0; }
        }

        @-webkit-keyframes blinker {
            0% { opacity: 1.0; }
            50% { opacity: 0.0; }
            100% { opacity: 1.0; }
        }


    </style>
</head>

<body>

<?php
include("header.php");

$my_levels=["admin","claims_specialist","controller"];
if(!in_array($_SESSION["level"],$my_levels))
{
    die("<script>alert('Access Denied');location.href = \"login.html\";</script>");
}
//session_start();
//$username=$_POST['user'];
include("edit_case_class.php");
?>

<br/><br/>
<br/><br/>
<?php
include("classes/notificationsClass.php");
$fb=new Feedback();
$fb->flashNow();
?>
<h2 class="tab"><u>Edit Case</u></h2>
<div id="details" class="alert success" style="display: none">

</div>


<form class="tab" action="edit_post.php" method="post" onsubmit="return validateForm()">
    <?php

    $sst="hidden";
    $read="readonly";
    if($open<>1 && $_SESSION['level']<>"admin")
    {
        if($_SESSION['level']=="controller" || $_SESSION["gap_admin"]=="assessor")
        {

        }
        else{
            echo "Edit failed";
            exit(1);
        }
    }
    if($_SESSION['level']=="admin" || $_SESSION['level'] == "controller" || $client_id == 1 || $client_id == 6) {
        $sst="";
        $read="";
    }
    ?>
    <div class="<?php echo $sst?>">
        <table class="table alert-info" width="100%">
            <tr>
                <td><b>Owner :
                        <select id="owner" name="owner" class="form-control">
                            <option value="<?php echo $username; ?>"><?php echo $username; ?></option>
                            <?php

                            $conn1 = connection("doc", "doctors");
                            $sql = 'SELECT DISTINCT username FROM staff_users where state=1 and (role="claims_specialist" or role="admin" or role="controller")';
                            $r = $conn1->query($sql);
                            foreach ($r as $row) {

                                ?>
                                <option value="<?php echo $row['username']; ?>"><?php echo $row['username']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </b></td>
                <td><b>State :
                        <select id="open" name="open" class="form-control">
                            <?php
                            if($open==4){
                                echo "<option value='4'>Under Review</option>";
                            }
                            elseif ($open==5)
                            {
                                echo "<option value='5'>Pre-Assessment</option>";

                            }
                            else
                            {
                                echo "<option value='$open'>$open1</option>";
                                echo "<option value='1'>Open</option>";
                            }
                            ?>
                        </select>
                    </b></td>
                <td><b>Reason :
                        <select id="open_reason" name="open_reason" class="form-control">
                            <option value="<?php echo $open_reason; ?>"><?php echo $open_reason; ?></option>
                            <option value="CS Request">CS Request</option>
                            <option value="Client Request">Client Request</option>
                        </select>
                    </b></td>
            </tr>
        </table>
    </div>
    <div style="float: right">
        <?php
        if($client_id==3) {
            echo "<h5><a target=\"_blank\" href=\"$fullpath\" style='color: #0d92e1'><u><i>Open File Explorer</i><u></u></a></h5>";
        }
        ?>
        <iframe src="upload.php" scrolling="no" frameborder="0" onload="resizeIframe(this)"></iframe>
    </div>
    <?php echo $duplicate.$otherclaims; ?>
    <div id="vbv"
    <p>

        <select id="client_id" name="client_id" onchange="validateClient(),validatePolicy(),generateClaimNomber()" <?php echo $read; ?>>
            <option value="<?php echo $client_id; ?>"><?php echo $client_name; ?></option>
            <?php


            $sql = 'select client_name, min(client_id) as client_id from clients group by client_name ORDER BY client_name ASC';
            $r=$conn->query($sql);
            foreach ($r as $row) {

                ?>
                <option value="<?php echo $row['client_id']; ?>"><?php echo $row['client_name']; ?></option>
                <?php
            }
            ?>
        </select>
        <label for="client_id">Client Name<span style="color:red">*</span></label>
    </p>
    <p>

        <input type="text" id="member_name" name="member_name" value="<?php echo $member_name; ?>" onblur="toUpper('member_name')" />
        <label for="member_name">Member's First Name or Initials</label>
    </p>
    <p>

        <input type="text" id="member_surname" name="member_surname" value="<?php echo $member_surname; ?>"  onblur="toUpper('member_surname')"/>
        <label for="member_surname">Member's Surname</label>
    </p>
    <p>

        <input type="text" id="patient_name" name="patient_name" value=""  onblur="toUpper('patient_name')"/>
        <label for="patient_name"> Patient Name</label>
        <input type="hidden"  id="myPatient" name="myPatient">
        <span id="myp" style="color: green;font-weight: bolder"><br>
            <?php

            $doc=$conn->prepare('SELECT patient_name FROM patient WHERE claim_id=:num');
            $doc->bindParam(':num', $claim_id, PDO::PARAM_STR);
            $doc->execute();
            $doc_num=$doc->rowCount();
            if($doc_num>0)
            {
                foreach ($doc->fetchAll() as $j)
                {
                    echo "<span onclick='deletePatient(\"$claim_id\",\"$j[0]\")' class='glyphicon glyphicon-trash' title='delete' style='color:#3C510C;cursor: pointer'></span><b style='color:#3C510C'> $j[0]</b><br>";
                }
            }
            ?>
        </span>
    </p>
    <p class="showcl">

        <input type="date" id="d_o_b" name="d_o_b" value="<?php echo $patient_dob; ?>"/>
        <label for="member_surname">Patient D.O.B</label>
    </p>
    <p class="showcl">

        <select id="patient_gender" name="patient_gender">
            <option value="<?php echo $patient_gender; ?>"><?php echo $patient_gender; ?></option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
        <label for="member_surname">Patient Gender</label>
    </p>
    <p>

        <input type="number" id="memb_telephone" name="memb_telephone" onblur="validateNumber('memb_telephone','10','11')" value="<?php echo $memb_telephone; ?>"  />
        <label for="member_telephone">Member's Telephone Number</label>
    </p>

    <p>

        <input type="number" id="memb_cell" name="memb_cell" onblur="validateNumber('memb_cell','10','11')" value="<?php echo $memb_cell; ?>"  />
        <label for="memb_cell">Member's Cell Phone Number</label>
    </p>

    <p>

        <input type="email" id="memb_email" name="memb_email" value="<?php echo $memb_email; ?>"  />
        <label for="memb_email">Member's e-mail Address</label>

    <p>
        <input type="text" id="policy_number" name="policy_number" value="<?php echo $policy_number; ?>" onblur="return validatePolicy(0)" <?php echo $read; ?>/>
        <label for="policy_number">GAP Policy Number<span style="color:red">*</span> </label><span style="color: red; font-weight: bold;" id="gap1"></span>
    </p>

    <p>
        <input type="text" id="claim_number" name="claim_number" value="<?php echo $claim_number; ?>" required onblur="return editValidate()" <?php echo $read; ?> tabindex="-1"/>
        <label for="claim_number" class="claimn">Claim Number<span style="color:red">*</span></label><span style="color: red;font-weight: bold;" id="claim1"></span>
    </p>

    <p>
        <select id="medical_scheme" name="medical_scheme" onchange="return Schemes()" REQUIRED>
            <option value="<?php echo $medical_scheme; ?>"><?php echo $medical_scheme; ?></option>
            <?php

            $conn=connection("mca","MCA_admin");
            $sql = 'SELECT DISTINCT name FROM schemes ORDER BY name ASC';
            $r=$conn->query($sql);
            foreach ($r as $row) {

                ?>
                <option value="<?php echo $row['name']; ?>"><?php echo $row['name']; ?></option>
                <?php
            }
            ?>
        </select>
        <label for="medical_scheme">Medical Scheme  <a href='#' target="popup" onclick="window.open('schemes.php','popup','width=1000,height=800'); return false;"><span class="glyphicon glyphicon-plus-sign" style="color: deepskyblue;font-size: 24px;cursor: pointer" title="Medical Schemes"></span></a></label>
    </p>
    <p style="display:none" id="schemeDiv">
        <input type="text" id="scheme_option" name="scheme_option" list="options" value="<?php echo $scheme_option; ?>" />
        <label for="medical_scheme">Scheme Option</label>
    </p>

    <p>
        <input type="text" id="scheme_number" name="scheme_number" value="<?php echo $scheme_number; ?>" />
        <label for="scheme_number">Scheme Membership Number</label>
    </p>

    <p>
        <input type="date" id="Service_Date" name="Service_Date" value="<?php echo $Service_Date; ?>" />
        <label for="Service_Date">From Date <span class="incid">(Incident Date)</span></label>

    </p>
    <p>
        <input type="date" id="end_date" name="end_date" value="<?php echo $end_date; ?>" />
        <label for="end_date">To Date <span class="incid">(Incident Date)</span></label>

    </p>

    <p>
        <input type="number" id="id_number" name="id_number" onblur="validateNumber('id_number','13','14')" value="<?php echo $id_number; ?>" />
        <label for="id_number">Member's ID number</label>
    </p>

    <p style="color:green; display: none" id="search">
        <b>searching...</b>
    </p>

    <p>
        <input type="text" id="prac_num_1" name="prac_num_1" value="" onblur="return Doctors('prac_num_1','doc_name_1')" />
        <label for="prac_num_1"><span class="glyphicon glyphicon-plus-sign" style="cursor: pointer" onclick="clearDoctor()"></span> Practice Number </label>
        <input type="hidden" id="doctors" name="doctors">

        <span id="mydoctors" style="color: green;font-weight: bolder"><br></span>
        <?php

        $doc=$conn->prepare('SELECT DISTINCT practice_number FROM doctors WHERE claim_id=:num');
        $doc->bindParam(':num', $claim_id, PDO::PARAM_STR);
        $doc->execute();
        $doc_num=$doc->rowCount();
        if($doc_num>0)
        {
            foreach ($doc->fetchAll() as $rx)
            {
                echo "<span onclick='deleteDoctor(\"$claim_id\",\"$rx[0]\")' class='glyphicon glyphicon-trash' title='delete' style='color:#3C510C;cursor: pointer'></span><b onclick='checkDoctors(\"$rx[0]\")' style='color:#3C510C'> $rx[0]</b><br>";
            }
        }
        ?>

    </p>



    <p>
        <input type="text" id="icd10" name="icd10" list="codes" value="<?php echo $icd10; ?>" required onblur="return Codes()"/>
        <label for="icd10">Primary ICD10 code</label>
    </p>
    <p style="display:none">
        <input type="text" id="myHide" name="myHide" value=""/>

    </p>

    <p style="display:none" id="pmbP">
        <input class="btn-success" style="border: none; padding: 5px" type="text" id="pmbx" name="pmbx" value="<?php echo $pmb; ?>"/>
        <label style="font-style: oblique; color: blue;">PMB?</label>

    <div id="icdDetails" style="font-weight: bolder;" class="alert-success"></div>
    </p>

    <p style="display: none;">
        <input type="radio" name="pmb" value="0" id="pmb2" checked="true">Yes
        <input type="radio" name="pmb" value="1" id="pmb3">No
    </p>
    <p style="">
        <label for="emer" style="color:red; font-weight: bolder">Emergency?</label><br>
        <b>
            <input type="radio" class="w3-radio" name="emergency" value="1" id="emergency1" <?php echo ($emergency== '1') ?  "checked" : "" ;  ?>>Yes
            <input type="radio" class="w3-radio" name="emergency" value="0" id="emergency2" <?php echo ($emergency== '0') ?  "checked" : "" ;  ?>>NO

        </b>

    </p>
    <span class="showcl">
    <p>
        <select id="medication_value" name="medication_value">

            <option value="<?php echo $medication_value; ?>"><?php echo $medication_value; ?></option>
            <option value="VENOFER ">VENOFER</option>
            <option value="FERINJECT">FERINJECT</option>
        </select>
        <label for="medication_value">Name of Medication</label>
    </p>
    <p>
        <select id="fusion_done" name="fusion_done">
            <option value="<?php echo $fusion_done; ?>"><?php echo $fusion_done; ?></option>
            <option value="IN ROOMS ">IN ROOMS</option>
            <option value="IN HOSPITAL">IN HOSPITAL</option>
        </select>
        <label for="fusion_done">Infusion to be do</label>
    </p>
    <p>
        <input type="text" id="dosage" name="dosage" list="dosage1" value="<?php echo $dosage; ?>"/>
        <label for="dosage">Dosage</label>
    </p>
    <p>
        <input type="text" id="codes" name="codes" value="<?php echo $codes; ?>"/>
        <label for="codes">Codes</label>
    </p>
    <p>
        <input type="text" id="nappi" name="nappi" value="<?php echo $nappi; ?>"/>
        <label for="nappi">Nappi</label>
    </p>
  <p>
        <input type="text" id="person_email" name="person_email" value="<?php echo $person_email; ?>" />
        <label for="person_email">Contact Person Email</label>

    </p>
</span>
    <span class="hidecl">
    <p>
        <input type="text" id="charged_amnt" name="charged_amnt" onblur="amountCalc()" value="<?php echo $charged_amnt; ?>" />
        <label for="charged_amnt">Total Value of all Claims. Rands only. Numbers only</label>
    </p>

    <p>
        <input type="text" id="scheme_paid" name="scheme_paid" onblur="amountCalc()"  value="<?php echo $scheme_paid; ?>" />
        <label for="scheme_paid">Total Value the Scheme Paid.  Rands only. Numbers only</label>
    </p>

    <p>
        <input type="text" id="gap" name="gap" value="<?php echo $gap; ?>" />
        <label for="gap">Member's Portion. Rands only. Numbers only</label>
    </p>
    <p>
        <input type="text" id="client_gap" name="client_gap" value="<?php echo $client_gap; ?>" />
        <label for="client_gap">Client Gap Amount. Rands only. Numbers only</label>
    </p>
    <p>
        <input type="text" style="color: #449d44" id="savings_scheme" name="savings_scheme" value="<?php echo $savings_scheme; ?>"  />
        <label for="gap">Scheme Savings</label>
    </p>

    <p>
        <input type="text" style="color: #449d44" id="savings_discount" name="savings_discount" value="<?php echo $savings_discount; ?>"  />
        <label for="gap">Discount Savings</label>
    </p>
</span>
    <input type="hidden" id="date_entered" name="date_entered" value="<?php date_default_timezone_set('Africa/Johannesburg');
    $date = date("Y-m-d h:i:sa");
    echo $date;
    ?>" />
    <input type="hidden" id="username" name="username" value="<?php echo $_SESSION['user_id']?>" />
    <input type="hidden" id="claim_id" name="claim_id" value="<?php echo $claim_id;?>" />

    <input type="hidden" id="Open" name="Open" value="1" />

    <button type="submit" class="w3-btn w3-white w3-border w3-border-blue w3-round-large" id="btn" name="btn1"><span class="glyphicon glyphicon-ok-circle" style="color:mediumseagreen"> </span> <b style="color:mediumseagreen"> Save Changes</b></button>



</form><br><br>
Note : <span style="color:red"> * </span>---ReadOnly Field.
</div>
<datalist id="codes">
    <?php

    $conn=connection("cod","Coding");
    $sql = 'SELECT DISTINCT diag_code FROM Diagnosis';
    $r=$conn->query($sql);
    foreach ($r as $row) {

    ?>
    <option value="<?php echo $row['diag_code']; ?>">
        <?php
        }
        ?>
</datalist>

<datalist id="options">
    <option value="test">
</datalist>

<datalist id="dosage1">
    <option value="200mgx2">
    <option value="1000mg">
</datalist>
<div id="userAccess" class="w3-modal w3-responsive">
    <div class="w3-modal-content w3-card-4 w3-animate-zoom">
        <header class="w3-container">
                    <span onclick="document.getElementById('userAccess').style.display='none'" class="w3-button w3-red w3-xl w3-display-topright">
                        &times;
                    </span>
            <div class="w3-bar w3-red w3-border-bottom">
                <p align="center" class="w3-white w3-text-black"><span style="color: red">Invalid Doctor's Practice Number</span><br><a href='#' target="popup" onclick="window.open('add_doc_form.php','popup','width=800,height=600'); return false;" class="w3-btn w3-white w3-border w3-border-blue w3-round-large"><span class="glyphicon glyphicon-plus"></span> Add Doctor</a></p>
            </div>
        </header>
    </div></div>
<script type="text/javascript" src="js/w3js.js">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAxEPQ45jn7zzBxD2eUfzxqAkFDio7p_6Q&libraries=places&callback=initMap" async defer></script>


</body>
<hr>
<?php
include('footer.php');
?>
</html></html>