<?php
session_start();

error_reporting(0);
?>
<html>
<head>
    <title>Edit Doctor</title>
    <link rel="stylesheet" href="bootstrap3/css/bootstrap.min.css">
    <script src="jquery/jquery.min.js"></script>
    <script src="bootstrap3/js/bootstrap.min.js"></script>
    <script src="jquery/jquery.js"></script>
    <link href="w3/w3.css" rel="stylesheet" />
    <link rel="stylesheet" href="uikit/css/uikit.min.css" />
    <script src="uikit/js/uikit.min.js"></script>
    <script src="uikit/js/uikit-icons.min.js"></script>
    <script>
        doctor_details=[];
        function hid()
        {
            var nna="No";
            var radios = document.getElementsByName('discount');
            for (var i = 0, length = radios.length; i < length; i++) {
                if (radios[i].checked) {
                    // do whatever you want with the checked radio
                    nna=(radios[i].value);

                    // only one radio can be logically checked, don't check the rest
                    break;
                }
            }

            if(nna=="Yes")
            {
                document.getElementById('hhid').style.display='block';

            }
            else
            {
                document.getElementById('hhid').style.display='none';
            }

        }
        function hi12()
        {

            var nna="R";
            var radios = document.getElementsByName('discount_v');
            for (var i = 0, length = radios.length; i < length; i++) {
                if (radios[i].checked) {
                    // do whatever you want with the checked radio
                    nna = (radios[i].value);

                    // only one radio can be logically checked, don't check the rest
                    break;
                }
            }

            if(nna=="P")
            {
                document.getElementById('discount_perc').style.display='block';
                document.getElementById('discount_value').style.display='none';

            }
            else
            {
                document.getElementById('discount_value').style.display='block';
                document.getElementById('discount_perc').style.display='none';

            }

        }
        function show123()
        {
            //alert("Alert");


            if(document.getElementById('signed').checked==true)
            {
                document.getElementById('mmd').style.display='block';
            }
            else
            {
                document.getElementById('mmd').style.display='none';
            }

        }

        function add()
        {
            var element = {};
            var vl=$("#discount_perc").val();
            var vl1=$("#discount_value").val();
            var days_number=$("#days_number").val();
            var nna="R";
            var mesg="";
            var radios = document.getElementsByName('discount_v');
            for (var i = 0, length = radios.length; i < length; i++) {
                if (radios[i].checked) {
                    // do whatever you want with the checked radio
                    nna = (radios[i].value);

                    // only one radio can be logically checked, don't check the rest
                    break;
                }
            }

            element.main_value =nna;
            element.discount_perc = vl;
            element.discount_value = vl1;
            element.days_number = days_number;

            var count=0;
            for (var key in doctor_details) {
                if(days_number==doctor_details[key].days_number)
                {
                    count=1;
                }
            }
            if(nna=="P")
            {
                if(count<1 && vl.length>0)
                {
                    doctor_details.push(element);
                    mesg="<br><span uk-icon=\"check\"></span> "+vl+"% discount if the claim is "+days_number+" days";
                }


            }
            else
            {
                if(count<1 && vl1.length>0)
                {
                    doctor_details.push(element);
                    mesg="<br><span uk-icon=\"check\"></span> R "+vl1+" discount if the claim is "+days_number+" days";
                }

            }

            var json = JSON.stringify(doctor_details);
            $("#dr_value").val(json);
            $("#txt").append(mesg);
        }

        function del(id) {
            var obj={

                del_id:id
            };
            $.ajax({
                url:"ajaxPhp/deleting.php?identity=33",
                type:"GET",
                data:obj,
                success:function(data){
                    if(data==1)
                    {
                        $("#x"+id).css("color","red")
                    }
                    else
                    {
                        alert("Failed to delete");
                    }

                },
                error:function(jqXHR, exception)
                {
                    alert(jqXHR.responseText);
                }
            });

        }

        function addNote() {

            var notes= $("#mynotes").val();
            var author="<?php echo $_SESSION["user_id"];?>";
            var mytime=currentDate();
            var doc_id=$("#id").val();


if(notes!="") {

    var obj={

        doc_id:doc_id,
        author:author,
        notes:notes
    };
    $.ajax({
        url:"ajaxPhp/deleting.php?identity=36",
        type:"GET",
        data:obj,
        success:function(data){
            if(data==1)
            {
                $("#artsec").append("<article class=\"uk-comment\"><header class=\"uk-comment-header\"><div class=\"uk-grid-medium uk-flex-middle\" uk-grid><div class=\"uk-width-expand\">" +
                    "<ul class=\"uk-comment-meta uk-subnav uk-subnav-divider uk-margin-remove-top\">" +
                    "<li><a href=\"#\" id=\"mytime\">" + mytime + "</a></li><li><a href=\"#\" id=\"mytime\">" + author + "</a></li></ul></div></div></header><div class=\"uk-comment-body\"><p>" + notes + "</p></div></article><hr>");

                $("#mynotes").val("");
            }
            else
            {
                alert("Failed to update");
            }

        },
        error:function(jqXHR, exception)
        {
            alert(jqXHR.responseText);
        }
    });


}

        }


        function currentDate()
        {
            var currentTime = new Date();
            hour = currentTime.getHours();
            min = currentTime.getMinutes();
            mon = currentTime.getMonth() + 1;
            day = currentTime.getDate();
            year = currentTime.getFullYear();
            if (mon.toString().length == 1) {
                var mon = '0' + mon;
            }
            if (day.toString().length == 1) {
                var day = '0' + day;
            }
            if (hour.toString().length == 1) {
                var hour = '0' + hour;
            }
            if (min.toString().length == 1) {
                var min = '0' + min;
            }

            var gg = year + "-" + mon + "-" + day + " " + hour + ":" + min;

            return gg;
        }
    </script>

    <style type="text/css">

        input[type=text],input[type=date],input[type=number]{
            width:250px;

        }
        input[type=email]{
            width:250px;
        }
        #ali{
            position: relative;
            width: 60%;
            margin-left: auto;
            margin-right: auto;


        }
        .uk-select{
            width: 250px;
        }
    </style>
