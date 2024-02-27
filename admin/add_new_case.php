<?php
header('Cache-Control: no cache'); //no cache
session_cache_limiter('private_no_expire'); // works
session_start();
error_reporting(0);
?>
<html>
<head>

    <title>Med ClaimAssist: Add a Case</title>
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



    </script>
    <style>
        input[type=text],input[type=number],input[type=email],input[type=date],#client_id,#fusion_done,#medication_value,#nappi,#dosage,#patient_gender{
            width:250px;
            border-color: grey;
            border-radius: 1px;
        }
   .showcl{
            display: none;
        }
    </style>
    <script type="application/javascript">
        function generateClaimNomber() {
            var client = document.getElementById("client_id").value;

            if (client == 4 || client==31) {

                $(document).ready(function () {

                    $.ajax({

                        url: "ajaxPhp/ajaxRetrieve.php",
                        type: "GET",
                        data: {
                            identityNum: 6,
                            client:client
                        },
                        success: function (data) {
                            $('#claim_number').val(data);

                        },
                        error: function (jqXHR, exception) {
                            $('#claim_number').html(jqXHR.responseText);
                        }
                    });

                });
            }
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
    </script>
</head>

<body>

<?php
include("header.php");
//$username=$_POST['user'];
$my_levels=["admin","claims_specialist","controller"];
if(!in_array($_SESSION["level"],$my_levels))
{
    die("<script>alert('Access Denied');location.href = \"login.html\";</script>");
}
?>

<br/><br/>
<br/><br/>
<h2 class="tab"><u>Complete all the fields below to add a case</u></h2>

<hr>
<div id="details" class="alert success" style="display: none">

</div>


<form class="tab" action="new_case_page.php" method="post" onsubmit="return validateForm()">
    <p>

        <select id="client_id" name="client_id" onchange="validateClient(),validatePolicy(),generateClaimNomber()">
            <?php
            require_once('dbconn.php');
            try {
                $conn = connection("mca", "MCA_admin");
                $sql = 'select client_name, min(client_id) as client_id from clients group by client_name ORDER BY client_name ASC';
                $r = $conn->query($sql);
                foreach ($r as $row) {

                    ?>
                    <option value="<?php echo $row['client_id']; ?>"><?php echo $row['client_name']; ?></option>
                    <?php
                }
            }
            catch (Exception $r)
            {
                echo "There is an error ".$r;
            }
            ?>
        </select>
        <label for="client_id">Client Name</label>
    </p>

    <p>

        <input type="text" id="member_name" name="member_name" value="" onblur="toUpper('member_name')"/>
        <label for="member_name">Member's First Name or Initials</label>

    </p>
    <p>

        <input type="text" id="member_surname" name="member_surname" value="" onblur="toUpper('member_surname')" />
        <label for="member_surname">Member's Surname</label>
    </p>
    <p>

        <input type="text" id="patient_name" name="patient_name" value=""  onblur="toUpper('patient_name')"/>
        <label for="patient_name"> Patient Name</label>
        <input type="hidden"  id="myPatient" name="myPatient">
        <span id="myp" style="color: green;font-weight: bolder"><br></span>
    </p>
 <p class="showcl">

        <input type="date" id="d_o_b" name="d_o_b"/>
        <label for="member_surname">Patient D.O.B</label>
    </p>
<p class="showcl">

        <select id="patient_gender" name="patient_gender">
            <option value="">select gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
        <label for="member_surname">Patient Gender</label>
    </p>
    <p>

        <input type="number" id="memb_telephone" name="memb_telephone" onblur="validateNumber('memb_telephone','10','11')"  value=""  />
        <label for="member_telephone">Member's Telephone Number</label>
    </p>

    <p>

        <input type="number" id="memb_cell" name="memb_cell" onblur="validateNumber('memb_cell','10','11')" value=""  />
        <label for="memb_cell">Member's Cell Phone Number</label>
    </p>

    <p>

        <input type="email" id="memb_email" name="memb_email" value=""  />
        <label for="memb_email">Member's e-mail Address</label>

    <p>
        <input type="text" id="policy_number" name="policy_number" value="" onblur="return validatePolicy(1)"/>
        <label for="policy_number">GAP Policy Number</label><span style="color: red; font-weight: bold;" id="gap1"></span>
    </p>

    <p>
        <input type="text" id="claim_number" name="claim_number" value="" required onblur="return validateClaimNo()">
        <label for="claim_number" class="claimn">Claim Number</label><span style="color: red;font-weight: bold;" id="claim1"></span>
    </p>

    <p>
        <select id="medical_scheme" name="medical_scheme" onchange="return Schemes()" REQUIRED>
            <option value="Unknown">[Select Scheme]</option>
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
        <label for="medical_scheme">Medical Scheme  <a href='#' target="popup" onclick="window.open('schemes.php','popup','width=1000,height=800'); return false;"><span class="glyphicon glyphicon-plus-sign btn-info" style="font-size: 24px;cursor: pointer" title="Medical Schemes"></span></a></label>
    </p>
    <p style="display: none" id="schemeDiv">
        <input type="text" id="scheme_option" name="scheme_option" list="options" value="" />
        <label for="medical_scheme">Scheme Option</label>
    </p>

    <p>
        <input type="text" id="scheme_number" name="scheme_number" value="" />
        <label for="scheme_number">Scheme Membership Number</label>
    </p>

    <p>
        <input type="date" id="Service_Date" name="Service_Date" value="" />
        <label for="Service_Date">From Date <span class="incid">(Incident Date)</span></label>

    </p>
    <p>
        <input type="date" id="end_date" name="end_date" value="" />
        <label for="Service_Date">To Date <span class="incid">(Incident Date)</span></label>

    </p>

    <p>
        <input type="number" id="id_number" name="id_number" onblur="validateNumber('id_number','13','14')"  value="" />
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

    </p>


    <p>
        <input type="text" id="icd10" name="icd10" list="codes" value="" required onblur="return Codes()"/>
        <label for="icd10">Primary ICD10 code</label>
    </p>
    <p style="display:none">
        <input type="text" id="myHide" name="myHide" value=""/>

    </p>

    <p style="display:none" id="pmbP">
        <input class="btn-success" style="border: none; padding: 5px" type="text" id="pmbx" name="pmbx" value=""/>
        <label style="font-style: oblique; color: blue;">PMB?</label>

    <div id="icdDetails" style="font-weight: bolder;" class="alert-success"></div>
    </p>

    <p style="display: none;">
        <input type="radio" name="pmb" value="0" id="pmb2" checked="true">
        <input type="radio" name="pmb" value="1" id="pmb3">
    </p>
    <span class="showcl">
    <p>
        <select id="medication_value" name="medication_value">
<option value="">[select]</option>
            <option value="VENOFER ">VENOFER</option>
            <option value="FERINJECT">FERINJECT</option>
        </select>
        <label for="medication_value">Name of Medication</label>
    </p>
    <p>
        <select id="fusion_done" name="fusion_done">
<option value="">[select]</option>
            <option value="IN ROOMS ">IN ROOMS</option>
            <option value="IN HOSPITAL">IN HOSPITAL</option>
        </select>
        <label for="fusion_done">Infusion to be do</label>
    </p>
    <p>
           <input id="dosage" name="dosage" list="dosage1">

        <label for="dosage">Dosage</label>
    </p>
    <p>
        <input type="text" id="codes" name="codes"/>
        <label for="codes">Codes</label>
    </p>
    <p>
      <select id="nappi" name="nappi">
<option value="">[select]</option>
            <option value="873276019 (Venofer)">873276019 (Venofer)</option>
            <option value="720107001 (Ferinject)">720107001 (Ferinject)</option>
        </select>
        <label for="nappi">Nappi</label>
    </p>
  <p>
        <input type="text" id="person_email" name="person_email" value="" />
        <label for="person_email">Contact Person Email</label>

    </p>
</span>
 <span class="hidecl">
    <p style="">
        <label for="emer" style="color:red; font-weight: bolder">Emergency?</label><br>
        <b>
            <input type="radio" class="w3-radio" name="emergency" value="1" id="emergency1">Yes
            <input type="radio" class="w3-radio" name="emergency" value="0" id="emergency2">NO

        </b>

    </p>
    <p>
        <input type="number" id="charged_amnt" name="charged_amnt" onblur="amountCalc()" value="" />
        <label for="charged_amnt">Total Value of all Claims. Rands only. Numbers only</label>
    </p>

    <p>
        <input type="number" id="scheme_paid" name="scheme_paid" onblur="amountCalc()" value="" />
        <label for="scheme_paid">Total Value the Scheme Paid.  Rands only. Numbers only</label>
    </p>

    <p>
        <input type="number" id="gap" name="gap" value="" />
        <label for="gap">Member's Portion. Rands only. Numbers only</label>
    </p>
    <p>
        <input type="number" id="client_gap" name="client_gap" value="" />
        <label for="client_gap">Client Gap Amount. Rands only. Numbers only</label>
    </p>
</span>
    <input type="hidden" id="date_entered" name="date_entered" value="<?php date_default_timezone_set('Africa/Johannesburg');
    $date = date("Y-m-d h:i:sa");
    echo $date;
    ?>" />
    <input type="hidden" id="username" name="username" value="<?php echo $_SESSION['user_id']?>" />
    <input type="hidden" id="entered_by" name="entered_by" value="<?php echo $_SESSION['user_id']?>" />

    <input type="hidden" id="Open" name="Open" value="1" />

    <button type="submit" class="w3-btn w3-white w3-border w3-border-blue w3-round-large" id="btn" name="btn"><span class="glyphicon glyphicon-ok-circle" style="color:mediumseagreen"> </span> <b style="color:mediumseagreen">Add Case</b></button>

    <button type="reset" class="w3-btn w3-white w3-border w3-border-red w3-round-large" id="btn1" name="btn1"><span class="glyphicon glyphicon-remove-circle" style="color: red"> </span> <b style="color: red">Clear All</b></button>



</form>
<hr>
<?php
include('footer.php');
?>
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
</html>