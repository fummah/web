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
$limit = 10;
$page = 1;
$searched="";
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
$val=1;
$condition=":username";
$rolex="other";
if($control->isClaimsSpecialist())
{
    $condition="username=:username";
    $val=$control->loggedAs();
}

$queries = $control->viewQueries($condition,$val);
?>
<html>
<head>

    <title>MCA : Query</title>
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
    <div class="uk-margin">
        <form class="uk-search uk-search-default" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <button name="btnsearch" class="uk-search-icon-flip" uk-search-icon></button>
            <input class="uk-search-input" name="search" value="<?php echo $searched;?>" type="search" placeholder="Search...">
        </form>
    </div>
    <table class="striped uk-table" width="100%">
        <thead>
        <tr>
            <th>First Name</th>
            <th>Surname</th>
            <th>Email</th>
            <th>Category</th>
            <th>Date</th>
            <th>Username</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php
        if(count($queries)>0) {
            foreach ($queries as $row) {
                $query_id=$row['id'];
                $first_name=$row['first_name'];
                $last_name=$row['last_name'];
                $category=$row['category'];
                $email=$row['email'];
                $date_entered=$row['date_entered'];
                $assigned_to=$row['assigned_to'];

                echo "<tr>";
                echo "<td>$first_name</td>";
                echo "<td>$last_name</td>";
                echo "<td>$email</td>";
                echo "<td>$category</td>";
                echo "<td>$date_entered</td>";
                echo "<td>$assigned_to</td>";
                echo "<td><form action='query_view.php' method='post'><input type='hidden' name='query_id' value='$query_id'/><button name='query_btn' uk-icon=\"icon: info\"></button></form></td>";
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

    $total_pages = ceil(count($queries) / $limit);
    $pagLink = "<nav><ul class='pagination'>";
    for ($i=1; $i<=$total_pages; $i++) {
        $pagLink .= "<li><a href='queries.php?page=".$i."'>".$i."</a></li>";
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
            hrefTextPrefix : 'queries.php?tab=<?php echo $status;?>&page='
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