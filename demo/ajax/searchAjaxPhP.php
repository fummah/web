<?php
session_start();
error_reporting(0);


if(!isset($_SESSION['logxged']))
{
    die("There is an error.");

}
$val=validateXss($_GET['id']);

$term=validateXss($_GET['search1']);

try
{

    if ($val==1) {
        include("../classMainSearch.php");
        searchFunction($term);
    }
    else if($val==2)
    {
        include("../search_target.php");

        searchDoctor($term);
    }
    else{
        echo("Unkown value");
    }
}
catch(Exception $e)
{
    echo "There is an Error";
}


function validateXss($string)
{
    $newstr = filter_var($string, FILTER_SANITIZE_STRING);
    $newstr=sanitize_system_string($newstr, $min='', $max='');
    $newstr=htmlspecialchars($newstr);
    $newstr=my_utf8_decode($newstr);
    $newstr=trim($newstr);
    return $newstr;

}
?>