<?php
session_start();
$_SESSION['start_db']=true;
require "dbconn.php";
include "classes/ticketClass.php";
$new=new ticketClass();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_FILES['files'])) {
        $errors = [];
        $path = '../../mca/tickets/';
//$path = 'classes/';
        $extensions = ['jpg', 'jpeg', 'png', 'gif','JPEG','JPG','PDF','pdf','doc','docx','xlsx','xls','txt','PDF','PNG','msg','MSG'];

        $all_files = count($_FILES['files']['tmp_name']);

        for ($i = 0; $i < $all_files; $i++) {
            $file_name = $_FILES['files']['name'][$i];
            $file_tmp = $_FILES['files']['tmp_name'][$i];
            $file_type = $_FILES['files']['type'][$i];
            $file_size = $_FILES['files']['size'][$i];
            $ra = rand(0, 1000);
            $file_ext = strtolower(end(explode('.', $_FILES['files']['name'][$i])));

            $file = $path .$ra. $file_name;

            if (!in_array($file_ext, $extensions)) {
                $errors[] = 'Extension not allowed: ' . $file_name . ' ' . $file_type;
            }

            if ($file_size > 2097152) {
                $errors[] = 'File size exceeds limit: ' . $file_name . ' ' . $file_type;
            }

            if (empty($errors)) {

                move_uploaded_file($file_tmp, $file);

              //$new->issue_id=0;
              $log_id=(int)$_SESSION["my_idx"];
$ll=$new->getLog($log_id);
               $new->DBaddfiles($file_name, $file_size, $file_type, $ra,$ll);
            }
        }

        if ($errors) print_r($errors);
    }
}
?>