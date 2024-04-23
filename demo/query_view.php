<?php
session_start();
define("access",true);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
if(!isset($_POST["query_btn"]))
{
    die("Invalid entry");
}
include ("classes/controls.php");
$control=new controls();
if (!$control->isInternal())
{
    die("Invalid entry");
}
include("header.php");
$mail = new PHPMailer(true);
$query_id= (int)$_POST['query_id'];
$details=$control->viewQuery($query_id);
$first_name=$details['first_name'];
$last_name=$details['last_name'];
$email=$details['email'];
$id_number=$details['id_number'];
$medical_name=$details['scheme_name'];
$scheme_number=$details['scheme_number'];
$category=$details['category'];
$descrip=$details['description'];
$date_entered=$details['date_entered'];
$username=$details['assigned_to'];
$status=(int)$details['status'];
$query_id=(int)$details['id'];
?>
<html>
<head>

    <title>MCA | View Query</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="css/simplePagination.css" />
    <script src="js/jquery.simplePagination.js"></script>

    
    <style>
        .linkButton {
            background: none;
            border: none;
            color: #0066ff;
            text-decoration: underline;
            cursor: pointer;
        }
        .uk-button{
            border-radius:15px

        }
    </style>
</head>

<body>
<?php
echo "<br><br>";
?>
<div class="container">

    <div class="row uk-card uk-card-default uk-card-body">
        <p><b><u>Details</u></b></p>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-4">
                    Full Name : <b><?php echo $first_name." ".$last_name;?></b>
                </div>
                <div class="col-md-4">
                    Email : <b><?php echo $email;?></b>
                </div>
                <div class="col-md-4">
                    ID Number : <b><?php echo $id_number;?></b>
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-4">
                    Scheme Name : <b><?php echo $medical_name;?></b>
                </div>
                <div class="col-md-4">
                    Scheme Number : <b><?php echo $scheme_number;?></b>
                </div>
                <div class="col-md-4">
                    Category : <b>R <?php echo $category;?></b>
                </div>
            </div><br>

            <div class="row">
                <div class="col-md-4">Username : <b><?php echo $username;?></b></div>
                <div class="col-md-4">Date Created : <b><?php echo $date_entered;?></b></div>

            </div><hr>
            <div class="row">
                <div class="col-md-12">
                    <?php
                    echo"Additional Information : <b>".nl2br($descrip)."</b>";
                    ?>
                </div>

            </div>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <div class="uk-inline">
                        <button class="uk-button uk-button-default" type="button">Files</button>
                        <div uk-dropdown>
                            <?php
                            $files=$control->viewQueryDocs($query_id);
                            if(count($files)<1)
                            {
                                echo "No Files";
                            }
                            foreach ($files as $rrow)
                            {
                                $id = htmlspecialchars($rrow["id"]);
                                $ra = "";
                                $nname = htmlspecialchars($rrow['document_name']);


                                $desc = "uploads/" . $ra . $nname;

                                //echo "<a href='$desc' onclick=\"window.open('$desc','popup','width=800,height=600'); return false;\" title='Click to view'>$nname</a>";
                                echo "<form action='view_file.php' method='post' target=\"print_popup\" onsubmit=\"window.open('test5.php','print_popup','width=1000,height=800');\"/><input type=\"hidden\" name=\"my_doc\" value=\"$desc\" />
<input type=\"hidden\" name=\"my_id\" value=\"$id\" />
<input type=\"submit\" class=\"linkbutton\" name=\"doc\" value=\"$nname\">

</form>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
               
                </div>

                <div class="col-md-4">
                
                </div>
            </div>

            <hr>         

        </div>
    </div>
 


</div>
</div>
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
