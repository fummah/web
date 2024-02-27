<?php

ignore_user_abort(true); //if connect is close, we continue php script in background up to script will be end

header("Connection: close\r\n");
header("Content-Encoding: none\r\n");
header("Content-Length: 1");
ob_end_clean();

include ("../../mca/link4.php");
$conn=connection("seamless","seamless");
$conn1=connection("doc","doctors");
$conn2=connection("cod","Coding");
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    sleep(5);
    $file = file_get_contents('php://input', true);
    $result=getObject();

    $ch = curl_init('https://kaelo.onowls.com/owls/external/external.php?method=medclaimassistextended&server=live&username=externalmedclaimassist&password=r4Xr3MNJHcN4FNKF3JWAdYKBKdvgwKuMTAPUQDNBC97VbgSHX6');

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// where to post
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $result);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'authKey:e15c4c44-2ea3-4bc7-bc5d-5b7555bb9c63',
            'Content-Type", "application/raw',
            'Content-Type: application/json')
    );

    $rresp = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) {
        $rresp=$err;
    }

    allaudit(1,1,1,5,$rresp,$result);
}
else{
    header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request', true, 400);
    $err=array("message"=>"Bad Request","status"=>"400");
    echo json_encode($err,true);
}
function allaudit($total,$failed,$succeed,$status="",$desciption="",$desciption1="")
{
    try {
        global $conn;
        $ip_address=get_IP_address();
        $stnt = $conn->prepare('INSERT INTO `jarvis_files`(`status`,`total`, `succeed`,`failed`,`desciption`,`desciption1`,`ip_address`) VALUES (:status,:total,:succeed,:failed,:desciption,:desciption1,:ip_address)');
        $stnt->bindParam(':status', $status, PDO::PARAM_STR);
        $stnt->bindParam(':total', $total, PDO::PARAM_STR);
        $stnt->bindParam(':succeed', $succeed, PDO::PARAM_STR);
        $stnt->bindParam(':failed', $failed, PDO::PARAM_STR);
        $stnt->bindParam(':desciption', $desciption, PDO::PARAM_STR);
        $stnt->bindParam(':desciption1', $desciption1, PDO::PARAM_STR);
        $stnt->bindParam(':ip_address', $ip_address, PDO::PARAM_STR);
        $stnt->execute();
    }
    catch (Exception $r)
    {
        //$this->mess2=$r->errorMessage();
    }
}
function getObject()
{
    try {
        global $conn;
        $auto="Auto Response to OWLS";
        $stnt = $conn->prepare('SELECT desciption1 FROM `jarvis_files` WHERE `desciption` = :auto ORDER BY id DESC LIMIT 1');
        $stnt->bindParam(':auto', $auto, PDO::PARAM_STR);
        $stnt->execute();
        return $stnt->fetchColumn();
    }
    catch (Exception $r)
    {
        //$this->mess2=$r->errorMessage();
    }
}
function get_IP_address()
{
    foreach (array('HTTP_CLIENT_IP',
                 'HTTP_X_FORWARDED_FOR',
                 'HTTP_X_FORWARDED',
                 'HTTP_X_CLUSTER_CLIENT_IP',
                 'HTTP_FORWARDED_FOR',
                 'HTTP_FORWARDED',
                 'REMOTE_ADDR') as $key){
        if (array_key_exists($key, $_SERVER) === true){
            foreach (explode(',', $_SERVER[$key]) as $IPaddress){
                $IPaddress = trim($IPaddress); // Just to be safe

                if (filter_var($IPaddress,
                        FILTER_VALIDATE_IP,
                        FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)
                    !== false) {

                    return $IPaddress;
                }
            }
        }
    }
}
?>