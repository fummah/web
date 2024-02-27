<?php
session_start();
define("access",true);
include ("classes/controls.php");
$control=new controls();
if (!$control->isInternal())
{
    die("Invalid entry");
}
include("header.php");
require_once ("classes/leadClass.php");
$obj=new leadClass();
$limit = 10;
$page = 1;
if (isset($_GET["page"])) {
    $page = (int)$_GET["page"];
    $tab = (int)$_GET["tab"];
} else {
    $page = 1;
    $tab=0;
}
$role=$control->myRole();
$username=$control->loggedAs();
$start_from = ($page - 1) * $limit;
$clas1="uk-button-danger";
$clas2="uk-button-primary";
$clas3="uk-button-primary";
$clas4="uk-button-primary";
if(!isset($_SESSION['clas'])) {
    $_SESSION["clas"]="Active";
    $clas1="uk-button-danger";
    $clas2="uk-button-primary";
    $clas3="uk-button-primary";
    $clas4="uk-button-primary";
    $status=0;
}

$status=0;
if(isset($_POST["promoted"]) || $tab==1)
{
    $clas2="uk-button-danger";
    $clas1="uk-button-primary";
    $clas3="uk-button-primary";
    $clas4="uk-button-primary";
    $_SESSION["clas"]="Promoted";
    $status=1;
}
elseif (isset($_POST["declined"]) || $tab==2)
{
    $clas2="uk-button-primary";
    $clas1="uk-button-primary";
    $clas3="uk-button-danger";
    $clas4="uk-button-primary";
    $_SESSION["clas"]="Declined";
    $status=2;
}
elseif (isset($_POST["requested"]) || $tab==3)
{
    $clas2="uk-button-primary";
    $clas1="uk-button-primary";
    $clas4="uk-button-danger";
    $clas3="uk-button-primary";
    $_SESSION["clas"]="Requested";
    $status=3;
}
elseif (isset($_POST["active"])){
    $clas2="uk-button-primary";
    $clas3="uk-button-primary";
    $clas1="uk-button-danger";
    $_SESSION["clas"]="Active";
    $status=0;
}

$val="";
if(isset($_POST['btnsearch']))
{
    $val=$_POST['search'];
    $statuss=0;
    if($_SESSION["clas"]=="Promoted")
    {
        $clas2="uk-button-danger";
        $clas3="uk-button-primary";
        $clas1="uk-button-primary";
        $clas4="uk-button-primary";
        $statuss=1;
    }
    elseif ($_SESSION["clas"]=="Declined")
    {
        $clas3="uk-button-danger";
        $clas2="uk-button-primary";
        $clas1="uk-button-primary";
        $clas4="uk-button-primary";
        $statuss=2;
    }
    elseif ($_SESSION["clas"]=="Requested")
    {
        $clas4="uk-button-danger";
        $clas2="uk-button-primary";
        $clas1="uk-button-primary";
        $clas3="uk-button-primary";
        $statuss=3;
    }

    else{
        $clas2="uk-button-primary";
        $clas3="uk-button-primary";
        $clas4="uk-button-primary";
        $clas1="uk-button-danger";
        $statuss=0;
    }
    $pagn=$obj->fetchSearch($val,$start_from, $limit,$statuss,$role,$username);
    $total_records=count($obj->fetchAllSearch($val,$statuss,$role,$username));
}
else
{
    $pagn=$obj->fetchLeads($start_from, $limit, $status,$role,$username);
    $total_records=count($obj->fetchAllLeads($status,$role,$username));

}
$r1=count($obj->fetchAllLeads(0,$role,$username));
$r2=count($obj->fetchAllLeads(1,$role,$username));
$r3=count($obj->fetchAllLeads(2,$role,$username));
$r4=count($obj->fetchAllLeads(3,$role,$username));
?>
<html>
<head>

    <title>MCA : Leads</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/simplePagination.css"/>
    <script src="js/jquery.simplePagination.js"></script>
    <style>
        .uk-button{
            border-radius:15px
        }
        .w3-black{
            background-color: black !important;
        }
    </style>
</head>

<body>
<?php
echo "<br><br>";
?>
<div class="container">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
        <p uk-margin>
            <button name="active" class="uk-button <?php echo $clas1;?>"><span class="uk-badge w3-black"><?php echo $r1;?></span> Active</button>
            <button name="promoted" class="uk-button <?php echo $clas2;?>"><span class="uk-badge w3-black"><?php echo $r2;?></span> Promoted</button>
            <button name="declined" class="uk-button <?php echo $clas3;?>"><span class="uk-badge w3-black"><?php echo $r3;?></span> Declined</button>
            <button name="requested" class="uk-button <?php echo $clas4;?>"><span class="uk-badge w3-black"><?php echo $r4;?></span> Requested</button>

    </form>
    <div class="uk-margin">
        <form class="uk-search uk-search-default" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <button name="btnsearch" class="uk-search-icon-flip" uk-search-icon></button>
            <input class="uk-search-input" name="search" value="<?php echo $val;?>" type="search" placeholder="Search...">
        </form>
    </div>
    <table class="striped uk-table" width="100%">
        <thead>
        <tr>
            <th>First Name</th>
            <th>Surname</th>
            <th>Email</th>
            <th>Contact.No</th>
            <th>Date</th>
            <th>Username</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php
        if($total_records>0) {
            foreach ($pagn as $row) {
                $lead_id=$row[0];
                echo "<tr>";
                echo "<td>$row[1]</td>";
                echo "<td>$row[2]</td>";
                echo "<td>$row[3]</td>";
                echo "<td>$row[4]</td>";
                echo "<td>$row[9]</td>";
                echo "<td>$row[10]</td>";
                echo "<td><form action='view_lead.php' method='post'><input type='hidden' name='lead_id' value='$lead_id'/><button name='lead_btn' uk-icon=\"icon: info\"></button></form></td>";
                echo "</tr>";
            }
        }
        else{
            echo "<tr style='background-color: white'><td class='uk-text-light'>No records</td></tr>";
        }
        ?>
        </tbody>
    </table>
    <?php

    $total_pages = ceil($total_records / $limit);
    $pagLink = "<nav><ul class='pagination'>";
    for ($i=1; $i<=$total_pages; $i++) {
        $pagLink .= "<li><a href='leads.php?page=".$i."&tab=".$status."'>".$i."</a></li>";
    };
    echo $pagLink . "</ul></nav>";
    ?>
</div>
</body>
</html>
<script type="text/javascript">
    $(document).ready(function(){
        $('.pagination').pagination({
            items: <?php echo $total_records;?>,
            itemsOnPage: <?php echo $limit;?>,
            cssStyle: 'light-theme',
            currentPage : <?php echo $page;?>,
            hrefTextPrefix : 'leads.php?tab=<?php echo $status;?>&page='
        });
    });
</script>
<?php
include "footer.php";
?>
<script>
    $(document).ready(function() {
        $('select').formSelect();
    } );
</script>