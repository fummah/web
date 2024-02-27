<style>
.cll {
list-style-type: none;
margin: 0;
padding: 0;
overflow: hidden;
background-color: #333333;
}

li {
float: left;
}

li a {
display: block;
color: white;
text-align: center;
padding: 16px;
text-decoration: none;
}

li a:hover {
background-color: #111111;
}
    .acti{
        background-color:#d3d9df;
        color: #00b3ee;
        font-weight: bolder;
    }
</style>
<?php
$neww="";
$iss="";
$cal="";
$fil="";

$script=$_SERVER['SCRIPT_NAME'];

if($script=="/admin/new_issue.php")
{
    $neww="acti";
}
elseif ($script=="/admin/issues.php")
{
    $iss="acti";
}
else{
    $fil="";
}
?>
<ul class="cll">
<li class="<?php echo $neww;?>"><a href="new_issue.php">New Issue</a></li>
<li class="<?php echo $iss;?>"><a href="issues.php">Issues</a></li>

</ul>
