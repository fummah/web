<?php
session_start();
error_reporting(0);
?>
    <html>
    <head>
        <title>Doctor</title>
        <link rel="stylesheet" href="bootstrap3/css/bootstrap.min.css">
        <script src="jquery/jquery.min.js"></script>
        <script src="bootstrap3/js/bootstrap.min.js"></script>
        <script src="jquery/jquery.js"></script>
        <style type="text/css">
            <!--
            .tab { margin-left: 200px; }
            -->
        </style>


    </head>

<body>
<?php
include("header.php");
//$username=$_POST['user'];
//session_start();
$my_levels=["admin","claims_specialist","controller"];
if(!in_array($_SESSION["level"],$my_levels))
{
    die("<script>alert('Access Denied');location.href = \"login.html\";</script>");
}
?>

    <br/><br/>
    <br/><br/>
    <br>
<?php
require_once('dbconn.php');
$conn=connection("mca","MCA_admin");
if ($_SESSION['level'] == "admin" || $_SESSION['level'] == "claims_specialist" || $_SESSION['level'] == "controller")
{
    $username=$_SESSION['user_id'];
    if(isset($_POST['edit']))
    {
        try {
            $id = (int)validateXss($_POST['id']);
            $stmtpp = $conn->prepare('INSERT INTO doctor_details_log(doc_id, name_initials, surname, telephone, tel1code, tel2, tel2code, 
admin_name, gives_discount, discipline, practice_number, gender, lat, lon, physad1, physsuburb, town, date_entered, disciplinecode, sub_disciplinecode, 
group_disciplinecode, sub_disciplinecode_description, disciplinecode_id, email, days_number, dr_value, signed, date_joined,entered_by,changed_by) SELECT doc_id, name_initials,
 surname, telephone, tel1code, tel2, tel2code, admin_name, gives_discount, discipline, practice_number, gender, lat, lon, physad1, physsuburb, town, 
 date_entered, disciplinecode, sub_disciplinecode, group_disciplinecode, sub_disciplinecode_description, disciplinecode_id, email, days_number, dr_value,
  signed, date_joined,entered_by,"'.$username.'" FROM doctor_details WHERE doc_id=:pp');
            $stmtpp->bindParam(':pp', $id, PDO::PARAM_STR);
            $stmtpp->execute();
            $name = validateXss($_POST['name']);
            $surname = validateXss($_POST['surname']);
            $practice_code = validateXss($_POST['practice_code']);
            $receptionist = validateXss($_POST['receptionist']);
            $telephone = validateXss($_POST['telephone']);
            $discount = validateXss($_POST['discount']);
            $displine_id = strtoupper($_POST ['displine_type']);
            $email = $_POST ['email'];
            $days_number = $_POST ['days_number'];
            $dr_value = $_POST ['dr_value'];
            $myob=json_decode($dr_value,true);
            $numbrarr=(int)count($myob);
            $signed = isset($_POST ['signed'])?1:0;
            $date_joined = validateXss($_POST ['date_joined']);
            $discount_v = validateXss($_POST ['discount_v']);
            $discount_perc = validateXss($_POST ['discount_perc']);
            $discount_value = validateXss($_POST ['discount_value']);
            $data=checkNow($displine_id);
            $displine_type = strtoupper($data['code']);
            $subcode = strtoupper($data['subcode']);
            $discipline = strtoupper($data['descr']);
            $subdesr = strtoupper($data['subdescr']);
$practice_code=trim($practice_code,' ');
            $practice_number=str_pad($practice_code, 7, '0', STR_PAD_LEFT);
            $sql = "Update doctor_details SET name_initials=:name,surname=:surname,telephone=:telephone,admin_name=:admin,gives_discount=:discount,
discipline=:disc,practice_number=:pract,disciplinecode=:disciplinecode,sub_disciplinecode=:sub_disciplinecode,sub_disciplinecode_description=:sub_disciplinecode_description,
sub_disciplinecode=:sub_disciplinecode,email=:email,dr_value=:dr_value,days_number=:days_number,signed=:signed,date_joined=:date_joined,disciplinecode_id=:disciplinecode_id WHERE doc_id=:num";
//SELECT doc_id,name_initials,surname,telephone,admin_name,gives_discount,discipline,practice_number
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':num', $id, PDO::PARAM_STR);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
            $stmt->bindParam(':telephone', $telephone, PDO::PARAM_STR);
            $stmt->bindParam(':admin', $receptionist, PDO::PARAM_STR);
            $stmt->bindParam(':discount', $discount, PDO::PARAM_STR);
            $stmt->bindParam(':disc', $discipline, PDO::PARAM_STR);
            $stmt->bindParam(':pract', $practice_number, PDO::PARAM_STR);
            $stmt->bindParam(':disciplinecode', $displine_type, PDO::PARAM_STR);
            $stmt->bindParam(':sub_disciplinecode', $subcode, PDO::PARAM_STR);
            $stmt->bindParam(':sub_disciplinecode_description', $subdesr, PDO::PARAM_STR);
            $stmt->bindParam(':disciplinecode_id', $displine_id, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':dr_value', $dr_value, PDO::PARAM_STR);
            $stmt->bindParam(':days_number', $days_number, PDO::PARAM_STR);
            $stmt->bindParam(':signed', $signed, PDO::PARAM_STR);
            $stmt->bindParam(':date_joined', $date_joined, PDO::PARAM_STR);
            $ccc = $stmt->Execute();
            if ($ccc == 1) {

                for($i=0;$i<$numbrarr;$i++)
                {
                    $main_value = $myob[$i]["main_value"];
                    $discount_perc = $myob[$i]["discount_perc"];
                    $discount_value = $myob[$i]["discount_value"];
                    $days_number = $myob[$i]["days_number"];
                    addDiscount($main_value,$discount_perc,$discount_value,$days_number,$practice_number);
                }
                echo "<h3 align='center'>";
                echo "Success!";
                echo "</h3>";
                echo "<p align='center'>";
                echo "You have edited";
                echo "<br><br>";
                echo "Would you like to";
                echo "<a href=\"add_doc_form.php\">";
                echo " add a doctor?";
                echo "</a>";
                echo "<br>";
                echo "<br>";
                echo "Or, would you like to";
                echo "<a href=\"search_form.php\">";
                echo " search ";
                echo "</a>";
                echo "for a doctor already in the system?";
                echo "</p>";
            } else {
                echo "Failed to update";
            }
        }
        catch (Exception $e)
        {
            echo "There is an error : ".$e->getMessage();
        }
    }
    else {
        try {
            $name_initials =strtoupper(validateXss($_POST ['name']));
            $surname = strtoupper($_POST ['surname']);
            $telephone = validateXss($_POST ['telephone']);
            $admin_name = strtoupper($_POST ['receptionist']);
            $gives_discount = validateXss($_POST ['discount']);
            $displine_id = strtoupper($_POST ['displine_type']);
            $email = $_POST ['email'];
            $days_number = $_POST ['days_number'];
            $dr_value = $_POST ['dr_value'];
            $myob=json_decode($dr_value,true);
            $numbrarr=(int)count($myob);

            $data=checkNow($displine_id);
            $displine_type = strtoupper($data['code']);
            $subcode = strtoupper($data['subcode']);
            $discipline = strtoupper($data['descr']);
            $subdesr = strtoupper($data['subdescr']);
$practice_code=trim($practice_code,' ');
            $practice_number = validateXss($_POST ['practice_code']);
            $practice_number=str_pad($practice_number, 7, '0', STR_PAD_LEFT);
            $signed = isset($_POST ['signed'])?1:0;
            $date_joined = validateXss($_POST ['date_joined']);
            $discount_v = validateXss($_POST ['discount_v']);
            $discount_perc = validateXss($_POST ['discount_perc']);
            $discount_value = validateXss($_POST ['discount_value']);
            $st=$conn->prepare('SELECT practice_number FROM doctor_details WHERE practice_number=:pr');
            $st->bindParam(':pr', $practice_number, PDO::PARAM_STR);
            $st->execute();
            $cc=$st->rowCount();

            if($cc>0)
            {
                die("<h3 style='color: red' class = \"tab\">Practice number is already in the database</h3>");
            }
//sub_disciplinecode,group_disciplinecode,sub_disciplinecode_description,disciplinecode_id
            $stmt = $conn->prepare('Insert INTO doctor_details(name_initials,surname,telephone,admin_name,practice_number,gives_discount,discipline,disciplinecode,sub_disciplinecode,
sub_disciplinecode_description,disciplinecode_id,email,dr_value,days_number,signed,date_joined,discount_perc,discount_v,rand_perc,entered_by) VALUES (:name1,:surname,:telephone,
:admin,:practice_number,:discount,:disc,:disciplinecode,:sub_disciplinecode,:sub_disciplinecode_description,:disciplinecode_id,:email,:dr_value,:days_number,:signed,:date_joined,:discount_perc,:discount_v,:rand_perc,:entered_by)');
            //SELECT doc_id,name_initials,surname,telephone,admin_name,gives_discount,discipline,practice_number
            $stmt->bindParam(':name1', $name_initials, PDO::PARAM_STR);
            $stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
            $stmt->bindParam(':telephone', $telephone, PDO::PARAM_STR);
            $stmt->bindParam(':admin', $admin_name, PDO::PARAM_STR);
            $stmt->bindParam(':practice_number', $practice_number, PDO::PARAM_STR);
            $stmt->bindParam(':discount', $gives_discount, PDO::PARAM_STR);
            $stmt->bindParam(':disc', $discipline, PDO::PARAM_STR);
            $stmt->bindParam(':disciplinecode', $displine_type, PDO::PARAM_STR);
            $stmt->bindParam(':sub_disciplinecode', $subcode, PDO::PARAM_STR);
            $stmt->bindParam(':sub_disciplinecode_description', $subdesr, PDO::PARAM_STR);
            $stmt->bindParam(':disciplinecode_id', $displine_id, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':dr_value', $dr_value, PDO::PARAM_STR);
            $stmt->bindParam(':days_number', $days_number, PDO::PARAM_STR);
            $stmt->bindParam(':signed', $signed, PDO::PARAM_STR);
            $stmt->bindParam(':date_joined', $date_joined, PDO::PARAM_STR);
            $stmt->bindParam(':discount_v', $discount_v, PDO::PARAM_STR);
            $stmt->bindParam(':discount_perc', $discount_perc, PDO::PARAM_STR);
            $stmt->bindParam(':rand_perc', $discount_value, PDO::PARAM_STR);
            $stmt->bindParam(':entered_by', $username, PDO::PARAM_STR);
            $ccc = $stmt->Execute();

            if ($ccc < 1) {
                echo "There is an error";
            } else {
                for($i=0;$i<$numbrarr;$i++)
                {
                    $main_value = $myob[$i]["main_value"];
                    $discount_perc = $myob[$i]["discount_perc"];
                    $discount_value = $myob[$i]["discount_value"];
                    $days_number = $myob[$i]["days_number"];
                    addDiscount($main_value,$discount_perc,$discount_value,$days_number,$practice_number);
                }

//    echo $response;
                echo "<h3 align='center'>";
                echo "Success!";
                echo "</h3>";
                echo "<p align='center'>";
                echo "You have added the doctor to the database";
                echo "<br><br>";
                echo "Would you like to";
                echo "<a href=\"add_doc_form.php\">";
                echo " add another doctor?";
                echo "</a>";
                echo "<br>";
                echo "<br>";
                echo "Or, would you like to";
                echo "<a href=\"search_form.php\">";
                echo " search ";
                echo "</a>";
                echo "for a doctor already in the system?";
                echo "</p>";
            }
        }
        catch (Exception $e)
        {
            echo "There is an error : ".$e->getMessage();
        }
    }

}
else
{
    echo "Invalid entry";
}


