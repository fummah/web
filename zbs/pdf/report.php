<?php
ini_set('memory_limit', '5024M');
set_time_limit(5000);
ini_set("pcre.backtrack_limit", "500000000");
session_start();
define("access",true);
include ("../classes/DBConnect.php");
$db=new DBConnect();
require_once ("vendor/autoload.php");
$mpdf=new \Mpdf\Mpdf();
$arrcolor=["gold","red","black","green"];
$funeral_id=(int)$_GET["funeral_id"];
$farr=$db->getFuneralById($funeral_id);
$fname=strtoupper($farr["funeral_name"]);
$date=date("d/m/Y");
$count=1;
$tot1=0;
$tot2=0;
$tot3=0;
$undertaker_cost=0;
$other_costs=0;
$bank_charges=0;
$data="<p align='center'><img src=\"../images/logo.jpg\" height='30' width='60'></p><h4 style='color: green; text-decoration: underline' align=\"center\">$fname FUNERAL($date)</h4>";
$data.="<table style='border-collapse: collapse; width: 100%'> <thead><tr style='border: 1px solid;'><th style='color: dodgerblue; border: 1px solid;'>Location</th>
<th style='color: dodgerblue;border: 1px solid;'>Amount Received</th><th style='color: dodgerblue;border: 1px solid;'>Expenses</th>
<th style='color: dodgerblue;'>Balance</th></tr></thead><tbody>";
foreach ($db->getLocationsReport($funeral_id) as $rowLocation) {
    $location_id = $rowLocation["location_id"];
    $location_name = strtoupper($rowLocation["location_name"]);
    $exticks=(int)$rowLocation["ex"];
    $examount=(double)$rowLocation["amount_paid"]*$exticks;
    $mxx=$db->getTransEx($funeral_id,$location_id);
        $advancetotal=0;
        if($mxx != false)
        {
        $advancetotal=(double)$mxx["amount"]+$examount;
        }
        $advancetotal=-1*$advancetotal;
    $actual_amount = (double)$rowLocation["actual_amount"]+$advancetotal;
    $expenses = (double)$rowLocation["expenses"];
    $undertaker_cost=(double)$rowLocation["undertaker_cost"];
    $other_costs=(double)$rowLocation["other_costs"];
    $bank_charges=(double)$rowLocation["bank_charges"];
    $system_cost=(double)$rowLocation["system_cost"];
    $actual_total = $actual_amount - $expenses;
    $tot1+=$actual_amount;
    $tot2+=$expenses;
    $tot3+=$actual_total;
    $amountgroc = $db->moneyformat($actual_amount);
    $amountepense = $db->moneyformat($expenses);
    $amountactual = $db->moneyformat($actual_total);
    $data .= "<tr style='border: 1px solid; color: #0f6674'><td style='color: #0f6674'>$count. $location_name</td>
<td style='border: 1px solid; text-align: center; font-size: 11px'>R$amountgroc</td>
<td style='border: 1px solid;color: red;text-align: center;font-size: 11px'>R$amountepense</td>
<td style='border: 1px solid;color: green;text-align: center;font-size: 11px'>R$amountactual</td></tr>";
    $count++;
}
$undetot=$undertaker_cost+$other_costs+$bank_charges+$system_cost;
$ovtot=$tot3-$undetot;
$tot1 = $db->moneyformat($tot1);
$tot2 = $db->moneyformat($tot2);
$tot3 = $db->moneyformat($tot3);
$ovtot = $db->moneyformat($ovtot);
$undertaker_cost = $db->moneyformat($undertaker_cost);
$other_costs = $db->moneyformat($other_costs);
$bank_charges = $db->moneyformat($bank_charges);
$system_cost = $db->moneyformat($system_cost);
$undetot = $db->moneyformat($undetot);
$data.="<tr><td style='background-color: gold !important;border: 1px solid; text-align: center; font-size: 11px'><b>Gross Totals</b></td><td style='border: 1px solid; text-align: center; font-size: 11px'><b style='color: #0a58ca'>R$tot1</b></td><td style='border: 1px solid; text-align: center; font-size: 11px'><b style='color: red'>R$tot2</b></td><td style='border: 1px solid; text-align: center; font-size: 11px'><b style='color: green'>R$tot3</b></td></tr>";
$data.="<tr><td style='background-color: red !important;border: 1px solid; text-align: center; font-size: 11px'><b>Less Expenses</b></td><td style='color: red; border: 1px solid; text-align: center; font-size: 11px'><b style='color: red'>Undertaker : </b></td><td style='color: red; border: 1px solid; text-align: center; font-size: 11px'><b style='color: red'>R$undertaker_cost</b></td><td style='border: 1px solid; text-align: center; font-size: 11px'><b style='color: green'></b></td></tr>";
$data.="<tr><td style='background-color: red !important;border: 1px solid; text-align: center; font-size: 11px'><b></b></td><td style='color: red; border: 1px solid; text-align: center; font-size: 11px'><b style='color: red'>Bank Charges : </b></td><td style='color: red; border: 1px solid; text-align: center; font-size: 11px'><b style='color: red'>R$bank_charges</b></td><td style='border: 1px solid; text-align: center; font-size: 11px'><b style='color: green'></b></td></tr>";
$data.="<tr><td style='background-color: red !important;border: 1px solid; text-align: center; font-size: 11px'><b></b></td><td style='color: red; border: 1px solid; text-align: center; font-size: 11px'><b style='color: red'>System Maintenance Cost : </b></td><td style='color: red; border: 1px solid; text-align: center; font-size: 11px'><b style='color: red'>R$system_cost</b></td><td style='border: 1px solid; text-align: center; font-size: 11px'><b style='color: green'></b></td></tr>";
$data.="<tr><td style='background-color: red !important;border: 1px solid; text-align: center; font-size: 11px'><b></b></td><td style='color: red;border: 1px solid; text-align: center; font-size: 11px'><b style='color: red'>Protection Fee : </b></td><td style='color: red;border: 1px solid; text-align: center; font-size: 11px'><b style='color: red'>R$other_costs</b></td><td style='border: 1px solid; text-align: center; font-size: 11px'><b style='color: green'></b></td></tr>";
$data.="<tr><td style='background-color: red !important;border: 1px solid; text-align: center; font-size: 11px'><b></b></td><td style='color: red;border: 1px solid; text-align: center; font-size: 11px'><b style='color: red'>Total Expenses : </b></td><td style='color: red;border: 1px solid; text-align: center; font-size: 11px'><b style='color: red'>R$undetot</b></td><td style='border: 1px solid; text-align: center; font-size: 11px'><b style='color: green'></b></td></tr>";
$data.="<tr><td style='background-color: green !important;border: 1px solid; text-align: center; font-size: 11px'><b>Balance</b></td><td style='color: green;border: 1px solid; text-align: center; font-size: 11px'><b style='color: red'></b></td><td style='color: red;border: 1px solid; text-align: center; font-size: 11px'><b style='color: red'></b></td><td style='border: 1px solid; text-align: center; font-size: 11px'><b style='color: green'>R$ovtot</b></td></tr>";
$data.="</body></table>";
$data.="<br><br><br><h4 style='color: #0a53be'>Signatures : </h4>";
$data.="<h5>Treasurer General : ...........M.CHIGWEREVE.......</h5>";
$data.="<h5>Family Representative : ..........................</h5>";
$data.="<h5>Witness : ........................................</h5>";
$data.="<h6>Date : ...........................................</h6>";

//echo $data;
$mpdf->WriteHTML($data);
$mpdf->Output('Report.pdf','D');

?>