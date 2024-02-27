<?php
session_start();
require_once "dbconn.php";
$conn=connection("mca","MCA_admin");
function addFiles($lead_id,$file_type,$file_size,$file_name,$random)
{
    global $conn;
    try {
        $stmt = $conn->prepare('INSERT INTO `leads_file`(lead_id,file_type,file_name,file_size,random) VALUES(:lead_id,:file_type,:file_name,:file_size,:random)');
        $stmt->bindParam(':lead_id', $lead_id, PDO::PARAM_STR);
        $stmt->bindParam(':file_type', $file_type, PDO::PARAM_STR);
        $stmt->bindParam(':file_name', $file_name, PDO::PARAM_STR);
        $stmt->bindParam(':file_size', $file_size, PDO::PARAM_STR);
        $stmt->bindParam(':random', $random, PDO::PARAM_STR);
        $stmt->execute();
    }
    catch (Exception $e)
    {
        echo "There is an error : ".$e->getMessage();
    }
}
if (isset($_FILES["fileAjax"])) {
    //$currentDir = getcwd();
    

// Define available file extensions
    $fileExtensions = ['jpeg', 'jpg', 'png', 'gif', 'PNG'];
    $allowedExts = ['jpeg', 'jpg', 'png', "pdf", "doc", "docx", "xlsx", "xls", "txt", "PDF", "PNG", "msg", "MSG", "eml", "EML", "zip", "ZIP", "rar", "RAR", "x-zip-compressed", "X-ZIP-COMPRESSED"];
    $fileExtensions = ['jpeg', 'jpg', 'png', "pdf", "vnd.openxmlformats-officedocument.spreadsheetml.sheet","xlsx", "vnd.openxmlformats-officedocument.wordprocessingml.document", "vnd.ms-excel", "msword", "vnd.oasis.opendocument.text", "application/pdf", "PDF", "PNG", "msg", "MSG", "octet-stream", "eml", "EML", "application/octet-stream", "message/rfc822", "rfc822", "application/x-zip-compressed", "x-zip-compressed", "X-ZIP-COMPRESSED"];
    $uploadDirectory = "../../mca/leads/";
//if(!empty($_POST['fileAjax']) || $_FILES['image']) {
    $fileName = $_FILES['fileAjax']['name'];
    $fileTmpName = $_FILES['fileAjax']['tmp_name'];
    $fileType = $_FILES['fileAjax']['type'];
    $fileSize=$_FILES['fileAjax']["size"];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $nux = substr_count($fileName, '.');

    if (in_array($fileExtension, $allowedExts) && strlen($fileName) < 100 && $nux == 1 && $fileSize > 0) {
        if (in_array($fileExtension, $fileExtensions) && ($fileSize < 20000000)) {
            $random= rand(0, 1000);
            $uploadPath = $uploadDirectory .$random. basename($fileName);



            if (isset($fileName)) {
                $didUpload = move_uploaded_file($fileTmpName, $uploadPath);
                if ($didUpload) {
                    $lead_id=$_SESSION["sess_lead"];
                    addFiles($lead_id,$fileType,$fileSize,$fileName,$random);
                    echo basename($fileName) . " has been uploaded.";
                } else {
                    echo basename($fileName)." has an error occurred while uploading. Try again.";
                }
            } else {
                echo "Invalid Request";
            }
        }

        else{
            echo " $fileName is an invalid file, check your file";
        }
    }
    else
    {
        echo " $fileName is an invalid file";
    }
}

?>