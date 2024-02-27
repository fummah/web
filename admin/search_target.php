<?php

include_once "dbconn.php";
include_once "function.php";
if ($_SESSION['level'] == "admin" || $_SESSION['level'] == "controller" || $_SESSION['level']=="claims_specialist") {
}
else{
    die("Error");
}
?>

<style type="text/css">
    .navi {
        width: 500px;
        margin: 5px;
        padding:2px 5px;
        border:1px solid #eee;
    }

    .show {
        color: blue;
        margin: 5px 0;
        padding: 3px 5px;
        cursor: pointer;
        font: 15px/19px Arial,Helvetica,sans-serif;
    }
    .show a {
        text-decoration: none;
    }
    .show:hover {
        text-decoration: underline;
    }


    ul.setPaginate li.setPage{
        padding:15px 10px;
        font-size:14px;
    }

    ul.setPaginate{
        margin:0px;
        padding:0px;
        height:100%;
        overflow:hidden;
        font:12px 'Tahoma';
        list-style-type:none;
    }

    ul.setPaginate li.dot{padding: 3px 0;}

    ul.setPaginate li{
        float:left;
        margin:0px;
        padding:0px;
        margin-left:5px;
    }



    ul.setPaginate li a
    {
        background: none repeat scroll 0 0 #ffffff;
        border: 1px solid #cccccc;
        color: #999999;
        display: inline-block;
        font: 15px/25px Arial,Helvetica,sans-serif;
        margin: 5px 3px 0 0;
        padding: 0 5px;
        text-align: center;
        text-decoration: none;
    }

    ul.setPaginate li a:hover,
    ul.setPaginate li a.current_page
    {
        background: none repeat scroll 0 0 #0d92e1;
        border: 1px solid #000000;
        color: #ffffff;
        text-decoration: none;
    }

    ul.setPaginate li a{
        color:black;
        display:block;
        text-decoration:none;
        padding:5px 8px;
        text-decoration: none;
    }
    .rw:hover{
        background-color: #e8f6ff;
    }



</style>
</head>

<body>
<table class="table table-striped">
    <thead>
    <tr class="btn-info">
        <th>Full Name</th>
        <th>Practice Number</th>
        <th>Type</th>
        <th>Telephone 1</th>
        <th>Telephone 2</th>
        <th>Displine</th>
        <th>Address</th>
        <th></th>
    </tr>
    </thead>
    <?php

    $conn=connection("mca","MCA_admin");
    // Your SQL query go here. This query will display all record by setting the Limit.

    function allDoctors()
    {

        global $conn;
        if (isset($_GET["page"]))
            $page = (int)$_GET["page"];
        else
            $page = 1;

        $setLimit = 10;
        $pageLimit = ($page * $setLimit) - $setLimit;
        if ($_SESSION['level'] == "admin" || $_SESSION['level'] == "controller" || $_SESSION['level']=="claims_specialist") {

            $sql = $conn->prepare("SELECT name_initials,surname,telephone,gives_discount,discipline,practice_number,physad1,town,tel1code,tel2code,tel2,doc_id FROM doctor_details LIMIT " . $pageLimit . " , " . $setLimit);
            $sql->execute();
            $nu = $sql->rowCount();
            if ($nu > 0) {
                foreach ($sql->fetchAll() as $row) {
                    $name = $row[0] . " " . $row[1];
                    $practice_number = $row[5];
                    $tel1 = $row[2];
                    $tel2 = $row[10];
                    $discount = $row[3];
                    $discipline = $row[4];
                    $address = $row[6] . ", " . $row[7];
                    $doc_id=$row[11];


                    ?>
                    <tr class="rw">

                        <td><?php echo $name; ?></td>
                        <td><?php echo $practice_number; ?></td>
                        <td><?php echo "Local"; ?></td>
                        <td><?php echo $tel1; ?></td>
                        <td><?php echo $tel2; ?></td>
                        <td><?php echo $discipline; ?></td>
                        <td><?php echo $address; ?></td>
                        <td><?php
                            echo "<form action='edit_doctor.php' method='post' />";
                            echo "<input type=\"hidden\" name=\"doc_id\" value=\"$doc_id\" />";
                            echo "<input type=\"hidden\" name=\"dtype\" value=\"person\" />";
                            echo "<button type=\"submit\" class=\"btn btn-info\" name=\"btn\" value=\"\"><span class=\"glyphicon glyphicon-pencil\"> Edit</span></button>";
                            echo "</form>";
                            ?>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<b style='color:red'>No Data</b>";
            }

            echo "</table>";

            // Call the Pagination Function to load Pagination.

            echo doctorsPaginationBelow($setLimit, $page);

            echo("<br>");
            echo("<b>Results : " . totDoctors() . "</b>");
        }
    }

    ///////////////////////////////////

    function searchDoctor($val)
    {
        if (!empty($val)) {
            global $conn;
            $dval = "%" . $val . "%";
            $var = $val;
            if (substr($var, 0, 2) == "00") {
                $val = substr($var, 2);
            } elseif (substr($var, 0, 1) == "0") {
                $val = substr($var, 1);
            }

            $searched = "%" . $val . "%";

            $sql = $conn->prepare("SELECT DISTINCT name_initials,surname,telephone,gives_discount,discipline,practice_number,physad1,town,tel1code,tel2code,tel2,doc_id FROM doctor_details WHERE name_initials like :search OR surname like :search OR telephone like :search OR tel2 like :search OR practice_number like :search");
            $sql->bindParam(':search', $searched, PDO::PARAM_STR);
            $sql->execute();
            $nu = $sql->rowCount();
            if ($nu > 0) {
                foreach ($sql->fetchAll() as $row) {
                    $name = $row[0] . " " . $row[1];
                    $practice_number = $row[5];
                    $tel1 = "(0" . $row[8] . ")" . $row[2];
                    $tel2 = "(0" . $row[9] . ")" . $row[10];
                    $discount = $row[3];
                    $discipline = $row[4];
                    $address = $row[6] . ", " . $row[7];
$doc_id=$row[11];
                    ?>
                    <tr class="rw">

                        <td><?php echo $name; ?></td>
                        <td><?php echo $practice_number; ?></td>
                        <td><?php echo "Private"; ?></td>
                        <td><?php echo $tel1; ?></td>
                        <td><?php echo $tel2; ?></td>
                        <td><?php echo $discipline; ?></td>
                        <td><?php echo $address; ?></td>
                        <td><?php
                            echo "<form action='edit_doctor.php' method='post' />";
                            echo "<input type=\"hidden\" name=\"doc_id\" value=\"$doc_id\" />";
                            echo "<input type=\"hidden\" name=\"dtype\" value=\"person\" />";
                            echo "<button type=\"submit\" class=\"btn btn-info\" name=\"btn\" value=\"\"><span class=\"glyphicon glyphicon-pencil\"> Edit</span></button>";
                            echo "</form>";
                            ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            //Organisation Search

//Local Search


            echo "</table>";
            $tott = $nu;
            echo "<h3>Total : <b style='color: red'>" . $tott . "</b></h3>";

            // Call the Pagination Function to load Pagination.


            echo("<br>");

        }
        else
        {
            echo "<h3><b style='color: red'>Incorrect Input</b></h3>";
        }
    }


    ?>


    <script type="text/javascript">
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-38304687-1']);
        _gaq.push(['_trackPageview']);

        (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();

    </script>

    <!--********************************************

      For More Detail please Visit:

      http://www.discussdesk.com/download-pagination-in-php-and-mysql-with-example.htm

      ************************************************-->

</body>
</html>

