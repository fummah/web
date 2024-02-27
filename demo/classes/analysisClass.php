<?php
namespace mcaAPI;
//error_reporting(0);
include ("../../../mca/link2.php");
$conn=connection("mca", "MCA_admin");
class analysisClass
{
    private $myfield_prop;
    function __constructor()
    {
        if (!defined('access')) {
            die('Access not permited');
        }
    }  
    function getSchemeSummary($start_date,$end_date,$open,$section,$sec_section,$top_section,$typ,$checkboxes,$final_section)
    {
        
        try {
            global $conn; 
           $end_date=$this->setEndate($start_date,$end_date);           
           $query="";           
           if($open=="1")
           {
            $dat=" AND 1";
               $op="Open=1";
           }
           elseif ($open=="0")
           {
            $dat=!empty($start_date)?" AND a.date_closed >='".$start_date."' AND a.date_closed<'".$end_date."' ":" AND 1";
            $op="Open=0";
           } 
           else{
            $dat=!empty($start_date)?" AND a.date_entered >='".$start_date."' AND a.date_entered<'".$end_date."' ":" AND 1";
            $op="Open<>2";
           }
           
           $this->myfield_prop=$this->getArr($section)["myfields"];        
           if(!empty($sec_section))
           {
            $query_field=$this->getArr($section)["query_field"];
            $op.= " AND ".$query_field.".".$section."='".$sec_section."'";
           } 
         // echo json_encode($checkboxes,true);
         
           $idarr = array_search("unchecked", array_column($checkboxes, 'status'));
           $idarr1 = array_search("checked", array_column($checkboxes, 'status'));         
           if($idarr > -1 && $idarr1 > -1)
{
    $query=" AND ".$this->extendedCondition($checkboxes);
}         
           $all=$op.$dat.$query; 
           //echo $all.$idarr."->".$idarr1;
           //Functions
           $mysql=$this->myQuery($all,$section);
           if(!empty($top_section))
           {
            $top_section = str_replace("_sub","",$top_section); 
            $mysql = str_replace($this->extraFields1().$this->myfield_prop,"a.claim_id",$mysql);
            $this->myfield_prop=$this->getArr($top_section)["myfields"]; 
            $further="";
            if(!empty($final_section))
            {
            $query_field=$this->getArr($top_section)["query_field"];
            $further=" AND ".$query_field.".".$top_section."='".$final_section."'";
            }
            $mysql=$this->schemeQueryGroup($mysql,$top_section,$further);       
           } 
          //echo $mysql;          
           $stmt = $conn->prepare($mysql);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (\Exception $e)
        {
            //echo $e->getMessage();
            return $e->getMessage();
        }
    }
    
    private function myQuery($all,$section)
    {
        $sql="";
        if($section=='medical_scheme')  
        {
        $sql='SELECT '.$this->extraFields().' FROM claim_line as x INNER JOIN `claim` as a ON x.mca_claim_id=a.claim_id '.$this->defaultQuery().' WHERE '.$all.' AND a.Open<>2';
        }
        elseif($section=='tariff_code')  
        {
        $sql='SELECT '.$this->extraFields().' FROM claim_line as x INNER JOIN `claim` as a ON x.mca_claim_id=a.claim_id '.$this->defaultQuery().' INNER JOIN TariffMaster as t ON x.tariff_code=t.Tariff_Code WHERE '.$all.' AND Open<>2';
        }
        elseif($section=='primaryICDCode')  
        {
        $sql='SELECT '.$this->extraFields().' FROM claim_line as x INNER JOIN `claim` as a ON x.mca_claim_id=a.claim_id '.$this->defaultQuery().' WHERE '.$all.' AND Open<>2';
        }
        elseif($section=='practice_number')  
        {
        $sql='SELECT '.$this->extraFields().' FROM doctors as f INNER JOIN `claim` as a ON f.claim_id=a.claim_id INNER JOIN claim_line as x ON f.claim_id=x.mca_claim_id AND f.practice_number=x.practice_number '.$this->defaultQuery().' INNER JOIN doctor_details as dd ON f.practice_number=dd.practice_number WHERE '.$all.' AND Open<>2';
        }
        return $sql;
    }
    private function schemeQueryGroup($inquery,$top_section,$further="")
    {
        $sql="xx";
        if($top_section=='medical_scheme')  
        {
        $sql='SELECT '.$this->extraFields().' FROM claim_line as x INNER JOIN `claim` as a ON x.mca_claim_id=a.claim_id  '.$this->defaultQuery().' WHERE a.claim_id IN(SELECT claim_id FROM('.$inquery.') as a1)'.$further;
        }
        elseif($top_section=='tariff_code') 
    {
        $sql='SELECT '.$this->extraFields().' FROM claim_line as x INNER JOIN `claim` as a ON x.mca_claim_id=a.claim_id '.$this->defaultQuery().' INNER JOIN TariffMaster as t ON x.tariff_code=t.Tariff_Code WHERE a.claim_id IN(SELECT claim_id FROM('.$inquery.') as a1)'.$further;
       }
       elseif($top_section=='primaryICDCode') 
    {
        $sql='SELECT '.$this->extraFields().' FROM claim_line as x INNER JOIN `claim` as a ON x.mca_claim_id=a.claim_id '.$this->defaultQuery().' WHERE a.claim_id IN(SELECT claim_id FROM('.$inquery.') as a1)'.$further;
       }
       elseif($top_section=='practice_number') 
    {
        $sql='SELECT '.$this->extraFields().' FROM doctors as f INNER JOIN `claim` as a ON f.claim_id=a.claim_id INNER JOIN claim_line as x ON f.claim_id=x.mca_claim_id AND f.practice_number=x.practice_number '.$this->defaultQuery().' INNER JOIN doctor_details as dd ON x.practice_number=dd.practice_number WHERE a.claim_id IN(SELECT claim_id FROM('.$inquery.') as a1)'.$further;
        }
    return $sql;
}
private function emergencyCodes()
{
    return "('0011','0145','0146','0001','415','0147')";
}
function extendedCondition($arr)
{    
    $ext_query=""; 
    $emergency_codes=$this->emergencyCodes();   
    $emergency_checked=$arr[0]["status"];    
    $non_emergency_checked=$arr[1]["status"];   
    $pmb_checked=$arr[2]["status"];    
    $non_pmb_checked=$arr[3]["status"];
if($emergency_checked=="checked")
{
    $ext_query.= "(x.tariff_code IN ".$emergency_codes." OR emergency=1) ";
}
if($non_emergency_checked=="checked")
{
    $ext_query= $emergency_checked=="checked"?"(".$ext_query." OR (x.tariff_code NOT IN ".$emergency_codes." OR emergency=0)) ":"(x.tariff_code NOT IN ".$emergency_codes." OR emergency=0) ";
  
}
if($pmb_checked=="checked")
{
    $ext_query.=strpos($ext_query,"emergency")> -1?" AND (pmb_code<>''":" (pmb_code<>''";     
}
if($non_pmb_checked=="checked")
{
    if(strpos($ext_query,"emergency")> -1)
        {
            $ext_query.=strpos($ext_query,"pmb")> -1?" OR pmb_code='')":" AND (pmb_code='')";
        }
        else
        {
            $ext_query.=strpos($ext_query,"pmb")> -1?" OR pmb_code='')":" (pmb_code='')";
        }    
}
else{
    if(strpos($ext_query,"pmb")> -1)
            {
                $ext_query.= ")";
            }
}
return $ext_query;
} 
private function getArr($section)
{
    $id = array_search($section, array_column($this->mainVal(), 'field_name'));
    return $this->mainVal()[$id];
}
    private function extraFields()
    {
        return $this->extraFields1().$this->myfield_prop;
    }
    private function extraFields1()
    {
        $fields="DISTINCT a.claim_number,CONCAT(b.first_name,' ',b.surname) as full_name,a.username,cc.client_name,(a.charged_amnt-a.scheme_paid) as claim_value,a.savings_scheme,a.savings_discount,(a.savings_scheme+a.savings_discount) as total_savings,a.claim_id,";
        return $fields;
    }
    private function defaultQuery()
    {
        $query="INNER JOIN coding as c ON x.primaryICDCode=c.diag_code INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as cc ON b.client_id=cc.client_id";
        return $query;
    }
    function mainVal()
    {
        $arr=array(
            array("ui_name"=>"Medical Schemes","field_name"=>"medical_scheme","query_field"=>"b","myfields"=>"CONCAT(UPPER(b.medical_scheme),'~') as heading","status"=>"checked"),
            array("ui_name"=>"Tariff Codes","field_name"=>"tariff_code","query_field"=>"x","myfields"=>"CONCAT(x.tariff_code,'~',t.Description) as heading","status"=>""),
            array("ui_name"=>"ICD10 Codes","field_name"=>"primaryICDCode","query_field"=>"x","myfields"=>"CONCAT(x.primaryICDCode,'~',c.ccs_grouper_desc) as heading","status"=>""),           
            array("ui_name"=>"Providers","field_name"=>"practice_number","query_field"=>"f","myfields"=>"CONCAT(f.practice_number,'~',dd.name_initials,' ',dd.surname) as heading","status"=>""),
        );
        return $arr;
    }
    function selectorVal()
    {
        $arr=array(        
            array("ui_name"=>"Emergency","field_name"=>"emergency","status"=>""),
            array("ui_name"=>"Non-Emergency","field_name"=>"non_emergency","status"=>""),
            array("ui_name"=>"PMB","field_name"=>"pmb","status"=>""),
            array("ui_name"=>"NON-PMB","field_name"=>"non_pmb","status"=>"")

        );
        return $arr;
    }
    public function moneyformat($val)
    {
        return number_format($val,2,'.',',');
    }
    private function setEndate($start_date,$end_date)
    {
        if(!empty($start_date))
        {
            $date = new \DateTime($end_date);
            $date->modify('+1 day');
            $end_date=$date->format('Y-m-d');
        }
        return $end_date;
    }
    
}