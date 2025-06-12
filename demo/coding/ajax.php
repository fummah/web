<?php
define("access",true);
include "../classes/apiClass.php";
use mcaAPI\apiClass as myAPI;
$api= new myAPI();
if(!isset($_POST["identity_number"]))
{
    die("Invalid access");
}
$identity=(int)$_POST["identity_number"];
$start_date=$_POST["start_date"];
$end_date=$_POST["end_date"];
$medical_scheme=isset($_POST["medical_scheme"])?$_POST["medical_scheme"]:"";
$txtup=isset($_POST["txtup"])?$_POST["txtup"]:"";
$txtstatus=isset($_POST["txtstatus"])?$_POST["txtstatus"]:"";
$scheme_sql="";
if(!empty($medical_scheme))
{
    $medical_scheme_array=array_map('strval', $medical_scheme);
    $medical_em = implode("','",$medical_scheme_array);
    $scheme_sql=!empty($medical_scheme)?" AND b.medicalaid IN ('".$medical_em."')":"";
}

$individual="";
$claimstatus="";
if($txtup=="Top 10"){$txtup=" DESC LIMIT 10";}
elseif ($txtup=="Bottom 10"){$txtup=" ASC LIMIT 10";}
else
{
    $individual=$txtup;
    $txtup="";
}
if($txtstatus=="Rejected Claims"){$claimstatus="Claim Rejected";}
elseif ($txtstatus=="Approved Claims"){$claimstatus="Claim Approved and Paid";}


