<?php
session_start();
define("access",true);
error_reporting(0);
include ("classes/controls.php");
$control=new controls();
if (!$control->isInternal())
{
    die("Invalid entry");
}
include("header.php");
?>
<style>
    .main{
        width: 80%;
        position: relative;
        margin-left: auto;
        margin-right: auto;
    }
    .row{
        padding: 10px;
    }
    .star{
        color: red;
    }

</style>
<title>MCA | New Issue</title>

<link rel="stylesheet" href="css/simplePagination.css" />
<script src="js/jquery.simplePagination.js"></script>
<link href="css/w3.css" rel="stylesheet" />
<?php


echo "<br/>";
?>
<div class="main">
    <?php
    require ("ticket_header.php");
    ?>

    <div class="uk-card uk-card-body">
        <h3 style="margin-left: 10px"><u>New Issue</u></h3>
        <br>
        <form method="post" action="issues.php" enctype="multipart/form-data">
            <div class="container w3-border w3-large w3-animate-zoom">
                <div class="row">
                    <div class="col-sm-2">
                        Tracker <span class="star">*</span>
                    </div>
                    <div class="col-sm-4">
                        <select name="tracker">
                            <option value="Bug">Bug</option>
                            <option value="Enhancement">Enhancement</option>
                            <option value="Support and Maintenance">Support and Maintenance</option>
                        </select>


                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        Environment <span class="star">*</span>
                    </div>
                    <div class="col-sm-4">
                        <select name="environment">
                            <option value="MCA Production">MCA Production</option>
                            <option value="MCA UAT">MCA UAT</option>
                            <option value="Jarvis production">Jarvis production</option>
                            <option value="Jarvis UAT">Jarvis UAT</option>
                        </select>

                    </div>
                    <div class="col-sm-2">
                        Related To <span class="star">*</span>
                    </div>
                    <div class="col-sm-4">
                        <select name="related_to">
                            <option value="Claims">Claims</option>
                            <option value="Provider">Provider</option>
                            <option value="Documents">Documents</option>
                            <option value="AAA">AAA</option>
                       
                        </select>

                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        Subject <span class="star">*</span>
                    </div>
                    <div class="col-sm-10">
                        <input type="text" class="w3-input w3-small" name="subject" style="width: 100%" REQUIRED>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-2">
                        Description <span class="star">*</span>
                    </div>
                    <div class="col-sm-10">
                        <textarea class="w3-input w3-small"  cols="100"  style="width: 100%" id="issue_description" name="issue_description" rows="10"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        Status <span class="star">*</span>
                    </div>
                    <div class="col-sm-10">
                        <select name="status" style="width: 50%;">
                            <option value="New">New</option>


                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        Priority <span class="star">*</span>
                    </div>
                    <div class="col-sm-10">
                        <select name="priority" style="width: 50%;">
                            <option value="Normal">Normal</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                            <option value="Immediate">Immediate</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-2">
                        Assignee <span class="star">*</span>
                    </div>
                    <div class="col-sm-10">
                        <select name="assignee" style="width: 50%;" required>
                            <option value="Naomi">Naomi</option>
                            <?php
                            if($_SESSION['level'] != "claims_specialist")
                            {
                                echo "<option value=\"Mandy\">Mandy</option>";
                                echo "<option value=\"Faghry\">Faghry</option>";
                                echo "<option value=\"Tendai\">Tendai</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        Files
                    </div>
                    <div class="col-sm-10">
                        <input type="file" name="file" id="file">
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-2">

                    </div>
                    <div class="col-sm-10">
                        <button class="w3-btn w3-white w3-border w3-border-blue w3-round-large" name="btn">Create and Continue</button>
                    </div>
                </div>
            </div>
        </form>
    </div></div>
<?php
include "footer.php";
?>
<script>
    $(document).ready(function() {
        $('select').formSelect();
    } );
</script>
