<?php
session_start();

define("access",true);
if(!defined('access')) {
    die('Access not permited');
}
include("dbconn.php");
$con_main_db = connection("mca", "MCA_admin");
$con_doc_db = connection("doc","doctors");
$con_code_db = connection("cod", "Coding");
$con_seamless_db = connection("seamless", "seamless");
$conn=$con_main_db;
class DBConnect
{
    public $conn;
    public $conn1;
    public $conn2;
    public $conn3;
    public $claim_id;
    public function __construct()
    {
        global $con_main_db;
        global $con_doc_db;
        global $con_code_db;
        global $con_seamless_db;
        $this->conn = $con_main_db;
        $this->conn1 = $con_doc_db;
        $this->conn2 = $con_code_db;
        $this->conn3 = $con_seamless_db;
    }
 
    function getOpenNew($condition="username=:username",$val="Wanda")
    {
        $note="This claim was sent for clinical review.";
        try {
           // $sql="SELECT SUM(CASE WHEN a.Open=1 THEN 1 ELSE 0 END) as open1,SUM(CASE WHEN a.new=0 THEN 1 ELSE 0 END) as new1,SUM(CASE WHEN a.Open=5 THEN 1 ELSE 0 END) as preassess1 FROM `claim` as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE (a.Open=1 OR a.Open=5) AND $condition vvv $val";
           // echo $sql;
            $stmt = $this->conn->prepare('SELECT SUM(CASE WHEN a.Open=1 THEN 1 ELSE 0 END) as open1,SUM(CASE WHEN a.new=0 THEN 1 ELSE 0 END) as new1,SUM(CASE WHEN a.Open=5 THEN 1 ELSE 0 END) as preassess1 FROM `claim` as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE (a.Open=1 OR a.Open=5) AND '.$condition);
            $stmt->bindParam(':username', $val, PDO::PARAM_STR);
            $stmt->execute();    
            $data=$stmt->fetch();   
            
            return $data;
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
   
}
 $x= new DBConnect();
 $p=$x->getOpenNew();
 print_r($p);
