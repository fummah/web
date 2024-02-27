<?php
session_start();
define("access",true);
$_SESSION["admin_main"]=true;
if(!isset($_POST["identity_number"]))
{
    die("Invalid access");
}

include ("../classes/DBConnect.php");
$db=new DBConnect();
$identity = (int)$_POST['identity_number'];
$user_id=(int)$_SESSION['user_id'];
if($identity==1)
{
    echo json_encode($db->locations(0 ,10000,"",0));
}
elseif($identity==2)
{
    $contact_number=htmlspecialchars($_POST["contact_number"]);
    $member_last_name=ucfirst(htmlspecialchars($_POST["member_last_name"]));
    $member_first_name=ucfirst(htmlspecialchars($_POST["member_first_name"]));
    $member_location=(int)$_POST["member_location"];
    $member_email_address=htmlspecialchars($_POST["member_email_address"]);
    $member_id_number=htmlspecialchars($_POST["member_id_number"]);
    $tick_funeral=(int)htmlspecialchars($_POST["tick_funeral"]);
    $contact_number=str_replace(" ","",$contact_number);

    if($member_location<1 || strlen($contact_number)>11 || (strlen($contact_number)<10 && strlen($contact_number)>0) || strlen($member_last_name)<3 || strlen($member_first_name)<3)
    {
        echo "<div class=\"uk-alert-danger\" uk-alert><a class=\"uk-alert-close\" uk-close></a><p align='center'>Invalid Input $member_location</p></div>";

    }
    else{
        if($db->isOpenFuneral() || $db->isTopLevel() || $db->isSecretary()) {
            $contact_number = substr($contact_number, -9);
            $contact_number = "+27" . $contact_number;
            if ($db->searchUserBy($contact_number, "contact_number", "members") == false) {
                if($db->searchByNameAndSurname($member_first_name,$member_last_name))
                {
                     die("<div class=\"uk-alert-danger\" uk-alert><a class=\"uk-alert-close\" uk-close></a><p align='center'>First Name and Surname already exist in the System</p></div>");
                }
                if ($db->addMember($member_first_name, $member_last_name, $contact_number, $member_id_number, $member_email_address, $db->loggedAs(), $member_location)) {
                    if ($tick_funeral > 0 && $db->isOpenFuneral()) {
                        $member_id = $db->getLatestMember($db->loggedAs());
                        $funeral_id = $db->getLatestFuneral();
                        $db->addRegister($member_id, $funeral_id, $db->loggedAs(), "paid");
                        $db->myAddPDF($member_id, $member_first_name,$member_last_name,$contact_number);
                    }
                    echo "<div class=\"uk-alert-success\" uk-alert><a class=\"uk-alert-close\" uk-close></a><p align='center'>Member Successfully loaded and ticked</p></div>";

                } else {
                    echo "<div class=\"uk-alert-danger\" uk-alert><a class=\"uk-alert-close\" uk-close></a><p align='center'>Failed to load member</p></div>";


                }
            } else {
                echo "<div class=\"uk-alert-danger\" uk-alert><a class=\"uk-alert-close\" uk-close></a><p align='center'>Duplicate Contact Number</p></div>";

            }
        }
        else
        {
            echo "<div class=\"uk-alert-danger\" uk-alert><a class=\"uk-alert-close\" uk-close></a><p align='center'>All Funerals are closed</p></div>";

        }

    }

}
elseif($identity==3)
{
    $funeral_id=$db->getLatestFuneral();
    echo json_encode($db->getFuneralHighDetails($funeral_id));
}
elseif($identity==4)
{
    $member_id=(int)$_POST["member_id"];
    echo json_encode($db->getSingleMember($member_id));
}
elseif($identity==5)
{
    $dependency_first_name=ucfirst(htmlspecialchars($_POST["dependency_first_name"]));
    $dependency_last_name=ucfirst(htmlspecialchars($_POST["dependency_last_name"]));
    $dependency_dob=htmlspecialchars($_POST["dependency_dob"]);
    $dependency_status=htmlspecialchars($_POST["dependency_status"]);
    $member_id=(int)htmlspecialchars($_POST["member_id"]);
    if($db->addDepedency($member_id,$dependency_first_name,$dependency_last_name,$db->loggedAs(),$dependency_dob,$dependency_status))
    {
        echo "success $dependency_first_name $dependency_last_name $dependency_dob $dependency_status $member_id";
    }
    else{
        echo "failed";
    }
}
elseif($identity==6)
{
    $member_id=(int)htmlspecialchars($_POST["member_id"]);
    echo json_encode($db->getDependencies($member_id));
}
elseif($identity==7) {
    $first_name = ucfirst(htmlspecialchars($_POST["first_name"]));
    $last_name = ucfirst(htmlspecialchars($_POST["last_name"]));
    $contact_number = htmlspecialchars($_POST["contact_number"]);
    if(strlen($contact_number)<10 || strlen($first_name) < 2 || strlen($last_name)<2)
    {
        die("<div class=\"uk-alert-danger\" uk-alert><a class=\"uk-alert-close\" uk-close></a><p align='center'>Invalid name / contact Number</p></div>");
    }
    $id_number = htmlspecialchars($_POST["id_number"]);
    $email_number = htmlspecialchars($_POST["email_number"]);
    $status = isset($_POST["status"])?htmlspecialchars($_POST["status"]):"";
    $location_id = (int)htmlspecialchars($_POST["location_id"]);
    $member_id = (int)htmlspecialchars($_POST["member_id"]);
    $contact_number = str_replace(" ", "", $contact_number);
    $contact_number = substr($contact_number, -9);
    $contact_number = "+27" . $contact_number;
    $coount=0;
    $arr=array("first_name"=>$first_name,"last_name"=>$last_name,"contact_number"=>$contact_number,"id_number"=>$id_number,"email_number"=>$email_number,"location_id"=>$location_id,"status"=>$status);
    foreach($arr as $key => $val) {
        $coount=$db->editDiff($key,$val,"member_id",$member_id,"members");
        //echo $key."--".$val."<hr>";
    }
    if ($coount==1) {
        $db->insertLogs($member_id,$db->loggedAs());

        echo "<div class=\"uk-alert-success\" uk-alert><a class=\"uk-alert-close\" uk-close></a><p align='center'>Member Successfully edited</p></div>";

    } else {
        echo "<div class=\"uk-alert-danger\" uk-alert><a class=\"uk-alert-close\" uk-close></a><p align='center'>Failed to edit</p></div>";


    }
}
elseif($identity==8)
{
    $start_from=$_POST["start_from"];
    $limit=$_POST["limit"];
    $member_id=$_POST["member_id"];
    $rows="";
    foreach($db->getIndividualFunerals($start_from,$limit) as $row)
    {
        $funeral_name=$row["funeral_name"];
        $funeral_id=(int)$row["funeral_id"];
        $register_arr=$db->getRegister($funeral_id,$member_id);
        $dod=$row["d_o_d"];
        $date_entered="--";
        $entered_by="--";
        $icon="--";
        if($register_arr==true)
        {
            $date_entered=$register_arr["date_entered"];
            $entered_by=$register_arr["entered_by"];
            $payment_status=$register_arr["status"];
            $icon=$db->getStatusIcon($payment_status);
        }
        $payment_amount=$row["amount_paid"];
        $rows.="<tr style=\" font-family: 'Montserrat',Helvetica,Arial,Lucida,sans-serif;  line-height: 1.6em; color: #0b8278\">
<td><span class='not_desktop' style='color: grey !important;'>Funeral Name : </span>$funeral_name</td>
<td><span class='not_desktop' style='color: grey !important;'>Date of Death : </span>$dod</td>
<td><span class='not_desktop' style='color: grey !important;'>Amount Paid : </span>$payment_amount</td>
<td><span class='not_desktop' style='color: grey !important;'>Date Entered : </span>$date_entered</td>
<td><span class='not_desktop' style='color: grey !important;'>Ticked By : </span>$entered_by</td><td>$icon</td></tr>";
    }
    echo $rows;
}
elseif ($identity==9)
{
    $funeral_id=(int)$_POST["funeral_id"];
    $member_id=(int)$_POST["member_id"];
    $ticked=(int)$_POST["ticked"];
    if($ticked==1)
    {
        if($db->addRegister($member_id,$funeral_id,$db->loggedAs(),"paid"))
        {
            $data=$db->getSingleMember($member_id);
            $first_name=$data["first_name"];
            $last_name=$data["last_name"];
            $contact_number=$data["contact_number"];
            $db->deleteR($member_id);
            $db->myAddPDF($member_id,$first_name,$last_name,$contact_number);
            echo "Ticked, thank you";
        }
        else{
            echo "Failed";
        }
    }
    else
    {
        if($db->deleteRegister($funeral_id,$member_id))
        {
            $db->deleteR($member_id);
            echo "Unticked";
        }
        else{
            echo "Failed";
        }
    }

}
elseif ($identity==10)
{
    if(!$db->isOpenFuneral())
    {
        die("No open funeral");
    }
    $funeral_id=(int)$_POST["funeral_id"];
    $member_id=(int)$_POST["member_id"];
    $ticked=(int)$_POST["ticked"];
    $data=$db->getSingleMember($member_id);
    $status=$data["status"];
    $first_name=$data["first_name"];
    $last_name=$data["last_name"];
    $contact_number=$data["contact_number"];
    if($status!="Active")
    {
        die("This is a deactivated member so you can't action on it. Please inform ZBS Administration.");
    }
    if($ticked==1)
    {
        if($db->addRegister($member_id,$funeral_id,$db->loggedAs(),"home"))
        {
            $db->deleteR($member_id);
            $db->myAddPDF($member_id, $first_name,$last_name,$contact_number);
            echo "Ticked";
        }
        else{
            echo "Failed";
        }
    }
    else
    {
        if($db->deleteRegister($funeral_id,$member_id))
        {
            $db->deleteR($member_id);
            echo "Unticked";
        }
        else{
            echo "Failed";
        }
    }

}
else if($identity==11)
{
    try {
        if(strlen($_POST["keyword"])>0) {
            $keyword=$_POST["keyword"];

            $xarr=$db->getSearchedMembers($keyword);
            $ccount=count($xarr);
            $msg="";
            if($ccount>0)
            {
                $msg="<ul id=\"country-list\" class=\"uk-card uk-card-body uk-card-default\">";
                foreach ($xarr as $row)
                {
                    $member_id=$row["member_id"];
                    $first_name=$row["first_name"];
                    $last_name=$row["last_name"];
                    $contact_number=$row["contact_number"];
                    $group_name=$row["group_name"];
                    $fullname=$first_name." ".$last_name;
                    $msg.="<li style=\"color: yellow;\" onClick=\"selectSearchedMember('$member_id','$fullname')\">$first_name $last_name<br><span style=\"color: #fff; font-size: small\">$contact_number</span><span style=\"color: black; font-size: small\"> ($group_name)</span></li>";
                }
                $msg.="</ul>";
            }
            echo $msg;
        }
    }
    catch (Exception $e)
    {
        echo $e->getMessage();
    }
}
elseif($identity==12)
{
    $paid="";$upaid="";$home="";
    $member_id=(int)$_POST["member_id"];
    $pos=isset($_POST["pos"])?(int)$_POST["pos"]:0;
    $aaar=$db->getSingleMember($member_id);
    $first_name=$aaar["first_name"];
    $last_name=$aaar["last_name"];
    $contact_number=$aaar["contact_number"];
    $status=$aaar["status"];
    $group_name=$aaar["group_name"];
    if($group_name!=$db->getGroupName())
    {
        die("<tr><td colspan='10'><div>You don't have permission to change anything on <b>Group $group_name </b>members, please change Group or contact your Admin</div></td></tr>");
    }
    $mem_arr=array_reverse($db->getIndividualFunerals(0,11));
    if($pos==3)
    {
        $funeral_id=(int)$_POST["funeral_id"];
        $reg_arr=$db->getRegister($funeral_id,$member_id);
        if($reg_arr==true)
        {
            $reg_status=$reg_arr["status"];
            if($reg_status=="paid")
            {
                $paid="checked";
            }
            elseif ($reg_status=="unpaid")
            {
                $upaid="checked";
            }
            elseif ($reg_status=="home")
            {
                $home="checked";
            }

        }
        echo " <div class=\"uk-margin uk-grid-small uk-child-width-auto uk-grid\">
            <label><input class=\"uk-radio\" type=\"radio\" name=\"radio2\" value='paid' onclick='decideHere(\"paid\",\"$member_id\")' $paid> <span uk-icon=\"check\" class='uk-icon-button' style=\"color: limegreen\"></span></label>
            <label><input class=\"uk-radio\" type=\"radio\" name=\"radio2\" value='unpaid' onclick='decideHere(\"unpaid\",\"$member_id\")' $upaid> <span uk-icon=\"close\" class='uk-icon-button' style=\"color: red\"></span></label>
            <label><input class=\"uk-radio\" type=\"radio\" name=\"radio2\" value='home' onclick='decideHere(\"home\",\"$member_id\")' $home> <span uk-icon=\"home\" class='uk-icon-button' style=\"color: cadetblue\"></span></label>
            
        </div>";

    }
    else{
        $db->loadMemberT($member_id,$first_name,$last_name,$contact_number,$status,$mem_arr);
    }

}
elseif($identity==13)
{
    $funeral_id=(int)$_POST["funeral_id"];
    $total_amount=(double)$_POST["paid_amount"];
    $expenses=(double)$_POST["paid_expenses"];
    if ($db->addAmounts($funeral_id,$total_amount,$expenses)) {

        echo "<div class=\"uk-alert-success\" uk-alert><a class=\"uk-alert-close\" uk-close></a><p align='center'>Successfully updated the funeral</p></div>";

    } else {
        echo "<div class=\"uk-alert-danger\" uk-alert><a class=\"uk-alert-close\" uk-close></a><p align='center'>Failed to add</p></div>";

    }
}
elseif($identity==14)
{
    $funeral_id=(int)$_POST["funeral_id"];
    echo json_encode($db->getPayments($funeral_id));
}
elseif($identity==15)
{
    $member_id=(int)$_POST["member_id"];
    $funeral_id=(int)$_POST["funeral_id"];
    $stta=htmlspecialchars($_POST["wch"]);
    $data=$db->getSingleMember($member_id);
     $first_name=$data["first_name"];
     $last_name=$data["last_name"];
     $contact_number=$data["contact_number"];
    if($db->getRegister($funeral_id,$member_id)==true)
    {
        if($db->updateRegister($funeral_id,$member_id,$stta))
        {
            $db->deleteR($member_id);
            $db->myAddPDF($member_id, $first_name,$last_name,$contact_number);
            echo "Successfully updated, thank you.";
        }
        else
        {
            echo "Failed to update";
        }
    }
    else
    {
        if($db->addRegister($member_id,$funeral_id,$db->loggedAs(),$stta))
        {
            $db->deleteR($member_id);
            $db->myAddPDF($member_id, $first_name,$last_name,$contact_number);
            echo "Successfully updated";
        }
        else{
            echo "Failed to update";
        }
        //$member_date_entered=$db->getSingleMember($member_id)["date_entered"];
        //$funeral_date_entered=$db->getFuneralById($funeral_id)["date_entered"];
        //if($member_date_entered>$funeral_date_entered)
    }
}
else if($identity==16)
{
    try {
        if(strlen($_POST["keyword"])>0) {
            $keyword=$_POST["keyword"];

            $xarr=$db->getSearchedFuneral($keyword);
            $ccount=count($xarr);
            $msg="";
            if($ccount>0)
            {
                $msg="<ul id=\"country-list\" class=\"uk-card uk-card-body uk-card-default\">";
                foreach ($xarr as $row)                {
                  
                    $funeral_id=$row["funeral_id"];
                    $funeral_name=$row["funeral_name"];
                                       
                    $msg.="<li style=\"color: yellow;\" onClick=\"selectSearchedFuneral('$funeral_id','$funeral_name')\">$funeral_name<br><span style=\"color: darkblue; font-size: small\">ID : $funeral_id</span></li>";
                }
                $msg.="</ul>";
            }
            echo $msg;
        }
    }
    catch (Exception $e)
    {
        echo $e->getMessage();
    }
}
elseif($identity==17)
{
    $funeral_id=(int)$_POST["funeral_id"];
    echo "<div style='background-color: red; color: white; padding: 10px'>
  <span class='open' data='users' style='cursor: pointer'>Users</span> | 
  <span class=\"open\" data='locations'  style='cursor: pointer'>Locations</span>
</div>";
    $msg="<div class='xop' id='users'><table id=\"myTable\" class='uk-table uk-table-divider'><thead><tr><th>Entered By</th><th>Total</th></tr></thead><tbody>";
    $ccount=1;
    $display = in_array($db->myRole(),$db->eRoles()) ?"":"ex";
    foreach ($db->getMarkers($funeral_id) as $row)
    {
        $ccount1=1;
        $entered_by=$row["entered_by"];
        $total=$row["total"];
        $msg.="<tr href=\"#\" data-node-id=\"$ccount\"><td><span>$entered_by</span></td><td><span class='uk-badge'>$total</span></td></tr>";
        foreach ($db->getMarkersByDay($funeral_id,$entered_by) as $row1) {
            $ccount2=1;
            $first=$ccount.".".$ccount1;
            $second=$ccount;
            $date_entered=$row1["date_entered"];
            $total1=$row1["total"];
            $msg.="<tr style='color: #2b669a !important; font-weight: bolder !important;' data-node-id=\"$first\" data-node-pid=\"$second\"><td><span>$date_entered</span></td><td><span class='uk-badge' style='background-color: #2b669a'>$total1</span></td></tr>";

            $ccount1++;
        }
        $ccount++;
    }
    $msg.="</tbody></table></div>";
    $msg2="<div class='xop' id='locations' style='display:none'><h2>Locations Report</h2><table class='uk-table uk-table-responsive uk-table-striped'><thead><tr><th>Location</th><th>Total</th><th>Marked</th><th>Unmarked</th><th>Home</th><th>Expected Amount</th><th>Advance Ticks</th><th>Advance Total</th><th>Actual Amount</th><th>Expenses</th><th class='$display'>Ex</th><th>Net Amount</th></tr></thead><tbody>";
$undertaker_name="";
$undertaker_cost=0;
$other_costs=0;
$bank_charges=0;
    foreach ($db->getLocationsReport($funeral_id) as $rowLocation)
    {
        $location_id=$rowLocation["location_id"];
        $location_name=$rowLocation["location_name"];
        $exticks=$rowLocation["ex"];
        $examount=$rowLocation["amount_paid"]*$exticks;
        $total=$rowLocation["total"]-$exticks;
        $total_paid=$rowLocation["total_paid"]-$exticks;
        $mxx=$db->getTransEx($funeral_id,$location_id);
        $advanceticks=0;
        $advancetotal=0;
        if($mxx != false)
        {
        $advanceticks=$mxx["total"]-$exticks;
        $advancetotal=$mxx["amount"]+$examount;
        }
        $total_unpaid=$rowLocation["total_unpaid"];
        $total_home=$rowLocation["total_home"];
        $total_expected=(double)$rowLocation["total_expected"];
        $expenses=(double)$rowLocation["expenses"];
        $actual_amount=(double)$rowLocation["actual_amount"];
        $undertaker_name=$rowLocation["undertaker_name"];
        $undertaker_cost=(double)$rowLocation["undertaker_cost"];
        $other_costs=(double)$rowLocation["other_costs"];
        $bank_charges=(double)$rowLocation["bank_charges"];
        $system_cost=(double)$rowLocation["system_cost"];
        $actual_total=$actual_amount-$expenses+$advancetotal;
        $amountxpected=$db->moneyformat($total_expected);
        $amountactual=$db->moneyformat($actual_total);
        
        
        $amtid="amt".$location_id;
        $expid="exp".$location_id;
        $ovid="ov".$location_id;
        $exid="exid".$location_id;
        $msg2.="<tr><td><span class='not_desktop'>Location : </span> <b>$location_name</b></td><td><span class='not_desktop'>Total Members : </span><span class='uk-badge'>$total</span></td><td><span class='not_desktop'>Total Marked : </span> <span class='uk-badge'>$total_paid</span></td>
<td><span class='not_desktop'>Total Unmarked : </span> <span class='uk-badge'>$total_unpaid</span></td><td><span class='not_desktop'>Total Home : </span><span class='uk-badge'>$total_home</span></td><td><span class='not_desktop'>Expected Amount : </span> <b>R $amountxpected</b></td>
<td><span class='not_desktop'>Advance Ticks : </span> <b>$advanceticks</b></td><td><span class='not_desktop'>Advance Amount : </span> <b>R $advancetotal</b></td>
<td><span class='not_desktop'>Amount Received : </span> <input type='text' class='uk-input inp' id='$amtid' value='$actual_amount' data='$location_id'></td>
<td><span class='not_desktop'>Expenses : </span><input type='text' id='$expid' class='uk-input inp' data='$location_id' value='$expenses'></td>
<td class='$display'><span class='not_desktop'>Ex : </span> <input type='text' class='uk-input inp' id='$exid' value='$exticks' data='$location_id'></td>
<td><span class='not_desktop'>Net Amount : </span> <b><span style='color: red !important;' id='$ovid'>R $amountactual</span></b></td>
<td><button class='uk-button uk-button-danger mybtn' data-abide='$funeral_id' data='$location_id'>Update</button></td></tr>";

    }
    $msg2.="</tbody></table><table class='uk-table uk-table-responsive uk-table-striped'><tr><td>Undertaker Name : <input type='text' class='uk-input inp' id='undertaker_name' value='$undertaker_name'></td>
<td>Undertaker Cost : <input type='text' class='uk-input inp' id='undertaker_cost' value='$undertaker_cost'></td>
<td>Security Cost : <input type='text' class='uk-input inp' id='other_costs' value='$other_costs'></td>
<td>Bank Charges : <input type='text' class='uk-input inp' id='bank_charges' value='$bank_charges'></td>
<td>System Maintenance Cost : <input type='text' class='uk-input inp' id='system_cost' value='$system_cost'></td>
<td><button class='uk-button uk-button-success undertaker' data='$funeral_id'>Update</button></td></tr></table><a href='pdf/report.php?funeral_id=$funeral_id'><button class='uk-button uk-button-secondary'><span uk-icon=\"icon: cloud-download\"></span> Download Report</button></a>";
    $msg1="</div><table class='uk-table uk-table-divider'><thead><tr><th>Date Entered</th><th>Total</th></tr></thead><tbody>";
    foreach ($db->getMarkersByDayAll($funeral_id) as $row3)
    {
        $date_entered=$row3["date_entered"];
        $total=$row3["total"];
        $msg1.="<tr style='background-color: cadetblue !important;'><td>$date_entered</td><td><span class='uk-badge' style='background-color: red'>$total</span></td></tr>";
        $ccount2++;
    }
    $msg1.="</tbody></table>";
    echo $msg."<hr>".$msg2."<hr>".$msg1;
    ?>
    <script>
        $('#myTable').simpleTreeTable({
            opened:'none',
        });
    </script>
    <?php
}
elseif($identity==18)
{
    $member_id=(int)$_POST["member_id"];
    $db->insertLogs($member_id,$db->loggedAs());
    if($db->deleteMember($member_id))
    {
        echo "Deleted";
    }
    else{
        echo "Failed to delete";
    }
}
elseif($identity==19)
{

    $confirm_password=htmlspecialchars($_POST["confirm_password"]);
    $password=htmlspecialchars($_POST["password"]);
    $user_id=(int)$_POST["user_id"];

    if(strlen($password)<7 || $password!=$confirm_password)
    {
        echo "<div class=\"uk-alert-danger\" uk-alert><a class=\"uk-alert-close\" uk-close></a><p align='center'>Invalid Password</p></div>";
    }
    else{
        $password = password_hash($password, PASSWORD_DEFAULT);
        $coount=$db->editDiff("password",$password,"user_id",$user_id,"users");
        if($coount>0)
        {
            echo "<div class=\"uk-alert-success\" uk-alert><a class=\"uk-alert-close\" uk-close></a><p align='center'>Password Successfully updated</p></div>";
        }
        else{

            echo "<div class=\"uk-alert-danger\" uk-alert><a class=\"uk-alert-close\" uk-close></a><p align='center'>Failed to update</p></div>";

        }
    }
}
elseif($identity==20)
{
    $user_id=(int)$_POST["user_id"];
    $status=(int)$_POST["status"];
    $status=$status==1?0:1;
    $coount=$db->editDiff("status",$status,"user_id",$user_id,"users");
    if($coount>0)
    {
        echo "Status Updated";
    }
    else{
        echo "Failed to update";
    }
}
elseif($identity==21)
{
    //$db->emptyActive();
    echo $db->getPDFNumber();
}
elseif($identity==22)
{
    $arr=array_reverse($db->getReportTrend());
    echo json_encode($arr,true);
}
elseif($identity==23)
{
    $funeral_id=(int)$_POST['funeral_id'];
    $location_id=(int)$_POST['location_id'];
    $amount=(double)$_POST['amount'];
    $expenses=(double)$_POST['expenses'];
    $ex=(int)$_POST['ex'];
    $entered_by=$db->loggedAs();
    if(count($db->getFuneralsTrans($funeral_id,$location_id))>0)
    {
        echo $db->updateFuneralsTrans($funeral_id,$location_id,$amount,$expenses,$entered_by,$ex);
    }
    else{
        echo $db->insertFuneralsTrans($funeral_id,$location_id,$amount,$expenses,$entered_by,$ex);
    }

}
elseif($identity==24)
{
    $user_id=(int)$_POST['user_id'];
    echo json_encode($db->getUser($user_id),true);
}
elseif($identity==25)
{
    $user_id=(int)$_POST['user_id'];
    $first_name=$_POST['first_name'];
    $last_name=$_POST['last_name'];
    $role=$_POST['role'];
    $contact_number=$_POST['contact_number'];
    $location_id=$_POST['location_id'];
    $db->updateUser($user_id,"first_name",$first_name);
    $db->updateUser($user_id,"last_name",$last_name);
    $db->updateUser($user_id,"role",$role);
    $db->updateUser($user_id,"contact_number",$contact_number);
    if($db->updateUser($user_id,"location_id",$location_id))
    {
        echo "Record Updated";
    }
    else{
        echo "Failed to update";
    }
}
elseif($identity==26)
{
    $funeral_id=(int)$_POST['funeral_id'];
    $undertaker_name=$_POST['undertaker_name'];
    $undertaker_cost=(double)$_POST['undertaker_cost'];
    $other_costs=(double)$_POST['other_costs'];
    $bank_charges=(double)$_POST['bank_charges'];
    $system_cost=(double)$_POST['system_cost'];
    $db->updateFuneral($funeral_id,"undertaker_name",$undertaker_name);
    $db->updateFuneral($funeral_id,"undertaker_cost",$undertaker_cost);
    $db->updateFuneral($funeral_id,"bank_charges",$bank_charges);
    $db->updateFuneral($funeral_id,"system_cost",$system_cost);
    echo $db->updateFuneral($funeral_id,"other_costs",$other_costs);

}

