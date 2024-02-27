<?php
session_start();
//error_reporting(0);
define("access",true);
require "classes/controls.php";
$control=new controls();
if(!$control->isInternal())
{
    die("Invalid entry");
}
include ("header.php");
?>
<html>
<head>

    <title>MCA | Consent Forms</title>
    <script type="application/javascript">
        function del(scheme,owner,id) {

            var obj={
                scheme:scheme,
                owner:owner,
                id:id,
                identityNum: 7
            };
            $.ajax({

                url: "ajax/ajaxRetrieve.php",
                type: "GET",
                data: obj,
                async: false,
                success: function (data) {
                    var t=data.indexOf("yes");

                    if(t>=0){

                        var k=id+"x";
                        //$("#"+id+"x").css("backgound-color","red");
                        document.getElementById(k).style.backgroundColor="red";
                    }
                    else
                    {
                        alert("Failed");
                    }                        },
                error: function (jqXHR, exception) {
                    alert(jqXHR.responseText);
                }
            });


        }
        ///////
        function show(owner) {

            var scheme=$("#scheme").val();

            if(scheme!=""){

                var obj={
                    scheme:scheme,
                    owner:owner,
                    identityNum: 8
                };

                $.ajax({

                    url: "ajax/ajaxRetrieve.php",
                    type: "GET",
                    data: obj,
                    async: false,
                    success: function (data) {
                        var t=data.indexOf("failed");

                        if(t>=0){

                            $("#uploads").show();
                            $("#main").hide();
                        }
                        else
                        {
                            $("#main").html(data);
                            $("#main").show();
                            $("#uploads").hide();
                        }
                    },
                    error: function (jqXHR, exception) {
                        alert(jqXHR.responseText);
                    }
                });
            }
        }
   function configureEmail() {

            var email=$("#email").val();
            var password=$("#password").val();

            if(email!=""){

                var obj={
                    email:email,
                    password:password,
                    identityNum: 11
                };

                $.ajax({

                    url: "ajax/ajaxRetrieve.php",
                    type: "GET",
                    data: obj,
                    async: false,
                    success: function (data) {
                        $("#iinfo").html(data);
                    },
                    error: function (jqXHR, exception) {
                        alert(jqXHR.responseText);
                    }
                });
            }
        }
    </script>
</head>
<body>
<?php
$username=$control->loggedAs();
require_once('dbconn.php');
$mess="";
$conn = connection("mca", "MCA_admin");

$sql = 'SELECT DISTINCT client_name,client_id FROM clients ORDER BY client_name ASC';
$selectForms = 'SELECT DISTINCT *FROM consent ORDER BY scheme ASC';
if ($_SESSION['level'] == "claims_specialist") {
    $selectForms = 'SELECT DISTINCT *FROM consent WHERE owner=:owner ORDER BY scheme ASC';
}

if (isset($_POST['upload'])) {

    $allowedExts= ["pdf","application/pdf"];
    $fileExtensions = ["pdf","application/pdf"];

    try {
        $scheme = filter_var($_POST['scheme'], FILTER_SANITIZE_STRING);
        if (isset($_FILES['file']) && is_file($_FILES['file']['tmp_name'])) {

            $type = basename($_FILES['file']['type']);
            $size = basename($_FILES['file']['size']);
            $nname = basename($_FILES['file']['name']);
            $fileExtension = basename($_FILES['file']['type']);
            $temp = explode(".", $_FILES["file"]["name"]);
            $presentExtention = end($temp);
            $nux=substr_count($nname, '.');
            if (in_array($presentExtention, $allowedExts) && strlen($nname) < 100 && $nux==1 && $size > 0) {

                if (in_array($fileExtension, $fileExtensions) && ($size < 20000000)) {

                    $target = "../../mca/schemes/";
                    $target = $target . $username . basename($_FILES['file']['name']);
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
                        $nname=filter_var($nname, FILTER_SANITIZE_STRING);
                        $sql = $conn->prepare('INSERT INTO consent(scheme,owner,doc_name) VALUES(:scheme,:owner,:doc)');
                        $sql->bindParam(':scheme', $scheme, PDO::PARAM_STR);
                        $sql->bindParam(':owner', $username, PDO::PARAM_STR);
                        $sql->bindParam(':doc', $nname, PDO::PARAM_STR);
                        $nu = $sql->execute();
                        if ($nu == 1) {
                            $mess = "<span class=\"notice\" style=\"color: green\">Your file has been uploaded.</span>";
                        } else {
                            $mess = "<span class=\"notice\" style=\"color: red\">Sorry, Failed to add consent.</span>";
                        }

                    } else {
                        $mess = "<span class=\"notice\" style=\"color: red\">Sorry, Failed to upload.</span>";
                    }

                } else {
                    $mess = "<span class=\"notice\" style=\"color: red\">Sorry, Incorrect File format.</span>";
                }
            }
        }
        else
        {
            $mess = "<span class=\"notice\" style=\"color: red\">Sorry, Incorrect File format.</span>";
        }
    } catch (Exception $e) {
        $mess = "There is an error";
    }

}

