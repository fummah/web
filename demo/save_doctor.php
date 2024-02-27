<?php
session_start();
define("access",true);
error_reporting(0);
if (isset($_POST['edit_doctor_post']) || isset($_POST["add_doctor"]))
{
}
else
{
    die("invalid entry");
}
//error_reporting(0);
require "classes/controls.php";
include ("templates/claim_templates.php");
$control=new controls();
if(!$control->isInternal())
{
    die("Invalid entry");
}
include ("header.php");
?>
    <html>
    <head>
        <title>MCA | Save Doctor</title>

        <style type="text/css">
            <!--
            .tab { margin-left: 200px; }
            -->
        </style>


    </head>

<body>
<?php
//$my_levels=["admin","claims_specialist","controller"];
?>
<br>
    <br>
<?php
    $username=$control->loggedAs();
    $name_initials =strtoupper(validateXss($_POST['name']));
            $surname = strtoupper($_POST['surname']);
            $telephone = validateXss($_POST['telephone']);
            $admin_name = strtoupper($_POST['receptionist']);
            $gives_discount = validateXss($_POST['discount']);
            $displine_id = strtoupper($_POST['displine_type']);
            $email = $_POST['email'];
            $days_number = $_POST['days_number'];
            $dr_value = $_POST['dr_value'];
            $myob=json_decode($dr_value,true);
            $numbrarr=is_array($myob)?count($myob):0;
            $data=$control->checkDisciplineCodes($displine_id);
            $displine_type = strtoupper($data['code']);
            $subcode = strtoupper($data['subcode']);
            $discipline = strtoupper($data['descr']);
            $subdesr = strtoupper($data['subdescr']);
             $practice_number = validateXss($_POST ['practice_number']);
            $practice_number=trim($practice_number,' ');
            $practice_number=str_pad($practice_number, 7, '0', STR_PAD_LEFT);
            $signed = isset($_POST ['signed'])?1:0;
            $date_joined = validateXss($_POST ['date_joined']);
            $discount_v = validateXss($_POST ['discount_v']);
            $discount_perc = validateXss($_POST ['discount_perc']);
            $discount_value = validateXss($_POST ['discount_value']);
    if(isset($_POST['edit_doctor_post'])) {
        try {
            $doctor_id = (int)validateXss($_POST['doctor_id']);
            $control->callInsertDoctorLogs($doctor_id,$username);
            $doctor_arr=array("name_initials"=>$name_initials,"surname"=>$surname,"telephone"=>$telephone,"admin_name"=>$admin_name,"gives_discount"=>$gives_discount,
"discipline"=>$discipline,"practice_number"=>$practice_number,"disciplinecode"=>$displine_type,"sub_disciplinecode"=>$subcode,"sub_disciplinecode_description"=>$subdesr,
"disciplinecode_id"=>$displine_id,"email"=>$email,"dr_value"=>$dr_value,"days_number"=>$days_number,"signed"=>$signed,"date_joined"=>$date_joined);
   foreach ($doctor_arr as $key => $value) {
            $ccc=$control->callUpdateDoctorDetailsKey($doctor_id,$key,$value);
        }
            if ($ccc == 1) {

                for($i=0;$i<$numbrarr;$i++)
                {
                    $main_value = $myob[$i]["main_value"];
                    $discount_perc = $myob[$i]["discount_perc"];
                    $discount_value = $myob[$i]["discount_value"];
                    $days_number = $myob[$i]["days_number"];
                    $control->callInsertDoctorDiscount($dr_value,$discount_perc,$discount_value,$days_number,$practice_number,$username);
                }
                echo "<div class='uk-alert-success'>";
                echo "<h3 align='center'>";
                echo "Doctor details Successfully updated!";
                echo "</h3>";
                echo "<p align='center'>";
                echo "<br>";
                echo "Would you like to";
                echo "<a href=\"add_doctor.php\">";
                echo " add a doctor?";
                echo "</a>";
                echo "<br>";
                echo "Or, would you like to";
                echo "<a href=\"search_doctor.php\">";
                echo " search ";
                echo "</a>";
                echo "for a doctor already in the system?";
                echo "</p>";
                echo "</div>";
            } else {
                echo "<div class='uk-alert-danger'>";
                  echo "<h3 align='center'>";
                echo "Failed to update the doctor";
                echo "</h3>";
                echo "</div>";
            }
        }
        catch (Exception $e)
        {
                 echo "<div class='uk-alert-danger'>";
                  echo "<h3 align='center'>";
               echo "There is an error : ".$e->getMessage();
                echo "</h3>";
                echo "</div>";

        }
    }
    elseif(isset($_POST["add_doctor"])) {
        try {

            if($control->viewDoctor($practice_number)==true)
            {
                die("<div class='uk-alert-danger'><h3 align='center'>Practice number is already in the database</h3></div>");
            }
$ccc=$control->callInsertDoctorDetails($name_initials,$surname,$telephone,$admin_name,$practice_number,$gives_discount,$discipline,$displine_type,$subcode,$subdesr,$displine_id,$email,$dr_value,$days_number,$signed,$date_joined,$discount_v,$discount_perc,$discount_value,$username);

            if ($ccc>0) {
              for($i=0;$i<$numbrarr;$i++)
                {
                    $main_value = $myob[$i]["main_value"];
                    $discount_perc = $myob[$i]["discount_perc"];
                    $discount_value = $myob[$i]["discount_value"];
                    $days_number = $myob[$i]["days_number"];
                    $control->callInsertDoctorDiscount($dr_value,$discount_perc,$discount_value,$days_number,$practice_number,$username);
                }
echo "<div class='uk-alert-success'>";
                echo "<h3 align='center'>";
                echo "New Doctor Successfully added";
                echo "</h3>";
                echo "<p align='center'>";
                echo "<br>";
                echo "Would you like to";
                echo "<a href=\"add_doctor.php\">";
                echo " add a doctor?";
                echo "</a>";
                echo "<br>";
                echo "Or, would you like to";
                echo "<a href=\"search_doctor.php\">";
                echo " search ";
                echo "</a>";
                echo "for a doctor already in the system?";
                echo "</p>";
                echo "</div>";
            } else {
                echo "<div class='uk-alert-danger'>";
                  echo "<h3 align='center'>";
                echo "Failed to update the doctor";
                echo "</h3>";
                echo "</div>";
            }
        }
        catch (Exception $e)
        {
            echo "<div class='uk-alert-danger'>";
                  echo "<h3 align='center'>";
               echo "There is an error : ".$e->getMessage();
                echo "</h3>";
                echo "</div>";
        }
    }

?>
<?php
include "footer.php";
?>
<script>
    $(document).ready(function() {
        $('select').formSelect();
    } );
</script>
