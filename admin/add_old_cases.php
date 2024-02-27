<?php
session_start();
error_reporting(0);
?>
<html>
<head>
    
    <title>Med ClaimAssist: Add Old Case</title>
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
   input[type=text],input[type=number],input[type=email],input[type=date],#client_id,#username{
            width:250px;
            border-color: grey;
            border-radius: 1px;
        }
</style>
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
<h2 class="tab"><u>Add Old Case<i style="color: red; font-size: 16px;">(Complete all the fields below to add a case)</i></u></h2>

<div id="details" class="alert success" style="display: none">
  
</div>



<form class="tab" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" onsubmit="return validateForm()">
    <p>

    <select id="client_id" name="client_id" onchange="validateClient(),validatePolicy()">
         <?php
        require_once('dbconn.php');
        $conn=connection("mca","MCA_admin");
        $sql = 'SELECT DISTINCT client_name,client_id FROM clients ORDER BY client_name ASC';
        $r=$conn->query($sql);
        foreach ($r as $row) {

            ?>
            <option value="<?php echo $row['client_id']; ?>"><?php echo $row['client_name']; ?></option>
            <?php
        }
        ?>
    </select>
    <label for="client_id">Client Name</label>
    </p>
<p>

        <select id="username" name="username" REQUIRED>
            <option value="">[select username]</option>
            <?php

            $conn = connection("doc", "doctors");
            $sql = 'SELECT DISTINCT username FROM staff_users where state=1 and (role="claims_specialist" or role="admin")';
            $r = $conn->query($sql);
            foreach ($r as $row) {

                ?>
                <option value="<?php echo $row['username']; ?>"><?php echo $row['username']; ?></option>
                <?php
            }
            ?>
        </select>
        <label for="username">Username</label>
    </p>
    <p>
 
            <input type="text" id="member_name" name="member_name" value=""  />
            <label for="member_name">Member's First Name or Initials</label>
        </p>
        <p>

            <input type="text" id="member_surname" name="member_surname" value=""  />
            <label for="member_surname">Member's Surname</label>
        </p>
         <p>

            <input type="text" id="patient_name" name="patient_name" value=""  />
            <label for="patient_name">Patient Name</label>
        </p>

    <p>

        <input type="text" id="memb_telephone" name="memb_telephone" value=""  />
        <label for="member_telephone">Member's Telephone Number</label>
    </p>

    <p>

        <input type="text" id="memb_cell" name="memb_cell" value=""  />
        <label for="memb_cell">Member's Cell Phone Number</label>
    </p>

    <p>

        <input type="email" id="memb_email" name="memb_email" value=""  />
        <label for="memb_email">Member's e-mail Address</label>

    <p>
        <input type="text" id="policy_number" name="policy_number" value="" onblur="return validatePolicy()"/>
        <label for="policy_number">GAP Policy Number</label><span style="color: red; font-weight: bold;" id="gap1"></span>
    </p>

    <p>
        <input type="text" id="claim_number" name="claim_number" value="" required onblur="return validateClaimNo()">
        <label for="claim_number">Claim Number</label><span style="color: red;font-weight: bold;" id="claim1"></span>
    </p>

        <p>
