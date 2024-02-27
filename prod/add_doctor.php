<?php
session_start();
error_reporting(0);
define("access",true);
require "classes/controls.php";
$control=new controls();
if(!$control->isInternal())
{
    die("Invalid access");
}
include ("header.php");
?>
<html>
<head>
    <title>MCA | Add New Doctor</title>

    <script>
        var doctor_details = [];
        function hid()
        {
            var nna="No";
            var radios = document.getElementsByName('discount');
            for (var i = 0, length = radios.length; i < length; i++) {
                if (radios[i].checked) {
                    // do whatever you want with the checked radio
                    nna=(radios[i].value);

                    // only one radio can be logically checked, don't check the rest
                    break;
                }
            }

            if(nna=="Yes")
            {
                document.getElementById('hhid').style.display='block';

            }
            else
            {
                document.getElementById('hhid').style.display='none';
            }

        }

        function hi12()
        {

            var nna="R";
            var radios = document.getElementsByName('discount_v');
            for (var i = 0, length = radios.length; i < length; i++) {
                if (radios[i].checked) {
                    // do whatever you want with the checked radio
                    nna = (radios[i].value);

                    // only one radio can be logically checked, don't check the rest
                    break;
                }
            }

            if(nna=="P")
            {
                document.getElementById('discount_perc').style.display='block';
                document.getElementById('discount_value').style.display='none';

            }
            else
            {
                document.getElementById('discount_value').style.display='block';
                document.getElementById('discount_perc').style.display='none';

            }

        }
        function show123()
        {
            //alert("Alert");


            if(document.getElementById('signed').checked==true)
            {
                document.getElementById('mmd').style.display='block';
            }
            else
            {
                document.getElementById('mmd').style.display='none';
            }

        }

        function add()
        {
            var element = {};
            var vl=$("#discount_perc").val();
            var vl1=$("#discount_value").val();
            var days_number=$("#days_number").val();
            var nna="R";
            var mesg="";
            var radios = document.getElementsByName('discount_v');
            for (var i = 0, length = radios.length; i < length; i++) {
                if (radios[i].checked) {
                    // do whatever you want with the checked radio
                    nna = (radios[i].value);

                    // only one radio can be logically checked, don't check the rest
                    break;
                }
            }

            element.main_value =nna;
            element.discount_perc = vl;
            element.discount_value = vl1;
            element.days_number = days_number;

            var count=0;
            for (var key in doctor_details) {
                if(days_number==doctor_details[key].days_number)
                {
                    count=1;
                }
            }
            if(nna=="P")
            {
                if(count<1 && vl.length>0)
                {
                    doctor_details.push(element);
                    mesg="<br><span uk-icon=\"check\"></span> "+vl+"% discount if the claim is "+days_number+" days";
                }


            }
            else
            {
                if(count<1 && vl1.length>0)
                {
                    doctor_details.push(element);
                    mesg="<br><span uk-icon=\"check\"></span> R "+vl1+" discount if the claim is "+days_number+" days";
                }

            }

            var json = JSON.stringify(doctor_details);
            $("#dr_value").val(json);
            $("#txt").append(mesg);
        }



    </script>

    <style type="text/css">

        input[type=text],input[type=date],input[type=number]{
            width:250px;

        }
        input[type=email]{
            width:250px;
        }
        #ali{
            margin: auto;
            width: 60%;
            padding: 10px;

        }
        .uk-select{
            width: 250px;
        }
    </style>
</head>

<body>

