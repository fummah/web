<?php
gc_enable();
gc_collect_cycles();
set_time_limit(0); // run without timeout limit
ignore_user_abort(1); // ignore my abort

exec("nohup php bigfile.php &");
ini_set('memory_limit', '1024M');
set_time_limit(5000);
ini_set("pcre.backtrack_limit", "500000000");
session_start();
define("access",true);
include ("classes/DBConnect.php");
$db=new DBConnect();
//$db->emptyActive();

$mem_arr=array_reverse($db->getIndividualFunerals(0,4));
$mem_arr1=array_reverse($db->getPDFFunerals(0,4));
$rr=0;

foreach ($db->getPDF() as $row) {
    $member_id = $row["member_id"];
    $first_name = $row["first_name"];
    $last_name = $row["last_name"];
    $contact_number = $row["contact_number"];
    $contact_number = $db->formatPhone($contact_number);
    
    $arr=[];

    foreach ($mem_arr1 as $rowx) {
        $icon = "-";
        $funeral_id = $rowx["funeral_id"];
        $register_arr = $db->getRegisterPDF($funeral_id, $member_id);
       
      
        if ($register_arr == true) {
            $payment_status = $register_arr["status"];
            if($payment_status=="paid")
            {
                $icon="Y";
            }
            elseif ($payment_status=="unpaid")
            {
                $icon="X";
            }
            elseif ($payment_status=="home")
            {
                $icon="H";
            }
        }
        array_push($arr,$icon);
       
    }
  
    $db->addPDF($member_id,$first_name,$last_name,$contact_number,$arr[0],$arr[1],$arr[2],$arr[3]);

}

echo "Done";

?>