<select id="medical_scheme" name="medical_scheme" onchange="return Schemes()"">
<option value="">[Select Scheme]</option>
<?php
require_once('dbconn.php');
$conn=connection("mca","MCA_admin");
$sql = 'SELECT DISTINCT id,name FROM schemes ORDER BY name ASC';
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
        <label for="Service_Date">Incident Date (please select rather than type)</label>

    </p>
 <p>
        <input type="date" id="date_entered" name="date_entered" value=""/>
        <label for="Service_Date">Date Entered (when the case was entered)</label>

    </p>
     <p>
        <input type="date" id="date_closed" name="date_closed" value="" />
        <label for="Service_Date">Date Closed (when the case was closed)</label>

    </p>


        <p>
            <input type="number" id="id_number" name="id_number" value="" />
            <label for="id_number">Member's ID number</label>
        </p>

        <p style="color:green; display: none" id="search">
            <b>searching...</b>
        </p> 

        <p>
            <input type="text" id="doc_name_1" name="doc_name_1" value="" readonly/>
            <label for="doc_name_1">Doctor #1 Name</label>
        </p>

        <p>
            <input type="text" id="prac_num_1" name="prac_num_1" value="" onblur="return Doctors('prac_num_1','doc_name_1')" />
            <label for="prac_num_1">Doctor #1's (above) Practice Number</label>
        </p>

        <p>
            <input type="text" id="doc_name_2" name="doc_name_2" value="" readonly/>
            <label for="doc_name_2">Doctor #2 Name</label>
        </p>

        <p>
            <input type="text" id="prac_num_2" name="prac_num_2" value="" onblur="return Doctors('prac_num_2','doc_name_2')"/>
            <label for="prac_num_2">Doctor #2's (above) Practice Number</label>
        </p>

        <p>
            <input type="text" id="doc_name_3" name="doc_name_3" value="" readonly/>
            <label for="doc_name_3">Doctor #3 Name</label>
        </p>

        <p>
            <input type="text" id="prac_num_3" name="prac_num_3" value="" onblur="return Doctors('prac_num_3','doc_name_3')"/>
            <label for="prac_num_3">Doctor #3's (above) Practice Number</label>
        </p>
 <p>
            <input type="text" id="doc_name_4" name="doc_name_4" value="" readonly/>
            <label for="doc_name_4">Doctor #4 Name</label>
        </p>

        <p>
            <input type="text" id="prac_num_4" name="prac_num_4" value="" onblur="return Doctors('prac_num_4','doc_name_4')"/>
            <label for="prac_num_4">Doctor #4's (above) Practice Number</label>
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

        <p>
            <input type="number" id="charged_amnt" name="charged_amnt" value="" />
            <label for="charged_amnt">Total Value of all Claims. Rands only. Numbers only</label>
        </p>

        <p>
            <input type="number" id="scheme_paid" name="scheme_paid" value="" />
            <label for="scheme_paid">Total Value the Scheme Paid.  Rands only. Numbers only</label>
        </p>

        <p>
            <input type="number" id="gap" name="gap" value="" />
            <label for="gap">Value for which client is liable. Rands only. Numbers only</label>
        </p>
      
        <input type="hidden" id="entered_by" name="entered_by" value="<?php echo $_SESSION['user_id']?>" />


        <input type="hidden" id="Open" name="Open" value="0" />


<button type="submit" class="w3-btn w3-white w3-border w3-border-blue w3-round-large" id="btn" name="btn"><span class="glyphicon glyphicon-ok-circle" style="color:mediumseagreen"> </span> <b style="color:mediumseagreen">Add Case</b></button>

    <button type="reset" class="w3-btn w3-white w3-border w3-border-red w3-round-large" id="btn" name="btn"><span class="glyphicon glyphicon-remove-circle" style="color: red"> </span> <b style="color: red">Clear All</b></button>
   

</form>
<hr>
<?php
include('footer.php');
?>
<datalist id="codes">
<?php
require_once('dbconn.php');
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


  <div id="userAccess" class="w3-modal w3-responsive">
            <div class="w3-modal-content w3-card-4 w3-animate-zoom">
                <header class="w3-container">
                    <span onclick="document.getElementById('userAccess').style.display='none'" class="w3-button w3-red w3-xl w3-display-topright">
                        &times;
                    </span>
                    <div class="w3-bar w3-blue w3-border-bottom">
                        <p align="center" class="w3-yellow w3-text-black"><span style="color: red">Invalid Doctor's Practice Number</span><br><a href='#' target="popup" onclick="window.open('add_doc_form.php','popup','width=800,height=600'); return false;" class="btn btn-success">Add Doctor<span class="glyphicon glyphicon-plus"></span></a></p>
                    </div>
                </header>
                </div></div>
        <script type="text/javascript" src="js/w3js.js">        

</body>
</html>