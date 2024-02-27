<?php
//error_reporting(0);
include ("../../mca/link4.php");
$conn=connection("seamless","seamless");

class jv_import_export
{
    function getClaimHeader($policy_number,$id_number,$product_name,$scheme_number,$icd10,$incident_date)
    {
        global $conn;
        $checkM=$conn->prepare('SELECT *FROM `claim` as a INNER JOIN member as b ON a.member_id=b.member_id WHERE (b.policy_number=:policy_number AND b.policy_number<>"") OR (b.id_number=:id_number AND b.id_number<>"") OR (b.scheme_number=:scheme_number AND b.scheme_number<>"")');
        $checkM->bindParam(':policy_number', $policy_number, PDO::PARAM_STR);
        $checkM->bindParam(':id_number', $id_number, PDO::PARAM_STR);
        $checkM->bindParam(':scheme_number', $scheme_number, PDO::PARAM_STR);    
        $checkM->execute();
        return $checkM->rowCount();
 
    }
      function getClaimLevel($policy_number,$id_number,$product_name,$scheme_number,$icd10,$incident_date)
    {
        global $conn;
        $range1 = date_create($incident_date);
        date_sub($range1, date_interval_create_from_date_string("1 days"));
        $range1=date_format($range1, "Y-m-d");
        $range2 = date_create($incident_date);
        date_add($range2, date_interval_create_from_date_string("1 days"));
        $range2=date_format($range2, "Y-m-d");
        echo $range1."===".$range2."<br>";
    $checkM=$conn->prepare('SELECT *FROM `claim` as a INNER JOIN member as b ON a.member_id=b.member_id WHERE ((b.policy_number=:policy_number AND b.policy_number<>"") OR (b.id_number=:id_number AND b.id_number<>"") OR (b.scheme_number=:scheme_number AND b.scheme_number<>"")) AND (a.Service_Date BETWEEN :date1 AND :date2)');
          $checkM->bindParam(':policy_number', $policy_number, PDO::PARAM_STR);
        $checkM->bindParam(':id_number', $id_number, PDO::PARAM_STR);
        $checkM->bindParam(':scheme_number', $scheme_number, PDO::PARAM_STR);
        $checkM->bindParam(':date1', $range1, PDO::PARAM_STR);
        $checkM->bindParam(':date2', $range2, PDO::PARAM_STR);
        $checkM->execute();
        return $checkM->rowCount();
 
    }
    function getClaimLineLevel($policy_number,$id_number,$product_name,$scheme_number,$icd10,$incident_date)
    {
        global $conn;
        $range1 = date_create($incident_date);
        date_sub($range1, date_interval_create_from_date_string("1 days"));
        $range1=date_format($range1, "Y-m-d");
        $range2 = date_create($incident_date);
        date_add($range2, date_interval_create_from_date_string("1 days"));
        $range2=date_format($range2, "Y-m-d");
        echo $range1."===".$range2."<br>";
    $checkM=$conn->prepare('SELECT *FROM claim_line as l INNER JOIN `claim` as a ON l.mca_claim_id=a.claim_id INNER JOIN member as b ON a.member_id=b.member_id WHERE ((b.policy_number=:policy_number AND b.policy_number<>"") OR (b.id_number=:id_number AND b.id_number<>"") OR (b.scheme_number=:scheme_number AND b.scheme_number<>"")) AND (a.Service_Date BETWEEN :date1 AND :date2)');
          $checkM->bindParam(':policy_number', $policy_number, PDO::PARAM_STR);
        $checkM->bindParam(':id_number', $id_number, PDO::PARAM_STR);
        $checkM->bindParam(':scheme_number', $scheme_number, PDO::PARAM_STR);
        $checkM->bindParam(':date1', $range1, PDO::PARAM_STR);
        $checkM->bindParam(':date2', $range2, PDO::PARAM_STR);
        $checkM->execute();
        return $checkM->rowCount();
 
    }
       function test()
    {
        global $conn;        
        $checkM=$conn->prepare('SELECT `id`,`policy_number`, `id_number`, `scheme_number`, `start_date`, `end_date` FROM `test2` where claim_level>0');
        $checkM->execute();
        return $checkM->fetchAll();
 
    }
         function updatetest($id,$val)
    {
        global $conn;        
        $checkM=$conn->prepare('UPDATE test2 SET claim_line_level=:header_level WHERE id=:id');
        $checkM->bindParam(':id', $id, PDO::PARAM_STR); 
        $checkM->bindParam(':header_level', $val, PDO::PARAM_STR);         
        return $checkM->execute();
 
    }
}

   $n=new jv_import_export();
    foreach($n->test() as $row)
    {
      $id=$row["id"];
      $policy_number=$row["policy_number"];
      $id_number=$row["id_number"];
      $scheme_number=$row["scheme_number"];
      $start_date=$row["start_date"];
      $end_date=$row["end_date"];
      
      //$count=$n->getClaimHeader($policy_number,$id_number,"",$scheme_number,"",$start_date);
      //$count=$n->getClaimLevel($policy_number,$id_number,"",$scheme_number,"",$start_date);
      $count=$n->getClaimLineLevel($policy_number,$id_number,"",$scheme_number,"",$start_date);
      $n->updatetest($id,$count);
      echo $policy_number."--->".$count;
      echo "<hr>";
    }
    echo "ec--sd";
?>

SELECT *FROM claim_line as l INNER JOIN `claim` as a ON l.mca_claim_id=a.claim_id INNER JOIN member as b ON a.member_id=b.member_id WHERE ((b.policy_number="" AND b.policy_number<>"AUHGAP398435") OR (b.id_number="3510145082082 " AND b.id_number<>"") OR (b.scheme_number="352289070" AND b.scheme_number<>"")) AND (a.Service_Date BETWEEN "2023-04-20" AND "2023-04-22");