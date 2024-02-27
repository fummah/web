<?php
session_start();
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

include("dbconn.php");
//get unique id
$up_id = uniqid();
if(!isset($_SESSION['docClaimID']) || empty($_SESSION['docClaimID']))
{
    die("The is an error");
}
if(!isset($_SESSION['user_id']) || empty(['user_id']))
{
    die("The is an error");
}
function DBaddfiles($description,$size,$type,$rand)
{
    $username=$_SESSION['user_id'];
    $claim_id=$_SESSION['docClaimID'];
    $conn=connection("mca","MCA_admin");
    $sql = $conn->prepare('INSERT INTO documents(claim_id,doc_description,doc_size,doc_type,randomNum,uploaded_by) VALUES(:claim,:description,:size,:type,:rand,:uploaded_by)');
    $sql->bindParam(':claim', $claim_id, PDO::PARAM_STR);
    $sql->bindParam(':description', $description, PDO::PARAM_STR);
    $sql->bindParam(':size', $size, PDO::PARAM_STR);
    $sql->bindParam(':type', $type, PDO::PARAM_STR);
    $sql->bindParam(':rand', $rand, PDO::PARAM_STR);
    $sql->bindParam(':uploaded_by', $username, PDO::PARAM_STR);
    $sql->execute();

}
function displayDoc()
{
    $claim_id = $_SESSION['docClaimID'];
    $conn = connection("mca", "MCA_admin");
    $sql = $conn->prepare('SELECT *FROM documents WHERE claim_id=:claim');
    $sql->bindParam(':claim', $claim_id, PDO::PARAM_STR);
    $sql->execute();
    $nu = $sql->rowCount();

    if ($nu > 0) {
        echo"<table border='0' width='100%'><tr style='background-color:cadetblue'><th>File Name</th><th>Size</th></tr>";
        foreach ($sql->fetchAll() as $row) {
            $id = $row[0];
            $ra=$row[6];
            $nname = $row[2];
            $desc="../../mca/documents/".$ra.$nname;
            $type = $row[3];
            $size = round($row[4]/1024);
            echo"<tr><td>
<form action='test5.php' method='post' target='_blank'/><input type=\"hidden\" name=\"my_doc\" value=\"$desc\" />
<input type=\"submit\" class=\"linkbutton\" name=\"doc\" value=\"$nname\">
</form>
 </td><td>$size<b style='color:green'>KB</b></td></tr>";
        }
        echo"</table>";
    }
}
//process the forms and upload the files
if (isset($_FILES["file"]) && is_file($_FILES['file']['tmp_name']))  {

    $allowedExts= ['jpeg','jpg','png',"pdf","doc","docx","xlsx","xls","txt","PDF","PNG","msg","MSG","eml","EML","zip","ZIP","TIF","tif","tiff","TIFF"];
    $fileExtensions = ['jpeg','jpg','png',"pdf",'TIF','tif',"vnd.openxmlformats-officedocument.spreadsheetml.sheet","vnd.openxmlformats-officedocument.wordprocessingml.document","vnd.ms-excel","msword","vnd.oasis.opendocument.text","application/pdf","PDF","PNG","msg","MSG","octet-stream","eml","EML","application/octet-stream","message/rfc822","rfc822","x-zip-compressed"];
    $temp = explode(".", $_FILES["file"]["name"]);
    $presentExtention = end($temp);
    $type = basename($_FILES['file']['type']);
    $nname = basename($_FILES['file']['name']);
    $fileSize = $_FILES['file']['size'];
    $fileExtension = basename($_FILES['file']['type']);
    $nux=substr_count($nname, '.');
    if(in_array($presentExtention,$allowedExts) && strlen($nname)<100 && $nux==1 && $fileSize >0) {
        if (in_array($fileExtension, $fileExtensions) && ($fileSize < 20000000)) {
            $ra = rand(0, 1000);
            $target = "../../mca/documents/";
            $target = $target . $ra . basename($_FILES['file']['name']);
            $ok = 1;
            if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
                $redirect = "success";
                $size = basename($_FILES['file']['size']);
                $nname=filter_var($nname, FILTER_SANITIZE_STRING);
                DBaddfiles($nname, $size, $type, $ra);
                echo "<span class=\"notice\" style=\"color: green\">Your file has been uploaded.</span>";


            } else {
                echo "<span class=\"notice\" style=\"color: red\">Sorry, Failed to upload.</span>";
            }


        } else {
            echo "<span class=\"notice\" style=\"color: red\">Sorry, incorrect file, failed to upload( $fileExtension )</span>";
        }
    }
    else {
        echo "<span class=\"notice\" style=\"color: red\">Sorry, incorrect file, failed to upload_$nname ($fileExtension)</span>";
    }

}

//

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Upload your file</title>

    <!--Progress Bar and iframe Styling-->
    <link href="style_progress.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="bootstrap3/css/bootstrap.min.css">
    <script src="jquery/jquery.min.js"></script>
    <script src="bootstrap3/js/bootstrap.min.js"></script>
    <script src="jquery/jquery.js"></script>
    <link href="w3/w3.css" rel="stylesheet" />

    <script src="js/newCase.js"></script>
    <link rel="stylesheet" type="text/css" href="css/newCase.css">


</head>

<body>

<fieldset class="alert-info">
    <legend style="color: #5c2699">Upload File</legend>

    <form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">

        <input name="file" type="file" id="file" size="30" REQUIRED/>
        <br />


        <p><input name="Submit" type="submit" id="submit" class="btn btn-info" value="Submit" /></p>
    </form>

</fieldset>
<?php displayDoc(); ?>
</body>

</html>
                        