<?php
session_start();
define("access",true);
//error_reporting(0);
include ("classes/controls.php");
include ("templates/claim_templates.php");
$control=new controls();
include("header.php");

?>
<html>
<head>
    <title>MCA | PMB Code</title>
    <script src="js/claim_loading_js.js"></script>
</head>
<body>
<br><br>
<div style="margin-left: auto; margin-right: auto; width: 50%; position: relative">
<div class="uk-margin">
    Enter ICD10 Code
    <input class="uk-input uk-form-width-large" type="text" id="icd10" placeholder="ICD10 Code">
</div>
    <p uk-margin>
        <button class="uk-button uk-button-primary uk-button-small" style="background-color: #54bf99;" onclick="Codes()"><span uk-icon="git-branch"></span> Submit</button>
    </p
</div>
<span id="show_info"></span>
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