<div id="ali">
    <h3 align="center"><u>Add the doctor's details below</u></h3>
    <form  name="form" action = "save_doctor.php" method="post">
        <div class="container">
            <div class="row">
                <div class="col-md-6"><input type="text" name="name" placeholder="First Name or Initials" class="uk-input" REQUIRED>
                    <label for="name">First Name or Initials</label>
                </div>
                <div class="col-md-6">
                    <input type="text" name="surname" class="uk-input" placeholder="Surname" REQUIRED>
                <label for="surname">Surname</label>
            </div>
            </div>
            <div class="row">
                <div class="col-md-6"><br>
                    <label for="practice_code">Discipline</label>
                    <?php

                    echo "<select id=\"displine_type\" name=\"displine_type\">";
                    echo "<option value=''>[select]</option>";
                    foreach ($control->viewDisciplinecodes() as $row)
                    {
                        echo "<option value=\"$row[0]\">$row[1] --- $row[2] --- $row[3] --- $row[4]</option>";
                    }
                    echo "</select>";
                    ?>

                </div>
                <div class="col-md-6">
                    <input type="text" name="practice_number" placeholder="The doctor's practice code/number" class="" REQUIRED>

                    <label for="displine_type">The doctor's practice code/number</label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <input type="text" name="receptionist" placeholder="Admin person" class="uk-input">
                    <label for="receptionist">Admin person or receptionist's name</label>
                </div>
                <div class="col-md-6">
                    <input type="text" name="telephone" placeholder="Telephone" class="uk-input">
                    <label for="telephone">Telephone</label>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <input type="email" name="email" placeholder="Email address" class="uk-input">
                    <label for="telephone">Email address</label>
                </div>
                <div class="col-md-6">Does the doctor give a discount?
                    <br>
                    <label>
                        <input type="radio" name="discount" class="uk-radio" value="Yes" onclick="hid()">
                        <span>Yes</span>
                    </label>
                    <label>
                        <input type="radio" name="discount" class="uk-radio" value="No" onclick="hid()">
                        <span>No</span>
                        <label>
                </div>

            </div>
            <span id="hhid" style="display: none;">
            <div class="row" >
                <hr>
                <div class="col-md-2">

                </div>
                 <div class="col-md-4">
                      <label><input type="checkbox" class="uk-checkbox" name="signed" id="signed" onclick="show123()"> <span>Signed Contract?</span></label>

                </div>
                <span style="display: none" id="mmd">
                <div class="col-md-3">
                  <input type="date" class="uk-input" name="date_joined" id="date_joined">
                    <label>Date Joined</label>
                </div>
                <div class="col-md-3">

                </div>
                </span>
            </div>
                <div class="row uk-card uk-card-default uk-card-body" >
     <div class="col-md-6">
          <b> <span style="color: #0c85d0">Discount</span> (%) <input type="radio" name="discount_v" class="uk-radio" value="P" onclick="hi12()" checked> / <input type="radio" name="discount_v" class="uk-radio" value="R" onclick="hi12()"> (Value)</b><br>

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
                <div class="col-md-3">

                    <b>Number of Days</b><br>
                    <select id="days_number" name="days_number" uk-tooltip="title: Days less than : ; pos: top-right">
                        <option value="">select</option>
                        <option value="< 30">< 30</option>
                        <option value="30 - 39">30 - 39</option>
                        <option value="40 - 59">40 - 59</option>
                        <option value="60 - 100">60 - 100</option>

                    </select>
                </div>
                    <div class="col-md-3"><br>
<span class="uk-margin-small-right uk-icon-button" style="cursor: pointer; color: #0c85d0" uk-tooltip="title: Add Details ; pos: top-right" uk-icon="check" onclick="add()"></span>
                    </div>

            </div>

                    <div class="uk-text-success" id="txt"> </div>

    </span>

            <div class="row">
                <div class="col-md-6">
                    <button type="submit" name="add_doctor" class="uk-button uk-button-primary">Add Doctor</button>
                </div>
                <div class="col-md-6"> <button type="reset" name="reset" class="uk-button uk-button-danger">Reset</button></div>
            </div>
        </div>


    </form>
</div>

<hr>

</body>
</html>
<?php
include "footer.php";
?>
<script>
    $(document).ready(function() {
        $('select').formSelect();
    } );
</script>
