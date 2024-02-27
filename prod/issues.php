<?php
session_start();
define("access",true);
error_reporting(0);
include ("classes/controls.php");
$control=new controls();
if (!$control->isInternal())
{
    die("Invalid entry");
}
include("header.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
$mail = new PHPMailer(true);
$limit = 10;
if (isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    $page = 1;
};
$start_from = ($page - 1) * $limit;
?>
<title>MCA | Issues</title>

<link rel="stylesheet" href="css/simplePagination.css" />
<script src="js/jquery.simplePagination.js"></script>

<script>
    function openNow(id) {
        $('#myinfo').empty();
        var obj={identity:1,id:id};
        $.ajax({
            url:"ajaxPhp/showticket.php",
            type:"POST",
            data:obj,
            success:function(data){

$("#myinfo").html(data);


                var tek=$('#myinfo').text();
                if(tek==0){
                    alert("No claims found");
                    $('#myinfo').hide();
                }
                else
                {
                    $('#myinfo').toggle();
                    $('#q'+id).nextAll('tr').toggle();
                    $('#q'+id).prevAll('tr').toggle();

                }

            },
            error:function(jqXHR, exception)
            {
                alert("There is an error hereb please");
            }
        });
    }

function showHide() {
    $(".edit").fadeToggle();
    //$("textarea").css("width","100%")
}

    function save(id) {
    $("form").submit(function(e){
            e.preventDefault();
        });

       var descr=$("#issue_description").val();
       var assi=$("#assignee").val();
  var st=$("#mystatus").val();
 $("#inf").text("wait....");
       var op=radio();
       var obj={identity:2,descr:descr,assi:assi,id:id,op:op,st:st};
        $.ajax({
            url:"ajaxPhp/showticket.php",
            type:"POST",
            data:obj,
            success:function(data){
$("#suc").html(data);
 myUpload(id);
 $("#inf").text("");
            },
            error:function(jqXHR, exception)
            {
                alert("There is an error tt");
            }
        });
    }
  function myUpload(id) {

        const url = 'process.php';
        const form = document.querySelector('form');

        const files = document.querySelector('[type=file]').files;
        const formData = new FormData();


        for (let i = 0; i < files.length; i++) {
            let file = files[i];

            formData.append('files[]', file)
        }

        fetch(url, {
            method: 'POST',
            body: formData,
            issue_id : id
        }).then(response => {
            console.log(response)
    })

    }
    function radio()
    {
        var radios = document.getElementsByName('Open');
        var open="";

        for (var i = 0, length = radios.length; i < length; i++) {
            if (radios[i].checked) {
                // do whatever you want with the checked radio
                open=(radios[i].value);

                // only one radio can be logically checked, don't check the rest
                break;
            }
        }
        return open;
    }
</script>
<?php
echo "<br/>";
?>

<style>
    .main{
        width: 80%;
        position: relative;
        margin-left: auto;
        margin-right: auto;

    }
textarea{

        padding: 10px;
        border-radius:5px;
    }
.linkButton {
        background: none;
        border: none;
        color: #0066ff;
        text-decoration: underline;
        cursor: pointer;
    }
</style>
<div class="main">
    <?php
require("ticket_header.php");
    ?>
<hr>

    <?php
    $myrole=1;

    $nextrole=$myrole+1;
    if($myrole==4)
    {
        $nextrole=4;
    }
    $username="Fuma";

    $status=1;
    require_once "dbconn.php";
    include_once "classes/ticketClass.php";
$myssst="";
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $myssst=validateXss($_POST['sstatus']);
    ?>
    <script>
        $(document).ready(function() {
            $("#sstatus").val('<?php echo $myssst;?>');

        });
    </script>
    <?php


    }
    $new=new ticketClass();
    if(isset($_POST["btn"]))
    {
        $tracker=validateXss($_POST['tracker']);
        $subject=validateXss($_POST['subject']);
        $descr=filter_var($_POST['issue_description'], FILTER_SANITIZE_STRING);

        $status=validateXss($_POST['status']);
        $priority=validateXss($_POST['priority']);
        $assignee=validateXss($_POST['assignee']);
        $initiator=$_SESSION["user_id"];
$environment=validateXss($_POST['environment']);
 $related_to=validateXss($_POST['related_to']);
        if($new->insertTicket($tracker, $status, $priority, $subject, $assignee,$descr,$nextrole,$initiator,$environment,$related_to))
        {
           $number=$new->lastEntry();
 $new->sendMail($assignee,$initiator,$number,$subject,$descr,$tracker,$status,$initiator);
           echo "<p align=\"center\" class=\"alert alert-success\"> <b>Record saved successfully <span class='badge badge-primary'>#$number</span></b></p>";
        }
        else
        {
            echo "<p align=\"center\" class=\"alert alert-danger\"> <b>Record failed to save </b></p>";
        }
    }

   $ttt=$new->selectRecords($myrole,$username,$status,$start_from,$myssst);
    $num=count($ttt);

    ?>
    <div class="col-sm-4">
   <form action="" method="post" >
       <h4 style="margin-left: 10px"><label>Status :</label> <select onchange="this.form.submit()" name="sstatus" id="sstatus"><option value="Open">Open</option><option value="Closed">Closed</option></select></h4>
    </form>
    </div>
    <br>
    <table width="100%" border="1" class="striped uk-table w3-animate-zoom w3-hover-shadow">
        <thead>
        <tr>
            <th>No#</th>
            <th>Tracker</th>
            <th>Status</th>
            <th>Priority</th>
            <th>Subject</th>
            <th>Assignee</th>
            <th>Updated</th>
            <th>Initiator</th>
        </tr>
        </thead>

        <tbody>
        <?php
        for($i=0;$i<$num;$i++)
        {
            //id, tracker, status, priority, subject, assignee, date_entered, last_updated

            $id=$ttt[$i]["id"];
            $tracker=$ttt[$i]['tracker'];
            $status=$ttt[$i]['status'];
            $priority=$ttt[$i]['priority'];
            $cl="";
            if($priority=="Medium")
            {
$cl="cornsilk";
            }
            elseif($priority=="High")
            {
                $cl="red";
            }
            elseif($priority=="Immediate")
            {
                $cl="#aa7700";
            }

            $subject=$ttt[$i]['subject'];
            $assignee=$ttt[$i]['assignee'];
            $updated=$ttt[$i]['last_updated'];
            $initiator=$ttt[$i]['initiator'];
            $myid="q".$id;


echo "<tr id='$myid' class='hv w3-hover-shadow'><td><span class='badge'> $id </span></td><td>$tracker</td><td>$status</td><td style='background-color: $cl'>$priority</td><td><span onclick=\"openNow('$id')\" style='color: green;cursor: pointer'>$subject</span></td><td>$assignee</td><td>$updated</td><td>$initiator</td>";
        }
        ?>

        </tbody>
    </table>

    <div id="myinfo" style="display: none; border-top: groove;border-top-color: deepskyblue" class="alert alert-warning w3-animate-bottom"></div>
    <hr>

    <?php
$closed="Closed";
    $fbStmt = $conn->prepare($new->sql1);
    $fbStmt->bindParam(':closed', $closed, PDO::PARAM_STR);
   // $fbStmt->bindParam(':st', $new->st, PDO::PARAM_STR);
    $fbStmt->execute();

    $row = $fbStmt->rowCount();
    $num=$row;

    $total_records = $fbStmt->rowCount();
    $total_pages = ceil($total_records / $limit);
    $pagLink = "<nav><ul class='pagination'>";
    for ($i=1; $i<=$total_pages; $i++) {
        $pagLink .= "<li><a href='issues.php?page=".$i."'>".$i."</a></li>";
    }
    echo $pagLink . "</ul></nav>";



    ?>

    <script type="text/javascript">
        $(document).ready(function(){
            $('.pagination').pagination({
                items: <?php echo $total_records;?>,
                itemsOnPage: <?php echo $limit;?>,
                cssStyle: 'light-theme',
                currentPage : <?php echo $page;?>,
                hrefTextPrefix : 'issues.php?page='
            });
        });
    </script>
</div>
<?php
include "footer.php";
?>
<script>
    $(document).ready(function() {
        $('select').formSelect();
    } );
</script>
