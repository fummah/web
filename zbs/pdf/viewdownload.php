<?php
ini_set('memory_limit', '5024M');
set_time_limit(5000);
ini_set("pcre.backtrack_limit", "500000000");
session_start();
define("access",true);
include ("../classes/DBConnect.php");
$db=new DBConnect();
$group_name=$db->getGroupName();
require_once ("vendor/autoload.php");
$mpdf=new \Mpdf\Mpdf();
$arrcolor=["black","red","gold","green"];
$coou=0;
$date=date('Y-m-d');
$data="<p align='center'><img src=\"../images/logo.jpg\" height='50' width='100'></p><h2 style='color: green; text-decoration: underline' align=\"center\">ZBS Group ($group_name) Register</h2>";
$data.="<table style='border-collapse: collapse;'> <thead><tr style='border: 1px solid;'><th style='color: dodgerblue; border: 1px solid;'>ID</th>
<th style='color: dodgerblue;border: 1px solid;'>Name</th><th style='color: dodgerblue;border: 1px solid;'>Surname</th>
<th style='color: dodgerblue;'>Contact</th>";
$mem_arr=array_reverse($db->getIndividualFunerals(0,4));

foreach($mem_arr as $row)
{
    $funeral_name=$row["funeral_name"];
    $color=$arrcolor[$coou];
    $data.="<th style='border: 1px solid darkblue; color: white; background-color: $color'>$funeral_name</th>";
    $coou++;
}
$data.="</tr></thead><tbody>";
foreach ($db->getPDFx() as $row) {
    $member_id = $row["id"];
    $first_name = $row["first_name"];
    $last_name = $row["last_name"];
    $ard=[$row["a1"],$row["a2"],$row["a3"],$row["a4"]];
    $contact_number = $row["contact_number"];
    //$contact_number = $db->formatPhone($contact_number);
    $data .= "<tr style='border: 1px solid; color: #0f6674'><td width='10%' style='color: #0f6674'>$member_id</td>
<td style='border: 1px solid;'>$first_name</td>
<td style='border: 1px solid;'>$last_name</td>
<td width='20%'>$contact_number</td>";
    for($i=0;$i<count($ard);$i++)
    {
        $payment_status=$ard[$i];
        if($payment_status=="Y")
        {
            $data .="<td style='border: 1px solid darkblue; text-align: center; vertical-align: middle;color: green'>Y</td>";
        }
        elseif ($payment_status=="X")
        {
            $data .="<td style='border: 1px solid darkblue; text-align: center; vertical-align: middle;color: red'>X</td>";
        }
        elseif ($payment_status=="H")
        {
            $data .="<td style='border: 1px solid darkblue; text-align: center; vertical-align: middle;color: cadetblue'>H</td>";
        }
        else{
            $data .="<td style='border: 1px solid darkblue; text-align: center; vertical-align: middle;'>-</td>";
        }

    }
    $data .= "</tr>";
}
$data.="</body></table>";

//echo $data;
$mpdf->SetHTMLHeader('<p style="text-align: right;font-size:10px">Date Created : '.$date.'</p>');
$mpdf->SetHTMLFooter('<p style="text-align: center; vertical-align: middle;color:brown;font-size:10px">Powered By <i><b>TF Solutions</b></i></p>');
$mpdf->WriteHTML($data);
$mpdf->Output('ZBS.pdf','D');

?>