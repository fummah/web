<?php
namespace mcaAPI;
//error_reporting(0);
include ("../../../mca/link2.php");
$conn=connection("mca","MCA_admin");
$con_doc_db=connection("doc","doctors");
$con_code_db=connection("cod","Coding");
$conn3=connection("bi","bi");
$conn4=connection("seamless","seamless");
class admedClass
{
    function __constructor()
    {
        if (!defined('access')) {
            die('Access not permited');
        }
    }

    

    function getTopLevel($columns,$hierach,$start_date,$end_date,$other_fields=array())
    {
        global $conn;
        $columns=json_decode($columns);
        $count=count($columns);
        if(!empty($start_date))
        {
            $date = new \DateTime($end_date);
            $date->modify('+1 day');
            $end_date=$date->format('Y-m-d');
        }
        $arrf=$this->groupTotals();
        $hierach_arr=$this->topFields();
        //print_r($other_fields);
        $dat=!empty($start_date)?" k.Claim_Date_Received >='".$start_date."' AND k.Claim_Date_Received<'".$end_date."' ":"1";  
        $xtrafield="";
        if(count($other_fields)>0)
        {
            foreach ($other_fields as $key => $val)
            {

                $id = array_search($key, array_column($hierach_arr, 'field_name'));
                $extra=$hierach_arr[$id]["table"].".".$key;
                $xtrafield.=" AND ".$extra." IN ('".$val."')";
            }
        }

        $all=$dat.$xtrafield;


        $id = array_search($hierach, array_column($hierach_arr, 'field_name'));
        $active_field=$hierach_arr[$id]["table"].".".$hierach;
        $str=$active_field;
        $orderby=$active_field;

        foreach ($columns as $coms)
        {
            $id = array_search($coms, array_column($arrf, 'field_name'));
            $table=$arrf[$id]["table"];
            $type=$arrf[$id]["type"];
            $dist=$type=="COUNT" || $type=="SUM"?"DISTINCT ":"";
            $str.=",$type($dist".$table.".".$coms.") as $coms";
        }
        if($count>0)
        {
            $id = array_search($columns[0], array_column($arrf, 'field_name'));
            $orderby=$arrf[$id]["table"].".".$columns[0];
        }
        $sql="SELECT ".$str." 
           FROM Admed_Data as k           
           INNER JOIN coding as j ON k.Claim_Incident_PrimaryCauseICD10=j.diag_code 
           WHERE ".$all."
           GROUP BY $active_field ORDER BY ".$orderby." DESC";
        //echo "==".$active_field."--->".$sql."<hr>";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();

    }

   

    function groupTotals()
    {
        $arr=array(
            array("ui_name"=>"Claim Total Charged","field_name"=>"Claim_Total_Charged","status"=>"checked","type"=>"SUM","table"=>"k"),
            array("ui_name"=>"Claim Total Medical Aid","field_name"=>"Claim_Total_MedicalAid","status"=>"checked","type"=>"SUM","table"=>"k"),
            array("ui_name"=>"Claim Total Value CoPayment","field_name"=>"Claim_Total_Value_CoPayment","status"=>"checked","type"=>"SUM","table"=>"k"),
            array("ui_name"=>"Claim Total Value Once Off","field_name"=>"Claim_Total_Value_OnceOff","status"=>"checked","type"=>"SUM","table"=>"k"),
            array("ui_name"=>"Claim Total Claimable","field_name"=>"Claim_Total_Claimable","status"=>"","type"=>"SUM","table"=>"k"),
            array("ui_name"=>"Claim Total Payable By Admed","field_name"=>"Claim_Total_PayableByAdmed","status"=>"","type"=>"SUM","table"=>"k"),
            array("ui_name"=>"Claim Total Payout","field_name"=>"Claim_Total_Payout","status"=>"","type"=>"SUM","table"=>"k"),
            array("ui_name"=>"Claim Total Payout Member","field_name"=>"Claim_Total_Payout_Member","status"=>"","type"=>"SUM","table"=>"k"),            
            array("ui_name"=>"Total Claims","field_name"=>"id","status"=>"checked","type"=>"COUNT","table"=>"k")

        );
        return $arr;
       
    }
    public function moneyformat($val)
    {
        return number_format($val,2,'.',',');
    }
    function topFields()
    {
        $arr=array(
            array("ui_name"=>"MCA Group","field_name"=>"ccs_grouper_desc","status"=>"checked","table"=>"j"),
            array("ui_name"=>"Medical Scheme","field_name"=>"MedicalScheme","status"=>"","table"=>"k"),
            array("ui_name"=>"Product Name","field_name"=>"Product_Name","status"=>"","table"=>"k"),
            array("ui_name"=>"Claim Number","field_name"=>"Claim_Identifier","table"=>"k")


        );
        return $arr;
    }


    public function getYear()
    {
        global $conn3;
        $checkM=$conn3->prepare('SELECT DATE_FORMAT(claim_incidentdate,\'%Y\') as dat,COUNT(*) FROM claim GROUP BY DATE_FORMAT(claim_incidentdate,\'%Y\') ORDER BY dat ASC');
        //$checkM->bindParam(':claim_id', $claim_id, \PDO::PARAM_STR);
        $checkM->execute();
        $array = array_column($checkM->fetchAll(), 'dat');
        return $array;

    }
    public function getYearValues($columns)
    {
        $columns=json_decode($columns);
        $count=count($columns);
       
        $arrf=$this->groupTotals();
        $hierach_arr=$this->topFields();
         
        $str="";
        foreach ($columns as $coms)
        {
            $id = array_search($coms, array_column($arrf, 'field_name'));
            //$table=$arrf[$id]["table"];
            $type=$arrf[$id]["type"];
            $dist=$type=="COUNT"?"Total_Number ":$coms;
            $str.=",$type(".$coms.") as $dist";
        }
        //echo $str;
        global $conn;
        $stmt=$conn->prepare("SELECT DATE_FORMAT(Claim_Date_Received,'%Y') as yr $str FROM `Admed_Data` GROUP BY DATE_FORMAT(Claim_Date_Received,'%Y')");
        $stmt->execute();
        return $stmt->fetchAll();

    }  
   
    
}