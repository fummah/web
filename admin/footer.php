<?php
session_start();
error_reporting(0);
if(!isset($_SESSION['logxged']))
{
    header("Location:login.html");
    die();

}
if(isset($_SESSION['my_code']) && !empty($_SESSION['my_code'])) {
    $cnn = connection('doc', 'doctors');
    $ss = $cnn->prepare("SELECT session_code FROM staff_users WHERE user_id=:id");
    $ss->bindParam(':id', $_SESSION['my_id'], PDO::PARAM_STR);
    $ss->execute();
    $code = $ss->fetchColumn();
    if($code!=$_SESSION['my_code'])
    {

        $d=$_SESSION['my_code'];
        unset($_SESSION['my_id']);
        unset($_SESSION['user_id']);
        unset($_SESSION['myUsername']);
        unset($_SESSION['level']);
        unset($_SESSION['role_value']);
        unset($_SESSION['logxged']);
        unset($_SESSION['sitePath']);
        unset($_SESSION['my_code']);
        unset($_SESSION['LAST_ACTIVITY']);
        unset($_SESSION['logged_in']);
        session_unset();
        session_destroy();
        session_write_close();
        die("<script>alert('Warning: Duplicate sessions, please login again' );location.href = \"login.html\";</script>");
    }

}
?>
<script>

    $(document).ready(function () {
        var current_role = "<?php echo $_SESSION['level'];?>";

        var roles_value = "<?php echo $_SESSION['role_value'];?>";
        var roles=[current_role];
        if (roles_value > 2) {
            roles.push("admin","controller","claims_specialist");
        }
        else if (roles_value == 2)
        {
            roles.push("controller","claims_specialist");
        }
        else if (roles_value == 1)
        {
            roles.push("claims_specialist");
        }


        var num=roles.length;
        var dup=[];
        for (i=0;i<num;i++)
        {
            if(dup.indexOf(roles[i])<0)
            {
                dup.push(roles[i]);
                $("#roleComb").append("<option value='"+roles[i]+"'>"+roles[i]+"</option>");
            }

        }
        $("#roleComb").change(function () {
            var sele=$("#roleComb").val();
            $.ajax({

                url:"ajaxPhp/deleting.php",
                type:"GET",
                data:{
                    vall:sele,
                    identity:17
                },
                success:function(data)
                {

                },
                error:function(jqXHR, exception)
                {

                }
            });
        });
        var url="<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>";
     
        $.ajax({

            url:"ajaxPhp/ajaxRetrieve.php",
            type:"GET",
            data:{
                url:url,
                identityNum:15
            },
            success:function(data)
            {
                //console.log(data);
            },
            error:function(jqXHR, exception)
            {

            }
        });
        
    });

</script>
<footer>
    <div class="header">
        <p align="center">
            <img style="width: 30%; height: auto;" id="ximg" src="images\Med ClaimAssist Logo_1000px.png" >
            <span style="bottom: 0px; position: fixed; font-size: 20px; background-color: black; right: 0px;"><b style="color: deepskyblue">
 <select id="roleComb">
            </select>
</b></span>
        </p>
        <p style="width: 100%; background-color: black; height: 5px"></p>
</footer>