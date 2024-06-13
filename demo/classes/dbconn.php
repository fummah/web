<?php
//error_reporting(0);
if(!defined('access')) {
    die('Access not permited');
}
if(!isset($_SESSION['start_db']))
{
    die("There is a connection error");

}
if(strpos($_SESSION["admin_main"], "testing") !== false) {
    include("../../../mca/link3.php");
}
else{
    include("../../../mca/link3.php");
}
function my_utf8_decode($string)
{
    return strtr($string,
        "???????Â¥ÂµÃÃÃÃÃÃÃÃÃÃÃÃÃÃÃÃÃÃÃÃÃÃÃÃÃÃÃÃ Ã¡Ã¢Ã£Ã¤Ã¥Ã¦Ã§Ã¨Ã©ÃªÃ«Ã¬Ã­Ã®Ã¯Ã°Ã±Ã²Ã³Ã´ÃµÃ¶Ã¸Ã¹ÃºÃ»Ã¼Ã½Ã¿",
        "SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy");
}
// sanitize a string in prep for passing a single argument to system() (or similar)
function sanitize_system_string($string, $min='', $max='')
{
    $pattern = '/(;|\||`|>|<|&|%|=|^|"|'."\n|\r|'".'|{|}|[|]|\)|\()/i'; // no piping, passing possible environment variables ($),
    // seperate commands, nested execution, file redirection,
    // background processing, special commandss (backspace, etc.), quotes
    // newlines, or some other special characters
    $string = preg_replace($pattern, '', $string);
    $string = preg_replace('/\$/', '\\\$', $string); //make sure this is only interpretted as ONE argument
    $len = strlen($string);
    if((($min != '') && ($len < $min)) || (($max != '') && ($len > $max)))
        return FALSE;
    return $string;
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