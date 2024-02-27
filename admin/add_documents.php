<?php
session_start();


?>
<html>
<head>
    <title>Documents</title>
    <link rel="stylesheet" href="bootstrap3/css/bootstrap.min.css">
    <script src="jquery/jquery.min.js"></script>
    <script src="bootstrap3/js/bootstrap.min.js"></script>
    <script src="jquery/jquery.js"></script>
    <script src="js/jquery-1.12.4.js"></script>
    <link rel="stylesheet" href="uikit/css/uikit.min.css" />
    <script src="uikit/js/uikit.min.js"></script>
    <script src="uikit/js/uikit-icons.min.js"></script>
</head>
<style>
    table tr:nth-child(even) {
        background-color: #eee;
    }
    table tr:nth-child(odd) {
        background-color: #fff;
    }
    .linkButton {
        background: none;
        border: none;
        color: #0066ff;
        text-decoration: underline;
        cursor: pointer;

    }
</style>
<script>
    var doctor = [];
    function show() {
        document.getElementById("show").innerHTML = "please wait ...";
        var inp88=$("#inpu88").val();
        if(inp88==1)
        {
            document.getElementById("show").innerHTML = "please complete the information required (click pencil icon above and complete the blanks)";
            event.preventDefault();
        }
    }
    function check1() {
        var client_name=document.getElementById("client_name").value;
        var claim_number=document.getElementById("claim_number").value.toUpperCase();
        var chck = claim_number.includes("-");
        var chck1 = claim_number.includes("/");
        document.getElementById("upload").disabled = false;
        var mess="";
        var check2=0;
        if(claim_number !="") {
            var specialChars = "<>@!#$%^&*()+[]{}?:;|'\"\\,.~`=";
            var checkForSpecialChar = function(string){
                for(var i = 0; i < specialChars.length;i++){
                    if(string.indexOf(specialChars[i]) > -1){
                        return true
                    }
                }
                return false;
            }

            if(checkForSpecialChar(claim_number)){
                check2 = 1;
            }

            else if (claim_number.length > 4) {
                if(client_name=="Western") {
                    if (chck) {

                        var arr = claim_number.split('-');

                        if (arr.length > 1) {
                            var tyt = arr[0].trim();
                            var tyt1 = arr[1].trim();
                            var newline = tyt + " - " + tyt1;
                            document.getElementById("claim_number").value = newline.trim();
                            document.getElementById("info").innerHTML = "";
                            check2 = 0;
                            Western(claim_number);
                        } else {
                            check2 = 1;
                        }
                    } else {
                        check2 = 1;
                    }
                }
                else if (client_name=="Kaelo")
                {
                    if(chck1)
                    {
                        check2 = 0;
                    }

                    else if(chck1)
                    {
                        check2 = 0;
                    }
                    else
                    {
                        check2 = 1;
                    }
                    if (check2 === 0)
                    {
                        Kaelo(claim_number);
                    }
                }
            } else {
                check2 = 1;
            }
            if (check2 === 1) {
                document.getElementById("info").innerHTML = "<br>Incorrect claim number";
                document.getElementById("upload").disabled = true;
            } else {
                document.getElementById("info").innerHTML = "";

            }

        }
    }

    function addDoctor() {
        var element = {};
        var mess="";
        $("#doc").show();
        var practice_number=$("#practice_number").val();
        var fullname=$("#doc_full_name").val();
        var gap=$("#gap").val();
        var treatment_date=$("#treatment_date").val();
        if(practice_number.length>5 && fullname.length>2 && treatment_date.length>5 && gap.length>0)
        {
            element.practice_number = practice_number;
            element.fullname = fullname;
            element.gap = gap;
            element.treatment_date = treatment_date;


            var count=0;
            for (var key in doctor) {
                if(practice_number==doctor[key].practice_number)
                {
                    count=1;
                }
            }
            if(isNaN(practice_number))
            {
                count=41;
            }
            if(count<1)
            {
                doctor.push(element);
                mess="<ul><li>"+practice_number+" ["+fullname+"] ["+gap+"] ["+treatment_date+"]</li></ul>";
                $("#practice_number").val("");
                $("#doc_full_name").val("");
                $("#gap").val("");
                $("#treatment_date").val("");
                $(".vvb").css("display","none");
            }
            else {
                if (count>=4)
                {
                    mess="<div style='color: red' class='vvb'>Invalid practice number</div>";
                }
                else {
                    mess="<div style='color: red' class='vvb'>Duplicate Doctor</div>";
                }
            }


        }
        else {
            mess="<div style='color: red' class='vvb'>Please complete Doctor's information</div>";
        }
        var json=JSON.stringify(doctor);
        $("#txt").val(json);

        $("#doc").append(mess);


    }


    function add1() {
        var element = {};
        var first_name=$("#first_name").val();
        if(first_name.length>2) {
            var surname = $("#surname").val();
            var patient_name = $("#patient_name").val();
            var patient_surname = $("#patient_surname").val();
            var cell = $("#cell").val();
            var email = $("#email").val();
            var start_date = $("#start_date").val();
            var end_date = $("#end_date").val();
            element.first_name = first_name;
            element.surname = surname;
            element.patient_name = patient_name;
            element.patient_surname = patient_surname;
            element.cell = cell;
            element.email = email;
            element.start_date = start_date;
            element.end_date = end_date;

            if(doctor.length>0)
            {
                doctor.push(element);
                $("#inpu").val(1);
                $("#inpu88").val(2);
                $("#add_doc").hide();
                var json = JSON.stringify(doctor);
                $("#txt").val(json);
                $("#aler").show();
            }
            else
            {
                alert("Please enter atleast one doctor information");
            }

        }
        else
        {
            alert("Please enter at least first name of the member");
        }
    }
    function Kaelo(claim_number) {

//kaeloclientkaelochek
        var objSelect = document.getElementById("kaeloclient");
        var kclient="Kaelo";
        if(claim_number.substring(0, 2) === "30")
        {
            kclient="Sanlam";
        }
        $("#kclient").text(kclient);
        setSelectedValue(objSelect, kclient);
        $("#kaelochek").prop("checked", false);
        document.getElementById("upload").disabled = true;
        $("#mypen").hide();
        $("#kaelo").fadeIn();
    }
    function Western(claim_number) {

//kaeloclientkaelochek
        var objSelect = document.getElementById("kaeloclient");
        var kclient="Western";
        $("#kclient").text(kclient);
        setSelectedValue(objSelect, kclient);
        $("#kaelochek").prop("checked", false);
        document.getElementById("upload").disabled = true;
        $("#mypen").hide();
        $("#kaelo").fadeIn();
    }
    function setSelectedValue(selectObj, valueToSet) {
        for (var i = 0; i < selectObj.options.length; i++) {
            if (selectObj.options[i].text== valueToSet) {
                selectObj.options[i].selected = true;
                return;
            }
        }
    }
    function validate() {
        if (document.getElementById('kaelochek').checked) {
            document.getElementById("upload").disabled = false;
            $("#mypen").show();
        } else {
            document.getElementById("upload").disabled = true;
            $("#mypen").hide();
        }
    }

    function ddp() {

        var kclient=$("#kaeloclient").val();

        $("#kclient").text(kclient);
    }
