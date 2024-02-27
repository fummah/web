<?php
error_reporting(0);
session_start();

if(isset($_SESSION['logxged']) && !empty($_SESSION['logxged'])) {
    require_once('../dbconn1.php');
    $identity = validateXss($_GET['identityNum']);
    $conn = connection("doc", "doctors");
    $conn1 = connection("mca", "MCA_admin");
    $conn2 = connection("cod", "Coding");
    $my_levels=["admin","claims_specialist","controller"];
    if ($identity == 1) {
        try {

            $type = 1;

            if(!in_array($_SESSION["level"],$my_levels))
            {
                die("There is an error");
            }

            $prac_number = validateXss($_GET['practiceNumber']);
            if (!empty($prac_number)) {
                $practice_number = str_pad($prac_number, 7, '0', STR_PAD_LEFT);
                $stmt = $conn1->prepare('SELECT doc_id,name_initials,surname,gender,telephone,gives_discount,discipline,practice_number,lat,`lon`,physad1,physsuburb,town,tel1code FROM doctor_details WHERE practice_number = :num LIMIT 1');
                $stmt->bindParam(':num', $prac_number, PDO::PARAM_STR);
                $stmt->execute();
                $personNum = $stmt->rowCount();
//name_initials,surname,telephone,gives_discount,discipline,practice_number,physad1,town,tel1code,tel2code,tel2,doc_id

                if ($personNum > 0) {
                    foreach ($stmt->fetchAll() as $row) {
                        $discount = $row[5];
                        $doc_id=$row[0];
                        if ($discount === "") {
                            $discount = "<input type=\"radio\" name=\"yes1\" value=\"Yes\" id=\"yes1\" onclick=\"discount($row[7],'Yes',$type)\">Yes<input type=\"radio\" name=\"yes1\" value=\"0\" id=\"no1\" onclick=\"discount($row[7], 'No')\">NO<span style=\"color: #4CAF50\" id=\"conf\"></span>";
                        }
                        ?>
                        <table>
                            <tr style="border-bottom: double; border-color: green;background-color: yellow">
                                <td>Practice Number :</td>
                                <td><b><?php echo str_pad($row[7], 7, '0', STR_PAD_LEFT); ?></b></td>
                            </tr>

                            <tr>
                                <td>Unique ID</td>
                                <td>
                                    <form action="edit_doctor.php" method="post" target="print_popup" onsubmit="window.open('edit_doctor.php','print_popup','width=1000,height=800');">
                                        <input type="hidden" name="doc_id" value="<?php echo $doc_id;?>">
                                        <button class="w3-btn w3-white w3-border w3-border-blue w3-round-large" name="btn" title="edit claim"> <b><span class="glyphicon glyphicon-pencil" style="color: skyblue;cursor: pointer"></span> <?php echo $row[0]; ?></b></button> </form>

                                </td>
                            </tr>

                            <tr>
                                <td>Name</td>
                                <td>: <b><span id="pname"><?php echo $row[1] . " " . $row[2]; ?></span></b></td>
                            </tr>
                            <tr>
                                <td>Gender</td>
                                <td>: <b><?php echo $row[3]; ?></b></td>
                            </tr>
                            <tr>
                                <td>Phone</td>
                                <td>: <b><?php echo "(0" . $row[13] . ") " . $row[4]; ?></b></td>
                            </tr>

                            <tr>
                                <td>Discipline</td>
                                <td>: <b><?php echo $row[6]; ?></b></td>
                            </tr>
                            <tr>
                                <td>Discount</td>
                                <td style="color: #0d92e1">: <b><?php echo $discount; ?></b></td>
                            </tr>
                            <tr>
                                <td>Address</td>
                                <td>: <b><?php echo $row[10] . ", " . $row[11] . ", " . $row[12]; ?></b></td>
                            </tr>

                        </table>

                        <div id="map" style="height: 400px;width: 100%;"></div>
                        <script type="text/javascript">
                            function initMap() {
                                var uluru = {
                                    lat: <?php echo $row[8]; ?>
                                    , lng: <?php echo $row[9]; ?> };
                                var map = new google.maps.Map(document.getElementById('map'), {
                                    zoom: 12,
                                    center: uluru
                                });
                                var marker = new google.maps.Marker({
                                    position: uluru,
                                    map: map
                                });
                            }
                        </script>
                        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAxEPQ45jn7zzBxD2eUfzxqAkFDio7p_6Q&libraries=places&callback=initMap"
                                async defer></script>


                        <?php
                    }
                } else {
                    echo "Invalid Practice Number";
                }
            }
        } catch (Exception $e) {
            echo("There is an error. ");
        }
    } else if ($identity == 2) {
        try {
            if(!in_array($_SESSION["level"],$my_levels))
            {
                die("There is an error");
            }
            $code = validateXss($_GET['icdoCode']);
            if (empty($code)) {
                $code = "XXX";
            }
            $stmt = $conn2->prepare('SELECT `diag_code`,`pmb_code`,`shortdesc` FROM `Diagnosis` WHERE diag_code=:num UNION SELECT `ICD10_Code`,`pmb`,`ICD10_3_Code_Desc` FROM `diagonisis1`  WHERE ICD10_Code=:num');
            $stmt->bindParam(':num', $code, PDO::PARAM_STR);
            $stmt->execute();
            $nu = $stmt->rowCount();
          if ($nu > 0) {

        $row=$stmt->fetch();
        $pmbCode = $row[1];
        $desc = $row[2];
        $valu = strlen($pmbCode);
        if ($valu > 1) {

            echo "Yesxxxq" . $desc;
        } else {
            echo "Noxxxq" . $desc;
        }
        ?>

        <?php

} else {
    echo "Invalid Code";
}
} catch (Exception $e) {
    echo("There is an error.");
}
    } else if ($identity == 3) {
        try {
            if(!in_array($_SESSION["level"],$my_levels))
            {
                die("There is an error");
            }
            $json = array();
            $code = filter_var($_GET['schemeId'], FILTER_SANITIZE_STRING);
            $code = htmlspecialchars($code);
            $code = my_utf8_decode($code);
            $code = trim($code);
            $stmt = $conn1->prepare('SELECT b.option_name FROM schemes as a inner join scheme_options as b on a.id=b.scheme_id  WHERE a.name = :num');
            $stmt->bindParam(':num', $code, PDO::PARAM_STR);
            $stmt->execute();
            $nu = $stmt->rowCount();
            if ($nu > 0) {
                foreach ($stmt->fetchAll() as $row) {
                    $json[] = filter_var($row[0], FILTER_SANITIZE_STRING);

                }

                $myJson = json_encode($json, JSON_NUMERIC_CHECK);
                echo $myJson;
            }

        } catch (Exception $e) {
            echo("There is an error. Please Retry ");
        }
    } else if ($identity == 5) {
        try {
            if ($_SESSION['level'] == "claims_specialist" || $_SESSION['level'] == "admin" || $_SESSION['level'] == "controller") {
                $prac_number = validateXss($_GET['pracN']);
                $val = validateXss($_GET['val']);
                $type = validateXss($_GET['type']);

                $stmt = $conn->prepare('UPDATE person SET gives_discount=:dis WHERE practiceno = :num');
                if ($type == 2) {
                    $stmt = $conn->prepare('UPDATE organisation SET gives_discount=:dis WHERE practiceno = :num');
                } elseif ($type == 3) {
                    $stmt = $conn->prepare('UPDATE details SET gives_discount=:dis WHERE practiceno = :num');
                }
                $stmt->bindParam(':num', $prac_number, PDO::PARAM_STR);
                $stmt->bindParam(':dis', $val, PDO::PARAM_STR);
                $nu = $stmt->execute();

                if ($nu > 0) {
                    echo "Updated!!!";
                } else {
                    echo "Failed!!!";
                }
            } else {
                echo "Failed!!!";
            }

        } catch (Exception $e) {
            echo("There is an error. Please Retry ");
        }
    } else if ($identity == 6) {

        try {
            if(!in_array($_SESSION["level"],$my_levels))
            {
                die("There is an error");
            }
 $client=(int)$_GET["client"];

            if($client==31)
         {
             $stmt = $conn1->prepare('SElECT a.claim_number FROM claim as a inner join member as b on a.member_id=b.member_id WHERE b.client_id=31 ORDER BY claim_number DESC LIMIT 1');

             $stmt->execute();
             if($stmt->rowCount()<1)
             {
                 die("ASPEN0001");
             }
             $row=$stmt->fetch();
             $newClaim = $row[0];
             $str = "1" . substr($newClaim, 5);

             $str = $str + 1;
             $finalG = substr($str, 1);
             echo "ASPEN" . $finalG;
         }
            else
            {
                $stmt = $conn1->prepare('SElECT a.claim_number FROM claim as a inner join member as b on a.member_id=b.member_id WHERE b.client_id=4 ORDER BY claim_number DESC LIMIT 1');

                $stmt->execute();
                $row=$stmt->fetch();
                $newClaim = $row[0];
                $str = "1" . substr($newClaim, 3);

                $str = $str + 1;
                $finalG = substr($str, 1);
                echo "MCA" . $finalG;
            }


        } catch (Exception $e) {
            echo("There is an error.");
        }
    } else if ($identity == 7) {
        $scheme = filter_var($_GET['scheme'], FILTER_SANITIZE_STRING);
        $owner = validateXss($_GET['owner']);
        $authUser = $_SESSION['user_id'];
        if ($owner == $authUser || $_SESSION['level'] == "admin" || $_SESSION['level'] == "controller") {
            $mess = "";
            try {

                $stmt = $conn1->prepare('DELETE FROM consent WHERE scheme=:scheme AND owner=:owner');
                $stmt->bindParam(':scheme', $scheme, PDO::PARAM_STR);
                $stmt->bindParam(':owner', $owner, PDO::PARAM_STR);
                $nu = $stmt->execute();

                if ($nu == 1) {
                    $mess = "yes";
                } else {
                    $mess = "failed";
                }

            } catch (Exception $e) {
                $mess = "failed";
            }
        } else {
            $mess = "failed";
        }
        echo $mess;
    } else if ($identity == 8) {
        if(!in_array($_SESSION["level"],$my_levels))
        {
            die("There is an error");
        }
        $scheme = filter_var($_GET['scheme'], FILTER_SANITIZE_STRING);
        $owner = validateXss($_GET['owner']);
        $mess = "";
        try {
            $stmt = $conn1->prepare('SELECT *FROM consent WHERE scheme=:scheme');
            if ($_SESSION['level'] == "claims_specialist") {
                $stmt = $conn1->prepare('SELECT *FROM consent WHERE scheme=:scheme AND owner=:owner');
                $stmt->bindParam(':owner', $owner, PDO::PARAM_STR);
            }
            $stmt->bindParam(':scheme', $scheme, PDO::PARAM_STR);

            $stmt->execute();
            $nu = $stmt->rowCount();
            if ($nu > 0) {
                echo "<table border='1' align='center' width='70%'>";
                echo "<caption><h4 align='center'>$scheme Consent Form</h4></caption>";
                echo "<tr style='background-color: black;color: white'>";
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
                foreach ($stmt->fetchAll() as $row) {
                    $nn += 1;
                    $idd = $nn . "x";
                    $desc = "schemes/" . $row[1] . $row[2];
                    echo "<tr id='$idd'>";
                    echo "<td>";
                    echo $row[0];
                    echo "</td>";
                    echo "<td>";
                    echo "<a href='#' target=\"popup\" onclick=\"window.open('$desc','popup','width=1000,height=800'); return false;\">" . $row[2] . "</a>";
                    echo "</td>";
                    echo "<td>";
                    echo $row[1];
                    echo "</td>";
                    echo "<td>";
                    echo "<span title='delete form' style='color: red;cursor: pointer' class='glyphicon glyphicon-trash' onclick='del(\"$row[0]\",\"$row[1]\",\"$nn\")'></span>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                $mess = "failed";
            }

        } catch (Exception $e) {
            $mess = "failed";
        }
        echo $mess;
    }
 else if ($identity == 9) {
        $claim_number= validateXss($_GET['claim_number']);
        $mess = "";
        try {
            $stmt = $conn1->prepare('SELECT username FROM claim WHERE claim_number=:claim');
            $stmt->bindParam(':claim', $claim_number, PDO::PARAM_STR);
            $stmt->execute();
            $cc=$stmt->rowCount();
            if($cc>0)
            {
                $uuser=$stmt->fetchColumn();
                $mess="Duplicate claim, please try another case to enter($uuser)";
            }
            else{
$mess="Error";
            }
        }
        catch (Exception $r)
        {
$mess="Error";
        }
        echo  $mess;
    }
    else if ($identity == 10) {
        $policy_number= validateXss($_GET['policy_number']);
        $mess = "cc";
        $myArrayx=array();
        try {
            $stmt = $conn1->prepare('SELECT first_name,surname,telephone,cell,email,id_number FROM member WHERE policy_number=:policy');
            $stmt->bindParam(':policy', $policy_number, PDO::PARAM_STR);
            $stmt->execute();
            $cc=$stmt->rowCount();
            if($cc>0)
            {
                $member=$stmt->fetch();
                $mess="Success";
                array_push($myArrayx,$member);
            }
            else{
                $mess="Error";
            }
        }
        catch (Exception $r)
        {
            $mess="Error";
        }

        $all=array("stat"=>$mess,"details"=>$myArrayx);
        $f=json_encode($all);
        echo $f;
    }
 else if ($identity == 11) {
        $user=$_SESSION['user_id'];
        $password= filter_var($_GET['password'], FILTER_SANITIZE_STRING);


     $cryptKey="MCA201734X$";
     $qEncoded=openssl_encrypt($password,"AES-128-ECB", $cryptKey);

        $mess = "cc";
        $myArrayx=array();
        try {
            $stmt = $conn->prepare('UPDATE staff_users SET email_password=:ep WHERE username=:user1');
            $stmt->bindParam(':user1', $user, PDO::PARAM_STR);
            $stmt->bindParam(':ep', $qEncoded, PDO::PARAM_STR);
            $cc= $stmt->execute();
            if($cc==1)
            {
                $mess="<span style='color: green'>Done!!!</span>";

            }
            else{
                $mess="<span style='color: red'>Error</span>";
            }
        }
        catch (Exception $r)
        {
            $mess="<span style='color: red'>Error</span>";
        }

        echo $mess;
    }
else if ($identity == 15) {

     $user=$_SESSION["user_id"];
     $url=$_GET["url"];

     try {

         $stmt = $conn->prepare('INSERT INTO user_visit_logs(username, url) VALUES (:username,:url)');
         $stmt->bindParam(':username', $user, PDO::PARAM_STR);
         $stmt->bindParam(':url', $url, PDO::PARAM_STR);
         $stmt->execute();
echo "success.==".$_SESSION["level"];
    if($_SESSION["level"]=="admin")
         {
             $purple=$_SESSION["mypurple"];
             $red=$_SESSION["myred"];
             $yellow=$_SESSION["myorange"];
             $stmt = $conn1->prepare('UPDATE email_configs SET purple=:purple,red=:red,yellow=:yellow WHERE 1');
             $stmt->bindParam(':purple', $purple, PDO::PARAM_STR);
             $stmt->bindParam(':red', $red, PDO::PARAM_STR);
             $stmt->bindParam(':yellow', $yellow, PDO::PARAM_STR);
             $stmt->execute();

         }
     } catch (Exception $e) {
         echo $e->getMessage();
     }
 }
}
else
{
    echo "Error";
}
?>

