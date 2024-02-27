<?php
session_start();
define("access",true);
if(!isset($_POST["btndownload"]))
{
    die("Error");
}
$_SESSION["admin_main"]=true;
require_once "dbconn.php";
$conn=connection("mca","MCA_admin");
require_once ("classes/leadClass.php");
$obj=new leadClass();
$claim_id=(int)$_POST["claim_id"];
$arr=$obj->getQualityDetails($claim_id);
$arr1=$obj->getmemberDetails($claim_id);
//start of default fields
$name=$arr1[0]." ".$arr1[1];
$claim_number=$arr1[2];
$policy_number=$arr1[3];
$username=$arr1[4];
if(count($arr)<1)
{
    die("Error");
}
$assessment_score=$arr["assessment_score"];
$date=$arr["date_entered"];
$entered_by=$arr["entered_by"];
$data1=$arr["data1"];$data2=$arr["data2"];$data3=$arr["data3"];$data4=$arr["data4"];$data5=$arr["data5"];$sla17=$arr["sla17"];$data7=$arr["data7"];$sla19=$arr["sla19"];$data9=$arr["data9"];

//end
$sla1=$arr["sla1"];$sla2=$arr["sla2"];$sla3=$arr["sla3"];$sla4=$arr["sla4"];$sla5=$arr["sla5"];$sla6=$arr["sla6"];$sla7=$arr["sla7"];$sla8=$arr["sla8"];$sla9=$arr["sla9"];$sla10=$arr["sla10"];$sla11=$arr["sla11"];$sla12=$arr["sla12"];$sla13=$arr["sla13"];$sla14=$arr["sla14"];$sla15=$arr["sla15"];$sla16=$arr["sla16"];$sla20=$arr["sla20"];$sla21=$arr["sla21"];$sla22=$arr["sla22"];

//section2
$auto2=$arr["auto2"];$auto3=$arr["auto3"];$sla18=$arr["sla18"];$auto5=$arr["auto5"];$auto9=$arr["auto9"];$auto10=$arr["auto10"];
//$autotot=$obj->calcVal("auto",9,$arr);
//end
$emails1=$arr["emails1"];$emails2=$arr["emails2"];$emails3=$arr["emails3"];$emails4=$arr["emails4"];$emails5=$arr["emails5"];$emails6=$arr["emails6"];$emails7=$arr["emails7"];$emails8=$arr["emails8"];$emails9=$arr["emails9"];$emails10=$arr["emails10"];

//section2
$calls1=$arr["calls1"];$calls2=$arr["calls2"];$calls3=$arr["calls3"];$calls4=$arr["calls4"];$calls5=$arr["calls5"];$calls6=$arr["calls6"];$calls7=$arr["calls7"];$calls8=$arr["calls8"];$calls9=$arr["calls9"];$calls10=$arr["calls10"];$calls11=$arr["calls11"];
$callscomment=$arr["calls11"];

$qa_signed=$arr["qa_signed"];$cs_signed=$arr["cs_signed"];$cs_date=$arr["cs_date"];

header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=Quality_Assurance.doc");
echo "<html>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
echo "<body>";

echo "<p align='center' style='font-size: 21px; font-family: Calibri'><u>Quality Assessment Form</u></p>";
echo"<table style='width: 100%;'>";
echo"<tr style='padding-top:15px;'><td style='background-color: #f2f2f2; border: 1px solid black;border-collapse: collapse;'>Name</td><td style='border: 1px solid black;border-collapse: collapse;'>$name</td></tr>";
echo"<tr style='padding-top:15px;'><td style='background-color: #f2f2f2; border: 1px solid black;'>Assessment Date</td><td style='border: 1px solid black;border-collapse: collapse;'>$date</td></tr>";
echo"<tr style='padding-top:15px;'><td style='background-color: #f2f2f2; border: 1px solid black;'>Claim number</td><td style='border: 1px solid black;border-collapse: collapse;'>$claim_number</td></tr>";
echo"<tr style='padding-top:15px;'><td style='background-color: #f2f2f2; border: 1px solid black;'>GAP policy holder number</td><td style='border: 1px solid black;border-collapse: collapse;'>$policy_number</td></tr>";
echo"<tr style='padding-top:15px;'><td style='background-color: #f2f2f2; border: 1px solid black;'>Assessment score</td><td style='border: 1px solid black;border-collapse: collapse;'>$assessment_score</td></tr>";
echo"</table>";