</script>
<?php
if ($_SESSION['level']!="gap_cover") {
    die("Error");
}
include("header.php");
include_once "dbconn.php";

echo "<br/><br/><br/>";

$conn=connection("mca","MCA_admin");
if(!isset($_SESSION['user_id']) || empty(['user_id']))
{
    die("The is an error");
}
class docs
{

    public $successarr;
    public $errorarr;
    public $mmyid;
    public $myClaim;
    public $myform;
    public $editnew=0;
    function __construct()
    {
        $this->errorarr=array();
        $this->successarr=array();
        $this->mmyid=0;
        $this->myform="";
    }

    function getUser()
    {
        global $conn;
        $sql = $conn->prepare('SELECT username FROM users_information WHERE status=1 ORDER BY datetime ASC LIMIT 1');
        $sql->execute();
        return $sql->fetchColumn();
    }
    function updateUser($username)
    {
        global $conn;
        $dat=date('Y-m-d H:i:s');
        $sql = $conn->prepare('UPDATE users_information SET datetime=:dat WHERE username=:user1');
        $sql->bindParam(':user1', $username, PDO::PARAM_STR);
        $sql->bindParam(':dat', $dat, PDO::PARAM_STR);
        $sql->execute();

    }
    function checkClient($client_name)
    {
        global $conn;

        $sql = $conn->prepare('SELECT reporting_client_id FROM clients WHERE client_name=:user1 LIMIT 1');
        $sql->bindParam(':user1', $client_name, PDO::PARAM_STR);
        $sql->execute();
        return $sql->fetchColumn();

    }
    function openClaim($claim_id)
    {
        global $conn;
        $sql = $conn->prepare('UPDATE claim SET Open=1 WHERE claim_id=:claim');
        $sql->bindParam(':claim', $claim_id, PDO::PARAM_STR);
        $sql->execute();

    }
    function checkMember($policy_number,$client_id)
    {
        global $conn;
        $myPolicy_number=0;
        $sql = $conn->prepare('SELECT member_id FROM member WHERE policy_number=:policy AND policy_number<>"" AND client_id=:id');
        $sql->bindParam(':policy', $policy_number, PDO::PARAM_STR);
        $sql->bindParam(':id', $client_id, PDO::PARAM_STR);
        $sql->execute();
        $nu = $sql->rowCount();
        if($nu>0)
        {
            $myPolicy_number=$sql->fetchColumn();
        }
        return (int)$myPolicy_number;
    }
    function checkClaim($claim_number,$client_id)
    {
        global $conn;
        $myClaim_number=0;
        $sql = $conn->prepare('SELECT claim_id FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id WHERE a.claim_number=:claim AND b.client_id=:id');
        $sql->bindParam(':claim', $claim_number, PDO::PARAM_STR);
        $sql->bindParam(':id', $client_id, PDO::PARAM_STR);
        $sql->execute();
        $nu = $sql->rowCount();
        if($nu>0)
        {
            $myClaim_number=$sql->fetchColumn();
        }
        return (int)$myClaim_number;
    }
    function checkClaimnumber($claim_id)
    {
        global $conn;
        $client_name=$_SESSION['user_id'];
        $client_id=(int)$this->checkClient($client_name);
        $sql = $conn->prepare('SELECT a.claim_number,a.date_entered,a.Open,b.policy_number,a.date_closed,a.savings_scheme,a.savings_discount FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id WHERE a.claim_id=:claim AND b.client_id=:client_id');
        $sql->bindParam(':claim', $claim_id, PDO::PARAM_STR);
        $sql->bindParam(':client_id', $client_id, PDO::PARAM_STR);
        $sql->execute();
        $row=$sql->fetch();
        $myClaim_number['claim_number']=$row[0];
        $myClaim_number['date_entered']=$row[1];
        $myClaim_number['open']=(int)$row[2];
        $myClaim_number['policy_number']=$row[3];
        $myClaim_number['date_closed']=$row[4];
        $myClaim_number['savings_scheme']=$row[5];
        $myClaim_number['savings_discount']=$row[6];

        return $myClaim_number;
    }
    function insertReOpenedCases($claim_id,$reason,$entered_by,$date_closed,$last_scheme_savings,$last_discount_savings)
    {
        global $conn;
        $logClaim = $conn->prepare('INSERT INTO `reopened_claims`(claim_id,reason,entered_by,date_closed,last_scheme_savings,last_discount_savings) VALUES(:claim_id,:reason,:entered_by,:date_closed,:last_scheme_savings,:last_discount_savings)');
        $logClaim->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $logClaim->bindParam(':reason', $reason, PDO::PARAM_STR);
        $logClaim->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
        $logClaim->bindParam(':date_closed', $date_closed, PDO::PARAM_STR);
        $logClaim->bindParam(':last_scheme_savings', $last_scheme_savings, PDO::PARAM_STR);
        $logClaim->bindParam(':last_discount_savings', $last_discount_savings, PDO::PARAM_STR);
        return (int)$logClaim->execute();
    }
    function addMember($policy_number,$entered_by,$kaeloclient="")
    {

        global $conn;
        $client_name=$_SESSION['user_id'];
        if($client_name=="Kaelo" || $client_name=="Western")
        {
            $client_name=$kaeloclient;
        }
        $client_id=(int)$this->checkClient($client_name);
        $tempmember=$this->checkMember($policy_number,$client_id);
        if($tempmember==0) {
            $sql = $conn->prepare('INSERT INTO `member`(`client_id`, `policy_number`) VALUES (:client_id,:policy_number)');
            $sql->bindParam(':client_id', $client_id, PDO::PARAM_STR);
            $sql->bindParam(':policy_number', $policy_number, PDO::PARAM_STR);
            $result=$sql->execute();
            if($result==1) {

                $checkClaim = $conn->prepare('SELECT MAX(member_id) FROM member WHERE entered_by=:entered_by');
                $checkClaim->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
                $checkClaim->execute();
                $tempmember = $checkClaim->fetchColumn();
            }
        }
        return $tempmember;
    }
    function addClaim($claim_number,$policy_number,$entered_by,$kaeloclient ="",$claim_type="")
    {

        global $conn;
        $createdBy=$_SESSION['fullname'];
        $client_name=$_SESSION['user_id'];
        $senderId="";
        if($client_name=="Kaelo" || $client_name=="Western")
        {
            $client_name=$kaeloclient;
            $senderId=23;
        }
        $client_id=(int)$this->checkClient($client_name);
        $tempclaim=$this->checkClaim($claim_number,$client_id);
        $openx=5;
        try {
            if ($tempclaim == 0) {
                $member_id = $this->addMember($policy_number, $entered_by,$kaeloclient);
                // $username = $this->getUser();
                $nameAssesor = file('preassessor.txt')[0];
                $newAssessor1 = "Keasha";
                $newAssessor2 = "Keasha";
                $realName=$nameAssesor==$newAssessor1?$newAssessor2:$newAssessor1;
                $fh = fopen("preassessor.txt", "w");
                fwrite($fh, $realName);
                fclose($fh);
                $username = $nameAssesor;
                //$username = "Shirley";

                $sql = $conn->prepare('INSERT INTO `claim`(`member_id`, `claim_number`,`username`,`createdBy`,`Open`,`senderId`,`claim_type`) VALUES (:member_id,:claim_number,:username,:createdBy,:Open1,:senderId,:claim_type)');
                $sql->bindParam(':member_id', $member_id, PDO::PARAM_STR);
                $sql->bindParam(':claim_number', $claim_number, PDO::PARAM_STR);
                $sql->bindParam(':username', $username, PDO::PARAM_STR);
                $sql->bindParam(':createdBy', $createdBy, PDO::PARAM_STR);
                $sql->bindParam(':Open1', $openx, PDO::PARAM_STR);
                $sql->bindParam(':senderId', $senderId, PDO::PARAM_STR);
                $sql->bindParam(':claim_type', $claim_type, PDO::PARAM_STR);
                $result = $sql->execute();
                if ($result == 1) {

                    $checkClaim = $conn->prepare('SELECT MAX(claim_id) FROM claim WHERE entered_by=:entered_by');
                    $checkClaim->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
                    $checkClaim->execute();
                    $tempclaim = $checkClaim->fetchColumn();
                    //$this->updateUser($username);
                    array_push($this->successarr, "New Claim created");
                    $rrr= "<form action='case_detail.php' method='post' /><input type=\"hidden\" name=\"claim_id\" value=\"$tempclaim\" />
<input type=\"hidden\" name=\"user\" value=\"$client_name\" />
<input type=\"submit\" class=\"uk-input uk-form-success uk-form-width-medium\" name=\"btn\" value=\"View Claim\"></form>";
                    $this->myform=$rrr;

                } else {
                    array_push($this->errorarr, "Failed to create new claim");
                }
            } else {
                $rrr= "<form action='case_detail.php' method='post' /><input type=\"hidden\" name=\"claim_id\" value=\"$tempclaim\" />
<input type=\"hidden\" name=\"user\" value=\"$client_name\" />
<input type=\"submit\" class=\"uk-input uk-form-success uk-form-width-medium\" name=\"btn\" value=\"View Claim\"></form>";
                $this->myform=$rrr;
                array_push($this->successarr, "Claim Number already in the System");
            }
        }
        catch (Exception $ex)
        {
            array_push($this->errorarr, $ex->getMessage());
        }
        return $tempclaim;
    }
    function updateMember($policy_number,$first_name,$surname,$cell,$email)
    {
        global $conn;
        $insert = $conn->prepare('UPDATE member SET first_name=:first_name,surname=:surname,email=:email,cell=:cell WHERE policy_number=:policy_number');
        $insert->bindParam(':policy_number', $policy_number, PDO::PARAM_STR);
        $insert->bindParam(':first_name', $first_name, PDO::PARAM_STR);
        $insert->bindParam(':surname', $surname, PDO::PARAM_STR);
        $insert->bindParam(':email', $email, PDO::PARAM_STR);
        $insert->bindParam(':cell', $cell, PDO::PARAM_STR);
        $insert->execute();
    }
    function addPatient($claim_id,$patient_name,$patient_surname)
    {
        if(strlen($patient_name)>2)
        {
            global $conn;
            $username="System";
            $patient_name=$patient_name." ".$patient_surname;

            $smt=$conn->prepare('SELECT claim_id FROM patient WHERE claim_id=:claim_id');
            $smt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
            $smt->execute();
            if($smt->rowCount()<1)
            {
                $insert = $conn->prepare('  INSERT INTO `patient`(`claim_id`, `patient_name`,`entered_by`) VALUES (:claim_id,:patient_name,:entered_by)');
                $insert->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
                $insert->bindParam(':patient_name', $patient_name, PDO::PARAM_STR);
                $insert->bindParam(':entered_by', $username, PDO::PARAM_STR);
                $insert->execute();
            }


        }

    }
    function checkDoctor($number)
    {

        global $conn;
        $check=false;
        try {
            $stmt = $conn->prepare('SELECT practice_number FROM doctor_details WHERE practice_number=:num');
            $stmt->bindParam(':num', $number, PDO::PARAM_STR);
            $stmt->execute();
            $ccc = $stmt->rowCount();
            if ($ccc > 0) {
                $check = true;
            }
        }
        catch (Exception $e)
        {
            $check=false;
        }
        return $check;
    }
    public function insertNotes($claim_id,$intervention_desc,$username)
    {
        global $conn;
        $j="---";
        $stmt = $conn->prepare('INSERT INTO intervention(claim_id,intervention_desc,owner,claim_number) VALUES(:claim,:notes,:owner,:claim_number)');
        $stmt->bindParam(':claim', $claim_id, PDO::PARAM_STR);
        $stmt->bindParam(':notes', $intervention_desc, PDO::PARAM_STR);
        $stmt->bindParam(':owner', $username, PDO::PARAM_STR);
        $stmt->bindParam(':claim_number', $j, PDO::PARAM_STR);
        return (int)$stmt->execute();
    }
    function createDoctor($practiceName,$practiceNo,$providertypedesc)
    {
        global $conn;
        $insertDoctor1 = $conn->prepare('INSERT INTO doctor_details(name_initials,practice_number,discipline) VALUES(:firstname,:practiceno,:service)');
        $insertDoctor1->bindParam(':firstname', $practiceName, PDO::PARAM_STR);
        $insertDoctor1->bindParam(':practiceno', $practiceNo, PDO::PARAM_STR);
        $insertDoctor1->bindParam(':service', $providertypedesc, PDO::PARAM_STR);
        $insertDoctor1->execute();
    }
    function addDoctor($claim_id,$practiceNo,$practiceName,$gap,$treatment_date)
    {
        global $conn;
        if (!$this->checkDoctor($practiceNo)) {
            $providertypedesc="";
            $this->createDoctor($practiceName,$practiceNo,$providertypedesc);
        }
        $checkM = $conn->prepare('SELECT claim_id,practice_number FROM doctors WHERE claim_id=:claim_id AND practice_number=:practice_number LIMIT 1');
        $checkM->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $checkM->bindParam(':practice_number', $practiceNo, PDO::PARAM_STR);
        $checkM->execute();
        $cc = $checkM->rowCount();
        if($cc<1)
        {

            $insertDoctor = $conn->prepare('INSERT INTO doctors(claim_id,practice_number,doc_gap) VALUES(:claim_id,:practice_number,:doc_gap)');
            $insertDoctor->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
            $insertDoctor->bindParam(':practice_number', $practiceNo, PDO::PARAM_STR);
            $insertDoctor->bindParam(':doc_gap', $gap, PDO::PARAM_STR);
            $rr=$insertDoctor->execute();
            if($rr==1){
                $this->addclaimLine($claim_id,$practiceNo,$treatment_date);
            }

        }

    }
    function updateClaim($claim_id,$start_date,$end_date)
    {
        global $conn;

        $checkM = $conn->prepare('UPDATE claim SET Service_Date=:start_date,end_date=:end_date WHERE claim_id=:claim_id');
        $checkM->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $checkM->bindParam(':start_date', $start_date, PDO::PARAM_STR);
        $checkM->bindParam(':end_date', $end_date, PDO::PARAM_STR);
        $checkM->execute();
    }
    function addclaimLine($claim_id,$practiceNo,$treatment_date)
    {
        global $conn;
        $dctor = $conn->prepare('INSERT INTO claim_line(mca_claim_id,practice_number,treatmentDate) VALUES(:claim_id,:practice_number,:treatmentDate)');
        $dctor->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $dctor->bindParam(':practice_number', $practiceNo, PDO::PARAM_STR);
        $dctor->bindParam(':treatmentDate', $treatment_date, PDO::PARAM_STR);

        $dctor->execute();

    }
    function DBaddfiles($description,$size,$type,$rand,$claim_id,$username,$additional_doc=0)
    {
        global $conn;
        $username=$_SESSION['fullname'];

        try {
            $sql = $conn->prepare('INSERT INTO documents(claim_id,doc_description,doc_size,doc_type,randomNum,uploaded_by,additional_doc) VALUES(:claim,:description,:size,:type,:rand,:uploaded_by,:additional_doc)');
            $sql->bindParam(':claim', $claim_id, PDO::PARAM_STR);
            $sql->bindParam(':description', $description, PDO::PARAM_STR);
            $sql->bindParam(':size', $size, PDO::PARAM_STR);
            $sql->bindParam(':type', $type, PDO::PARAM_STR);
            $sql->bindParam(':rand', $rand, PDO::PARAM_STR);
            $sql->bindParam(':uploaded_by', $username, PDO::PARAM_STR);
            $sql->bindParam(':additional_doc', $additional_doc, PDO::PARAM_STR);
            $sql->execute();
        }
        catch (Exception $ex)
        {
            array_push($this->errorarr, "Document error : ".$ex->getMessage());
        }

    }
    function displayDoc($claim_id)
    {
        global $conn;
        $sql = $conn->prepare('SELECT *FROM documents WHERE claim_id=:claim');
        $sql->bindParam(':claim', $claim_id, PDO::PARAM_STR);
        $sql->execute();
        $nu = $sql->rowCount();
        if ($nu > 0) {
            echo"<table border='0' width='100%'><tr style='background-color:black;color: white'><th>File Name</th><th>Size</th><th>Date Entered</th></tr>";
            foreach ($sql->fetchAll() as $row) {
                $id = $row[0];
                $ra=$row[6];
                $nname = $row[2];
                $desc="../../mca/documents/".$ra.$nname;
                $date = $row[5];
                $size = round($row[4]/1024);
                echo"<tr><td>
<form action='test5.php' method='post' target='_blank'/><input type=\"hidden\" name=\"my_doc\" value=\"$desc\" />
<input type=\"hidden\" name=\"my_id\" value=\"$id\" />
<input type=\"submit\" class=\"linkbutton\" name=\"doc\" value=\"$nname\">
</form>
 </td><td>$size<b style='color:green'>KB</b></td><td>$date</td></tr>";
            }
            echo"</table>";
        }
    }
    function process()
    {
        if (isset($_POST['upload'])) {

            try {
                $claim_number = validateXss($_POST['claim_number']);
                $kaeloclient = validateXss($_POST['kaeloclient']);
                $this->myClaim=$claim_number;
                $policy_number="";
                $claim_type = "";
                if($_SESSION['user_id']=="Western")
                {
                    $str_arr = explode("-", $claim_number);
                    $policy_number = trim($str_arr[0]);
                    $claim_number = $policy_number . " - " . trim($str_arr[1]);
                }
                else if($_SESSION['user_id']=="Total_risk_administrators" || $_SESSION['user_id']=="Cinagi")
                {
                    $str_arr = explode("-", $claim_number);
                    $policy_number = trim($str_arr[0]);
                    $claim_number = trim($str_arr[1]);
                    $claim_type = validateXss($_POST['totalrisk']);
                }
                else if($_SESSION['user_id']=="Kaelo")
                {

                    $str_arr = explode("/", $claim_number);
                    $policy_number = trim($str_arr[0]);
                    $claim_number = $policy_number . " / " . trim($str_arr[1]);

                }
                else if($_SESSION['user_id']=="Turnberry" || $_SESSION['user_id']=="Gaprisk_administrators" || $_SESSION['user_id']=="Medway")
                {


                    $pos = strpos($claim_number, "-");

                    if ($pos == true) {
                        $str_arr = explode("-", $claim_number);
                        $policy_number = trim($str_arr[0]);
                        $claim_number = trim($str_arr[1]);
                    }

                    else {
                        die("Incorrect claim number");
                    }
                }
                $username = "System";
                if(strlen($claim_number)<2 || strlen($policy_number)<2)
                {
                    die("Incorrect policy/claim number");
                }
                $claim_id = $this->addClaim($claim_number, $policy_number, $username,$kaeloclient,$claim_type);
                $this->mmyid = $claim_id;
                $val=(int)$_POST["inpu"];
                if($val==1)
                {
                    $txtobject=$_POST["txt"];
                    $myob=json_decode($txtobject,true);
                    $numbrarr=count($myob);
                    $first_name=$myob[$numbrarr-1]["first_name"];
                    $surname=$myob[$numbrarr-1]["surname"];
                    $cell=$myob[$numbrarr-1]["cell"];
                    $email=$myob[$numbrarr-1]["email"];
                    $start_date=$myob[$numbrarr-1]["start_date"];
                    $end_date=$myob[$numbrarr-1]["end_date"];
                    $this->updateMember($policy_number,$first_name,$surname,$cell,$email);
                    $this->updateClaim($claim_id,$start_date,$end_date);
                    $patient_name=$myob[$numbrarr-1]["patient_name"];
                    $patient_surname=$myob[$numbrarr-1]["patient_surname"];
                    if(($_SESSION['user_id']=="Total_risk_administrators" || $_SESSION['user_id']=="Cinagi") && $_POST["totalrisk"]=="Clinical Review")
                    {
                        $this->insertNotes($claim_id,"This claim was sent for clinical review.",$username);
                    }
                    if(!empty($patient_name))
                    {
                        $this->addPatient($claim_id,$patient_name,$patient_surname);
                    }

                    for($i=0;$i<$numbrarr-1;$i++)
                    {
                        $practice_number = trim($myob[$i]["practice_number"],' ');
                        $fullname = $myob[$i]["fullname"];
                        $gap = $myob[$i]["gap"];
                        $treatment_date = $myob[$i]["treatment_date"];
                        if(!empty($practice_number)) {
                            $practiceNo=str_pad($practice_number, 7, '0', STR_PAD_LEFT);
                            $this->addDoctor($claim_id,$practiceNo,$fullname,$gap,$treatment_date);
                        }
                    }


                }

                $xdata=$this->checkClaimnumber($this->mmyid);
                $open=$xdata["open"];
                $date_closed=$xdata["date_closed"];
                $savings_scheme=$xdata["savings_scheme"];
                $savings_discount=$xdata["savings_discount"];
                if($open==0)
                {
                    $this->openClaim($this->mmyid);
                    array_push($this->successarr, "Claim re-opened.");
                    $this->insertReOpenedCases($claim_id,"Additional Document",$username,$date_closed,$savings_scheme,$savings_discount);

                }

                if (isset($_FILES["file"])) {

                    $allowedExts = ['jpeg', 'jpg', 'png', "pdf", "doc", "docx", "xlsx", "xls", "txt", "PDF", "PNG", "msg", "MSG", "eml", "EML","zip","ZIP","rar","RAR","x-zip-compressed","X-ZIP-COMPRESSED"];
                    $fileExtensions = ['jpeg', 'jpg', 'png', "pdf", "vnd.openxmlformats-officedocument.spreadsheetml.sheet", "vnd.openxmlformats-officedocument.wordprocessingml.document", "vnd.ms-excel", "msword", "vnd.oasis.opendocument.text", "application/pdf", "PDF", "PNG", "msg", "MSG", "octet-stream", "eml", "EML", "application/octet-stream", "message/rfc822", "rfc822","application/x-zip-compressed","x-zip-compressed","X-ZIP-COMPRESSED"];


                    $name_array=$_FILES['file']["name"];
                    $type_array=$_FILES['file']["type"];
                    $temp_array=$_FILES['file']["tmp_name"];
                    $size_array=$_FILES['file']["size"];
                    $error_array=$_FILES['file']["error"];

                    for($i=0;$i<count($temp_array);$i++) {
                        try {
                            if ($i < 15) {
                                if (!empty($temp_array[$i])) {
                                    $temp = explode(".", $name_array[$i]);
                                    $presentExtention = end($temp);
                                    $type = basename($type_array[$i]);
                                    $nname = basename($name_array[$i]);
                                    $fileSize = $size_array[$i];
                                    $fileExtension = basename($type_array[$i]);
                                    $temporary_name=$temp_array[$i];

                                    $nux = substr_count($nname, '.');
                                    if (in_array($presentExtention, $allowedExts) && strlen($nname) < 100 && $nux == 1 && $fileSize > 0) {
                                        if (in_array($fileExtension, $fileExtensions) && ($fileSize < 20000000)) {
                                            $ra = rand(0, 1000);
                                            $target = "../../mca/documents/";
                                            //$target = "images/";
                                            $target = $target . $ra . basename($nname);
                                            $ok = 1;
                                            if (move_uploaded_file($temporary_name, $target)) {
                                                $redirect = "success";
                                                $size = basename($fileSize);
                                                $nname = filter_var($nname, FILTER_SANITIZE_STRING);

                                                //echo "<span class=\"notice\" style=\"color: green\">Your file has been uploaded.</span>";
                                                array_push($this->successarr, "File has been uploaded successfully ($nname).");
                                                $date_entered = $this->checkClaimnumber($this->mmyid)["date_entered"];

                                                date_default_timezone_set('Africa/Johannesburg');
                                                $from_date = date('Y-m-d H:i:s', strtotime($date_entered));
                                                $today = date('Y-m-d H:i:s');
                                                $datetime1 = strtotime($from_date);
                                                $datetime2 = strtotime($today);
                                                $secs = $datetime2 - $datetime1;
                                                $mins = round($secs / 60);
                                                $ddc = 0;
                                                if ($mins > 30) {
                                                    $ddc = 1;
                                                }
                                                $this->DBaddfiles($nname, $size, $type, $ra, $claim_id, $username, $ddc);





                                            } else {
                                                //echo "<span class=\"notice\" style=\"color: red\">Sorry, Failed to upload.</span>";
                                                array_push($this->errorarr, "Sorry, Failed to upload. ($nname)");
                                            }


                                        } else {
                                            //echo "<span class=\"notice\" style=\"color: red\">Sorry, incorrect file, failed to upload( $fileExtension )</span>";
                                            array_push($this->errorarr, "Sorry, incorrect file, failed to upload( $nname )");
                                        }
                                    } else {
                                        //echo "<span class=\"notice\" style=\"color: red\">Sorry, incorrect file, failed to upload_$nname</span>";
                                        array_push($this->errorarr, "Sorry, incorrect file, failed to upload ( $nname )");
                                    }
                                }
                            }
                            else
                            {
                                array_push($this->errorarr, "You have exceed the number of files required");
                            }
                        }
                        catch (Exception $ex)
                        {
                            array_push($this->errorarr, "There is an error on the file : ".$ex->getMessage());
                        }

                    }
                }

            } catch (Exception $ex) {
                array_push($this->errorarr, "There is an error : " . $ex->getMessage());
                //echo "There is an error : ".$ex->getMessage();
            }

        }
        if(isset($_POST['btn']))
        {
            $this->editnew=11;
            $this->mmyid=(int)$_POST['claim_id'];
            if($_SESSION['user_id']=="Western")
            {
                $this->myClaim=$this->checkClaimnumber($this->mmyid)["claim_number"];
            }
            else
            {
                $this->myClaim=$this->checkClaimnumber($this->mmyid)["policy_number"]."-".$this->checkClaimnumber($this->mmyid)["claim_number"];
            }

        }
    }
//
}