if($identity==1)
{
echo json_encode($api->getClients(),true);
}
elseif($identity==2)
{
    echo json_encode($api->getUsers(),true);
}
elseif($identity==3)
{
    $trx="";
    $val=$_POST["val_ticked"];
    $cli=json_decode($_POST["clients"],true);
    $use=json_decode($_POST["users"],true);
    $clients=implode(",",$cli);
    $users=implode(",",$use);
    $start_date=$_POST["start_date"];
    $end_date=$_POST["end_date"];
    $hierach=json_decode($_POST["hierach"],true);
    if(empty($start_date) || empty($end_date))
    {
        die("Please Select Date Range");
    }
    $columnsx=json_decode($_POST["columns"]);
    $count=count($columnsx);
    $xarr=$api->getTopLevel($_POST["columns"],$hierach[0],$clients,$users,$start_date,$end_date);
    $countx=0;
if(count($xarr)>0) {
    foreach ($xarr as $rows) {
        $countx++;
        $initial_desc=$rows[0];
        $initial=empty($initial_desc)?"NULL":$initial_desc;
        $id1="group-".$countx;
        $idstring="groups-".$countx;
        $trx .= "<tr title='$hierach[0]' style='color: #0b8278; font-weight: bolder;' id='$idstring' data-node-id=\"$countx\"><td width=\"30%\" style='cursor: pointer; padding-top: 5px !important;padding-bottom: 5px !important;'> <span onclick='addTree(\"$countx\",\"$initial_desc\")'><span uk-icon='play-circle'></span></span> $initial</td>";
        for ($i = 1; $i < $count + 1; $i++) {
            $val = $rows[$i];
            //$pos = strpos($val, ".");
            //$val=$pos===false?$val:$api->moneyformat($val);
            $trx .= "<td style='padding-left: 10px'>" . $val . "</td>";
        }
        $trx .= "</tr>";
    }
    echo $trx;
}
else{
    echo "No Information found.";
}
}
elseif($identity==4)
{
    $arr=array("top"=>$api->topFields(),"totals"=>$api->groupTotals());
    echo json_encode($arr,true);
}
elseif ($identity==5)
{
    $cli=json_decode($_POST["clients"],true);
    $use=json_decode($_POST["users"],true);
    $clients=implode(",",$cli);
    $users=implode(",",$use);
    $start_date=$_POST["start_date"];
    $end_date=$_POST["end_date"];
    $hierach=$_POST["hierach"];
    $columns=$_POST["columns"];
    $counter=(int)$_POST["counter"];
    $myf1=$_POST["name"];
    $columnsx=json_decode($columns);
    $countcolums=count($columnsx);
    $ourarr=json_decode($hierach,true);
    $count_hierach=count($ourarr);
    $myclass="clas-".$counter;
    $field=$ourarr[0];
    $first_arr=[$field=>$myf1];

        $id2="sub-group-".$counter;
        $id3="sub-sub-group-".$counter;
        $xarr=$api->getTopLevel($columns,$ourarr[1],$clients,$users,$start_date,$end_date,$first_arr);
        $num1=0;
        foreach ($xarr as $xrow)
        {
            //first row
            $search_arr=$first_arr;
            $num1++;
            $firstid=$counter.".".$num1;
            $forsec1=$counter;
            $field=$ourarr[1];
            $myf1=$xrow[0];
            $myf1_desc=empty($myf1)?"NULL":$myf1;
echo "<tr title='$field' class='$myclass cc' style='color: brown; font-weight: bolder' data-node-id=\"$firstid\" data-node-pid=\"$forsec1\" style='font-weight: bolder'><td width=\"30%\"> $myf1_desc</td>";
            for ($i = 1; $i < $countcolums + 1; $i++) {
                $val = $xrow[$i];
                //$pos = strpos($val, ".");
                //$val=$pos===false?$val:$api->moneyformat($val);
                echo"<td style='padding-left: 10px'>" . $val . "</td>";
            }


            $search_arr=array_merge($search_arr,[$field=>$myf1]);

            echo "</tr>";
            $xarr=$api->getTopLevel($columns,$ourarr[2],$clients,$users,$start_date,$end_date,$search_arr);
            $num2=0;
            foreach ($xarr as $xrow1) {
                //second row
                $search_arr1=$search_arr;
                $num2++;
                $myf1 = $xrow1[0];
                $firstid1=$counter.".".$num1.".".$num2;
                $forsec2=$counter.".".$num1;
                $field = $ourarr[2];
                $myf1_desc=empty($myf1)?"NULL":$myf1;
                echo "<tr title='$field' style='color: dodgerblue; font-weight: bolder' class='$myclass cc' data-node-id=\"$firstid1\" data-node-pid=\"$forsec2\"><td width=\"30%\"> $myf1_desc</td>";
                for ($i = 1; $i < $countcolums + 1; $i++) {
                    $val = $xrow1[$i];
                    //$pos = strpos($val, ".");
                    //$val=$pos===false?$val:$api->moneyformat($val);
                    echo"<td style='padding-left: 10px'>" . $val . "</td>";
                }

echo"</tr>";

                $search_arr1=array_merge($search_arr1,[$field=>$myf1]);
                $xarr = $api->getTopLevel($columns, $ourarr[3], $clients, $users, $start_date, $end_date,$search_arr1);
                $num3=0;
                foreach ($xarr as $xrow2) {
                    //third row
                    $search_arr2=$search_arr1;
                    $num3++;
                    $myf1 = $xrow2[0];
                    $forsec3=$counter.".".$num1.".".$num2;
                    $firstid2=$counter.".".$num1.".".$num2.".".$num3;
                    $field = $ourarr[3];
                    $myf1_desc=empty($myf1)?"NULL":$myf1;
                    echo "<tr title='$field' style='color: darkslateblue; font-weight: bolder' class='$myclass cc' data-node-id=\"$firstid2\" data-node-pid=\"$forsec3\"><td width=\"30%\"> $myf1_desc</td>";
                    for ($i = 1; $i < $countcolums + 1; $i++) {
                        $val = $xrow2[$i];
                        //$pos = strpos($val, ".");
                        //$val=$pos===false?$val:$api->moneyformat($val);
                        echo"<td style='padding-left: 10px'>" . $val . "</td>";
                    }
                    echo "</tr>";

                    $search_arr2=array_merge($search_arr2,[$field=>$myf1]);
                    $xarr = $api->getTopLevel($columns, $ourarr[4], $clients, $users, $start_date, $end_date,$search_arr2);
                    $num4=0;
                    foreach ($xarr as $xrow3) {
                        //forth row
                        $search_arr3=$search_arr2;
                        $num4++;
                        $myf1 = $xrow3[0];
                        $firstid3=$counter.".".$num1.".".$num2.".".$num3.".".$num4;
                        $forsec4=$counter.".".$num1.".".$num2.".".$num3;
                        $field = $ourarr[4];
                        $myf1_desc=empty($myf1)?"NULL":$myf1;
                        echo "<tr title='$field' style='color: red; font-weight: bolder' class='$myclass cc' data-node-id=\"$firstid3\" data-node-pid=\"$forsec4\"><td width=\"30%\"> $myf1_desc</td>";
                        for ($i = 1; $i < $countcolums + 1; $i++) {
                            $val = $xrow3[$i];
                            //$pos = strpos($val, ".");
                            //$val=$pos===false?$val:$api->moneyformat($val);
                            echo"<td style='padding-left: 10px'>" . $val . "</td>";
                        }
                        echo "</tr>";

                        $search_arr3=array_merge($search_arr3,[$field=>$myf1]);
                        $xarr = $api->getTopLevel($columns, $ourarr[5], $clients, $users, $start_date, $end_date,$search_arr3);
                        $num5=0;
                        foreach ($xarr as $xrow4) {
                            //fifth row
                            $search_arr4=$search_arr3;
                            $num5++;
                            $myf1 = $xrow4[0];
                            $firstid4=$counter.".".$num1.".".$num2.".".$num3.".".$num4.".".$num5;
                            $forsec5=$counter.".".$num1.".".$num2.".".$num3.".".$num4;
                            $field = $ourarr[5];
                            $myf1_desc=empty($myf1)?"NULL":$myf1;
                            echo "<tr title='$field' style='color: green; font-weight: bolder' class='$myclass cc' data-node-id=\"$firstid4\" data-node-pid=\"$forsec5\"><td width=\"30%\"> $myf1_desc</td>";
                            for ($i = 1; $i < $countcolums + 1; $i++) {
                                $val = $xrow4[$i];
                                //$pos = strpos($val, ".");
                                //$val=$pos===false?$val:$api->moneyformat($val);
                                echo"<td style='padding-left: 10px'>" . $val . "</td>";
                            }
                            echo "</tr>";

                            $search_arr4=array_merge($search_arr4,[$field=>$myf1]);
                            $xarr = $api->getTopLevel($columns, $ourarr[6], $clients, $users, $start_date, $end_date,$search_arr4);
                            $num6=0;
                            foreach ($xarr as $xrow5) {
                                //sixth row
                                //$search_arr5=$search_arr4;
                                $num6++;
                                $myf1 = $xrow5[0];
                                $firstid5=$counter.".".$num1.".".$num2.".".$num3.".".$num4.".".$num5.".".$num6;
                                $forsec6=$counter.".".$num1.".".$num2.".".$num3.".".$num4.".".$num5;
                                $myf1_desc=empty($myf1)?"NULL":$myf1;
                                echo "<tr title='$ourarr[6]' class='$myclass cc' data-node-id=\"$firstid5\" data-node-pid=\"$forsec6\"><td width=\"30%\"><a href='claim.php?claim_number=$myf1' target=\"popup\" 
  onclick=\"window.open('claim.php?claim_number=$myf1','popup','width=1000,height=600'); return false;\"> <i>$myf1_desc</i></a></td>";
                                for ($i = 1; $i < $countcolums + 1; $i++) {
                                    $val = $xrow5[$i];
                                    //$pos = strpos($val, ".");
                                    //$val=$pos===false?$val:$api->moneyformat($val);
                                    echo"<td style='padding-left: 10px'>" . $val . "</a></td>";
                                }
                                echo "</tr>";
                            }

                        }

                    }

                }

            }

        }
        ?>
    <script>
        $('#myTable').simpleTreeTable({
            opened:'none',
        });
        $('#open1').on('click', function() {
            $('#basic').data('simple-tree-table').openByID("1");
        });
        $('#close1').on('click', function() {
            $('#basic').data('simple-tree-table').closeByID("1");
        });
    </script>
<?php
}
elseif($identity==6)
{
    $arr=array("top"=>$api->biMenu(),"totals"=>$api->groupTotals(),"medical_aid"=>$api->getMedicalScheme());
    echo json_encode($arr,true);
}
elseif($identity==7)
{
    $arr=$api->getBenefit();
    echo json_encode($arr,true);
}
elseif($identity==8)
{
    $individual=!empty($individual)?" AND Hospital = '".$individual."'":"";
    $claimstatus=!empty($claimstatus)?" AND claim_status = '".$claimstatus."'":"";
    $individual.=$claimstatus;
    $arr=$api->getHospitals($start_date,$end_date,$scheme_sql,$txtup,$individual);
    echo json_encode($arr,true);
}
elseif($identity==9)
{
    $arr=$api->getHospitalsByRegion();
    echo json_encode($arr,true);
}
elseif($identity==10)
{
    $individual=!empty($individual)?" AND disciplinecode = '".$individual."'":"";
    $claimstatus=!empty($claimstatus)?" AND claim_status = '".$claimstatus."'":"";
    $individual.=$claimstatus;
    $arr=array("products"=>$api->clientArr(),"data"=>$api->groupBySpeciality($start_date,$end_date,$scheme_sql,$txtup,$individual));
    echo json_encode($arr,true);
}
elseif($identity==11)
{
    $arr=array("data"=>$api->getRegionsHospitals(),"years"=>$api->getYear(),"products"=>$api->clientArr());
    echo json_encode($arr,true);
}
elseif($identity==12)
{
    $individual=!empty($individual)?" AND k.claiminsureditem_benefittiers = '".$individual."'":"";
    $claimstatus=!empty($claimstatus)?" AND claim_status = '".$claimstatus."'":"";
    $individual.=$claimstatus;
    $arr=array("products"=>$api->clientArr(),"data"=>$api->groupByBenefits($start_date,$end_date,$scheme_sql,$txtup,$individual),"seamless"=>$api->seamlessCount());
    echo json_encode($arr,true);

}
elseif($identity==13)
{
    //echo "$start_date,$end_date,$scheme_sql,$txtup,$individual";
   $individual=!empty($individual)?" AND k.claiminsureditem_icdcode = '".$individual."'":"";
   $claimstatus=!empty($claimstatus)?" AND claim_status = '".$claimstatus."'":"";
    $individual.=$claimstatus;
    $arr=array("products"=>$api->clientArr(),"data"=>$api->groupByICD($start_date,$end_date,$scheme_sql,$txtup,$individual),"seamless"=>$api->seamlessCount());
   echo json_encode($arr,true);
}
elseif($identity==14)
{
    $individual=!empty($individual)?" AND claim_claimnumber = '".$individual."'":"";
    $claimstatus=!empty($claimstatus)?" AND claim_status = '".$claimstatus."'":"";
    $individual.=$claimstatus;
    $arr=$api->getHighCostClaims($start_date,$end_date,$txtup,$individual);
    echo json_encode($arr,true);
}
else if($identity==15)
{
    try {
        if(strlen($_POST["keyword"])>0) {
            $keyword=$_POST["keyword"];
            $tabletyp=$_POST["lastclicked"];
            $field="";
            $table="";
            if($tabletyp=="ICD-10_grouped")
            {
                $field="claiminsureditem_icdcode";
                $table="claim_line";
            }
            elseif ($tabletyp=="Benefit_Utilisation")
            {
                $field="claiminsureditem_benefittiers";
                $table="claim_line";
            }
            elseif ($tabletyp=="Hospitals")
            {
                $field="Hospital";
                $table="hospitals";
            }
            elseif ($tabletyp=="Specialities")
            {
                $field="disciplinecode";
                $table="doctor_details";
            }
            elseif ($tabletyp=="High_Cost_Claims")
            {
                $field="claim_claimnumber";
                $table="claim";
            }
            if ($tabletyp=="Specialities")
            {
                $field2="discipline";
                $xarr=$api->getSearchedResults1($keyword,$field,$field2,$table);
                $ccount=count($xarr);
                $msg="";
                if($ccount>0)
                {
                    $msg="<ul id=\"country-list\" class=\"uk-card uk-card-body uk-card-default\">";
                    foreach ($xarr as $row)
                    {
                        $searched=$row[0];
                        $searched1=$row[1];
                        $msg.="<li style=\"color: yellow;\" onClick=\"selectSearched('$searched')\">$searched - $searched1</span></li>";
                    }
                    $msg.="</ul>";
                }
            }
            else{
                $xarr=$api->getSearchedResults($keyword,$field,$table);
                $ccount=count($xarr);
                $msg="";
                if($ccount>0)
                {
                    $msg="<ul id=\"country-list\" class=\"uk-card uk-card-body uk-card-default\">";
                    foreach ($xarr as $row)
                    {
                        $searched=$row[0];
                        $msg.="<li style=\"color: yellow;\" onClick=\"selectSearched('$searched')\">$searched</span></li>";
                    }
                    $msg.="</ul>";
                }
            }

            echo $msg;
        }
    }
    catch (Exception $e)
    {
        echo $e->getMessage();
    }
}
elseif($identity==16)
{
    $ccsgrouper=$_POST["ccsgrouper"];
    $arr=$api->getCSSICD($ccsgrouper);
    echo json_encode($arr,true);
}
?>
