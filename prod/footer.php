<?php
if(!defined('access')) {
    die('Access not permited');
}
if(isset($_SESSION['my_code']) && !empty($_SESSION['my_code'])) {
    $code_data=$control->viewUserById($_SESSION['my_id']);
    $code = $code_data["session_code"];
    if($code!=$_SESSION['my_code'])
    {
        /*
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
        */
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
                console.log(roles[i]);
                $("#roleComb").append("<option value='"+roles[i]+"'>"+roles[i]+"</option>");
                console.log("testts");
            }

        }
        $("#roleComb").change(function () {
            var sele=$("#roleComb").val();
            $.ajax({

                url:"ajax/claims.php",
                type:"POST",
                data:{
                    vall:sele,
                    identity_number:29
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

            url:"ajax/claims.php",
            type:"POST",
            data:{
                url:url,
                identity_number:28
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
<span style="position: fixed !important; right: 0; bottom: 0; background-color: white; width: 10%; text-align: right">


 <select class="escl" id="roleComb" style="width: 50% !important">
            </select>

     </span>