$pros=new docs();
$pros->process();
$hhis=$_SESSION['user_id']=="Western"?"date":"hidden";
$hhisx=$_SESSION['user_id']=="Kaelo"?"none":"block";
?>
<html>

<head>

    <title>Documents</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jquery.simple-dtpicker.js"></script>
    <link type="text/css" href="js/jquery.simple-dtpicker.css" rel="stylesheet" />
    <link href="w3/w3.css" rel="stylesheet" />


</head>
<body>

<div class="w3-container w3-blue">
    <h2 align="center">Add Documents</h2>
</div>
<div style="position: relative; width: 60%; margin-right: auto;margin-left: auto;" class="w3-panel w3-leftbar w3-rightbar w3-border-blue uk-card uk-card-default uk-card-body">
    <div class="w3-light-grey uk-comment-meta">
        <p align="center" class="uk-text-large uk-text-danger"><i>Allowed files : .msg,xls,xlsx,doc,docs,eml,pdf, with size less than 15MB</i></p>
        <input type="hidden" id="client_name" value="<?php echo $_SESSION['user_id'];?>">
    </div>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" onsubmit="show()">
        <p>

            <span style="color: red" id="info"></span>
        <div class="uk-margin">
            <div class="uk-inline">
                <?php
                $jj=0;
                if(count($pros->successarr)>0) {
                    $pros->editnew=12;
                }
                if(($_SESSION['user_id']=="Kaelo" || $_SESSION['user_id']=="Western" || $_SESSION['user_id']=="Medway" || $_SESSION['user_id']=="Gaprisk_administrators" || $_SESSION['user_id']=="Total_risk_administrators" || $_SESSION['user_id']=="Cinagi") && $pros->editnew==0) {
                    $jj=1;
                    ?>
                    <a class="uk-form-icon" id="mypen" href="#modal-sections" uk-toggle uk-icon="icon: pencil" style="display: <?php echo $hhisx;?>"></a>
                    <?php
                }
                ?>
                <input class="uk-input w3-input" type="text" placeholder="Enter claim number..." id="claim_number" name="claim_number" value="<?php echo $pros->myClaim;?>" onblur="check1()" REQUIRED>
            </div>
        </div>
        <div class="row" id="kaelo" style="display: none">
            <div class="col-md-6">
                <div class="uk-margin">
                    <select class="uk-select" name="kaeloclient" id="kaeloclient" onchange="ddp()">
                        <?php
                        if($_SESSION['user_id']=="Kaelo")
                        {
                            ?>
                            <option value="Kaelo">Kaelo</option>
                            <option value="KaeloVAP">KaeloVAP</option>
                            <option value="Sanlam">Sanlam</option>
                            <?php
                        }
                        else
                        {
                            ?>
                            <option value="KaeloVAP">KaeloVAP</option>
                            <option value="Western">Western</option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
                    <label><input class="uk-checkbox" name="kaelochek" id="kaelochek" type="checkbox" onclick="validate()" checked> Is this really a <span id="kclient">Kaelo</span> claim?</label>

                </div>
            </div>
        </div>
        </p>
        <p>
            <textarea id="txt" name="txt" hidden></textarea>
            <input id="inpu" name="inpu" value="0" hidden/>
            <input id="inpu88" name="inpu88" value="<?php echo $jj;?>" hidden/>
            <label>Upload Documents</label>
        <div uk-form-custom="target: true">
            <input class="w3-input" type="file" name="file[]" multiple="multiple" REQUIRED/>
            <input class="uk-input uk-form-width-large" type="text" placeholder="Select file(s) ...." disabled>
        </div>

        </p>
        <?php
        //echo $_SESSION['user_id'];
        if($_SESSION['user_id']=="Total_risk_administrators" || $_SESSION['user_id']=="Cinagi")
        {
            ?>
            <div class="uk-margin" style="width: 50%">
                <label>Service Type</label>
                <select class="uk-select" name="totalrisk">
                    <option value="Clinical Review">Clinical Review Services</option>
                    <option value="PMB Negotiation">PMB/Negotiation</option>
                </select>
            </div>
            <?php
        }
        ?>
        <p>
            <button class="w3-btn w3-white w3-border w3-border-blue w3-round-large" name="upload" id="upload" type="submit"><span class="glyphicon glyphicon-circle-arrow-up"></span> Upload Now</button>
        </p>
        <span id="show" style="color: red"></span>
    </form>
    <hr class="uk-divider-icon">
    <?php

    if(count($pros->successarr)>0) {
        echo "<div class=\"w3-panel w3-pale-green w3-border\" >";
        foreach ($pros->successarr as $su) {
            echo"<div style='color: #00a65a'><span class='glyphicon glyphicon-ok-sign'></span> $su</div>";
        }

        echo "</div><hr>";
    }

    ///errors
    if(count($pros->errorarr)>0) {
        echo "<div class=\"w3-panel w3-pale-red w3-border\">";
        foreach ($pros->errorarr as $su) {
            echo"<div><span class='glyphicon glyphicon-remove-sign'></span> $su</div>";
        }
        echo "</div><hr>";
    }
    echo $pros->myform;
    if($pros->mmyid>0) {
        $pros->displayDoc($pros->mmyid);
    }
    ?>
</div>
<?php
include('footer.php');
?>


<div id="modal-sections" uk-modal>
    <div class="uk-modal-dialog">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header">
            <h2 class="uk-modal-title">Add Additional Details</h2>
        </div>
        <div class="uk-modal-body">
            <div class="uk-column-1-2">
                <input type="text" class="w3-input uk-input" title="Policyholder Name" placeholder="Policyholder Name" name="first_name" id="first_name">
                <input type="text" class="w3-input uk-input" title="Policyholder Surname" placeholder="Policyholder Surname" name="surname" id="surname">
            </div>
            <div class="uk-column-1-2">
                <input type="text" class="w3-input uk-input" title="Patient Name" placeholder="Patient Name" name="patient_name" id="patient_name">
                <input type="text" class="w3-input uk-input" title="Patient Surname" placeholder="Patient Surname" name="patient_surname" id="patient_surname">
            </div>
            <div class="uk-column-1-2">
                <input type="text" class="w3-input uk-input" title="Cell Phone" placeholder="Cell Phone" name="cell" id="cell">
                <input type="email" class="w3-input uk-input" title="Email Address" placeholder="Email Address" name="email" id="email">
            </div>
            <div class="uk-column-1-2">
                <input type="<?php echo $hhis;?>" class="w3-input uk-input" uk-tooltip="title: Incident Start Date" placeholder="Incident Start Date" name="start_date" id="start_date">
                <input type="<?php echo $hhis;?>" class="w3-input uk-input" uk-tooltip="title: Incident End Date" placeholder="Incident End Date" name="end_date" id="end_date">
            </div>
            <span id="doc" style="display: none" class="uk-column-1-1 uk-text-meta uk-text-success">
<hr>
</span>
            <ul class="uk-nav-default uk-nav-parent-icon" uk-nav>
                <li class="uk-parent">
                    <a href="#"><span class="uk-icon-button" uk-icon="icon: plus"></span> Add Doctor(s)</a>
                    <ul class="uk-nav-sub">
                        <div class="uk-card uk-card-default uk-card-body">
                            <div class="uk-column-1-2">
                                <input type="text" class="w3-input uk-input" title="Service Provider Number" placeholder="Service Provider Number" name="practice_number" id="practice_number">
                                <input type="text" class="w3-input uk-input" title="Full Name" placeholder="Full Name" name="doc_full_name" id="doc_full_name">
                            </div>
                            <div class="uk-column-1-2">
                                <input type="number" class="w3-input uk-input" title="Gap Amount" placeholder="Gap Amount" name="gap" id="gap">
                                <input type="date" class="w3-input uk-input" title="Treatment Date" placeholder="Treatment Date" name="treatment_date" id="treatment_date">
                            </div>
                            <div class="uk-column-1-1">
                                <p align="center"> <span id="add_doc" style="cursor: pointer; color: #0c85d0" title="Add Doctor" class="uk-icon-button" uk-icon="icon: check" onclick="addDoctor()"></span></p>
                            </div>
                        </div>
                    </ul>
                </li>
            </ul>
            <div id="aler" class="uk-alert-success" style="display: none" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <p>Claim details successfully saved, note, you still need to go through the files upload process so that this information is saved into the system.</p>
            </div>
        </div>
        <div class="uk-modal-footer uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Close</button>
            <button class="uk-button uk-button-primary" type="button" onclick="add1()">Save</button>
        </div>
    </div>
</div>
</body>
</html>