<?php
session_start();
//error_reporting(0);
define("access",true);
require "classes/controls.php";
include ("templates/claim_templates.php");
$control=new controls();
if(!$control->isInternal())
{
    die("Invalid access");
}
include ("header.php");
$limit = 10;
if (isset($_GET["page"])) {
    $page = (int)$_GET["page"];
} else {
    $page = 1;
};
$start_from = ($page - 1) * $limit;
if(isset($_POST["search_button"]))
{

    $search_value=validateXss($_POST["search_term"]);
    $page = 1;
    $start_from = ($page - 1) * $limit;
}
elseif (isset($_GET['search']))
{
    $search_value=validateXss($_GET["search"]);
}
else
{

    $search_value="";
}
?>
<html>
<head>
    <title>MCA | Search Doctor</title>
    <link rel="stylesheet" href="css/simplePagination.css" />
    <script src="js/jquery.simplePagination.js"></script>
    <style>
        .pagination li.active{background-color: transparent !important;}
        .light-theme a, .light-theme span {
            border: 1px solid #54bf99 !important;
            background: #fff !important;
        }
        .light-theme .current{background-color: black !important;border: 1px solid #54bf99 !important;}
    </style>
</head>

<body>
<?php

?>

<div class="row uk-text-small" style="padding-left: 20px; padding-right: 20px; padding-top: 20px; border-bottom: 1px solid #54bc9c">
    <div class="col-md-4">
        <form class="uk-search uk-search-default" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <input class="uk-search-input" type="search" name="search_term" id="search_term" placeholder="Search Doctor" value="<?php echo $search_value;?>">
            <button class="uk-search-icon-flip" name="search_button" id="search_button" uk-search-icon></button>
        </form><a href="search_doctor.php" data-toggle="tooltip" title="Refresh"><span uk-icon="refresh" style="color: #54bc9c"></span></a>
    </div>

</div>


<div class="row uk-text-small" style="padding-left: 20px; padding-right: 15px;" >

    <div class="col-md-12">
        <?php
        echo "<table class='striped uk-table' style='border: 2px solid whitesmoke'><thead>";
        echo "<tr style=\"color: black\">
        <th>Full Name</th>
        <th>Practice Number</th>
        <th>Type</th>
        <th>Telephone 1</th>
        <th>Telephone 2</th>
        <th>Displine</th>
        <th>Address</th>   
        <th>Date Entered</th>   
        <th>Entered By</th>   
        <th></th>
        <th></th>
    </tr>";
        echo "</thead><tbody>";
        foreach($control->viewAllDoctors($start_from, $limit,$search_value, 0) as $row)
        {
            $doctor_id = htmlspecialchars($row["doc_id"]);
            $doctor_fullname = htmlspecialchars(strtoupper($row["name_initials"] . " " . $row["surname"]));
            $practice_number = htmlspecialchars(strtoupper($row["practice_number"]));
            $type = "Local";
            $telephone1 = htmlspecialchars($row["telephone"]);
            $telephone2 = htmlspecialchars($row["tel2"]);
            $discipline = htmlspecialchars($row["discipline"]);
            $address = htmlspecialchars($row["physad1"] . "," . $row["town"]);
            $date_entered = htmlspecialchars($row["date_entered"]);
            $entered_by = htmlspecialchars($row["entered_by"]);

            echo "<tr style=\"color: black\">
        <th>$doctor_fullname</th>
        <th>$practice_number</th>
        <th>$type</th>
        <th>$telephone1</th>
        <th>$telephone2</th>
        <th>$discipline</th>
        <th>$address</th>       
        <th>$date_entered</th>       
        <th>$entered_by</th>       
        <th><ul class=\"uk-iconnav\">";
            echo "<li><form action='edit_doctor.php' method='post' />";
            echo "<input type=\"hidden\" name=\"doc_id\" value=\"$doctor_id\" />";
            echo "<button name=\"doctor_edit_btn\" uk-icon=\"icon: pencil\" style=\"color: #0b8278;\"></button>";
            echo "</form></li>";
        echo "</ul></th></tr>";
        }
        echo "</tbody></table>";
        $total_records=$control->viewAllDoctors($start_from, $limit,$search_value, 1);
        echo "</tbody>";
        $total_pages = ceil($total_records / $limit);
        $pagLink = "<ul class='pagination'>";
        for ($i=1; $i<=$total_pages; $i++) {
            $pagLink .= "<li><a href='?search=".$search_value."&page=".$i."'>".$i."</a></li>";
        };
        echo $pagLink . "</ul>";
        ?>
    </div></div>

<span class="uk-text-lighter" style="padding-left: 20px;"> <?php echo "Page $page of $total_pages =>  $total_records";?></span>
<script type="text/javascript">
    $(document).ready(function(){
        $('.pagination').pagination({
            items: <?php echo $total_records;?>,
            itemsOnPage: <?php echo $limit;?>,
            cssStyle: 'light-theme',
            currentPage : <?php echo $page;?>,
            hrefTextPrefix : '?search=<?php echo $search_value;?>&page='
        });
    });
</script>


</body>
</html>
<?php
include "footer.php";
?>
<script>
    $(document).ready(function() {
        $('select').formSelect();
    } );
</script>