elseif($identity==27)
{
    if($db->isOpenFuneral())
    {
         die ("<div class=\"uk-alert-danger\" uk-alert><a class=\"uk-alert-close\" uk-close></a><p align='center'>Please close the current funeral</p></div>");
    }
    $funeral_type=$_POST['funeral_type'];
    $price=$_POST['price'];
    $final_payment_date=$_POST['final_payment_date'];
    $deceased=$_POST['deceased'];
    $entered_by=$db->loggedAs();
    $ccount=count($deceased);
    $latestfuneral=$db->getLatestFuneral()+1;
    $msg=0;
    if($ccount>1)
    {       
        $funeral_name="Combined-".$db->getGroupName().$latestfuneral;
        $db->addFuneral($funeral_name,$price,$final_payment_date,$entered_by,"Combined","","");
        $funeral_id=$db->getLatestFuneral();
        foreach($deceased as $row)
        {
            $member_id=$row['member_id'];
            $d_o_d=$row['d_o_d'];
            $family_member=$row['family_member'];
            $family_member_phone=$row['family_member_phone'];
            $state_mem=$row['state_mem'];
            $msg=$db->addDeceased($funeral_id,$member_id,$state_mem,$d_o_d,$family_member,$entered_by,$family_member_phone);
            if($state_mem=="Owner")
            {
                $db->editDiff("status","Dead","member_id",$member_id,"members");
            }
            
        }
        $db->registerUsers($funeral_id);
    }
    else{
        foreach($deceased as $row)
        {
            $member_id=$row['member_id'];
            $d_o_d=$row['d_o_d'];
            $family_member=$row['family_member'];
            $family_member_phone=$row['family_member_phone'];
            $state_mem=$row['state_mem'];
            $data=$db->getSingleMember($member_id);
            $funeral_name=$data["first_name"]." ".$data["last_name"];           
            $db->addFuneral($funeral_name,$price,$final_payment_date,$entered_by,"Single",$family_member,$family_member_phone);
            $funeral_id=$db->getLatestFuneral();
            $msg=$db->addDeceased($funeral_id,$member_id,$state_mem,$d_o_d,$family_member,$entered_by,$family_member_phone);
                if($state_mem=="Owner")
            {
                $db->editDiff("status","Dead","member_id",$member_id,"members");
            }
            
        }
        $db->registerUsers($funeral_id);
        
    }
if($msg>0)
{
echo "<div class=\"uk-alert-success\" uk-alert><a class=\"uk-alert-close\" uk-close></a><p align='center'>Funeral Successfully Created, you may click the flag to check</p></div>";

    } else {
        echo "<div class=\"uk-alert-danger\" uk-alert><a class=\"uk-alert-close\" uk-close></a><p align='center'>Failed to create funeral</p></div>";

    }

}
elseif($identity==28)
{
    $members=$_POST['members'];
    $failed=[];
    $success=[];
    $duplicates=[];
    foreach($members as $row)
    {
        $first_name=preg_replace("/[^a-zA-Z]/", "",$row["first_name"]);
        $last_name=preg_replace("/[^a-zA-Z]/", "",$row["last_name"]);

$xc=$db->getMemberLoader($first_name,$last_name);
if($xc==true)
{
    $member_id=$xc["member_id"];
    $first_name=$xc["first_name"];
    $last_name=$xc["last_name"];
    $contact_number=$xc["contact_number"];
    $funeral_id=$db->getLatestFuneral();
if($db->checkMarked($funeral_id,$member_id)==false) 
{
  if($db->addRegister($member_id,$funeral_id,$db->loggedAs(),"paid"))
        {
            $db->deleteR($member_id);
            $db->myAddPDF($member_id, $first_name,$last_name,$contact_number);
           $in=array("first_name"=>$first_name,"last_name"=>$last_name);
    array_push($success,$in);
        }
        else{
         $in=array("first_name"=>$first_name,"last_name"=>$last_name);
    array_push($failed,$in);
        }
}
else{
     $in=array("first_name"=>$first_name,"last_name"=>$last_name);
    array_push($duplicates,$in);
}
}
else
{
    $in=array("first_name"=>$first_name,"last_name"=>$last_name);
    array_push($failed,$in);
}
    }
    $arr=array("success"=>$success,"failed"=>$failed,"duplicates"=>$duplicates);
    
    echo json_encode($arr,true);
}
elseif($identity==29)
{
    $dep_id=(int)$_POST["dep_id"];
$xx=$db->insertDependencyLogs($dep_id,$db->loggedAs());
    $count=(int)$db->deleteDependent($dep_id);
    if($count>0)
    {
        
        echo "success";
    }
    else
    {
        echo "failed";
    }
    }

    elseif($identity==29)
{
    $dep_id=(int)$_POST["dep_id"];
$xx=$db->insertDependencyLogs($dep_id,$db->loggedAs());
    $count=(int)$db->deleteDependent($dep_id);
    if($count>0)
    {
        
        echo "success";
    }
    else
    {
        echo "failed";
    }
    }
    elseif($identity== 30)
    {
        $member_id=(int)$_POST["member_id"];
        $deposit_amount=(double)$_POST["deposit_amount"];
        $configs = $db->getConfig();
        $account_limit=(double)$configs["account_limit"];
        $deposit_limit=(double)$configs["deposit_limit"];
        $details =$db->getSingleMember($member_id);
        $current_balance = (double)$details["account_balance"];
        if($current_balance>= $account_limit)
        {
            echo "<div class='uk-alert-danger' uk-alert><a href class='uk-alert-close'uk-close></a><p>The member acount has reached the limit.</p></div>";
        }
        elseif($deposit_amount > $deposit_limit)
        {
            echo "<div class='uk-alert-danger' uk-alert><a href class='uk-alert-close'uk-close></a><p>The amount should not be greater than $deposit_limit</p></div>";
      
        }
        elseif($deposit_amount < 20)
        {
            echo "<div class='uk-alert-danger' uk-alert><a href class='uk-alert-close'uk-close></a><p>Invalid Ammount</p></div>";
      
        }
        else
        {
            $total_amount =$current_balance+$deposit_amount;
            $db->editDiff("account_balance",$total_amount,"member_id",$member_id,"members");
            $db->insertNotification($member_id,"Your deposit has been received. Thank you.","System","Amount Deposit");
            $db->insertTranctions($member_id,$deposit_amount,$db->loggedAs(),"Amount Deposit",0);
            echo "<div class='uk-alert-success' uk-alert><a href class='uk-alert-close'uk-close></a><p>Deposit Successful</p></div>";
      
        }
        
    }
    elseif($identity== 31)
    {
        $member_id=(int)$_POST["member_id"];
        $txt="";
        foreach($db->getTransaction($member_id) as $transaction)
        {
            $amount=$transaction["amount"];
            $date_entered=$transaction["date_entered"];
            $entered_by=$transaction["entered_by"];
            $transaction_name=$transaction["transaction_name"];
            $txt.= "Transaction : ".$transaction_name."<br>Entered By : ".$entered_by."<br>Date Entered : ".$date_entered."<br>Amount : R".$amount."<hr>";
        }
        echo $txt;
    }
?>