echo "<p><span style='color: red'>**</span> Scores are Yes / No / N/A (weightings apply)</p>";
echo"<table style='width: 100%;'>";
echo"<tr style='padding-top:15px;background-color:#f2f2f2; font-weight: bold'>
<td style='border: 1px solid black;border-collapse: collapse;'>DATA CAPTURING</td>
<td style='border: 1px solid black;border-collapse: collapse;'>Yes</td>
<td style='border: 1px solid black;border-collapse: collapse;'>No</td>
<td style='border: 1px solid black;border-collapse: collapse; background-color: lightgrey'>N/A</td>
</tr>";
$data11="";$data12="";$data13="";
if($data1=="1"){ $data11="X"; }
elseif ($data1=="0"){ $data12="X";}
elseif ($data1=="2"){ $data13="X";}
echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse; font-size: 16px'>The member detail fields are completed</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$data11</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$data12</td>
<td style='border: 1px solid black;border-collapse: collapse; background-color: lightgrey;color: brown'>$data13</td>
</tr>";
$data21="";$data22="";$data23="";
if($data2=="1"){ $data21="X"; }
elseif ($data2=="0"){ $data22="X";}
elseif ($data2=="2"){ $data23="X";}
echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse; font-size: 16px'>The correct dependant is chosen</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$data21</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$data22</td>
<td style='border: 1px solid black;border-collapse: collapse; background-color: lightgrey; color: brown'>$data23</td>
</tr>";
$data31="";$data32="";$data33="";
if($data3=="1"){ $data31="X"; }
elseif ($data3=="0"){ $data32="X";}
elseif ($data3=="2"){ $data33="X";}
echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse; font-size: 16px'>All the treating doctors are captured</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$data31</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$data32</td>
<td style='border: 1px solid black;border-collapse: collapse; background-color: lightgrey; color: brown'>$data33</td>
</tr>";
$data41="";$data42="";$data43="";
if($data4=="1"){ $data41="X"; }
elseif ($data4=="0"){ $data42="X";}
elseif ($data4=="2"){ $data43="X";}
echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse; font-size: 16px; width: 70%'>The Charged amount, Scheme paid amount and Member portions are captured and correct?</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$data41</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$data42</td>
<td style='border: 1px solid black;border-collapse: collapse; background-color: lightgrey; color: brown'>$data43</td>
</tr>";
$data51="";$data52="";$data53="";
if($data5=="1"){ $data51="X"; }
elseif ($data5=="0"){ $data52="X";}
elseif ($data5=="2"){ $data53="X";}
echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse; font-size: 16px'>The treatment code and ICD10 codes complement one another</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$data51</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$data52</td>
<td style='border: 1px solid black;border-collapse: collapse; background-color: lightgrey; color: brown'>$data53</td>
</tr>";

echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse; font-size: 16px' colspan='4'>Comments : <br><br>$data9</td>

