<?php
session_start();
error_reporting(0);
if($_SESSION['level']=="admin")
{  


$units = explode(' ', 'B KB MB GB TB PB');
$SIZE_LIMIT = 5368709120; // 5 GB
$disk_used = foldersize("/usr/www/users/greenwhc/mca/documents");

$disk_remaining = $SIZE_LIMIT - $disk_used;

echo("<html><body>");
echo('Disk Space Used:<i style="color:red"> ' . format_size($disk_used).'</i>');
}
else
{
die("There is an error");
}
echo("</body></html>");


function foldersize($path) {
    $total_size = 0;
    $files = scandir($path);
    $cleanPath = rtrim($path, '/'). '/';

    foreach($files as $t) {
        if ($t<>"." && $t<>"..") {
            $currentFile = $cleanPath . $t;
            if (is_dir($currentFile)) {
                $size = foldersize($currentFile);
                $total_size += $size;
            }
            else {
                $size = filesize($currentFile);
                $total_size += $size;
            }
        }
    }

    return $total_size;
}


function format_size($size) {
    global $units;

    $mod = 1024;

    for ($i = 0; $size > $mod; $i++) {
        $size /= $mod;
    }

    $endIndex = strpos($size, ".")+3;

    return substr( $size, 0, $endIndex).' '.$units[$i];
}

?>