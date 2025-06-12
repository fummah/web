<?php
if (isset($_FILES["fileAjax"])) {
    $currentDir = getcwd();
    $uploadDirectory = "/temp_upload/";

// Define available file extensions
    $fileExtensions = ['jpeg', 'jpg', 'png', 'gif', 'PNG'];
    $allowedExts = ['jpeg', 'jpg', 'png', "pdf", "doc", "docx", "xlsx", "xls", "txt", "PDF", "PNG", "msg", "MSG", "eml", "EML", "zip", "ZIP", "rar", "RAR", "x-zip-compressed", "X-ZIP-COMPRESSED"];
    $fileExtensions = ['jpeg', 'jpg', 'png', "pdf", "vnd.openxmlformats-officedocument.spreadsheetml.sheet","xlsx", "vnd.openxmlformats-officedocument.wordprocessingml.document", "vnd.ms-excel", "msword", "vnd.oasis.opendocument.text", "application/pdf", "PDF", "PNG", "msg", "MSG", "octet-stream", "eml", "EML", "application/octet-stream", "message/rfc822", "rfc822", "application/x-zip-compressed", "x-zip-compressed", "X-ZIP-COMPRESSED"];

//if(!empty($_POST['fileAjax']) || $_FILES['image']) {
    $fileName = $_FILES['fileAjax']['name'];
    $fileTmpName = $_FILES['fileAjax']['tmp_name'];
    $fileType = $_FILES['fileAjax']['type'];
    $fileSize=$_FILES['fileAjax']["size"];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $nux = substr_count($fileName, '.');

    if (in_array($fileExtension, $allowedExts) && strlen($fileName) < 100 && $nux == 1 && $fileSize > 0) {
        if (in_array($fileExtension, $fileExtensions) && ($fileSize < 20000000)) {
            $uploadPath = $currentDir . $uploadDirectory . basename($fileName);

//echo $uploadPath;

            if (isset($fileName)) {
                $didUpload = move_uploaded_file($fileTmpName, $uploadPath);
                if ($didUpload) {
                    echo "<span class='uk-text-success'><span uk-icon=\"check\"></span>".basename($fileName) . " has been uploaded.</span>";
                } else {
                    echo "<span class='uk-text-danger'><span uk-icon=\"close\"></span>".basename($fileName)." has an error occurred while uploading. Try again.</span>";
                }
            } else {
                echo "Invalid Request";
            }
        }

        else{
            echo "<span class='uk-text-danger'><span uk-icon=\"close\"></span> $fileName is an invalid file, check your file</span>";
        }
    }
    else
    {
        echo "<span class='uk-text-danger'><span uk-icon=\"close\"></span> $fileName is an invalid file</span>";
    }
}

?>