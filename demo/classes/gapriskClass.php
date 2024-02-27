<?php
namespace mcaAPI;
//error_reporting(0);
include ("../../../mca/link2.php");
$conn=connection("bi","bi");
class apiClass
{
    function __constructor()
    {
        if (!defined('access')) {
            die('Access not permited');
        }
    }

    function getClients($status = 1)
    {
      return [];
    }

    function getUsers($status = 1)
    {
        return [];
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
        $dat=!empty($start_date)?" x.claim_incidentdate >='".$start_date."' AND x.claim_incidentdate<'".$end_date."' ":"1";

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
            if($table=="mcaage")
            {
                $str.=",floor(SUM(datediff(claim_incidentdate, dob)/365)/COUNT(k.claim_id)) as $coms";
            }
            elseif ($table=="avcost")
            {
                $str.=",ROUND(SUM(Amt_Paid_Gap)/COUNT(DISTINCT k.claim_id),2) as $coms";
            }
            else{
                $dist=$type=="COUNT" || $type=="SUM"?"DISTINCT ":"";
                $str.=",$type($dist".$table.".".$coms.") as $coms";
            }

        }
        if($count>0)
        {
            $id = array_search($columns[0], array_column($arrf, 'field_name'));
            $orderby=$arrf[$id]["table"].".".$columns[0];
        }
        $sql="SELECT ".$str.",d.discipline
           FROM gaprisk_claim_line as k 
           INNER JOIN gaprisk_claim as x ON k.claim_id=x.claim_id 
           INNER JOIN doctor_details as d ON k.practice_number=d.practice_number 
           INNER JOIN gaprisk_member as y ON x.member_id=y.member_id            
           INNER JOIN coding as j ON k.icd10=j.diag_code 
           WHERE ".$all."
           GROUP BY $active_field ORDER BY 2 DESC";
        //echo "==".$active_field."--->".$sql."<hr>";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();

    }

    function groupTotals()
    {
        $arr=array(
            array("ui_name"=>"Charged Amount","field_name"=>"Amt_Charged","status"=>"checked","type"=>"SUM","table"=>"k"),
            array("ui_name"=>"Scheme Amount","field_name"=>"Amt_Paid_Med","status"=>"checked","type"=>"SUM","table"=>"k"),
            array("ui_name"=>"Gap","field_name"=>"Amt_Paid_Gap","status"=>"checked","type"=>"SUM","table"=>"k"),
            array("ui_name"=>"Amt_Diff","field_name"=>"Amt_Diff","status"=>"","type"=>"SUM","table"=>"k"),
            array("ui_name"=>"Claims","field_name"=>"claim_id","status"=>"checked","type"=>"COUNT","table"=>"x"),
            array("ui_name"=>"Average Age/MCA Grouper","field_name"=>"mcaage","status"=>"","type"=>"COUNT","table"=>"mcaage"),
            array("ui_name"=>"Average Cost/Patient","field_name"=>"avcost","status"=>"","type"=>"SUM","table"=>"avcost"),
            array("ui_name"=>"Claim Lines","field_name"=>"claim_line_id","status"=>"","type"=>"COUNT","table"=>"k")

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
            array("ui_name"=>"MCA CCS Group","field_name"=>"ccs_grouper_desc","show"=>"yes","status"=>"checked","table"=>"j"),
            array("ui_name"=>"Section","field_name"=>"section_desc","show"=>"yes","status"=>"","table"=>"j"),
            array("ui_name"=>"Medical Scheme","field_name"=>"medical_scheme","show"=>"yes","status"=>"","table"=>"y"),
            array("ui_name"=>"Discipline Code","field_name"=>"disciplinecode","show"=>"yes","status"=>"","table"=>"d"),
            array("ui_name"=>"Hospitals","field_name"=>"hospital_name","show"=>"yes","status"=>"","table"=>"x"),
            array("ui_name"=>"ICD10 Code","field_name"=>"icd10","show"=>"no","table"=>"k"),
            array("ui_name"=>"Tarrif Code","field_name"=>"tariff_code","show"=>"no","table"=>"k"),
            array("ui_name"=>"Claim Number","field_name"=>"claim_claimnumber","show"=>"no","table"=>"x")

        );
        return $arr;
    }

}