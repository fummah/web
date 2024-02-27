<?php
session_start();
error_reporting(0);
?>
<style>
    .main{
        width: 80%;
        position: relative;
        margin-left: auto;
        margin-right: auto;
        background-color: #d3d9df;
    }
    .row{
        padding: 10px;
    }
    .star{
        color: red;
    }

</style>
<title>MCA : New Issue</title>

<link rel="stylesheet" href="bootstrap3/css/bootstrap.min.css">
<script src="jquery/jquery.min.js"></script>
<script src="js/jquery-1.12.4.js"></script>
<script src="bootstrap3/js/bootstrap.min.js"></script>

<link rel="stylesheet" href="js/simplePagination.css" />
<script src="js/jquery.simplePagination.js"></script>
<link href="w3/w3.css" rel="stylesheet" />
<?php
include("header.php");

echo "<br/><br/><br/>";
?>
<div class="main">
    <?php
    require ("ticket_header.php");
    ?>

    <div class="w3-container w3-card">
        <h3 style="margin-left: 10px"><u>New Issue</u></h3>
        <br>
        <form method="post" action="issues.php" enctype="multipart/form-data">
            <div class="container w3-border w3-large w3-animate-zoom">
                <div class="row">
                    <div class="col-sm-2">
                        Tracker <span class="star">*</span>
                    </div>
                    <div class="col-sm-4">
                        <select name="tracker" class="w3-input w3-small">
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
                        <select name="environment" class="w3-input w3-small" >
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
                        <select name="related_to" class="w3-input w3-small">
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
                        <select name="status" class="w3-input w3-small" style="width: 50%;">
                            <option value="New">New</option>


                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        Priority <span class="star">*</span>
                    </div>
                    <div class="col-sm-10">
                        <select name="priority" class="w3-input w3-small" style="width: 50%;">
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
                        <select name="assignee" class="w3-input w3-small" style="width: 50%;" required>
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
include_once "footer.php";
?>
