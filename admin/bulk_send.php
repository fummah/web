<?php

session_start();
error_reporting(0);


?>
<html>
<head>

    <title>MCA Administration System</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bootstrap3/css/bootstrap.min.css">
    <script src="bootstrap3/js/bootstrap.min.js"></script>
    <script src="jquery/jquery.min.js"></script>
    <script src="js/jquery-1.12.4.js"></script>
    <link rel="stylesheet" href="admin_main/plugins/datatables-bs4/css/dataTables.bootstrap4.css">  <!-- Theme style -->
    <script src="admin_main/plugins/datatables/jquery.dataTables.js"></script>
    <script src="admin_main/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
    <script src="js/newCase.js"></script>
    <script src="js/search.js"></script>
    <link href="w3/w3.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="uikit/css/uikit.min.css"/>
    <script src="uikit/js/uikit.min.js"></script>
    <script src="uikit/js/uikit-icons.min.js"></script>
    <link rel="stylesheet" href="../admin/admin_main/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../admin/admin_main/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <script>
        var total_selected=0;
        var total_incremented=0;
        function hid()
        {
            var nna="No";
            $vval=0;
            var radios = document.getElementsByName('radio2');
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
                $vval=1;
                document.getElementById('mmm').style.display='block';
                document.getElementById('eem').style.display='none';

            }
            else
            {
                $vval=0;
                document.getElementById('mmm').style.display='none';
                document.getElementById('eem').style.display='block';
            }
            return $vval;
        }

        function send() {
            $("#pxl").text(total_incremented);
            $("#pl").show();
            var limit=$("#limit").val();
            var users=$("#users").val();
            var subject=$("#subject").val();
            var body=$("#body").val();
            var way=hid();
           total_selected=2000;
            var limit1=20;
            var obj={

                limit:limit1,
                way:way,
                users:users,
                subject:subject,
                body:body
            };
            $.ajax({
                url:"ajaxPhp/sendBulk.php?identity=1",
                type:"GET",
                data:obj,
                success:function(data){
                    $("#info").html(data);
                    $("#pl").hide();
                   total_incremented+=limit1;

                   if(total_incremented<=total_selected)
                    {
                        sample();
                   }
                },
                error:function(jqXHR, exception)
                {
                    $("#pl").hide();
$("#info").html(jqXHR.responseText);
                    sample();
                }
            });
        }
        async function delay(delayInms) {
            return new Promise(resolve  => {
                setTimeout(() => {
                    resolve(2);
                }, delayInms);
            });
        }
        async function sample() {
            console.log('a');
            console.log('waiting...')
            let delayres = await delay(3000);
            console.log('b');
            var myPlayer = document.getElementById("cc");
            myPlayer.click();
        }
    </script>
    <style>
        .na{
            width: 50%;
            position: center;
            margin: auto;
            padding: 20px;
        }
        #table-wrapper {
            position:relative;
        }
        #table-scroll {
            height:200px;
            overflow:auto;

        }
        #table-wrapper table {
            width:100%;
            border-top: 1px solid rgba(33, 114, 112, 0.97);

        }
        #table-wrapper table * {

        }
        #table-wrapper table thead th .text {
            position:absolute;
            top:-20px;
            z-index:2;
            height:20px;
            width:35%;
            border:1px solid red;
        }
    </style>
</head>
<body>

<div class="na" style="border-bottom: 1px solid rgba(33, 114, 112, 0.97);border-top: 1px solid rgba(33, 114, 112, 0.97);border-right: 1px solid rgba(33, 114, 112, 0.97);border-left: 1px solid rgba(33, 114, 112, 0.97);margin-top: 20px;border-radius: 3px">

    <fieldset class="uk-fieldset">

        <legend class="uk-legend" align="center">Bulk email distribution</legend>
        <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
            <label><input class="uk-radio" type="radio" name="radio2" value="Yes" checked onclick="hid()"> Select Bulk</label>
            <label><input class="uk-radio" type="radio" name="radio2" value="No" onclick="hid()"> Select Specific Emails</label>
        </div>
        <div class="uk-margin" id="mmm">
            <select class="uk-select" style="width: 20%" id="limit">
                <option value="">[select]</option>
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="500">500</option>
            </select>
        </div>
        <span id="eem" style="display: none">
        <select class="select2bs4" multiple="multiple" id="users" data-placeholder="select email address" style="width: 100%;">

            <?php
            session_start();
            $_SESSION['start_db']=true;
            require_once('dbconn.php');
            $subject="Communication Request";
            $conn = connection("mca", "MCA_admin");
            $stmt=$conn->prepare('SELECT DISTINCT email FROM member where email NOT IN(SELECT email FROM bulk_emails where subject =:subject) AND LENGTH(email)>4 LIMIT 50');
            $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
            $stmt->execute();
            foreach($stmt->fetchAll() as $row)
            {
                echo " <option>$row[0]</option>";
            }
            echo " <option>tendai@medclaimassist.co.za</option>";
            echo " <option>faghry@medclaimassist.co.za</option>";
            ?>
        </select>
            </span>
        <div class="uk-margin">
            <input class="uk-input" type="text" id="subject" style="color: rgba(33, 114, 112, 0.97)" value="Communication Request" placeholder="Enter your subject here" disabled>
        </div>



        <div class="uk-margin">
            <textarea class="uk-textarea" rows="5" id="body" placeholder="Message Body..."></textarea>
        </div>


        <button id="cc" class="uk-button uk-button-default uk-button-small" onclick="send()"><span uk-icon="icon: mail"></span> Send Now</button>
        <span id="pl" style="color: red;display: none">please wait....</span>


    </fieldset>
    <span id="pxl">0</span>
    <span id="info" class="na">

</span>
</div>

</body>
<script src="../admin/admin_main/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../admin/admin_main/plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="../admin/admin_main/plugins/select2/js/select2.full.min.js"></script>
<script src="../admin/admin_main/dist/js/graphs.js"></script>
</html>