<?php
session_start();
define("access",true);
include ("classes/controls.php");
include ("templates/claim_templates.php");
$control=new controls();
if (!$control->isInternal())
{
    die("Invalid entry");
}
$up_id = uniqid();
if(!isset($_SESSION['docClaimID']) || empty($_SESSION['docClaimID']))
{
    die("Invalid access");
}
?>
<style>
    table tr:nth-child(even) {
        background-color: #eee;
    }
    table tr:nth-child(odd) {
        background-color: #fff;
    }
    .linkButton {
        background: none;
        border: none;
        color: #0066ff;
        text-decoration: underline;
        cursor: pointer;

    }
</style>
<?php
$claim_id=(int)$_SESSION['docClaimID'];
$control->claim_id=$claim_id;
if (isset($_FILES["file"]) && is_file($_FILES['file']['tmp_name']))  {
    $path = "../../mca/documents/";
    $random_number = rand(0, 1000);
    uploadFiles($_FILES["file"],$_FILES['file']['tmp_name'],$_FILES['file']['name'],$_FILES['file']['type'],$_FILES['file']['size'],$path,$random_number,$control,$control->loggedAs());
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>MCA | File Upload</title>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js" integrity="" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js" integrity="" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/uikit.min.css" />
    <script src="js/uikit.min.js"></script>
    <script src="js/uikit-icons.min.js"></script>
</head>

<body>
<h5 class="uk-card-title">Upload File</h5>

<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
    <div class="uk-margin" uk-margin>
        <div uk-form-custom="target: true">
            <input name="file" type="file" id="file" size="30" REQUIRED/>
            <input class="uk-input uk-form-width-medium" type="text" placeholder="Select file" disabled>
        </div>
        <input name="Submit" type="submit" id="submit" class="uk-button uk-button-primary uk-button-small" value="Upload" />
    </div>

</form>
<?php
$doc_arr=$control->viewDocuments($control->claim_id);
displayDocuments($doc_arr);
?>
</body>

</html>
