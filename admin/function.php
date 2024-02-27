<?php
/********************************************

For More Detail please Visit:

http://www.discussdesk.com/download-pagination-in-php-and-mysql-with-example.htm
require_once('dbconn.php');
 ************************************************/
//require_once('dbconn.php');

function displayPaginationBelow($per_page,$page){
    $page_url="?";

    $r=$_SESSION['user_id'];
    $condition="";
    if ($_SESSION['level'] == "claims_specialist") {
        $condition = "username = :num";
    } else if ($_SESSION['level'] == "gap_cover") {
        $dbh = connection("mca", "MCA_admin");
        $stmt = $dbh->prepare("SELECT client_id FROM clients WHERE client_name = :name");
        $stmt->bindParam(':name', $r, PDO::PARAM_STR);
        $stmt->execute();
        $clientNameID = $stmt->fetchColumn();
        $r = $clientNameID;
        $condition = "client_id = :num";
    } else if ($_SESSION['level'] == "patient") {
        $condition = "patient_name = :num";
    }
    else if($_SESSION['level'] == "admin" || $_SESSION['level'] == "controller")
    {
        $condition="1";
    }
    else
    {
        die("There is an error");
    }

    $conn=connection("mca","MCA_admin");
    $stmt = $conn->prepare('SELECT Count(*) as a FROM claim WHERE '.$condition);
    if ($_SESSION['level'] == "claims_specialist" || $_SESSION['level'] == "gap_cover")
    {
        $stmt->bindParam(':num', $r, PDO::PARAM_STR);
    }
    $stmt->execute();
    $tot6=0;
    
    foreach ($stmt->fetchAll() as $row)
    {
        $tot6= $row[0];
    }
    $total = $tot6;
    $adjacents = "10";

    $page = ($page == 0 ? 1 : $page);
    $start = ($page - 1) * $per_page;

    $prev = $page - 1;
    $next = $page + 1;
    $setLastpage = ceil($total/$per_page);
    $lpm1 = $setLastpage - 1;

    $setPaginate = "";
    if($setLastpage > 1)
    {
        $setPaginate .= "<ul class='setPaginate' style='background-color:grey;height:50px'>";
        $setPaginate .= "<li class='setPage'>Page $page of $setLastpage</li>";
        if ($setLastpage < 7 + ($adjacents * 2))
        {
            for ($counter = 1; $counter <= $setLastpage; $counter++)
            {
                if ($counter == $page)
                    $setPaginate.= "<li><a class='current_page'>$counter</a></li>";
                else
                    $setPaginate.= "<li><a href='{$page_url}page=$counter'>$counter</a></li>";
            }
        }
        elseif($setLastpage > 5 + ($adjacents * 2))
        {
            if($page < 1 + ($adjacents * 2))
            {
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                {
                    if ($counter == $page)
                        $setPaginate.= "<li><a class='current_page'>$counter</a></li>";
                    else
                        $setPaginate.= "<li><a href='{$page_url}page=$counter'>$counter</a></li>";
                }
                $setPaginate.= "<li class='dot'>...</li>";
                $setPaginate.= "<li><a href='{$page_url}page=$lpm1'>$lpm1</a></li>";
                $setPaginate.= "<li><a href='{$page_url}page=$setLastpage'>$setLastpage</a></li>";
            }
            elseif($setLastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
            {
                $setPaginate.= "<li><a href='{$page_url}page=1'>1</a></li>";
                $setPaginate.= "<li><a href='{$page_url}page=2'>2</a></li>";
                $setPaginate.= "<li class='dot'>...</li>";
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                {
                    if ($counter == $page)
                        $setPaginate.= "<li><a class='current_page'>$counter</a></li>";
                    else
                        $setPaginate.= "<li><a href='{$page_url}page=$counter'>$counter</a></li>";
                }
                $setPaginate.= "<li class='dot'>..</li>";
                $setPaginate.= "<li><a href='{$page_url}page=$lpm1'>$lpm1</a></li>";
                $setPaginate.= "<li><a href='{$page_url}page=$setLastpage'>$setLastpage</a></li>";
            }
            else
            {
                $setPaginate.= "<li><a href='{$page_url}page=1'>1</a></li>";
                $setPaginate.= "<li><a href='{$page_url}page=2'>2</a></li>";
                $setPaginate.= "<li class='dot'>..</li>";
                for ($counter = $setLastpage - (2 + ($adjacents * 2)); $counter <= $setLastpage; $counter++)
                {
                    if ($counter == $page)
                        $setPaginate.= "<li><a class='current_page'>$counter</a></li>";
                    else
                        $setPaginate.= "<li><a href='{$page_url}page=$counter'>$counter</a></li>";
                }
            }
        }

        if ($page < $counter - 1){
            $setPaginate.= "<li><a href='{$page_url}page=$next'>Next</a></li>";
            $setPaginate.= "<li><a href='{$page_url}page=$setLastpage'>Last</a></li>";
        }else{
            $setPaginate.= "<li><a class='current_page'>Next</a></li>";
            $setPaginate.= "<li><a class='current_page'>Last</a></li>";
        }

        $setPaginate.= "</ul>\n";
    }


    return $setPaginate;
}

//=================================

function doctorsPaginationBelow($per_page,$page){
    $page_url="?";


    require_once('dbconn.php');
    $conn=connection("mca","MCA_admin");
    $sql = $conn->prepare("SELECT Count(*) as a FROM doctor_details");
    $sql->execute();

    $tot6=0;
    foreach ($sql->fetchAll() as $row)
    {
        $tot6= $row[0];
    }
    $total = $tot6;
    $adjacents = "10";

    $page = ($page == 0 ? 1 : $page);
    $start = ($page - 1) * $per_page;

    $prev = $page - 1;
    $next = $page + 1;
    $setLastpage = ceil($total/$per_page);
    $lpm1 = $setLastpage - 1;

    $setPaginate = "";
    if($setLastpage > 1)
    {
        $setPaginate .= "<ul class='setPaginate' style='background-color:grey;height:50px'>";
        $setPaginate .= "<li class='setPage'>Page $page of $setLastpage</li>";
        if ($setLastpage < 7 + ($adjacents * 2))
        {
            for ($counter = 1; $counter <= $setLastpage; $counter++)
            {
                if ($counter == $page)
                    $setPaginate.= "<li><a class='current_page'>$counter</a></li>";
                else
                    $setPaginate.= "<li><a href='{$page_url}page=$counter'>$counter</a></li>";
            }
        }
        elseif($setLastpage > 5 + ($adjacents * 2))
        {
            if($page < 1 + ($adjacents * 2))
            {
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                {
                    if ($counter == $page)
                        $setPaginate.= "<li><a class='current_page'>$counter</a></li>";
                    else
                        $setPaginate.= "<li><a href='{$page_url}page=$counter'>$counter</a></li>";
                }
                $setPaginate.= "<li class='dot'>...</li>";
                $setPaginate.= "<li><a href='{$page_url}page=$lpm1'>$lpm1</a></li>";
                $setPaginate.= "<li><a href='{$page_url}page=$setLastpage'>$setLastpage</a></li>";
            }
            elseif($setLastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
            {
                $setPaginate.= "<li><a href='{$page_url}page=1'>1</a></li>";
                $setPaginate.= "<li><a href='{$page_url}page=2'>2</a></li>";
                $setPaginate.= "<li class='dot'>...</li>";
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                {
                    if ($counter == $page)
                        $setPaginate.= "<li><a class='current_page'>$counter</a></li>";
                    else
                        $setPaginate.= "<li><a href='{$page_url}page=$counter'>$counter</a></li>";
                }
                $setPaginate.= "<li class='dot'>..</li>";
                $setPaginate.= "<li><a href='{$page_url}page=$lpm1'>$lpm1</a></li>";
                $setPaginate.= "<li><a href='{$page_url}page=$setLastpage'>$setLastpage</a></li>";
            }
            else
            {
                $setPaginate.= "<li><a href='{$page_url}page=1'>1</a></li>";
                $setPaginate.= "<li><a href='{$page_url}page=2'>2</a></li>";
                $setPaginate.= "<li class='dot'>..</li>";
                for ($counter = $setLastpage - (2 + ($adjacents * 2)); $counter <= $setLastpage; $counter++)
                {
                    if ($counter == $page)
                        $setPaginate.= "<li><a class='current_page'>$counter</a></li>";
                    else
                        $setPaginate.= "<li><a href='{$page_url}page=$counter'>$counter</a></li>";
                }
            }
        }

        if ($page < $counter - 1){
            $setPaginate.= "<li><a href='{$page_url}page=$next'>Next</a></li>";
            $setPaginate.= "<li><a href='{$page_url}page=$setLastpage'>Last</a></li>";
        }else{
            $setPaginate.= "<li><a class='current_page'>Next</a></li>";
            $setPaginate.= "<li><a class='current_page'>Last</a></li>";
        }

        $setPaginate.= "</ul>\n";
    }


    return $setPaginate;
}
function tot()
{
    $condition="";
    $r=$_SESSION['user_id'];
    if ($_SESSION['level'] == "claims_specialist") {
        $condition = "username = :num";
    } else if ($_SESSION['level'] == "gap_cover") {
        $dbh = connection("mca", "MCA_admin");
        $stmt = $dbh->prepare("SELECT client_id FROM clients WHERE client_name = :name");
        $stmt->bindParam(':name', $r, PDO::PARAM_STR);
        $stmt->execute();
        $clientNameID = $stmt->fetchColumn();
        $r = $clientNameID;
        $condition = "client_id = :num";
    } else if ($_SESSION['level'] == "patient") {
        $condition = "patient_name = :num";
    }
    else
    {
        $condition="1";
    }


    $conn=connection("mca","MCA_admin");
    $stmt = $conn->prepare('SELECT Count(*) as a FROM claim WHERE '.$condition);
    if ($_SESSION['level'] == "claims_specialist" || $_SESSION['level'] == "gap_cover")
    {
        $stmt->bindParam(':num', $r, PDO::PARAM_STR);
    }

    $stmt->execute();
    $tot6=0;

    foreach ($stmt->fetchAll() as $row)
    {
        $tot6= $row[0];
    }
    $total = $tot6;
    return $tot6;
}

function totDoctors()
{

    $username=$_SESSION['user_id'];
    require_once('dbconn.php');
    $conn=connection("mca","MCA_admin");
    $stmt = $conn->prepare('SELECT Count(*) as a FROM doctor_details');
    $stmt->execute();
    $tot6=0;

    foreach ($stmt->fetchAll() as $row)
    {
        $tot6= $row[0];
    }
    $total = $tot6;
    return $tot6;
}

?>