$connx = connection("doc", "doctors");

$rr=$connx->prepare("select email,email_password from staff_users where username=:user1");
$rr->bindParam(':user1', $username, PDO::PARAM_STR);
$rr->execute();
$det=$rr->fetch();
//print_r($det);
$email=$det[0];
$pass=$det[1];

$cryptKey="MCA201734X$";
$qDecoded=openssl_decrypt($pass,"AES-128-ECB",$cryptKey);
?>
<br><br>
<div>

    <div style="position: relative;width: 60%;margin-left: auto;margin-right: auto">
        <fieldset>
            <legend>Email Details</legend>
            <table width="100%"><tr>
                    <td>Email<input type="text" class="form-control" id="email" value="<?php echo $email;?>"></td>
                    <td>Email Password <input type="password" class="form-control" id="password" value="<?php echo $qDecoded;?>"></td>
                    <td>. <button class="btn btn-info form-control" onclick="configureEmail()">Save</button><span id="iinfo"></span> </td>
                </tr></table>

<p align="center" style="color: red; font-style: oblique">Configure your email address inorder to be able to send consent forms automatically.</p>
        </fieldset>
    </div>
<div>

    <div class="container" style="width: 70%">
        <span id="ff" style=""></span>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
              enctype="multipart/form-data">
            <h2 align="center"><u>Consent Forms</u></h2>
            <?php
            echo $mess;
            ?>
            <div class="row">
                <div class="col-sm-12">
                    <label> Medical Schemes : </label><select name="scheme" id="scheme"
                                                              onchange="show('<?php echo $username; ?>')">
                        <option value="">[Select Medical Scheme]</option>
                        <?php
                        $sql = 'SELECT DISTINCT name FROM schemes ORDER BY name ASC';
                        $r = $conn->query($sql);
                        foreach ($r as $row) {

                            ?>
                            <option value="<?php echo $row['name']; ?>"><?php echo $row['name']; ?></option>
                            <?php
                        }
                        ?>
                    </select></div>
            </div>
            <div class="row" id="uploads" style="display: none">
                <hr>
                <div class="col-sm-8">
                    <input type="file" name="file" id="file" size="30">
                </div>
                <div class="col-sm-4">
                    <button type="submit" name="upload"
                            class="w3-btn w3-white w3-border w3-border-blue w3-round-large glyphicon glyphicon-open">
                        Upload now
                    </button>
                </div>
            </div>
                    </form>
        <span id="main">
<?php
$stm = $conn->prepare($selectForms);
if ($_SESSION['level'] == "claims_specialist") {
$stm->bindParam(':owner', $username, PDO::PARAM_STR);
}
$stm->execute();
$count = $stm->rowCount();
if ($count > 0) {
    echo "<table border='1' align='center' width='70%' class='striped uk-table'>";
    echo "<caption><h4 align='center'><u>Available Consent Forms</u></h4></caption>";
    echo "<tr>";
    echo "<th>";
    echo "Medical Scheme";
    echo "</th>";
    echo "<th>";
    echo "Consent Form";
    echo "</th>";
    echo "<th>";
    echo "Owner";
    echo "</th>";
    echo "<th>";
    echo "</th>";
    echo "</tr>";
    $nn = 0;
    foreach ($stm->fetchAll() as $row) {
        $nn += 1;
        $idd = $nn . "x";
        $desc = "../../mca/schemes/" . $row[1] . $row[2];
        echo "<tr id='$idd'>";
        echo "<td>";
        echo $row[0];
        echo "</td>";
        echo "<td>";
        // echo "<a href='$desc' target=\"popup\" onclick=\"window.open('$desc','popup','width=1000,height=800'); return false;\">" . $row[2] . "</a>";
        echo "<form action='view_file.php' method='post' target=\"print_popup\" onsubmit=\"window.open('edit_hospital.php','print_popup','width=1000,height=800');\"/><input type=\"hidden\" name=\"my_doc\" value=\"$desc\" />
<input type=\"submit\" class=\"linkbutton\" name=\"doc\" value=\"$row[2]\">
</form>";
        echo "</td>";
        echo "<td>";
        echo $row[1];
        echo "</td>";
        echo "<td>";
        echo "<span title='delete form' style='color: red;cursor: pointer' uk-icon='trash' onclick='del(\"$row[0]\",\"$row[1]\",\"$nn\")'></span>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";

} else {
    ?>
    <h3 align="center" style="color: #00b3ee">No Consent Forms</h3>
    <?php
}
?>
    </span>
    </div>

    <?php
    include "footer.php";
    ?>
    <script>
        $(document).ready(function() {
            $('select').formSelect();
        } );
    </script>
</div>
</body>
</html>