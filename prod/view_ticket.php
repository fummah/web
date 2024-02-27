<?php
session_start();
define("access",true);
if(isset($_SESSION['logxged']) && !empty($_SESSION['logxged'])) {
    if (isset($_POST['doc'])) {
        $name =  $_POST['my_doc'];

        if(!isset($_POST['my_doc']))
        {
die();
        }
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
else if($ext=="xlsx" || $ext=="xls" || $ext=="XLSX" || $ext=="XLS")
        {
            $file = $name;
            $myName="download.xlsx";

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename='.basename($file));
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($file));
readfile($file);
        }
        else if($ext=="msg" || $ext=="MSG" || $ext=="eml" || $ext=="EML")
        {
            header("Content-Type: text/Calendar");
            header('Content-Disposition: inline; filename='. basename($name));
            echo base64_decode($name);
        }
        else
        {
            echo "The browser cannot open this file please contact system adminstrator";
        }
    } else {
        echo "There is an error";
    }
}
else{
    echo "Invalid Access";
}
?>
<?php
include "footer.php";
?>
<script>
    $(document).ready(function() {
        $('select').formSelect();
    } );
</script>
