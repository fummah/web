<style>
.note-editing-area{
    height: 200px !important;
}
    </style>
<?php

session_start();
$username=$_SESSION["user_id"];
include_once "../dbconn1.php";
$conn=connection("mca","MCA_admin");
include_once ("email.php");

$obj=new email();
$email="";
$subject="";
$typm="";
$practice_number="";
$template = "";
if(isset($_POST["claim_id"]))
{
 
    $template_id = (int)$_POST["template_id"];
    $template=$obj->getEmailTemplate($template_id);
    $template_content = $template[0];
    $tags = $template[1];
    foreach($_POST as $key => $value){
        if (is_array($value)) {
            $value = implode("\n", $value);
        } 
        $value = "<b>".ucfirst($value)."</b>";
        $template_content = str_replace('{'.$key.'}', $value, $template_content);
    }
    echo nl2br($template_content);
}



