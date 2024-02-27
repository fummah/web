<?php
define("access",true);
if(!isset($_POST['doctor_edit_btn']))
{
    die("Invalid entry");
}
session_start();
//error_reporting(0);
require "classes/controls.php";
include ("templates/claim_templates.php");
$control=new controls();
include ("header.php");
$username=$control->loggedAs();
?>
<html>
<head>
    <title>MCA | Edit Doctor</title>
    <script src="js/doctors.js"></script>
    <style type="text/css">
        input[type=text],input[type=date],input[type=number]{
            width:250px;
        }
        input[type=email]{
            width:250px;
        }
        #ali{
            position: relative;
            width: 60%;
            margin-left: auto;
            margin-right: auto;
        }
        .uk-select{
            width: 250px;
        }
    </style>
</head>
<body>
<?php
$doctor_id=(int)$_POST["doc_id"];
$row=$control->viewDoctorDetailsUsingId($doctor_id);
$practice_number= $row["practice_number"];
$first_name= $row["name_initials"];
$surname= $row["surname"];
$telephone= $row["telephone"];
$admin= $row["admin_name"];
$discount= $row["gives_discount"];
$displine= $row["discipline"];
$displine_type= $row["disciplinecode"];
$subcode= $row["sub_disciplinecode"];
$subdesr= $row["sub_disciplinecode_description"];
$email= $row["email"];
$disciplinecode_id= $row["disciplinecode_id"];
$dr_value= $row["dr_value"];
$days_number= $row["days_number"];
$signed= (int)$row["signed"]==1?"checked":"";
$chck_signed= (int)$row["signed"]==1?"block":"none";
$date_joined= $row["date_joined"];
$date_entered= $row["date_entered"];
$entered_by= $row["entered_by"];
?>
<div id="ali">
    <h3 align="center"><u>Add the doctor's details below</u></h3>
    <form  name="form" action = "save_doctor.php" method="post">
        <input type="hidden" id="doctor_id" name="doctor_id" value="<?php echo $doctor_id;?>">
        <div class="container">
            <div class="row">
                <div class="col-md-6"><input type="text" name="name" placeholder="First Name or Initials" value="<?php echo $first_name; ?>" class="uk-input" REQUIRED>
                    <label for="name">First Name or Initials</label>
                </div>
                <div class="col-md-6">
                    <input type="text" name="surname" class="uk-input" placeholder="Surname" value="<?php echo $surname; ?>" REQUIRED>
                    <label for="surname">Surname</label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6"><br>
                    <label for="practice_code">Discipline</label>
                    <?php

                    echo "<select style=\"width: 250px\" id=\"displine_type\" name=\"displine_type\">";
                    echo "<option value='$disciplinecode_id'>$displine_type --- $subcode --- $displine --- $subdesr</option>";
                    foreach ($control->viewDisciplinecodes() as $row_descipline)
                    {
                        echo "<option value=\"$row_descipline[0]\">$row_descipline[1] --- $row_descipline[2] --- $row_descipline[3] --- $row_descipline[4]</option>";
                    }
                    echo "</select>";
                    ?>
                </div>
                <div class="col-md-6">
                    <input type="text" name="practice_number" value="<?php echo $practice_number; ?>" class="uk-input" REQUIRED>
                    <label for="surname">The doctor's practice code/number</label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <input type="text" name="receptionist" value="<?php echo $admin; ?>" class="uk-input">
                    <label for="surname">Admin person or receptionist's name</label>
                </div>
                <div class="col-md-6">
                    <input type="text" name="telephone" value="<?php echo $telephone; ?>" class="uk-input">
                    <label for="surname">Practice telephone number</label>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <input type="email" name="email" class="uk-input" value="<?php echo $email; ?>">
                    <label for="surname">Email address</label>
                </div>
                <div class="col-md-6">
                    <br>
                        <input style="display: none" type="text" name="disc" id="disc" value="<?php echo $discount; ?>" class="form-control input-sm">
                        Does the doctor give a discount?
                    <br>
                    <label>
                    <input type="radio" name="discount" id="yes" class="uk-radio" value="Yes" onclick="hid()">
                       <span>Yes</span>
                        </label>
                    <label>
                    <input type="radio" name="discount" id="no" class="uk-radio" value="No" onclick="hid()">
                            <span>No</span>
                        <label>
                </div>

            </div>

            <div id="hhid" style="display: none;">
            <div class="row">
                <div class="col-md-6">
                    <label><input type="checkbox" class="uk-checkbox" name="signed" id="signed" onclick="show123()" <?php echo $signed;?>> <span>Signed Contract?</span></label>
                </div>

                <div class="col-md-6" style="display: <?php echo $chck_signed;?>" id="mmd">
                    <input type="date" class="uk-input" name="date_joined" id="date_joined" value="<?php echo $date_joined;?>">
                    <label>Date Joined</label>
                </div>

            </div>
                <div class="row" style="background-color: whitesmoke; padding: 10px">
                    <div class="col-md-5">
                        <span style="color: #0c85d0">Discount</span> (%)
                        <label><input type="radio" name="discount_v" class="uk-radio" value="P" onclick="hi12()" checked> <span>Yes</span></label> / <label><input type="radio" name="discount_v" class="uk-radio" value="R" onclick="hi12()"><span>No</span></label> (Value)

                        <select name="discount_perc" id="discount_perc">
                            <option value="">select</option>
                            <option value="5">5%</option>
                            <option value="10">10%</option>
                            <option value="15">15%</option>
                            <option value="20">20%</option>
                        </select>
                        <input type="number" class="uk-input" name="discount_value" id="discount_value" placeholder="R" style="display: none">

                        <input type="hidden" id="dr_value" name="dr_value">
                    </div>

                    <div class="col-md-4" style="display: <?php echo $chck_signed;?>" id="mmd">
                        <b>Number of Days</b>
                        <select id="days_number" name="days_number" uk-tooltip="title: Days less than : ; pos: top-right">
                            <option value="">select</option>
                            <option value="< 30">< 30</option>
                            <option value="30 - 39">30 - 39</option>
                            <option value="40 - 59">40 - 59</option>
                            <option value="60 - 100">60 - 100</option>

                        </select>

                    </div>
                    <div class="col-md-3">
                        <span class="uk-margin-small-right uk-icon-button" style="cursor: pointer; color: #0c85d0" uk-tooltip="title: Add Details ; pos: top-right" uk-icon="check" onclick="add()"></span>
                    </div>
                </div>


                    <div class="uk-text-success" id="txt">
                        <?php
                        foreach ($control->viewDoctorDiscount($practice_number) as $row)
                        {
                            $discount_id=$row["id"];
                            $hhid="x".$discount_id;
                            $mesg=$row["dr_value"]=="P"?"<br><span uk-icon=\"close\" style='color: red; cursor: pointer' onclick='del($discount_id)'></span> <span uk-icon=\"check\" id='$hhid'></span> ".$row["discount_perc"]."% discount if the claim is ".$row["days_number"]." days":"<br><span uk-icon=\"close\" style='color: red; cursor: pointer'></span> <span uk-icon=\"check\"></span> R ".$row["discount_value"]." discount if the claim is ".$row["days_number"]." days";
                            echo $mesg;
                        }
                        ?>
                    </div>

    </div>

            <hr>
            <div class="row">
                <div class="col-md-6">
                    <button type="submit" class="uk-button uk-button-primary" name="edit_doctor_post">Save Changes</button>

                </div>

            </div>
            <p><b><u>Notes</u></b></p>
            <div class="row uk-card uk-card-default uk-card-body">
                <div class="col-md-12">
                    <span id="artsec">
                        <?php
                        foreach ($control->viewDoctorNotes($doctor_id) as $row)
                        {
                            $notes=$row["description"];
                            $mytime=$row["date_entered"];
                            $author=$row["entered_by"];
                            echo "      <article class=\"uk-comment\">
                        <header class=\"uk-comment-header\">
                            <div class=\"uk-grid-medium uk-flex-middle\" uk-grid>
                                <div class=\"uk-width-expand\">
                                  
                                    <ul class=\"uk-comment-meta uk-subnav uk-subnav-divider uk-margin-remove-top\">
                                        <li><a href=\"#\" id=\"mytime\">$mytime</a></li>
                                        <li><a href=\"#\">$author</a></li>
                                    </ul>
                                </div>
                            </div>
                        </header>
                        <div class=\"uk-comment-body\">
                            <p>$notes</p>
                        </div>
                    </article><hr>";
                        }
                        ?>

                        </span>
                    <textarea class="uk-textarea" style="width: 100%" id="mynotes"></textarea>

                    <br><br>

                    <span class="uk-button uk-button-primary" onclick="addNote('<?php echo $username;?>')">Post</span>
                </div>
            </div>

            <?php