function checkNow($id)
{
    global $conn;
    $data["code"]="";
    $data["subcode"]="";
    $data["descr"]="";
    $data["subdescr"]="";
    $stmt=$conn->prepare('SELECT *FROM disciplinecodes WHERE id=:id');
    $stmt->bindParam(':id',$id,PDO::PARAM_STR);
    $stmt->execute();
    $nu=$stmt->rowCount();
    if($nu>0) {
        $arr = $stmt->fetch();
        $data["code"] = $arr[1];
        $data["subcode"] = $arr[2];
        $data["descr"] = $arr[3];
        $data["subdescr"] = $arr[4];
    }
    return $data;
}
function addDiscount($dr_value,$discount_perc,$discount_value,$days_number,$practice_number)
{
    global $conn;
    $username=$_SESSION['user_id'];
    $stmt=$conn->prepare('INSERT INTO discount_details(dr_value,discount_perc,discount_value,days_number,practice_number,entered_by) VALUES (:dr_value,:discount_perc,:discount_value,:days_number,:practice_number,:entered_by)');
    $stmt->bindParam(':dr_value',$dr_value,PDO::PARAM_STR);
    $stmt->bindParam(':discount_perc',$discount_perc,PDO::PARAM_STR);
    $stmt->bindParam(':discount_value',$discount_value,PDO::PARAM_STR);
    $stmt->bindParam(':days_number',$days_number,PDO::PARAM_STR);
    $stmt->bindParam(':practice_number',$practice_number,PDO::PARAM_STR);
    $stmt->bindParam(':entered_by',$username,PDO::PARAM_STR);
    $stmt->execute();

}
?>
    <hr>
<?php
include('footer.php');
?>