</tr>";
echo"</table>";
///////////////////// END
///
echo"<br><br><br><br><br><br><br><br>";
echo "<p><span style='color: red'>**</span> Please note: a no captured on any of the sla fails listed below will result in an automatic fail of your quality assessment.</p>";
echo"<table style='width: 100%;'>";
echo"<tr style='padding-top:15px;background-color:#f2f2f2; font-weight: bold'>
<td style='border: 1px solid black;border-collapse: collapse;'>SLA & VALIDATIONS / AUTO FAILS</td>
<td style='border: 1px solid black;border-collapse: collapse;'>Yes</td>
<td style='border: 1px solid black;border-collapse: collapse;'>No</td>
<td style='border: 1px solid black;border-collapse: collapse;'>N/A</td>
</tr>";
$sla11="";$sla12="";$sla13="";
if($sla1=="1"){ $sla11="X"; }
elseif ($sla1=="0"){ $sla12="X";}
elseif ($sla1=="2"){ $sla13="X";}
echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse; font-size: 16px'>Provider details were updated  and Indicated whether provider does give discounts (where applicable)</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla11</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla12</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla13</td>
</tr>";
$sla161="";$sla162="";$sla136="";
if($sla16=="1"){ $sla161="X"; }
elseif ($sla16=="0"){ $sla162="X";}
elseif ($sla16=="2"){ $sla163="X";}
echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse; font-size: 16px'>The Primary ICD10 code is captured</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla161</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla162</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla163</td>
</tr>";
$sla21="";$sla22="";$sla23="";
if($sla2=="1"){ $sla21="X"; }
elseif ($sla2=="0"){ $sla22="X";}
elseif ($sla2=="2"){ $sla23="X";}
echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse; font-size: 16px'>CS indicated whether PMB or Non-PMB / Emergency or Non-Emergency</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla21</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla22</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla23</td>
</tr>";
$sla31="";$sla32="";$sla33="";
if($sla3=="1"){ $sla31="X"; }
elseif ($sla3=="0"){ $sla32="X";}
elseif ($sla3=="2"){ $sla33="X";}
echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse; font-size: 16px'>CS indicated the type of procedure performed</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla31</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla32</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla33</td>
</tr>";
$sla41="";$sla42="";$sla43="";
if($sla4=="1"){ $sla41="X"; }
elseif ($sla4=="0"){ $sla42="X";}
elseif ($sla4=="2"){ $sla43="X";}
echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse; font-size: 16px; width: 70%'>In Emergency, consent was sent to Member (where necessary)</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla41</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla42</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla43</td>
</tr>";
$sla51="";$sla52="";$sla53="";
if($sla5=="1"){ $sla51="X"; }
elseif ($sla5=="0"){ $sla52="X";}
elseif ($sla5=="2"){ $sla53="X";}
echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse; font-size: 16px'>Consent form received from Member is Uploaded to MCA system</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla51</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla52</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla53</td>
</tr>";
$sla61="";$sla62="";$sla63="";
if($sla6=="1"){ $sla61="X"; }
elseif ($sla6=="0"){ $sla62="X";}
elseif ($sla6=="2"){ $sla63="X";}
echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse; font-size: 16px'>Regular updates were given to Member/Broker? Was it done timeously?</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla61</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla62</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla63</td>
</tr>";
$sla171="";$sla172="";$sla173="";
if($sla17=="1"){ $sla171="X"; }
elseif ($sla17=="0"){ $sla172="X";}
elseif ($sla17=="2"){ $sla173="X";}
echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse; font-size: 16px'>The notes are clear, accurate and factual</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla171</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla172</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla173</td>
</tr>";
$sla181="";$sla182="";$sla183="";
if($sla18=="1"){ $sla181="X"; }
elseif ($sla18=="0"){ $sla182="X";}
elseif ($sla18=="2"){ $sla183="X";}
echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse; font-size: 16px'>No savings opportunity was missed </td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla181</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla182</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla183</td>
</tr>";
$sla191="";$sla192="";$sla193="";
if($sla19=="1"){ $sla191="X"; }
elseif ($sla19=="0"){ $sla192="X";}
elseif ($sla19=="2"){ $sla193="X";}
echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse; font-size: 16px'>Correct payment instruction given to Client</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla191</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla192</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla193</td>
</tr>";
$sla71="";$sla72="";$sla73="";
if($sla7=="1"){ $sla71="X"; }
elseif ($sla7=="0"){ $sla72="X";}
elseif ($sla7=="2"){ $sla73="X";}
echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse; font-size: 16px'>Validations were done</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla71</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla72</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla73</td>
</tr>";
$sla81="";$sla82="";$sla83="";
if($sla8=="1"){ $sla81="X"; }
elseif ($sla8=="0"){ $sla82="X";}
elseif ($sla8=="2"){ $sla83="X";}
echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse; font-size: 16px'>New files checked</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla81</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla82</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla83</td>
</tr>";
$sla91="";$sla92="";$sla93="";
if($sla9=="1"){ $sla91="X"; }
elseif ($sla9=="0"){ $sla92="X";}
elseif ($sla9=="2"){ $sla93="X";}
echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse; font-size: 16px'>Zero amounts updated</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla91</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla92</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla93</td>
</tr>";
$sla101="";$sla102="";$sla103="";
if($sla10=="1"){ $sla101="X"; }
elseif ($sla10=="0"){ $sla102="X";}
elseif ($sla10=="2"){ $sla103="X";}
echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse; font-size: 16px'>ZESTLIFE: Claim documents Uploaded to MCA system</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla101</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla102</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla103</td>
</tr>";
$sla111="";$sla112="";$sla113="";
if($sla11=="1"){ $sla111="X"; }
elseif ($sla11=="0"){ $sla112="X";}
elseif ($sla11=="2"){ $sla113="X";}
echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse; font-size: 16px'>Provider Indicator used (stand on provider when making note/ add savings)</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla111</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla112</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla113</td>
</tr>";
$sla121="";$sla122="";$sla123="";
if($sla12=="1"){ $sla121="X"; }
elseif ($sla12=="0"){ $sla122="X";}
elseif ($sla12=="2"){ $sla123="X";}
echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse; font-size: 16px'>MAGPI: Was claim assessed correctly in respect of Claims Actions (eg. PMB / MSP / PMB, Continue with MSP)</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla121</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla122</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla123</td>
</tr>";
$sla131="";$sla132="";$sla133="";
if($sla13=="1"){ $sla131="X"; }
elseif ($sla13=="0"){ $sla132="X";}
elseif ($sla13=="2"){ $sla133="X";}
echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse; font-size: 16px'>MAGPI: Was provider banking details updated in Housekeeping</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla131</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla132</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla133</td>
</tr>";
$sla141="";$sla142="";$sla143="";
if($sla14=="1"){ $sla141="X"; }
elseif ($sla14=="0"){ $sla142="X";}
elseif ($sla14=="2"){ $sla143="X";}
echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse; font-size: 16px'>MAGPI Seamless Claims: Necessary information retrieved from Magpi</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla141</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla142</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla143</td>
</tr>";