$doctor_log_count=count($control->viewDoctorLogs($practice_number));
            $color=$doctor_log_count>0?"color: #0c85d0":"color: red";
            $stmtnt=$doctor_log_count>0?"View Transaction":"No Transaction";;
            ?>
            <div class="row">
                <div class="col-xs-12 ali">
                    <ul class="uk-nav-default uk-nav-parent-icon ali" uk-nav>
                        <li class="uk-parent ali">
                            <a href="#" style="<?php echo $color;?>"><span style="<?php echo $color;?>" class="uk-icon-button" uk-icon="icon:  history"></span> <?php echo $stmtnt;?></a>
                            <ul class="uk-nav-sub">
                                <div class="uk-card uk-card-default uk-card-body">
                                    <table class="uk-table">
                                        <thead>
                                        <tr>
                                            <th>Give Discount</th>
                                            <th>Changed By</th>
                                            <th>State Date</th>
                                            <th>End Date</th>
                                        </tr>
                                        <?php
                                        $count=0;
                                        $aarx=array("discount"=>$discount,"changed_by"=>$entered_by,"end_date"=>"to date");
                                        $arr=array();
                                        array_push($arr,$aarx);
                                        foreach ($control->viewDoctorLogs($practice_number) as $row1)
                                        {
                                            if($discount!=$row1[1])
                                            {
                                                $count++;
                                                $dics=$row1[1];$updated_date=$row1[2];$changed_by=$row1[3];
                                                $myarr=array("discount"=>$dics,"changed_by"=>$changed_by,"end_date"=>$updated_date);
                                                array_push($arr,$myarr);

                                            }
                                            $discount=$row1[1];
                                        }
                                        if($count==0)
                                        {
                                            echo "<h5 align='center' style='color: red'>No changes made</h5>";
                                        }
                                        else
                                        {
                                            $cco=count($arr);
                                            for($i=0;$i<$cco;$i++)
                                            {
                                                $dics=$arr[$i]["discount"];
                                                $start_date=$i==$cco-1?"------":$arr[$i+1]["end_date"];
                                                $end_date=$arr[$i]["end_date"];
                                                $changed_by=$arr[$i+1]["changed_by"];
                                                echo "<tr class='uk-text'><td>$dics</td><td>$changed_by</td><td>$start_date</td><td>$end_date</td></tr>";
                                            }
                                        }
                                        ?>
                                        </thead>
                                    </table>
                                </div>
                            </ul>
                        </li>
                    </ul>
                </div>

            </div>
    </div>
</div>
</form>

</div>
<?php
include "footer.php";
?>
<script>
    $(document).ready(function() {
        $('select').formSelect();
    } );
</script>
<script type="text/javascript">
    var txt=document.getElementById("disc").value;
    if(txt=="Yes")
    {
        document.getElementById("yes").checked = true;
        document.getElementById('hhid').style.display='block';

    }
    else if(txt=="No")
    {
        document.getElementById("no").checked = true;
        document.getElementById('hhid').style.display='none';
    }
    else
    {

        //document.getElementById("no").checked = true;
        document.getElementById('hhid').style.display='none';
    }
</script>
</body>
</html>