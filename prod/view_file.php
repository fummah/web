<?php
session_start();
define("access",true);
include ("classes/controls.php");
$control=new controls();
if (isset($_POST['doc'])) {
    $name =  $_POST['my_doc'];
    if(!isset($_POST['my_doc']))
    {
        die("Invalid access");
    }
    $my_id=(int)$_POST['my_id'];
    $control->callUpdateDocumentsKey($my_id,"additional_doc",0);
    $str=explode('.',$name);
    $count=count($str);
    $ext=$str[$count-1];
    $arr=["jpg","JPG","png","PNG"];
    if($ext=="PDF" || $ext=="pdf") {
        $fp = fopen($name, 'rb');
        header("Content-Type: application/pdf");
        header("Content-Length: " . filesize($name));
        fpassthru($fp);
    }
    else if($ext=="PNG" || $ext=="png")
    {
        $im = imagecreatefrompng($name);

        header('Content-Type: image/png');

        imagepng($im);
        imagedestroy($im);
    }
    else if($ext=="jpeg" || $ext=="JPEG" || $ext=="jpg" || $ext=="JPG")
    {
        $im = imagecreatefromjpeg($name);
        header('Content-Type: image/jpeg');


        imagejpeg($im);

        imagedestroy($im);
    }
    else if($ext=="doc" || $ext=="docx" || $ext=="DOC" || $ext=="DOCX")
    {
        $file = $name;
        $myName="download.docx";

        ob_clean();
        header("Cache-Control: no-store");
        header("Expires: 0");
        header("Content-Type: application/msword");
        header("Cache-Control: public");
        header('Content-Disposition: inline; filename="'.$myName.'"');
        header("Content-Transfer-Encoding: binary");
        header('Accept-Ranges: bytes');
        readfile($file);
    }
    else if($ext=="xls" || $ext=="xlsx")
    {
        $file = $name;
        $myName="download.xlsx";

        ob_clean();
        header("Cache-Control: no-store");
        header("Expires: 0");
        header("Content-Type: application/vnd.ms-excel");
        header("Cache-Control: public");
        header('Content-Disposition: inline; filename="'.$myName.'"');
        header("Content-Transfer-Encoding: binary");
        header('Accept-Ranges: bytes');
        readfile($file);
    }
    else if($ext=="msg" || $ext=="MSG" || $ext=="eml" || $ext=="EML")
    {
        //header("Content-Type: text/Calendar");
        //header('Content-Disposition: inline; filename='. basename($name));
        // echo base64_decode($name);

        header('Content-Type: Content-Type: octet-stream');

        header('Content-Disposition: attachment; filename='.$name);

        readfile($name);


    }
    else if($ext=="zip")
    {
        $archive_file_name=$name;
        header("Content-type: application/zip");
        header("Content-Disposition: attachment; filename=$archive_file_name");
        header("Content-length: " . filesize($archive_file_name));
        header("Pragma: no-cache");
        header("Expires: 0");
        readfile("$archive_file_name");
    }
    else
    {
        echo "The browser cannot open this file please contact system adminstrator";
    }
} else {
    echo "There is an error";
}

?>