$sla201="";$sla202="";$sla203="";
if($sla1=="1"){ $sla11="X"; }
elseif ($sla20=="0"){ $sla202="X";}
elseif ($sla20=="2"){ $sla203="X";}
echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse; font-size: 16px'>Documents: Where documents were requested the notes confirms that documents were received</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla201</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla202</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla203</td>
</tr>";
$sla211="";$sla212="";$sla213="";
if($sla21=="1"){ $sla211="X"; }
elseif ($sla21=="0"){ $sla212="X";}
elseif ($sla21=="2"){ $sla213="X";}
echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse; font-size: 16px'>Received documents were uploaded to the system</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla211</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla212</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla213</td>
</tr>";
$sla221="";$sla222="";$sla223="";
if($sla22=="1"){ $sla221="X"; }
elseif ($sla22=="0"){ $sla222="X";}
elseif ($sla22=="2"){ $sla223="X";}
echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse; font-size: 16px'>The correct business process followed - Colours</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla221</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla222</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$sla223</td>
</tr>";
echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse; font-size: 16px' colspan='4'>Comments : <br><br>$sla15</td>

</tr>";
echo"</table>";
///////////////////// END

///
echo"<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
echo "<p><span style='color: red'>**</span> Scores range from 1 - 5 (1/2 partially obtained: 3 done what is required: 4/5 exceeded) (weightings apply)</p>";
echo"<table style='width: 100%;'>";
echo"<tr style='padding-top:15px;background-color:#f2f2f2; font-weight: bold'>
<td style='border: 1px solid black;border-collapse: collapse;'>CALLS</td>
<td style='border: 1px solid black;border-collapse: collapse;'>1</td>
<td style='border: 1px solid black;border-collapse: collapse;'>2</td>
<td style='border: 1px solid black;border-collapse: collapse;'>3</td>
<td style='border: 1px solid black;border-collapse: collapse;'>4</td>
<td style='border: 1px solid black;border-collapse: collapse;'>5</td>
<td style='border: 1px solid black;border-collapse: collapse;'>N/A</td>
</tr>";
$calls11="";$calls12="";$calls13="";$calls14="";$calls15="";$calls16="";
if($calls1==1){ $calls11="X"; }
elseif ($calls1==2){ $calls12="X";}
elseif ($calls1==3){ $calls13="X";}
elseif ($calls1==4){ $calls14="X";}
elseif ($calls1==5){ $calls15="X";}
elseif ($calls1==101){ $calls16="X";}

echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse;'>The greeting was clear and sincere</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls11</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls12</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls13</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls14</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls15</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls16</td>
</tr>";

$calls21="";$calls22="";$calls23="";$calls24="";$calls25="";$calls26="";
if($calls2==1){ $calls21="X"; }
elseif ($calls2==2){ $calls22="X";}
elseif ($calls2==3){ $calls23="X";}
elseif ($calls2==4){ $calls24="X";}
elseif ($calls2==5){ $calls25="X";}
elseif ($calls2==101){ $calls26="X";}

echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse; width: 70%'>Rapport was built with caller (i.e. medical aid scheme / doctors' rooms</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls21</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls22</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls23</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls24</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls25</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls26</td>
</tr>";
$calls31="";$calls32="";$calls33="";$calls34="";$calls35="";$calls36="";
if($calls3==1){ $calls31="X"; }
elseif ($calls3==2){ $calls32="X";}
elseif ($calls3==3){ $calls33="X";}
elseif ($calls3==4){ $calls34="X";}
elseif ($calls3==5){ $calls35="X";}
elseif ($calls3==101){ $calls36="X";}

echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse'>The CS asked appropriate questions to help fact find needs</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls31</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls32</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls33</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls34</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls35</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls36</td>
</tr>";
$calls41="";$calls42="";$calls43="";$calls44="";$calls45="";$calls46="";
if($calls4==1){ $calls41="X"; }
elseif ($calls4==2){ $calls42="X";}
elseif ($calls4==3){ $calls43="X";}
elseif ($calls4==4){ $calls44="X";}
elseif ($calls4==5){ $calls45="X";}
elseif ($calls4==101){ $calls46="X";}

echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse'>Good listening skills were applied</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls41</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls42</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls43</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls44</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls45</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls46</td>
</tr>";
$calls51="";$calls52="";$calls53="";$calls54="";$calls55="";$calls56="";
if($calls5==1){ $calls51="X"; }
elseif ($calls5==2){ $calls52="X";}
elseif ($calls5==3){ $calls53="X";}
elseif ($calls5==4){ $calls54="X";}
elseif ($calls5==5){ $calls55="X";}
elseif ($calls5==101){ $calls56="X";}

echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse'>The CS took the correct approach with regards to his/her query</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls51</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls52</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls53</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls54</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls55</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls56</td>
</tr>";
$calls61="";$calls62="";$calls63="";$calls64="";$calls65="";$calls66="";
if($calls6==1){ $calls61="X"; }
elseif ($calls6==2){ $calls62="X";}
elseif ($calls6==3){ $calls63="X";}
elseif ($calls6==4){ $calls64="X";}
elseif ($calls6==5){ $calls65="X";}
elseif ($calls6==101){ $calls66="X";}

echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse'>The correct information was provided to the doctors' rooms / member?</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls61</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls62</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls63</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls64</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls65</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls66</td>
</tr>";
$calls71="";$calls72="";$calls73="";$calls74="";$calls75="";$calls76="";
if($calls7==1){ $calls71="X"; }
elseif ($calls7==2){ $calls72="X";}
elseif ($calls7==3){ $calls73="X";}
elseif ($calls7==4){ $calls74="X";}
elseif ($calls7==5){ $calls75="X";}
elseif ($calls7==101){ $calls76="X";}

echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse'>The CS did not interrupt the caller unnecessarily</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls71</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls72</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls73</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls74</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls75</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls76</td>
</tr>";
$calls81="";$calls82="";$calls83="";$calls84="";$calls85="";$calls86="";
if($calls8==1){ $calls81="X"; }
elseif ($calls8==2){ $calls82="X";}
elseif ($calls8==3){ $calls83="X";}
elseif ($calls8==4){ $calls84="X";}
elseif ($calls8==5){ $calls85="X";}
elseif ($calls8==101){ $calls86="X";}

echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse'>The CS showed empathy where required</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls81</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls82</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls83</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls84</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls85</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls86</td>
</tr>";
$calls91="";$calls92="";$calls93="";$calls94="";$calls95="";$calls96="";
if($calls9==1){ $calls91="X"; }
elseif ($calls9==2){ $calls92="X";}
elseif ($calls9==3){ $calls93="X";}
elseif ($calls9==4){ $calls94="X";}
elseif ($calls9==5){ $calls95="X";}
elseif ($calls9==101){ $calls96="X";}

echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse'>The CS maintained a level of courtesy throughout the call</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls91</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls92</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls93</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls94</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls95</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls96</td>
</tr>";
$calls101="";$calls102="";$calls103="";$calls104="";$calls105="";$calls106="";
if($calls10==1){ $calls101="X"; }
elseif ($calls10==2){ $calls102="X";}
elseif ($calls10==3){ $calls103="X";}
elseif ($calls10==4){ $calls104="X";}
elseif ($calls10==5){ $calls105="X";}
elseif ($calls10==101){ $calls106="X";}

echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse'>A willingness to help was displayed through positive statements when necessary</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls101</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls102</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls103</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls104</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls105</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$calls106</td>
</tr>";

echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse; font-size: 16px' colspan='6'>Comments : <br><br>$callscomment</td>

</tr>";
echo"</table>";
///////////////////// END
///
echo"<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
echo "<p><span style='color: red'>**</span> Scores range from 1 - 5 (1/2 partially obtained: 3 done what is required: 4/5 exceeded) (weightings apply)</p>";
echo"<table style='width: 100%;'>";
echo"<tr style='padding-top:15px;background-color:#f2f2f2; font-weight: bold'>
<td style='border: 1px solid black;border-collapse: collapse;'>EMAILS</td>
<td style='border: 1px solid black;border-collapse: collapse;'>0</td>
<td style='border: 1px solid black;border-collapse: collapse;'>1</td>
<td style='border: 1px solid black;border-collapse: collapse;'>2</td>
<td style='border: 1px solid black;border-collapse: collapse;'>3</td>
<td style='border: 1px solid black;border-collapse: collapse;'>4</td>
<td style='border: 1px solid black;border-collapse: collapse;'>5</td>
<td style='border: 1px solid black;border-collapse: collapse;'>N/A</td>
</tr>";
$emails11="";$emails12="";$emails13="";$emails14="";$emails15="";$emails16="";$emails10="";
if($emails1==1){ $emails11="X"; }
elseif ($emails1==2){ $emails12="X";}
elseif ($emails1==3){ $emails13="X";}
elseif ($emails1==4){ $emails14="X";}
elseif ($emails1==5){ $emails15="X";}
elseif ($emails1==0){ $emails10="X";}
elseif ($emails1==101){ $emails16="X";}

echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse;'>Correct email address used (i.e. Member / Medical Scheme/Doctors' rooms</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails10</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails11</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails12</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails13</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails14</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails15</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails16</td>
</tr>";

$emails21="";$emails22="";$emails23="";$emails24="";$emails25="";$emails20="";$emails26="";
if($emails2==1){ $emails21="X"; }
elseif ($emails2==2){ $emails22="X";}
elseif ($emails2==3){ $emails23="X";}
elseif ($emails2==4){ $emails24="X";}
elseif ($emails2==5){ $emails25="X";}
elseif ($emails1==0){ $emails20="X";}
elseif ($emails1==101){ $emails26="X";}

echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse; width: 70%'>Greeting is professional</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails20</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails21</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails22</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails23</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails24</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails25</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails26</td>
</tr>";
$emails31="";$emails32="";$emails33="";$emails34="";$emails35="";$emails30="";$emails36="";
if($emails3==1){ $emails31="X"; }
elseif ($emails3==2){ $emails32="X";}
elseif ($emails3==3){ $emails33="X";}
elseif ($emails3==4){ $emails34="X";}
elseif ($emails3==5){ $emails35="X";}
elseif ($emails3==0){ $emails30="X";}
elseif ($emails3==101){ $emails36="X";}

echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse'>Correct details provided in email (ie. Patient / Account number / Medical Aid details / Service date / Amounts)</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails30</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails31</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails32</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails33</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails34</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails35</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails36</td>
</tr>";
$emails41="";$emails42="";$emails43="";$emails44="";$emails45="";$emails40="";$emails46="";
if($emails4==1){ $emails41="X"; }
elseif ($emails4==2){ $emails42="X";}
elseif ($emails4==3){ $emails43="X";}
elseif ($emails4==4){ $emails44="X";}
elseif ($emails4==5){ $emails45="X";}
elseif ($emails4==0){ $emails40="X";}
elseif ($emails4==101){ $emails46="X";}

echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse'>The body of the email contains clear information and is concise</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails40</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails41</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails42</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails43</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails44</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails45</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails46</td>
</tr>";
$emails51="";$emails52="";$emails53="";$emails54="";$emails55="";$emails50="";$emails56="";
if($emails5==1){ $emails51="X"; }
elseif ($emails5==2){ $emails52="X";}
elseif ($emails5==3){ $emails53="X";}
elseif ($emails5==4){ $emails54="X";}
elseif ($emails5==5){ $emails55="X";}
elseif ($emails5==0){ $emails50="X";}
elseif ($emails5==101){ $emails56="X";}

echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse'>Attachments attached (where applicable)</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails50</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails51</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails52</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails53</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails54</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails55</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails56</td>
</tr>";
$emails61="";$emails62="";$emails63="";$emails64="";$emails65="";$emails60="";$emails66="";
if($emails6==1){ $emails61="X"; }
elseif ($emails6==2){ $emails62="X";}
elseif ($emails6==3){ $emails63="X";}
elseif ($emails6==4){ $emails64="X";}
elseif ($emails6==5){ $emails65="X";}
elseif ($emails6==0){ $emails60="X";}
elseif ($emails6==101){ $emails66="X";}

echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse'>The CS requested the correct information / documentation to resolve his/her query</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails60</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails61</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails62</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails63</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails64</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails65</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails66</td>
</tr>";
$emails71="";$emails72="";$emails73="";$emails74="";$emails75="";$emails70="";$emails76="";
if($emails7==1){ $emails71="X"; }
elseif ($emails7==2){ $emails72="X";}
elseif ($emails7==3){ $emails73="X";}
elseif ($emails7==4){ $emails74="X";}
elseif ($emails7==5){ $emails75="X";}
elseif ($emails7==0){ $emails70="X";}
elseif ($emails6==101){ $emails76="X";}

echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse'>The correct information was provided to the doctors' rooms / member / Medical Scheme?</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails70</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails71</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails72</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails73</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails74</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails75</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails76</td>
</tr>";
$emails81="";$emails82="";$emails83="";$emails84="";$emails85="";$emails80="";$emails86="";
if($emails8==1){ $emails81="X"; }
elseif ($emails8==2){ $emails82="X";}
elseif ($emails8==3){ $emails83="X";}
elseif ($emails8==4){ $emails84="X";}
elseif ($emails8==5){ $emails85="X";}
elseif ($emails8==0){ $emails80="X";}
elseif ($emails8==101){ $emails86="X";}

echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse'>There are no spelling errors / punctuation/grammar mistakes</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails80</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails81</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails82</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails83</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails84</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails85</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails86</td>
</tr>";
$emails91="";$emails92="";$emails93="";$emails94="";$emails95="";$emails90="";$emails96="";
if($emails9==1){ $emails91="X"; }
elseif ($emails9==2){ $emails92="X";}
elseif ($emails9==3){ $emails93="X";}
elseif ($emails9==4){ $emails94="X";}
elseif ($emails9==5){ $emails95="X";}
elseif ($emails9==0){ $emails90="X";}
elseif ($emails9==101){ $emails96="X";}

echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse'>The email carries the correct tone and is polite</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails90</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails91</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails92</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails93</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails94</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails95</td>
<td style='border: 1px solid black;border-collapse: collapse; color: brown'>$emails96</td>
</tr>";


echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse; font-size: 16px' colspan='6'>Comments : <br><br>$emails10</td>

</tr>";
echo"<tr style='padding-top:15px;'>
<td style='border: 1px solid black;border-collapse: collapse; font-size: 16px' colspan='6'><br>Other Comments : <br><br>";
$narr=$obj->getQAnotes($claim_id);
if(count($narr)>0)
{
    echo "<table style='width: 100%'>";
    echo "<tr><th>Entered By</th><th>Notes</th><th>Date</th></tr>";
    foreach ($narr as $row)
    {
        $notes=$row[2];
        $mytime=$row[3];
        $author=$row[4];
        echo "<tr><td>$author</td><td>$notes</td><td>$mytime</td></tr>";

    }
    echo "</table>";
}
echo"</td></tr>";

echo"</table>";
if($qa_signed=="1" && $cs_signed=="1")
{
    echo "<h3 align='center' style='color: green'>This Quality Assessment was signed off.</h3>";
}
else{
    echo "<h3 align='center' style='color: red'>This Quality Assessment is not yet signed off.</h3>";
    $cs_date="N/A";
}
echo"<br><br><table style='width: 100%'>";
echo "<tr style='padding-top:25px'><td><b>QA :  <span style='color:darkolivegreen; font-size: 18px'>$entered_by</span></b></td><td><b>Claims Specialist : <span style='color: darkolivegreen; font-size: 18px'>$username</span></b></td></tr>";
echo "<tr style='padding-top:25px'><td><b>Date : <span style='color: darkolivegreen; font-size: 18px'>$cs_date</span></b></td></tr>";
echo"</table>";

echo "</body>";
echo "</html>";
?>
