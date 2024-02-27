<?php
session_start();
define("access",true);
include ("classes/controls.php");
$control=new controls();
if(!$control->isGapCover())
{
    die("Invalid access");
}
include ("header.php");
?>
<html>
<head>
    <title>MCA | GAP New Claim</title>
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
class docs extends controls
{
    public $successarr;
    public $errorarr;
    public $mmyid;
    public $myClaim;
    public $myform;
    public $editnew=0;
    function __construct()
    {
        parent::__construct();
        $this->errorarr=array();
        $this->successarr=array();
        $this->mmyid=0;
        $this->myform="";
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
        $sql = $conn->prepare('SELECT a.claim_number,a.date_entered,a.Open,b.policy_number FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id WHERE a.claim_id=:claim');
        $sql->bindParam(':claim', $claim_id, PDO::PARAM_STR);
        $sql->execute();
        $row=$sql->fetch();
        $myClaim_number['claim_number']=$row[0];
        $myClaim_number['date_entered']=$row[1];
        $myClaim_number['open']=(int)$row[2];
        $myClaim_number['policy_number']=$row[3];

        return $myClaim_number;
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
    function addMember($policy_number,$entered_by,$kaeloclient="")
    {

        global $conn;
        $client_name=$_SESSION['user_id'];
        if($client_name=="Kaelo" || $client_name=="Western")
        {
            $client_name=$kaeloclient;
        }
        $client_id=(int)$this->viewCheckClient($client_name)["reporting_client_id"];
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
    function addClaim($claim_number,$policy_number,$entered_by,$kaeloclient ="")
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
        $client_id=(int)$this->viewCheckClient($client_name)["reporting_client_id"];
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

                $sql = $conn->prepare('INSERT INTO `claim`(`member_id`, `claim_number`,`username`,`createdBy`,`Open`,`senderId`) VALUES (:member_id,:claim_number,:username,:createdBy,:Open1,:senderId)');
                $sql->bindParam(':member_id', $member_id, PDO::PARAM_STR);
                $sql->bindParam(':claim_number', $claim_number, PDO::PARAM_STR);
                $sql->bindParam(':username', $username, PDO::PARAM_STR);
                $sql->bindParam(':createdBy', $createdBy, PDO::PARAM_STR);
                $sql->bindParam(':Open1', $openx, PDO::PARAM_STR);
                $sql->bindParam(':senderId', $senderId, PDO::PARAM_STR);
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
                $this->callInsertPatient($claim_id,$patient_name,$username);
            }


        }

    }


    function addDoctor($claim_id,$practiceNo,$practiceName,$gap,$treatment_date)
    {

        if ($this->viewDoctor($practiceNo)==false) {
            $providertypedesc="";
            $this->callInsertDoctorDetails($practiceName,"","","",$practiceNo,"",$providertypedesc,"'","","","","","","","","","","","",$_SESSION['fullname']);
        }
        if($this->viewSpecific($claim_id,$practiceNo)==false)
        {
            $rr=$this->callInsertClaimDoctor($practiceNo,$claim_id,$_SESSION['fullname']);
            if($rr==1){
                $this->callInsertClaimLine($claim_id,$practiceNo,0,0,0,"","","","","",0,"",$treatment_date,$_SESSION['fullname']);
            }

        }

    }

    function displayDoc($claim_id)
    {
        if (count($this->viewDocuments($claim_id)) > 0) {
            echo"<table border='0' width='100%'><tr style='background-color:black;color: white'><th>File Name</th><th>Size</th><th>Date Entered</th></tr>";
            foreach ($this->viewDocuments($claim_id) as $row) {
                //doc_id,randomNum,doc_description,additional_doc,doc_type,doc_size
                $id = $row["doc_id"];
                $ra=$row["randomNum"];
                $nname = $row["doc_description"];
                $desc="../../mca/test/".$ra.$nname;
                $date = $row["date"];
                $size = round($row["doc_size"]/1024);
                echo"<tr><td>
<form action='view_file.php' method='post' target='_blank'/><input type=\"hidden\" name=\"my_doc\" value=\"$desc\" />
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
                        if(strlen($claim_number)<2 || strlen($policy_number)<2)
                        {
                            die("Incorrect policy/claim number");
                        }
                    }

                    else {
                        die("Incorrect claim number");
                    }
                }
                $username = "System";
                $claim_id = $this->addClaim($claim_number, $policy_number, $username,$kaeloclient);
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
                    $this->callUpdateClaimKey($claim_id,"Service_Date",$start_date);
                    $this->callUpdateClaimKey($claim_id,"end_date",$end_date);
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
                $open=$this->checkClaimnumber($this->mmyid)["open"];
                if($open==0)
                {
                    $this->callUpdateClaimKey($this->mmyid,"Open",1);

                    array_push($this->successarr, "Claim re-opened.");
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
                                            $target = "../../mca/test/";
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
                                                $this->callInsertDocuments($claim_id,$nname,$size,$type,$ra,$_SESSION['fullname']);
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

<body>

<h3 align="center">MCA | Add New Claim</h3>

<div style="position: relative; width: 60%; margin-right: auto;margin-left: auto;" class="uk-card uk-card-default uk-card-body">
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
        if($_SESSION['user_id']=="Total_risk_administrators" || $_SESSION['user_id']=="Cinagi")
        {
            ?>
            <div class="uk-margin" style="width: 50%">
                <label>Service Type</label>
                <select class="" name="totalrisk">
                    <option value="Clinical Review">Clinical Review Services</option>
                    <option value="PMB Negotiation">PMB/Negotiation</option>
                </select>
            </div>
            <?php
        }
        ?>
        <p>
            <button class="uk-button uk-button-primary" name="upload" id="upload" style="background-color: #54bf99;" type="submit"><span class="glyphicon glyphicon-circle-arrow-up"></span> Upload Now</button>
        </p>
        <span id="show" style="color: red"></span>
    </form>
    <hr class="uk-divider-icon">
    <?php

    if(count($pros->successarr)>0) {
        echo "<div class=\"uk-placeholder uk-text-center\" >";
        foreach ($pros->successarr as $su) {
            echo"<div style='color: #00a65a'><span uk-icon=\"check\"></span> $su</div>";
        }

        echo "</div><hr>";
    }

    ///errors
    if(count($pros->errorarr)>0) {
        echo "<div class=\"uk-placeholder uk-text-center\">";
        foreach ($pros->errorarr as $su) {
            echo"<div><span uk-icon=\"trash\"></span> $su</div>";
        }
        echo "</div><hr>";
    }
    echo $pros->myform;
    if($pros->mmyid>0) {
        $pros->displayDoc($pros->mmyid);
    }
    ?>
</div>
<div id="modal-sections" uk-modal>
    <div class="uk-modal-dialog">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header">
            <h2 class="uk-modal-title">Add Additional Details</h2>
        </div>
        <div class="uk-modal-body">
            <div class="row">
                <div class="col-md-6"><input type="text" class="w3-input uk-input" title="Policyholder Name" placeholder="Policyholder Name" name="first_name" id="first_name"></div>
                <div class="col-md-6"><input type="text" class="w3-input uk-input" title="Policyholder Surname" placeholder="Policyholder Surname" name="surname" id="surname"></div>
            </div>
            <div class="row">
                <div class="col-md-6"><input type="text" class="w3-input uk-input" title="Patient Name" placeholder="Patient Name" name="patient_name" id="patient_name"></div>
                <div class="col-md-6"><input type="text" class="w3-input uk-input" title="Patient Surname" placeholder="Patient Surname" name="patient_surname" id="patient_surname"></div>
            </div>
            <div class="row">
                <div class="col-md-6"><input type="text" class="w3-input uk-input" title="Cell Phone" placeholder="Cell Phone" name="cell" id="cell"></div>
                <div class="col-md-6"><input type="email" class="w3-input uk-input" title="Email Address" placeholder="Email Address" name="email" id="email"></div>
            </div>
            <div class="row">
                <div class="col-md-6"><input type="<?php echo $hhis;?>" class="w3-input uk-input" uk-tooltip="title: Incident Start Date" placeholder="Incident Start Date" name="start_date" id="start_date"></div>
                <div class="col-md-6"><input type="<?php echo $hhis;?>" class="w3-input uk-input" uk-tooltip="title: Incident End Date" placeholder="Incident End Date" name="end_date" id="end_date"></div>
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