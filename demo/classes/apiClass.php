<?php
namespace mcaAPI;
//error_reporting(0);
include ("../../../mca/link2.php");
$conn=connection("mca","MCA_admin");
$con_doc_db=connection("doc","doctors");
$con_code_db=connection("cod","Coding");
$conn3=connection("bi","bi");
$conn4=connection("seamless","seamless");
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
        global $conn;
        $stmt = $conn->prepare('SELECT DISTINCT reporting_client_id as client_id,client_name as obj_name FROM clients WHERE reporting_status=:status ORDER BY client_name');
        $stmt->bindParam(':status', $status, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function getUsers($status = 1)
    {
        global $conn;
        $stmt = $conn->prepare('SELECT DISTINCT username as obj_name FROM users_information WHERE status=:status ORDER BY username');
        $stmt->bindParam(':status', $status, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function getTopLevel($columns,$hierach,$clients,$users,$start_date,$end_date,$other_fields=array())
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
        $dat=!empty($start_date)?" x.date_entered >='".$start_date."' AND x.date_entered<'".$end_date."' ":"1";
        $users_array=array_map('strval', explode(',', $users));
        $clients_array=array_map('strval', explode(',', $clients));
        $users_em = implode("','",$users_array);
        $clients_em = implode("','",$clients_array);
        $vol=!empty($users)?" AND x.username IN ('".$users_em."')":" AND 1";
        $vol1=!empty($clients)?" AND z.client_name IN ('".$clients_em."')":" AND 1";
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

        $all=$dat.$vol.$vol1.$xtrafield;


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
           FROM claim_line as k 
           INNER JOIN claim as x ON k.mca_claim_id=x.claim_id 
           INNER JOIN doctor_details as d ON k.practice_number=d.practice_number 
           INNER JOIN member as y ON x.member_id=y.member_id 
           INNER JOIN clients as z ON y.client_id=z.client_id 
           INNER JOIN coding as j ON k.primaryICDCode=j.diag_code 
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
            array("ui_name"=>"Charged Amount","field_name"=>"clmnline_charged_amnt","status"=>"checked","type"=>"SUM","table"=>"k"),
            array("ui_name"=>"Scheme Amount","field_name"=>"clmline_scheme_paid_amnt","status"=>"checked","type"=>"SUM","table"=>"k"),
            array("ui_name"=>"Gap","field_name"=>"gap","status"=>"checked","type"=>"SUM","table"=>"k"),
            array("ui_name"=>"Claims","field_name"=>"claim_id","status"=>"checked","type"=>"COUNT","table"=>"x"),
            array("ui_name"=>"Claim Lines","field_name"=>"id","status"=>"","type"=>"COUNT","table"=>"k"),
            array("ui_name"=>"Scheme Savings","field_name"=>"savings_scheme","status"=>"","type"=>"SUM","table"=>"x"),
            array("ui_name"=>"Discount Savings","field_name"=>"savings_discount","status"=>"","type"=>"SUM","table"=>"x")

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
            array("ui_name"=>"CCS Group","field_name"=>"ccs_grouper_desc","status"=>"checked","table"=>"j"),
            array("ui_name"=>"Section","field_name"=>"section_desc","status"=>"","table"=>"j"),
            array("ui_name"=>"Medical Scheme","field_name"=>"medical_scheme","status"=>"","table"=>"y"),
            array("ui_name"=>"Discipline Code","field_name"=>"disciplinecode","status"=>"","table"=>"d"),
            array("ui_name"=>"ICD10 Code","field_name"=>"primaryICDCode","table"=>"k"),
            array("ui_name"=>"Tarrif Code","field_name"=>"tariff_code","table"=>"k"),
            array("ui_name"=>"Claim Number","field_name"=>"claim_number","table"=>"x")

        );
        return $arr;
    }
    function biMenu()
    {
        $arr=array(
            array("ui_name"=>"Group Claims By","field_id"=>"group_claims_by","status"=>"checked","table"=>[
                ["ui_name"=>"ICD-10 grouped","condition"=>["Bottom 10","Top 10"]],
                ["ui_name"=>"Benefit Utilisation","condition"=>["Bottom 10","Top 10"]],
                //["ui_name"=>"Procedures","condition"=>["Bottom 10","Top 10"]],
                //["ui_name"=>"Providers","condition"=>["Bottom 10","Top 10"]],
                //["ui_name"=>"Hospitals","condition"=>["Bottom 10","Top 10"]]
            ]),
            array("ui_name"=>"Specialities","field_id"=>"specialities","status"=>"sidebar","table"=>[["ui_name"=>"Specialities","condition"=>["Bottom 10","Top 10"]]]),
            array("ui_name"=>"High Cost Claims","field_id"=>"high_cost_claims","status"=>"sidebar","table"=>[["ui_name"=>"Specialities","condition"=>["Bottom 10","Top 10"]]]),
            //array("ui_name"=>"Hospital with regions","field_id"=>"hospital_with_regions","status"=>"","table"=>[])
            //array("ui_name"=>"Benefit tier by Year","field_id"=>"benefit_tier_by_year","status"=>"sidebar","table"=>[["ui_name"=>"Benefit tier by Year","condition"=>["Bottom 10","Top 10"]]]),
            //array("ui_name"=>"Regions","field_id"=>"regions","status"=>"","table"=>[]),
            //array("ui_name"=>"ICD Volume & Value","field_id"=>"icd_volume_value","status"=>"","table"=>[]),
            //array("ui_name"=>"Paid Volume by Policy","field_id"=>"paid_volume_by_policy","status"=>"","table"=>[]),
            //array("ui_name"=>"Providers by Region","field_id"=>"providers_by_region","status"=>"checked","table"=>[])
            //array("ui_name"=>"Admissions","field_id"=>"admissions","status"=>"","table"=>[])
        );
        return $arr;
    }
    public function getBenefit()
    {
        global $conn3;
        $yearsarr=$this->getYear();
        $arr=array($yearsarr);
        $checkM=$conn3->prepare('SELECT DISTINCT claiminsureditem_benefittiers, COUNT(DISTINCT k.claim_id) tot,SUM(claiminsureditem_payout) payout FROM `claim_line` as k 
    INNER JOIN claim as a ON k.claim_id=a.claim_id GROUP BY claiminsureditem_benefittiers ORDER BY tot DESC LIMIT 10');
        //$checkM->bindParam(':claim_id', $claim_id, \PDO::PARAM_STR);
        $checkM->execute();
        foreach($checkM->fetchAll() as $row)
        {
            $benefittiers=$row["claiminsureditem_benefittiers"];
            $arrs=[$benefittiers];
            foreach ($yearsarr as $yr)
            {
                $cc=$this->getYearValues($yr,$benefittiers);
                array_push($arrs,$cc);
            }
            array_push($arr,$arrs);

        }
        return $arr;

    }
    public function getBenefitG($dat,$txtup)
    {
        global $conn3;
        $checkM=$conn3->prepare('SELECT DISTINCT claiminsureditem_benefittiers, COUNT(DISTINCT k.claim_id) tot FROM `claim_line` as k 
    INNER JOIN claim as a ON k.claim_id=a.claim_id INNER JOIN member as b ON a.member_id=b.member_id WHERE 1 '.$dat.' GROUP BY claiminsureditem_benefittiers ORDER BY tot '.$txtup);
        //$checkM->bindParam(':claim_id', $claim_id, \PDO::PARAM_STR);
        $checkM->execute();

        return $checkM->fetchAll();

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
    public function getYearValues($claim_incidentdate,$claiminsureditem_benefittiers)
    {
        global $conn3;
        $claim_incidentdate="%$claim_incidentdate%";
        $checkM=$conn3->prepare('SELECT COUNT(*) tot FROM `claim_line` as k INNER join claim as a on k.claim_id=a.claim_id WHERE claim_incidentdate LIKE :claim_incidentdate AND claiminsureditem_benefittiers=:claiminsureditem_benefittiers');
        $checkM->bindParam(':claim_incidentdate', $claim_incidentdate, \PDO::PARAM_STR);
        $checkM->bindParam(':claiminsureditem_benefittiers', $claiminsureditem_benefittiers, \PDO::PARAM_STR);
        $checkM->execute();
        return (int)$checkM->fetchColumn();

    }
    public function getHospitals($start_date,$end_date,$scheme_sql,$txtup,$individual)
    {
        global $conn3;
        $dat=!empty($start_date)?" AND a.claim_incidentdate >='".$start_date."' AND a.claim_incidentdate<'".$end_date."' ":"";
        $dat.=$dat.$scheme_sql.$individual;
        $arrHospitalmain=[$this->getRegions()];
        $checkM=$conn3->prepare('SELECT Hospital as hospital_name,COUNT(Hospital) tot FROM `claim` as a INNER JOIN member as b ON a.member_id=b.member_id WHERE 1 '.$dat.' GROUP BY Hospital ORDER BY tot '.$txtup);
        $checkM->execute();
        foreach ($checkM->fetchAll(\PDO::FETCH_ASSOC) as $row)
        {
            $hospital_name=$row["hospital_name"];
            $arrprovider=[$hospital_name];
            foreach ($this->getRegions() as $region)
            {
                $tot=$this->getHospitalRegion($hospital_name,$region,$dat);
                array_push($arrprovider,$tot);
            }
            array_push($arrHospitalmain,$arrprovider);
        }
        return $arrHospitalmain;
    }
    public function getICD($dat,$txtup)
    {
        global $conn3;
        $checkM=$conn3->prepare('SELECT DISTINCT d.ccs_grouper_desc,COUNT(ccs_grouper_desc) tot,floor(SUM(age)/COUNT(*)) age FROM `claim_line` as k inner join coding as d on k.claiminsureditem_icdcode=d.diag_code 
inner join claim as a on k.claim_id=a.claim_id INNER join member as b on a.member_id=b.member_id WHERE 1 '.$dat.' GROUP BY ccs_grouper_desc ORDER BY tot '.$txtup);
        $checkM->execute();
        return $checkM->fetchAll();
    }
    public function getMedicalScheme()
    {
        global $conn3;
        $checkM=$conn3->prepare('SELECT DISTINCT medicalaid FROM `member` where medicalaid is not null');
        $checkM->execute();
        return array_column($checkM->fetchAll(), 'medicalaid');
    }
    public function getHospitalsByRegion()
    {
        global $conn3;
        $checkM=$conn3->prepare('SELECT a.Region, COUNT(a.Region) tot,lat,lon FROM `hospitals` as a inner join regions as r on a.Region=r.region GROUP BY a.Region ORDER BY tot DESC');
        $checkM->execute();
        return $checkM->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getSpeciality($dat,$txtup)
    {
        global $conn3;
        $checkM=$conn3->prepare('SELECT disciplinecode,discipline,COUNT(DISTINCT q.claim_id) tot FROM claim_line as q INNER JOIN claim as a ON q.claim_id=a.claim_id INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN doctor_details d ON q.practice_number=d.practice_number WHERE 1 '.$dat.' GROUP BY disciplinecode ORDER BY tot '.$txtup);
        $checkM->execute();
        return $checkM->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getRegions()
    {
        global $conn3;
        $checkM=$conn3->prepare('SELECT DISTINCT Region FROM `hospitals` WHERE Region<>"All regions"');
        $checkM->execute();
        return $array = array_column($checkM->fetchAll(\PDO::FETCH_ASSOC), 'Region');
    }
    public function getRegionsHospitals()
    {
        global $conn3;
        $main_arr=[];
        $yearsarr=$this->getYear();
        $checkM=$conn3->prepare('SELECT DISTINCT Region FROM `hospitals`');
        $checkM->execute();
        foreach($checkM->fetchAll(\PDO::FETCH_ASSOC) as $row)
        {
            $region=$row["Region"];
            $arryear=[];
            foreach ($yearsarr as $year) {
                $arrproduct=[];
                foreach ($this->clientArr() as $product) {
                    $val=$this->getByRegion($year, $region, $product);
                    $products=["product"=>$product,"val"=>$val];
                    array_push($arrproduct,$products);
                }
                $years=["years"=>$year,"products"=>$arrproduct];
                array_push($arryear,$years);
            }
            $regionarr=["region"=>$region,"years"=>$arryear];
            array_push($main_arr,$regionarr);
        }
        return $main_arr;
    }
    public function clientArr()
    {
        return ["Kaelo Gap","Sanlam Gap","Western Gap"];
    }
    public function getByRegion($year,$region,$product_name)
    {
        global $conn3;
        $year="%$year%";
        $checkM=$conn3->prepare('SELECT COUNT(*) tot FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN hospitals as c ON a.Hospital=c.Hospital WHERE claim_incidentdate LIKE :claim_incidentdate AND c.Region=:Region AND b.Product=:Product');
        $checkM->bindParam(':claim_incidentdate', $year, \PDO::PARAM_STR);
        $checkM->bindParam(':Region', $region, \PDO::PARAM_STR);
        $checkM->bindParam(':Product', $product_name, \PDO::PARAM_STR);
        $checkM->execute();
        return (int)$checkM->fetchColumn();

    }
    public function getHospitalRegion($hopital_name,$region,$dat)
    {
        global $conn3;
        $checkM=$conn3->prepare('SELECT COUNT(*) tot FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN hospitals as c ON a.Hospital=c.Hospital WHERE c.Region=:Region AND a.Hospital=:Hospital'.$dat);
        $checkM->bindParam(':Region', $region, \PDO::PARAM_STR);
        $checkM->bindParam(':Hospital', $hopital_name, \PDO::PARAM_STR);
        $checkM->execute();
        return (int)$checkM->fetchColumn();

    }
  
    public function getBenefitGroupValue($claiminsureditem_benefittiers,$dat)
    {
        global $conn3;
        $checkM=$conn3->prepare('SELECT Product as product,COUNT(DISTINCT k.claim_id) counts,SUM(k.claiminsureditem_payout) value_payout FROM `claim_line` as k inner join claim as a on k.claim_id=a.claim_id INNER join member as b on a.member_id=b.member_id WHERE k.claiminsureditem_benefittiers=:claiminsureditem_benefittiers '.$dat.' GROUP BY Product');
        $checkM->bindParam(':claiminsureditem_benefittiers', $claiminsureditem_benefittiers, \PDO::PARAM_STR);
        $checkM->execute();
        return $checkM->fetchAll(\PDO::FETCH_ASSOC);

    }

  
    public function groupByICD($start_date,$end_date,$scheme_sql,$txtup,$individual)
    {
        global $conn3;
        $dat=!empty($start_date)?" AND a.claim_incidentdate >='".$start_date."' AND a.claim_incidentdate<'".$end_date."' ":"";
        $dat.=$dat.$scheme_sql.$individual;
        $main=[];
        $checkM=$conn3->prepare('SELECT DISTINCT d.ccs_grouper_desc,COUNT(DISTINCT a.claim_id) tot,floor(SUM(age)/COUNT(*)) age FROM `claim_line` as k inner join coding as d on k.claiminsureditem_icdcode=d.diag_code 
inner join claim as a on k.claim_id=a.claim_id INNER join member as b on a.member_id=b.member_id WHERE 1 '.$dat.' GROUP BY ccs_grouper_desc ORDER BY tot '.$txtup);
        $checkM->execute();
        foreach ($checkM->fetchAll() as $row)
        {
            $sst=$conn3->prepare('SELECT Product as product,COUNT(DISTINCT k.claim_id) as counts,SUM(k.claiminsureditem_payout) value_payout,floor(SUM(age)/COUNT(*)) age FROM `claim_line` as k inner join coding as d on k.claiminsureditem_icdcode=d.diag_code inner join claim as a on k.claim_id=a.claim_id INNER join member as b on a.member_id=b.member_id WHERE ccs_grouper_desc=:ccs_grouper_desc AND a.claim_incidentdate '.$dat.' GROUP BY Product');
            $sst->bindParam(':ccs_grouper_desc', $row["ccs_grouper_desc"], \PDO::PARAM_STR);
            $sst->execute();
            $myp=$sst->fetchAll();
            $inarr=array("diag_code"=>"","shortdesc"=>"","ccs_grouper_desc"=>$row["ccs_grouper_desc"],"products"=>$myp,"tot"=>0,"age"=>$row["age"]);
            array_push($main,$inarr);
        }
        return $main;
    }

    public function groupByBenefits($start_date,$end_date,$scheme_sql,$txtup,$individual)
    {
        $dat=!empty($start_date)?" AND a.claim_incidentdate >='".$start_date."' AND a.claim_incidentdate<'".$end_date."' ":"";
        $dat.=$dat.$scheme_sql.$individual;
        $main=[];
        foreach ($this->getBenefitG($dat,$txtup) as $row)
        {
            $claiminsureditem_benefittiers=$row["claiminsureditem_benefittiers"];
            $myp=$this->getBenefitGroupValue($claiminsureditem_benefittiers,$dat);
            $inarr=array("claiminsureditem_benefittiers"=>$claiminsureditem_benefittiers,"products"=>$myp);
            array_push($main,$inarr);
        }
        return $main;
    }
    public function getHighCostClaims($start_date,$end_date,$txtup,$individual)
    {
        global $conn3;
        $dat=!empty($start_date)?" AND a.claim_incidentdate >='".$start_date."' AND a.claim_incidentdate<'".$end_date."' ":"";
        $dat.=$dat.$individual;
        $main=[];
        $checkM=$conn3->prepare('SELECT k.claim_id,a.claim_claimnumber,b.Product,SUM(claiminsureditem_payout) payout FROM `claim_line` as k inner join claim as a on k.claim_id=a.claim_id INNER join member as b on a.member_id=b.member_id WHERE 1 '.$dat.' GROUP BY k.claim_id ORDER BY payout '.$txtup);

        $checkM->execute();
        foreach ($checkM->fetchAll()as $row)
        {
            $claim_id=(int)$row["claim_id"];
            $claim_claimnumber=$row["claim_claimnumber"];
            $product=$row["Product"];
            $payout=$row["payout"];
            $inarr=array("claim_id"=>$claim_id,"claim_claimnumber"=>$claim_claimnumber,"product"=>$product,"payout"=>$payout,"icd10"=>$this->getClaimICD($claim_id),"providers"=>$this->getClaimProvider($claim_id));
            array_push($main,$inarr);

        }
        return $main;
    }
    public function getClaimProvider($claim_id)
    {
        global $conn3;
        $checkM=$conn3->prepare('SELECT k.practice_number,CONCAT(j.name_initials," ",j.surname) as fullname,j.disciplinecode,j.discipline FROM `doctor` as k INNER JOIN doctor_details as j on k.practice_number=j.practice_number WHERE k.claim_id=:claim_id');
        $checkM->bindParam(':claim_id', $claim_id, \PDO::PARAM_STR);
        $checkM->execute();
        return $checkM->fetchAll();
    }
    public function getClaimICD($claim_id)
    {
        global $conn3;
        $checkM=$conn3->prepare('SELECT claiminsureditem_icdcode,p.shortdesc,p.ccs_grouper_desc FROM `claim_line` as k INNER JOIN coding as p ON k.claiminsureditem_icdcode=p.diag_code WHERE claim_id=:claim_id');
        $checkM->bindParam(':claim_id', $claim_id, \PDO::PARAM_STR);
        $checkM->execute();
        return $checkM->fetchAll();
    }
    public function getSeamless($client)
    {
        global $conn4;
        $date = date("Y-m-d");
        $policy_cancellationdate = date('Y-m-d', strtotime($date. '  -6 months'));
        $products="('Kaelo Gap','MedExpense','Centriq Cancer','Dis-Chem Health','OLD Dis-Chem Health - Western National')";
        if($client=="Western Gap")
        {
            $products="('Western Gap','Western Gap Care','OLD Dis-Chem Health - Western National')";
        }
        elseif ($client=="Sanlam Gap")
        {
            $products="('Sanlam Gap')";
        }
        $checkM=$conn4->prepare('SELECT COUNT(*) FROM `chf` WHERE ProductName IN '.$products.' AND (policy_cancellationdate>:policy_cancellationdate OR policy_cancellationdate is null OR policy_cancellationdate =\'\')');
        $checkM->bindParam(':policy_cancellationdate', $policy_cancellationdate, \PDO::PARAM_STR);
        $checkM->execute();
        return $checkM->fetchColumn();
    }
    public function seamlessCount()
    {
        $xarr=[];
        $coon=0;
        foreach ($this->clientArr() as $product)
        {
            $count=(int)$this->getSeamless($product);
            $jarr=array("product"=>$product,"count"=>$count);
            $coon+=$count;
            array_push($xarr,$jarr);
        }
        return array("all"=>$xarr,"call"=>$coon);
    }

    public function groupBySpeciality($start_date,$end_date,$scheme_sql,$txtup,$individual)
    {
        global $conn3;
        $dat=!empty($start_date)?" AND a.claim_incidentdate >='".$start_date."' AND a.claim_incidentdate<'".$end_date."' ":"";
        $dat.=$dat.$scheme_sql.$individual;
       // $productarr=$this->clientArr();
        $main=[];

        $checkM=$conn3->prepare('SELECT disciplinecode,discipline,COUNT(DISTINCT q.claim_id) tot FROM claim_line as q INNER JOIN claim as a ON q.claim_id=a.claim_id INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN doctor_details d ON q.practice_number=d.practice_number WHERE 1 '.$dat.' GROUP BY disciplinecode ORDER BY tot '.$txtup);
        $checkM->execute();
        foreach ($checkM->fetchAll() as $row)
        {
            $sst=$conn3->prepare('SELECT Product as product,COUNT(DISTINCT q.claim_id) counts,SUM(claiminsureditem_payout) value_payout FROM claim_line as q INNER JOIN doctor_details d ON q.practice_number=d.practice_number inner join claim as a on q.claim_id=a.claim_id INNER join member as b on a.member_id=b.member_id WHERE disciplinecode=:disciplinecode '.$dat.' GROUP BY Product');
            $sst->bindParam(':disciplinecode', $row["disciplinecode"], \PDO::PARAM_STR);
            $sst->execute();
            $myp=$sst->fetchAll();
            $inarr=array("discipline"=>$row["discipline"],"disciplinecode"=>$row["disciplinecode"],"products"=>$myp);
            array_push($main,$inarr);
        }
        return $main;
    }
    public function getSearchedResults($keyword,$field,$table)
    {
        global $conn3;
        $keyword="%".$keyword."%";
        $stmt = $conn3->prepare('SELECT DISTINCT '.$field.' FROM '.$table.' WHERE '.$field.' LIKE :keyword LIMIT 5');
        $stmt->bindParam(':keyword', $keyword, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();

    }
    public function getSearchedResults1($keyword,$field,$field2,$table)
    {
        global $conn3;
        $keyword="%".$keyword."%";
        $stmt = $conn3->prepare('SELECT DISTINCT '.$field.','.$field2.' FROM '.$table.' WHERE '.$field.' LIKE :keyword OR '.$field2.' LIKE :keyword LIMIT 5');
        $stmt->bindParam(':keyword', $keyword, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();

    }
    public function getCSSICD($ccsgrouper)
    {
        global $conn3;
        $stmt = $conn3->prepare('SELECT DISTINCT diag_code,shortdesc,ccs_grouper_desc FROM coding WHERE ccs_grouper_desc=:ccs_grouper_desc LIMIT 7');
        $stmt->bindParam(':ccs_grouper_desc', $ccsgrouper, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();

    }
}