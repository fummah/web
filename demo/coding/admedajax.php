<?php
define("access",true);
include "../classes/admedClass.php";
use mcaAPI\admedClass as myAPI;
$api= new myAPI();
if(!isset($_POST["identity_number"]))
{
    die("Invalid access");
}
$identity=(int)$_POST["identity_number"];
$start_date=isset($_POST["start_date"])?$_POST["start_date"]:"";
$end_date=isset($_POST["end_date"])?$_POST["end_date"]:"";
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
echo json_encode($api->getYearValues($_POST["columns"]));
}
elseif($identity==3)
{
    $trx="";
    $start_date=$_POST["start_date"];
    $end_date=$_POST["end_date"];
    $hierach=json_decode($_POST["hierach"],true);
    if(empty($start_date) || empty($end_date))
    {
        die("Please Select Date Range");
    }
    $columnsx=json_decode($_POST["columns"]);
    $count=count($columnsx);
    $xarr=$api->getTopLevel($_POST["columns"],$hierach[0],$start_date,$end_date);
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
        $xarr=$api->getTopLevel($columns,$ourarr[1],$start_date,$end_date,$first_arr);
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
            $xarr=$api->getTopLevel($columns,$ourarr[2],$start_date,$end_date,$search_arr);
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
$xarr = $api->getTopLevel($columns, $ourarr[3], $start_date, $end_date,$search_arr1);
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


?>