</head>
</head>

<body>
<?php

include("header.php");
echo "<br>
<br>
<br>
<br>
<br>";

include("dbconn.php");
if(isset($_POST['btn']))

{
    $doc_id=(int)$_POST['doc_id'];
    $name= '';
    $surname= '';
    $telephone= '';
    $admin= '';
    $discount= '';
    $displine= '';
    $displine_type= '';
    $practice_number='';
    $subcode = '';
    $discipline = '';
    $subdesr = '';
    $did = '';
    $email = '';
    $dr_value = '';
    $days_number = '';
    $signed = '';
    $date_joined = '';
    $chck='';


    $conn=connection("mca","MCA_admin");
    $sql="SELECT doc_id,name_initials,surname,telephone,admin_name,gives_discount,discipline,practice_number,disciplinecode,sub_disciplinecode,sub_disciplinecode_description,disciplinecode_id,email,dr_value,days_number,signed,date_joined,date_entered,entered_by FROM doctor_details WHERE doc_id=:num LIMIT 1";
    $sql = $conn->prepare($sql);
    $sql->bindParam(':num', $doc_id, PDO::PARAM_STR);
    $sql->execute();
    $nu=$sql->rowCount();

    if($nu>0)
    {
        foreach ($sql->fetchAll() as $row)
        {
            $practice_number= $row[7];
            $name= $row[1];
            $surname= $row[2];
            $telephone= $row[3];
            $admin= $row[4];
            $discount= $row[5];
            $displine= $row[6];
            $displine_type= $row[8];
            $subcode= $row[9];
            $subdesr= $row[10];
            $did= $row[11];
            $email= $row[12];
            $dr_value= $row[13];
            $days_number= $row[14];
            $signed= (int)$row[15]==1?"checked":"";
            $chck= (int)$row[15]==1?"block":"none";
            $date_joined= $row[16];
            $date_entered= $row[17];
            $entered_by= $row[18];

        }
    }
    ?>
    <div class="container">
    <div id="ali">
        <h3 align="center"><u>Edit the doctor's details below</u></h3>
        <h5 align="center" style="color:red; font-style: italic;">Please be careful with numbers and spelling</h5><hr>
        <form name="form" action = "add_doctor.php" method="post">


                <div class="row">
                    <div class="col-xs-6"><b>First Name or Initials</b><br>
                        <input type="text" name="name" value="<?php echo $name; ?>" class="uk-input" REQUIRED>
                        <input style="display:none" type="text" name="id" id="id" value="<?php echo $doc_id; ?>" class="form-control input-sm">
                    </div>
                    <div class="col-xs-6"><b>Surname</b><br>
                        <input type="text" name="surname" value="<?php echo $surname; ?>" class="uk-input" REQUIRED></div>
                </div>

                <div class="row">
                    <div class="col-xs-6">
                        <b>Discipline</b><br>
                        <?php
                        $st=$conn->prepare('SELECT *FROM disciplinecodes');
                        $st->execute();
                        echo "<select style=\"width: 250px\" id=\"displine_type\" name=\"displine_type\" class=\"uk-select\">";
                        echo "<option value='$did'>$displine_type --- $subcode --- $displine --- $subdesr</option>";
                        foreach ($st->fetchAll() as $row)
                        {
                            echo "<option value=\"$row[0]\">$row[1] --- $row[2] --- $row[3] --- $row[4]</option>";
                        }
                        echo "</select>";
                        ?>
                    </div>
                    <div class="col-xs-6">       <b>The doctor's practice code/number</b><br>
                        <input type="text" name="practice_code" value="<?php echo $practice_number; ?>" class="uk-input" REQUIRED>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <b>Admin person or receptionist's name</b><br>
                        <input type="text" name="receptionist" value="<?php echo $admin; ?>" class="uk-input">
                    </div>
                    <div class="col-xs-6">
                        <b>Practice telephone number</b><br>
                        <input type="text" name="telephone" value="<?php echo $telephone; ?>" class="uk-input">
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6">
                        <b>Email address</b><br>
                        <input type="email" name="email" class="uk-input" value="<?php echo $email; ?>">
                    </div>
                    <div class="col-xs-6"><b>
                            <input style="display: none" type="text" name="disc" id="disc" value="<?php echo $discount; ?>" class="form-control input-sm">
                            Does the doctor give a discount?</b>
                        <br>
                        <input type="radio" name="discount" id="yes" class="uk-radio" value="Yes" onclick="hid()"> Yes
                        <br>
                        <input type="radio" name="discount" id="no" class="uk-radio" value="No" onclick="hid()"> No
                    </div>

                </div>
                <span id="hhid" style="display: none;">
            <div class="row" >
                <hr>
                <div class="col-xs-2">

                    <b>Signed Contract?</b>

                </div>
                 <div class="col-xs-4">
                     <input type="checkbox" class="uk-checkbox" name="signed" id="signed" onclick="show123()" <?php echo $signed;?>>

                </div>
                <span style="display: <?php echo $chck;?>" id="mmd">
                <div class="col-xs-3">
                    <b>Date Joined</b><br>
                  <input type="date" class="uk-input" name="date_joined" id="date_joined" value="<?php echo $date_joined;?>">
                </div>
                <div class="col-xs-3">

                </div>
                </span>
            </div>
                <div class="row uk-card uk-card-default uk-card-body" >
     <div class="col-xs-6">
          <b> <span style="color: #0c85d0">Discount</span> (%) <input type="radio" name="discount_v" class="uk-radio" value="P" onclick="hi12()" checked> / <input type="radio" name="discount_v" class="uk-radio" value="R" onclick="hi12()"> (Value)</b><br>

                <select class="uk-select" name="discount_perc" id="discount_perc">
                        <option value="">select</option>
                        <option value="5">5%</option>
                        <option value="10">10%</option>
                        <option value="15">15%</option>
                        <option value="20">20%</option>
                    </select>
                    <input type="number" class="uk-input" name="discount_value" id="discount_value" placeholder="R" style="display: none">

                    <input type="hidden" id="dr_value" name="dr_value">

                </div>
                <div class="col-xs-3">

                    <b>Number of Days</b><br>
                    <select class="uk-select" id="days_number" name="days_number" uk-tooltip="title: Days less than : ; pos: top-right">
                        <option value="">select</option>
                        <option value="< 30">< 30</option>
                        <option value="30 - 39">30 - 39</option>
                        <option value="40 - 59">40 - 59</option>
                        <option value="60 - 100">60 - 100</option>

                    </select>
                </div>
                    <div class="col-xs-3"><br>
<span class="uk-margin-small-right uk-icon-button" style="cursor: pointer; color: #0c85d0" uk-tooltip="title: Add Details ; pos: top-right" uk-icon="check" onclick="add()"></span>
                    </div>

            </div>

                    <div class="uk-text-success" id="txt">
                        <?php

                        $st=$conn->prepare('SELECT dr_value,discount_perc,discount_value,days_number,id FROM discount_details WHERE practice_number=:pra AND status=1');
                        $st->bindParam(':pra', $practice_number, PDO::PARAM_STR);
                        $st->execute();
                        foreach ($st->fetchAll() as $row)
                        {

                            $idd=$row[4];
                            $hhid="x".$idd;
                            $mesg=$row[0]=="P"?"<br><span uk-icon=\"close\" style='color: red; cursor: pointer' onclick='del($idd)'></span> <span uk-icon=\"check\" id='$hhid'></span> ".$row[1]."% discount if the claim is ".$row[3]." days":"<br><span uk-icon=\"close\" style='color: red; cursor: pointer'></span> <span uk-icon=\"check\"></span> R ".$row[2]." discount if the claim is ".$row[3]." days";
                            echo $mesg;
                        }
                        ?>
                    </div>

    </span>

                <hr>
                <div class="row">
                    <div class="col-xs-6">
                        <button type="submit" class="uk-button uk-button-primary" name="edit">Save Changes</button>

                    </div>

                </div>
  <p><b><u>Notes</u></b></p>
            <div class="row uk-card uk-card-default uk-card-body">
                <div class="col-xs-12">
                    <span id="artsec">

                        <?php
                        $st=$conn->prepare('SELECT description,date_entered,entered_by FROM doctor_notes WHERE doctor_id=:doctor_id');
                        $st->bindParam(':doctor_id', $doc_id, PDO::PARAM_STR);
                        $st->execute();
                        foreach ($st->fetchAll() as $row)
                        {
                            $notes=$row[0];
                            $mytime=$row[1];
                            $author=$row[2];
                            echo "      <article class=\"uk-comment\">
                        <header class=\"uk-comment-header\">
                            <div class=\"uk-grid-medium uk-flex-middle\" uk-grid>
                                <div class=\"uk-width-expand\">
                                  
                                    <ul class=\"uk-comment-meta uk-subnav uk-subnav-divider uk-margin-remove-top\">
                                        <li><a href=\"#\" id=\"mytime\">$mytime</a></li>
                                        <li><a href=\"#\">$author</a></li>
                                    </ul>
                                </div>
                            </div>
                        </header>
                        <div class=\"uk-comment-body\">
                            <p>$notes</p>
                        </div>
                    </article><hr>";
                        }
                        ?>



                        </span>
                    <textarea class="uk-textarea" style="width: 100%" id="mynotes"></textarea>

<br><br>

                    <span class="uk-button uk-button-primary" onclick="addNote()">Post</span>
                </div>
            </div>

            <?php

            $st1=$conn->prepare('SELECT practice_number,gives_discount,updated_date,changed_by FROM doctor_details_log WHERE practice_number=:pra ORDER BY updated_date DESC');
            $st1->bindParam(':pra', $practice_number, PDO::PARAM_STR);
            $st1->execute();

            $color=$st1->rowCount()>0?"color: #0c85d0":"color: red";
            $stmtnt=$st1->rowCount()>0?"View Transaction":"No Transaction";;
            ?>
            <div class="row">
                <div class="col-xs-12 ali">
                    <ul class="uk-nav-default uk-nav-parent-icon ali" uk-nav>
                        <li class="uk-parent ali">
                            <a href="#" style="<?php echo $color;?>"><span style="<?php echo $color;?>" class="uk-icon-button" uk-icon="icon:  history"></span> <?php echo $stmtnt;?></a>
                            <ul class="uk-nav-sub">
                                <div class="uk-card uk-card-default uk-card-body">
                                    <table class="uk-table">
                                        <thead>
                                        <tr>
                                            <th>Give Discount</th>
                                            <th>Changed By</th>
                                            <th>State Date</th>
                                            <th>End Date</th>
                                        </tr>
                                        <?php
                                        $count=0;
                                        $aarx=array("discount"=>$discount,"changed_by"=>$entered_by,"end_date"=>"to date");
                                        $arr=array();
                                        array_push($arr,$aarx);
                                        foreach ($st1->fetchAll() as $row1)
                                        {
                                            if($discount!=$row1[1])
                                            {
                                                $count++;
                                                $dics=$row1[1];$updated_date=$row1[2];$changed_by=$row1[3];
                                                $myarr=array("discount"=>$dics,"changed_by"=>$changed_by,"end_date"=>$updated_date);
                                                array_push($arr,$myarr);

                                            }
                                            $discount=$row1[1];

                                        }
                                        if($count==0)
                                        {
                                            echo "<h5 align='center' style='color: red'>No changes made</h5>";
                                        }
                                        else
                                        {
                                            $cco=count($arr);
                                            for($i=0;$i<$cco;$i++)
                                            {
                                                $dics=$arr[$i]["discount"];
                                                $start_date=$i==$cco-1?"------":$arr[$i+1]["end_date"];
                                                $end_date=$arr[$i]["end_date"];
                                                $changed_by=$arr[$i+1]["changed_by"];
                                                echo "<tr class='uk-text'><td>$dics</td><td>$changed_by</td><td>$start_date</td><td>$end_date</td></tr>";
                                            }
                                        }


                                        ?>
                                        </thead>
                                    </table>
                                </div>
                            </ul>
                        </li>
                    </ul>
                </div>

            </div>
    </div>
            </div>



        </form>

    </div>
        <script type="text/javascript">

            var txt=document.getElementById("disc").value;
            if(txt=="Yes")
            {
                document.getElementById("yes").checked = true;
                document.getElementById('hhid').style.display='block';

            }
            else if(txt=="No")
            {
                document.getElementById("no").checked = true;
                document.getElementById('hhid').style.display='none';
            }
            else
            {

                //document.getElementById("no").checked = true;
                document.getElementById('hhid').style.display='none';
            }


        </script>

    <hr>
    <?php
}
include('footer.php');
?>
</body>

</html>