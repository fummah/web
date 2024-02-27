<?php
if(!defined('access')) {
    header("Location: logout.php");
    die();
}
class Validate
{
    function __construct()
    {
        if(!$this->isLogged() || !in_array($this->myRole(),$this->allRoles()))
        {
            header("Location: logout.php");
            die();
        }
    }
    function allRoles()
    {
        return ["super_admin","admin","finance_admin","secretary","chief_secretary","ordinary"];
    }
    function topRoles()
    {
        return ["super_admin","admin","finance_admin","chief_secretary"];
    }
    function eRoles()
    {
        return ["super_admin","finance_admin"];
    }
    function isLogged()
    {
        return isset($_SESSION['logxged']) && !empty($_SESSION['logxged'])?true:false;
    }
    function myRole()
    {
        return isset($_SESSION['role']) && !empty($_SESSION['role'])?$_SESSION['role']:"unknown";
    }
    function loggedAsID()
    {
        return $_SESSION['user_id'];
    }
    function loggedAs()
    {
        return $_SESSION['username'];
    }
    function isInternal()
    {
        return in_array($this->myRole(),$this->allRoles())?true:false;
    }
    function isTopLevel()
    {
        return in_array($this->myRole(),$this->topRoles())?true:false;
    }
    function isAdmin()
    {
        return $this->myRole()=="admin"?true:false;
    }
    function isSecretary()
    {
        return $this->myRole()=="secretary"?true:false;
    }  
    function isChiefSecretary()
    {
        return $this->myRole()=="chief_secretary"?true:false;
    }
    function isFinaceAdmin()
    {
        return $this->myRole()=="finance_admin"?true:false;
    }
    function isOrdinary()
    {
        return $this->myRole()=="ordinary"?true:false;
    }
       function getGroupID()
    {
        return (int)$_SESSION['group_id'];
    }
    function getGroupName()
    {
       return $_SESSION['group_name'];
    }
    function getGroupParents()
    {
       return $_SESSION['parent_groups'];
    }

}
include("dbconn.php");
$con_main_db = connection("zbs", "zbs");
$con_code_db = $con_main_db;

class DBConnect extends Validate
{
    public $conn;
    public $conn2;
    public $group_id;
    public $parent_groups;
    public function __construct()
    {
        parent::__construct();
        global $con_main_db;
        global $con_code_db;
        $this->conn = $con_main_db;
        $this->conn2 = $con_code_db;
        $this->group_id=$this->getGroupID();
        $this->parent_groups=$this->getGroupParents();
    }
    function funerals($pageLimit ,$setLimit,$search_value="",$count=0)
    {
        $limits = "";
        $fields = "COUNT(funeral_id)";
        if ($count == 0) {
            $limits = "ORDER BY funeral_id DESC LIMIT $pageLimit , $setLimit";
            $fields = "funeral_id,funeral_name,funeral_type,amount_paid,status,date_entered,date_closed,entered_by,contact_person,contact_person_number";
        }
        if(strlen($search_value)>0)
        {
            $search_value="%".$search_value."%";
        $sql="SELECT $fields FROM `funerals` WHERE funeral_name LIKE :searched_term AND group_id IN (".$this->parent_groups.") $limits";
        $stmt=$this->conn->prepare($sql);
        $stmt->bindParam(':searched_term', $search_value, PDO::PARAM_STR);
        }
        else
        {
        $sql="SELECT $fields FROM `funerals` WHERE group_id IN (".$this->parent_groups.") $limits";
        $stmt=$this->conn->prepare($sql); 
        }
       
        $stmt->execute();
        if ($count == 0) {
            return $stmt->fetchAll();
        } else {
            return $stmt->fetchColumn();
        }
    }
    function users($pageLimit ,$setLimit,$search_value="",$count=0)
    {
        $limits = "";
        $fields = "COUNT(user_id)";
        if ($count == 0) {
            $limits = "ORDER BY user_id DESC LIMIT $pageLimit , $setLimit";
            $fields = "user_id,username,status,a.date_entered,a.entered_by,b.location_name,a.role,a.last_name,g.group_name";
        }
        if(strlen($search_value)>0)
        {
            $search_value="%".$search_value."%";
      $sql="SELECT $fields FROM `users` as a INNER JOIN locations as b ON a.location_id=b.location_id INNER JOIN `groups` as g ON b.group_id=g.group_id WHERE a.username LIKE :searched_term $limits";        
        $stmt=$this->conn->prepare($sql);
        $stmt->bindParam(':searched_term', $search_value, PDO::PARAM_STR);
        }
        else
        {
             $sql="SELECT $fields FROM `users` as a INNER JOIN locations as b ON a.location_id=b.location_id INNER JOIN `groups` as g ON b.group_id=g.group_id $limits";        
        $stmt=$this->conn->prepare($sql);
        }
        $stmt->execute();
        if ($count == 0) {
            return $stmt->fetchAll();
        } else {
            return $stmt->fetchColumn();
        }
    }

    function subscribedMembers($pageLimit ,$setLimit,$search_value="",$count=0)
    {
        $limits = "";
        $fields = "COUNT(m.member_id)";
        if ($count == 0) {
            $limits = "ORDER BY member_id DESC LIMIT $pageLimit , $setLimit";
            $fields = "m.member_id,m.first_name,m.last_name,l.location_name,m.account_balance";
        }
        if(strlen($search_value)>0)
        {
            $search_value="%".$search_value."%";
      $sql='SELECT '.$fields.' FROM `members` as m INNER JOIN locations as l ON m.location_id=l.location_id WHERE m.status="Active" AND (first_name LIKE :keyword OR last_name LIKE :keyword OR CONCAT(first_name," ", last_name) LIKE :keyword OR CONCAT(last_name," ", first_name)) '.$limits;        
        $stmt=$this->conn->prepare($sql);
        $stmt->bindParam(':keyword', $search_value, PDO::PARAM_STR);
        }
        else
        {
             $sql="SELECT $fields FROM `members` as m INNER JOIN locations as l ON m.location_id=l.location_id WHERE m.status='Active' $limits";        
        $stmt=$this->conn->prepare($sql);
        }
        $stmt->execute();
        if ($count == 0) {
            return $stmt->fetchAll();
        } else {
            return $stmt->fetchColumn();
        }
    }
    function getUser($user_id)
    {
        $sql="SELECT a.user_id,a.username,a.status,a.date_entered,a.entered_by,b.location_name,a.location_id,a.first_name,a.last_name,a.role,a.contact_number FROM `users` as a INNER JOIN locations as b ON a.location_id=b.location_id WHERE user_id=:user_id";
        $stmt=$this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $stmt->execute();
       return $stmt->fetch();
    }
    function locations($pageLimit ,$setLimit,$search_value="",$count=0)
    {
        $limits = "";
        $fields = "COUNT(location_id)";
        if ($count == 0) {
            $limits = "ORDER BY location_id DESC LIMIT $pageLimit , $setLimit";
            $fields = "l.location_id,l.location_name,l.date_entered,l.entered_by,l.group_id,g.group_name";
        }       
        if($this->isTopLevel())
        {  
        if(strlen($search_value)>0) 
        {
            $search_value="%".$search_value."%";
            $sql="SELECT $fields FROM `locations` as l INNER JOIN `groups` as g ON l.group_id=g.group_id WHERE location_name LIKE :search_value $limits";  
        $stmt=$this->conn->prepare($sql);
        $stmt->bindParam(':search_value', $search_value, PDO::PARAM_STR);
        }
        else
        {
            $sql="SELECT $fields FROM `locations` as l INNER JOIN `groups` as g ON l.group_id=g.group_id $limits";  
        $stmt=$this->conn->prepare($sql);
    }        
        
        }
        else{
            if(strlen($search_value)>0) 
            {
                $search_value="%".$search_value."%";
               $sql="SELECT $fields FROM `locations` as l INNER JOIN `groups` as g ON l.group_id=g.group_id WHERE l.group_id=:group_id WHERE location_name LIKE :search_value $limits";  
               $stmt=$this->conn->prepare($sql); 
               $stmt->bindParam(':search_value', $search_value, PDO::PARAM_STR);
            }
            else
            {
                 $sql="SELECT $fields FROM `locations` as l INNER JOIN `groups` as g ON l.group_id=g.group_id WHERE l.group_id=:group_id  $limits";  
               $stmt=$this->conn->prepare($sql); 
            }
        
        $stmt->bindParam(':group_id', $this->group_id, PDO::PARAM_STR);
        }    
        $stmt->execute();
        if ($count == 0) {
            return $stmt->fetchAll();
        } else {
            return $stmt->fetchColumn();
        }
    }
    
    function getLatestMember($entered_by)
    {
        $stmt=$this->conn->prepare("SELECT MAX(member_id) FROM members WHERE entered_by=:entered_by");
        $stmt->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    function getLatestFuneral()
    {       
        $stmt=$this->conn->prepare("SELECT MAX(funeral_id) FROM funerals WHERE group_id IN (".$this->parent_groups.") ORDER BY funeral_id DESC");   
 //$stmt->bindParam(':group_id', $this->group_id, PDO::PARAM_STR);      
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    function searchUserBy($serch_term,$field,$table)
    {
        $stmt=$this->conn->prepare("SELECT * FROM $table WHERE $field=:search_term");
        $stmt->bindParam(':search_term', $serch_term, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
      function searchByNameAndSurname($first_name,$last_name)
    {
        $stmt=$this->conn->prepare("SELECT member_id FROM members WHERE first_name=:first_name AND last_name=:last_name");
        $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
        $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
    function isOpenFuneral($status="Open")
    {
        $stmt=$this->conn->prepare("SELECT funeral_id FROM funerals WHERE status=:status AND group_id=:group_id");
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':group_id', $this->group_id, PDO::PARAM_STR);
        $stmt->execute();
        if($stmt->rowCount()>0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    function getLocation($location_name)
    {
        $stmt=$this->conn->prepare("SELECT  *FROM locations WHERE location_name=:location_name");
        $stmt->bindParam(':location_name', $location_name, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
       function getGroups()
    {
        $stmt=$this->conn->prepare("SELECT `group_id`, `group_name`FROM `groups` WHERE group_id>1");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    function getSingleMember($member_id)
    {
        $stmt=$this->conn->prepare("SELECT `member_id`,`first_name`,`last_name`,`contact_number`,`id_number`,`email_number`,a.status,a.entered_by,a.date_entered,b.location_name,a.location_id,g.group_name,a.account_balance FROM `members` as a INNER JOIN locations as b ON a.location_id=b.location_id INNER JOIN `groups` as g ON b.group_id=g.group_id WHERE member_id=:member_id");
        $stmt->bindParam(':member_id', $member_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
    function getAllMembers($pageLimit ,$setLimit,$search_value="",$count=0)
    {
        $limits = "";
        $fields = "COUNT(member_id)";
        if ($count == 0) {
            $limits = "ORDER BY first_name ASC LIMIT $pageLimit , $setLimit";
            $fields = "`member_id`,`first_name`,`last_name`,`contact_number`,`id_number`,`email_number`,a.status,a.entered_by,a.date_entered,`location_name`";
        }
        $sql="SELECT $fields FROM `members` as a INNER JOIN locations as b ON a.location_id=b.location_id WHERE a.status<>'Funeral' AND b.group_id=:group_id $limits";
        $stmt=$this->conn->prepare($sql);
$stmt->bindParam(':group_id', $this->group_id, PDO::PARAM_STR);
        $stmt->execute();
        if ($count == 0) {
            return $stmt->fetchAll();
        } else {
            return $stmt->fetchColumn();
        }
    }
    function getPDF($status="Active")
    {
        $sql="SELECT `member_id`,`first_name`,`last_name`,`contact_number` FROM `members` as a INNER JOIN locations as b ON a.location_id=b.location_id WHERE status=:status AND b.group_id=:group_id AND member_id NOT IN(SELECT id FROM active_r WHERE group_id=:group_id) LIMIT 1000";
        $stmt=$this->conn->prepare($sql);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':group_id', $this->group_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    function getPDFNumber($status="Active")
    {
        $sql="SELECT COUNT(member_id) as total FROM `members` as a INNER JOIN locations as b ON a.location_id=b.location_id WHERE status=:status AND b.group_id=:group_id AND member_id NOT IN(SELECT id FROM active_r WHERE group_id=:group_id)";
        $stmt=$this->conn->prepare($sql);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':group_id', $this->group_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    function addTransaction($total_amount,$expenses)
    {
        try {
            $stmt = $this->conn->prepare('INSERT INTO `daily_transactions`(`total_amount`,`expenses`) VALUES (:total_amount,:expenses)');
            $stmt->bindParam(':total_amount', $total_amount, PDO::PARAM_STR);
            $stmt->bindParam(':expenses', $expenses, PDO::PARAM_STR);
            return $stmt->execute();
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }
    function addPDF($id,$first_name,$last_name,$contact_number,$a1,$a2,$a3,$a4)
    {
        try {
            $stmt = $this->conn->prepare('INSERT INTO `active_r`(`id`,`first_name`,`last_name`,`contact_number`,`a1`,`a2`,`a3`,`a4`,`group_id`) VALUES (:id,:first_name,:last_name,:contact_number,:a1,:a2,:a3,:a4,:group_id)');
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
            $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
            $stmt->bindParam(':contact_number', $contact_number, PDO::PARAM_STR);
            $stmt->bindParam(':a1', $a1, PDO::PARAM_STR);
            $stmt->bindParam(':a2', $a2, PDO::PARAM_STR);
            $stmt->bindParam(':a3', $a3, PDO::PARAM_STR);
            $stmt->bindParam(':a4', $a4, PDO::PARAM_STR);
            $stmt->bindParam(':group_id', $this->group_id, PDO::PARAM_STR);
            return $stmt->execute();
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }

    function addLocation($location_name,$entered_by)
    {
        try {
            $stmt = $this->conn->prepare('INSERT INTO `locations`(`location_name`,`entered_by`,`group_id`) VALUES (:location_name,:entered_by,:group_id)');
            $stmt->bindParam(':location_name', $location_name, PDO::PARAM_STR);
            $stmt->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
            $stmt->bindParam(':group_id', $this->group_id, PDO::PARAM_STR);
            return $stmt->execute();
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }
    function addUsers($username,$password,$entered_by,$location_id,$first_name,$last_name,$role)
    {
        try {
            $stmt = $this->conn->prepare('INSERT INTO `users`(`username`,`password`,`entered_by`,`location_id`,`first_name`,`last_name`,`role`) VALUES (:username,:password,:entered_by,:location_id,:first_name,:last_name,:role)');
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
            $stmt->bindParam(':location_id', $location_id, PDO::PARAM_STR);
            $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
            $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
            $stmt->bindParam(':role', $role, PDO::PARAM_STR);
            return $stmt->execute();
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }
    function deleteRegister($funeral_id,$member_id)
    {
        try {
            $this->insertRegisterLogs($funeral_id,$member_id);
            $stmt = $this->conn->prepare('DELETE FROM register WHERE funeral_id=:funeral_id AND member_id=:member_id');
            $stmt->bindParam(':funeral_id', $funeral_id, PDO::PARAM_STR);
            $stmt->bindParam(':member_id', $member_id, PDO::PARAM_STR);
            return $stmt->execute();
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }
    function addMember($first_name,$last_name,$contact_number,$id_number,$email_number,$entered_by,$location_id,$status="Active")
    {
        try {
            $stmt = $this->conn->prepare('INSERT INTO `members`(`first_name`,`last_name`,`contact_number`,`id_number`,`email_number`,`entered_by`,`location_id`,`status`) VALUES (:first_name,:last_name,:contact_number,:id_number,:email_number,:entered_by,:location_id,:status)');
            $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
            $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
            $stmt->bindParam(':contact_number', $contact_number, PDO::PARAM_STR);
            $stmt->bindParam(':id_number', $id_number, PDO::PARAM_STR);
            $stmt->bindParam(':email_number', $email_number, PDO::PARAM_STR);
            $stmt->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
            $stmt->bindParam(':location_id', $location_id, PDO::PARAM_STR);
$stmt->bindParam(':status', $status, PDO::PARAM_STR);
            return $stmt->execute();
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }
function addDeceased($funeral_id,$member_id,$_type,$date_of_death,$contact_person,$entered_by,$contact_person_number)
    {
        try {
            $stmt = $this->conn->prepare('INSERT INTO `deceased`(`funeral_id`,`member_id`,`_type`,`date_of_death`,`contact_person`,`entered_by`,`contact_person_number`) VALUES (:funeral_id,:member_id,:_type,:date_of_death,:contact_person,:entered_by,:contact_person_number)');
            $stmt->bindParam(':funeral_id', $funeral_id, PDO::PARAM_STR);
            $stmt->bindParam(':member_id', $member_id, PDO::PARAM_STR);
            $stmt->bindParam(':_type', $_type, PDO::PARAM_STR);
            $stmt->bindParam(':date_of_death', $date_of_death, PDO::PARAM_STR);
            $stmt->bindParam(':contact_person', $contact_person, PDO::PARAM_STR);
            $stmt->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
            $stmt->bindParam(':contact_person_number', $contact_person_number, PDO::PARAM_STR);
            return $stmt->execute();
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }
    function addDepedency($member_id,$first_name,$surname,$entered_by,$d_o_b,$status)
    {
        try {
            $stmt = $this->conn->prepare('INSERT INTO `dependencies`(`member_id`,`first_name`,`surname`,`entered_by`,`d_o_b`,`status`) VALUES (:member_id,:first_name,:surname,:entered_by,:d_o_b,:status)');
            $stmt->bindParam(':member_id', $member_id, PDO::PARAM_STR);
            $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
            $stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
            $stmt->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
            $stmt->bindParam(':d_o_b', $d_o_b, PDO::PARAM_STR);
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            return $stmt->execute();
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }
    function getDependencies($member_id)
    {
        $stmt=$this->conn->prepare("SELECT * FROM `dependencies` WHERE member_id=:member_id");
        $stmt->bindParam(':member_id', $member_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    function addFuneral($funeral_name,$amount_paid,$date_closed,$entered_by,$funeral_type,$contact_person,$contact_person_number)
    {
        try {
            $stmt = $this->conn->prepare('INSERT INTO `funerals`(`funeral_name`,`amount_paid`,`date_closed`,`entered_by`,`funeral_type`,`contact_person`,`contact_person_number`,`group_id`) VALUES (:funeral_name,:amount_paid,:date_closed,:entered_by,:funeral_type,:contact_person,:contact_person_number,:group_id)');
            $stmt->bindParam(':funeral_name', $funeral_name, PDO::PARAM_STR);
            $stmt->bindParam(':amount_paid', $amount_paid, PDO::PARAM_STR);
            $stmt->bindParam(':date_closed', $date_closed, PDO::PARAM_STR);
            $stmt->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
            $stmt->bindParam(':funeral_type', $funeral_type, PDO::PARAM_STR);     
            $stmt->bindParam(':contact_person', $contact_person, PDO::PARAM_STR);
            $stmt->bindParam(':contact_person_number', $contact_person_number, PDO::PARAM_STR);
            $stmt->bindParam(':group_id', $this->group_id, PDO::PARAM_STR);
            return $stmt->execute();
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
        finally{
            $this->emptyActive();
        }
    }
    private function checkRegister($member_id,$funeral_id)
    {
        $stmt=$this->conn->prepare("SELECT funeral_id,member_id FROM `register` WHERE funeral_id=:funeral_id AND member_id=:member_id");
        $stmt->bindParam(':funeral_id', $funeral_id, PDO::PARAM_STR);
        $stmt->bindParam(':member_id', $member_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }

    function addRegister($member_id,$funeral_id,$entered_by,$status)
    {
        try {
            if($this->checkRegister($member_id,$funeral_id))
            {
                return 0;
            }
            else {
                $stmt = $this->conn->prepare('INSERT INTO `register`(`member_id`,`funeral_id`,`entered_by`,`status`) VALUES (:member_id,:funeral_id,:entered_by,:status)');
                $stmt->bindParam(':member_id', $member_id, PDO::PARAM_STR);
                $stmt->bindParam(':funeral_id', $funeral_id, PDO::PARAM_STR);
                $stmt->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
                $stmt->bindParam(':status', $status, PDO::PARAM_STR);
                return $stmt->execute();
            }
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }
    function getFuneralById($funeral_id)
    {
        $stmt=$this->conn->prepare("SELECT funeral_id,funeral_name,funeral_type,amount_paid,status,date_entered,date_closed,entered_by,contact_person,contact_person_number FROM `funerals` WHERE funeral_id=:funeral_id");
        $stmt->bindParam(':funeral_id', $funeral_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();

    }
    function getPayments($funeral_id)
    {
        $stmt=$this->conn->prepare("SELECT `id`,`funeral_id`,`date_entered`, `total_amount`, `expenses` FROM `daily_transactions` WHERE funeral_id=:funeral_id");
        $stmt->bindParam(':funeral_id', $funeral_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    function getFuneraAmt($funeral_id)
    {
        $stmt=$this->conn->prepare("SELECT SUM(actual_amount) as total_amount FROM `funeral_transactions` WHERE funeral_id=:funeral_id");
        $stmt->bindParam(':funeral_id', $funeral_id, PDO::PARAM_STR);
        $stmt->execute();
        return (double)$stmt->fetchColumn();
    }
    function countTicks($funeral_id,$status)
    {
        $stmt=$this->conn->prepare("SELECT COUNT(funeral_id) as total FROM register WHERE funeral_id=:funeral_id AND status=:status");
        $stmt->bindParam(':funeral_id', $funeral_id, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    function getFuneralHighDetails($funeral_id)
    {
        $data=$this->getFuneralById($funeral_id);
        $green_tick=(int)$this->countTicks($funeral_id,"paid")-(int)$this->getTotalExs($funeral_id);
        $red_tick=$this->countTicks($funeral_id,"unpaid");
        $home=$this->countTicks($funeral_id,"home");
        $total_amount=$green_tick*$data["amount_paid"];
        $arr=array("funeral_name"=>$data["funeral_name"],"funeral_type"=>$data["funeral_type"],"date_entered"=>$data["date_entered"],"entered_by"=>$data["entered_by"],"date_closed"=>$data["date_closed"],
            "amount_paid"=>$data["amount_paid"],"status"=>$data["status"],"green_tick"=>$green_tick,"red_tick"=>$red_tick,
            "home"=>$home,"total_amount"=>$total_amount,"actual_amount"=>0);
        return $arr;
    }
    function editDiff($key,$value,$search_key,$search_key_value,$table)
    {
        try {
            $stmt = $this->conn->prepare("UPDATE $table SET $key=:mykey WHERE $search_key=:search_key");
            $stmt->bindParam(':mykey', $value, PDO::PARAM_STR);
            $stmt->bindParam(':search_key', $search_key_value, PDO::PARAM_STR);
            return $stmt->execute();
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }
    function getIndividualFunerals($start_from,$limit)
    {
        $stmt=$this->conn->prepare("SELECT funeral_id,amount_paid,date_entered,
       contact_person,contact_person_number,funeral_name,status,d_o_d FROM funerals WHERE group_id IN (".$this->parent_groups.") ORDER BY funeral_id DESC LIMIT $start_from,$limit");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    function getPDFFunerals($start_from,$limit)
    {
        $stmt=$this->conn->prepare("SELECT funeral_id FROM funerals WHERE group_id IN (".$this->parent_groups.") ORDER BY funeral_id DESC LIMIT $start_from,$limit");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    function emptyActive()
    {
        $stmt=$this->conn->prepare("DELETE FROM active_r WHERE group_id=:group_id");
        $stmt->bindParam(':group_id', $this->group_id, PDO::PARAM_STR);
        return $stmt->execute();

    }
    function getPDFx()
    {
        $stmt=$this->conn->prepare("SELECT * FROM active_r WHERE group_id=:group_id ORDER BY first_name,last_name ASC");
        $stmt->bindParam(':group_id', $this->group_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    function getExs($funeral_id)
    {
        $stmt=$this->conn->prepare("SELECT member_id FROM members WHERE member_id NOT IN(SELECT member_id FROM `register` WHERE funeral_id=:funeral_id) AND status='Active'");
        $stmt->bindParam(':funeral_id', $funeral_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    function getMarkTotal($funeral_id,$status)
    {
        $stmt=$this->conn->prepare("SELECT member_id FROM register WHERE funeral_id=:funeral_id AND status=:status");
        $stmt->bindParam(':funeral_id', $funeral_id, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount();
    }
    function getRegister($funeral_id,$member_id)
    {
        $stmt=$this->conn->prepare("SELECT `date_entered`,`entered_by`,`status` FROM `register` WHERE funeral_id=:funeral_id AND member_id=:member_id");
        $stmt->bindParam(':funeral_id', $funeral_id, PDO::PARAM_STR);
        $stmt->bindParam(':member_id', $member_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
        function getRegisterPDF($funeral_id,$member_id)
    {
        $stmt=$this->conn->prepare("SELECT `status` FROM `register` WHERE funeral_id=:funeral_id AND member_id=:member_id");
        $stmt->bindParam(':funeral_id', $funeral_id, PDO::PARAM_STR);
        $stmt->bindParam(':member_id', $member_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
    function updateRegister($funeral_id,$member_id,$statt)
    {
        $this->insertRegisterLogs($funeral_id,$member_id);
        $stmt=$this->conn->prepare("UPDATE `register` SET status=:status WHERE funeral_id=:funeral_id AND member_id=:member_id");
        $stmt->bindParam(':funeral_id', $funeral_id, PDO::PARAM_STR);
        $stmt->bindParam(':member_id', $member_id, PDO::PARAM_STR);
        $stmt->bindParam(':status', $statt, PDO::PARAM_STR);
        return $stmt->execute();
    }
      function insertRegisterLogs($funeral_id,$member_id)
    {
         $stmt=$this->conn->prepare("SELECT status FROM `register` WHERE funeral_id=:funeral_id AND member_id=:member_id");
        $stmt->bindParam(':funeral_id', $funeral_id, PDO::PARAM_STR);
        $stmt->bindParam(':member_id', $member_id, PDO::PARAM_STR);
        $stmt->execute();
        if($stmt->rowCount()>0)
        {
        $status=$stmt->fetchColumn();
        $entered_by=$this->loggedAs();
        $stmt=$this->conn->prepare("INSERT INTO `register_logs`(member_id,funeral_id,entered_by,status) VALUES(:member_id,:funeral_id,:entered_by,:status)");
        $stmt->bindParam(':funeral_id', $funeral_id, PDO::PARAM_STR);
        $stmt->bindParam(':member_id', $member_id, PDO::PARAM_STR);
        $stmt->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->execute();
        }

       
    }
    function getTotalFunerals()
    {
        $stmt=$this->conn->prepare("SELECT COUNT(funeral_id) FROM `funerals` WHERE group_id IN (".$this->parent_groups.")");
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    function getTotalAmounts()
    {
        $stmt=$this->conn->prepare("SELECT SUM(total_amount) FROM `daily_transactions`");
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    function sumAmounts($funeral_id)
    {
        $stmt=$this->conn->prepare("SELECT SUM(total_amount) as tot_amount,SUM(expenses) as tot_expenses FROM `daily_transactions` WHERE funeral_id=:funeral_id");
        $stmt->bindParam(':funeral_id', $funeral_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
    function getReportTrend()
    {
        $xarray=array();
        $stmt=$this->conn->prepare("SELECT a.funeral_id,b.funeral_name,COUNT(*) as total,COUNT(CASE WHEN a.status='paid' THEN 1 END) as total_paid,COUNT(CASE WHEN a.status='unpaid' THEN 1 END) as total_unpaid,COUNT(CASE WHEN a.status='home' THEN 1 END) as total_home FROM `register` as a INNER JOIN funerals as b ON a.funeral_id=b.funeral_id WHERE group_id IN (".$this->parent_groups.") GROUP BY a.funeral_id ORDER BY a.register_id DESC LIMIT 6");
        $stmt->execute();
        foreach($stmt->fetchAll() as $row)
        {
            $funeral_id = $row["funeral_id"];
            $paidx = (double)$row["total_paid"]-(int)$this->getTotalExs($funeral_id);
            $inarr = array("funeral_id"=>$funeral_id,"funeral_name"=>$row["funeral_name"],"total"=>$row["total"],"total_paid"=>$paidx,"total_unpaid"=>$row["total_unpaid"],"total_home"=>$row["total_home"]);
        array_push($xarray,$inarr);
        }
        return $xarray;
    }
    function getLocationsReport($funeral_id)
    {
        $stmt=$this->conn->prepare("SELECT l.location_id,l.location_name,COUNT(*) as total,COUNT(CASE WHEN a.status='paid' THEN 1 END) as total_paid,COUNT(CASE WHEN a.status='unpaid' THEN 1 END) as total_unpaid,COUNT(CASE WHEN a.status='home' THEN 1 END) as total_home,SUM(CASE WHEN a.status='paid' THEN b.amount_paid END) as total_expected,t.expenses,t.actual_amount,t.ex,b.amount_paid,b.undertaker_name,b.undertaker_cost,b.other_costs,b.bank_charges, b.system_cost FROM `register` as a INNER JOIN funerals as b ON a.funeral_id=b.funeral_id INNER JOIN members as c ON a.member_id=c.member_id INNER JOIN locations as l ON c.location_id=l.location_id LEFT JOIN funeral_transactions as t ON a.funeral_id=t.funeral_id AND c.location_id=t.location_id WHERE a.funeral_id=:funeral_id AND l.group_id=:group_id GROUP BY l.location_name ORDER BY total_expected DESC;");
        $stmt->bindParam(':funeral_id', $funeral_id, PDO::PARAM_STR);
        $stmt->bindParam(':group_id', $this->group_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    function getStatusIcon($status)
    {
        $output="";
        if($status=="paid")
        {
            $output="<span uk-icon=\"check\" class='uk-icon-button' style=\"color: limegreen\"></span>";
        }
        elseif ($status=="unpaid")
        {
            $output="<span uk-icon=\"close\" class='uk-icon-button' style=\"color: red\"></span>";
        }
        elseif ($status=="home")
        {
            $output="<span uk-icon=\"home\" class='uk-icon-button' style=\"color: cadetblue\"></span>";
        }
        return $output;
    }
    public function getSearchedMembers($keyword)
    {
        $keyword="%".$keyword."%";
        $stmt = $this->conn2->prepare('SELECT member_id,first_name,last_name,contact_number,g.group_name FROM `members` as a INNER JOIN locations as b ON a.location_id=b.location_id INNER JOIN `groups` as g ON b.group_id=g.group_id WHERE first_name LIKE :keyword OR last_name LIKE :keyword OR contact_number LIKE :keyword OR CONCAT(first_name," ", last_name) LIKE :keyword OR CONCAT(last_name," ", first_name) LIKE :keyword LIMIT 20');
        $stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);
        //$stmt->bindParam(':group_id', $this->group_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function getSearchedFuneral($keyword)
    {
        $keyword="%".$keyword."%";
        $stmt = $this->conn2->prepare('SELECT funeral_name,funeral_id FROM `funerals` WHERE funeral_name LIKE :keyword AND group_id IN ('.$this->parent_groups.') LIMIT 5');
        $stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function getMarkers($funeral_id)
    {
        $stmt = $this->conn2->prepare('select `entered_by`, COUNT(entered_by) as total from `register` where `funeral_id` = :funeral_id group by `entered_by` ORDER BY total DESC;');
        $stmt->bindParam(':funeral_id', $funeral_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function insertLogs($member_id,$entered_by)
    {
        $stmt = $this->conn2->prepare("INSERT INTO `members_logs`(`member_id`, `first_name`, `last_name`, `contact_number`, `id_number`, `email_number`, `status`, `entered_by`, `date_entered`, `location_id`, `new_entered_by`) 
SELECT *,:entered_by FROM members WHERE member_id=:member_id");
        $stmt->bindParam(':member_id', $member_id, PDO::PARAM_STR);
        $stmt->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
        return $stmt->execute();
    }
    function loadMemberT($member_id,$first_name,$last_name,$contact_number,$status,$mem_arr=array())
    {
        $actvcolor="";
        $disable="";
        $hidden="";
        if($status!="Active")
        {
            $actvcolor="brown";
            $disable="disabled";
            $hidden="hidden";
        }
        $statusicon=$status=="Active"?"<span class=\"uk-badge\">$status</span>":"<span class=\"uk-badge\" style='background-color: goldenrod !important;'>$status</span>";
        $ischecked="";
        $ishome="";
        $contact_number=$this->formatPhone($contact_number);
        echo "<tr class='et_pb_textr $member_id' style='color: $actvcolor;'><td class='not_mobile'><span class=\"uk-badge\" style='background-color: #0b8278 !important;'>$member_id</span></td><td class='maintxt not_mobile'>$first_name</td><td class='maintxt not_mobile'>$last_name</td>
<td class='maintxt not_mobile'>$contact_number</td><td class='not_mobile'>$statusicon</td>";
        echo "<td class='not_desktop'><span class=\"uk-badge\" style='background-color: #0b8278'>$member_id</span> <span style='color: #0b8278' uk-icon='chevron-double-right'></span> <b style='color: #0b8278'>$first_name $last_name</b> <br>$statusicon <span style='color: #0b8278' uk-icon='chevron-double-right'></span> <a style='color: green !important; font-weight: bold' href=\"tel:$contact_number\">$contact_number</a></td>";
        $xconmobile="";
        foreach($mem_arr as $rowx)
        {
            $icon="<span uk-icon='minus-circle' class='uk-icon-button' style='color: darkgrey'></span>";
            $funeral_id=$rowx["funeral_id"];
            $funeral_name=$rowx["funeral_name"];           
            $register_arr=$this->getRegister($funeral_id,$member_id);
            if($rowx["status"]=="Open")
            {
                if($register_arr==true)
                {
                    $ischecked=$register_arr["status"]=="paid"?"checked":"";
                    $ishome=$register_arr["status"]=="home"?"checked":"";
                }
                continue;
            }
            if($register_arr==true)
            {
                $payment_status=$register_arr["status"];
                $icon=$this->getStatusIcon($payment_status);
            }
            echo "<td class='f1 not_mobile'>$icon</td>";
            $xconmobile.="<span class='not_desktop' title='$funeral_name'>$icon</span>";
        }
        echo "<td class='f1 not_desktop'><span style='color: #0b8278' uk-icon='arrow-right'></span> $xconmobile</td>";
        $fid=$funeral_id."_".$member_id;
        $fidx=$fid."_x";
        if($this->isOpenFuneral())
        {

            echo"<td class='f2'>
<label class=\"\"><input class=\"uk-checkbox funeral_check\" type=\"checkbox\" id='$fid' onclick='tickMark(\"$fid\")' $ischecked $disable>
<span class=\"\"></span></label>
</td>";
        }
        echo"<td class='f2'>
<ul class=\"uk-iconnav\">";
        if(in_array($this->myRole(),$this->topRoles())) {
            echo "<li title='Add Funeral'><a href='add_funeral.php' uk-icon=\"icon: plus-circle\" style='color: green !important;'></a></li>";
            echo " <li title='Delete Member' onclick='deleteMember(\"$member_id\",\"$first_name\",\"$last_name\")'><a uk-icon=\"icon: trash\" style='color: green !important;'></a></li> ";
             }
        if($this->isSecretary() || in_array($this->myRole(),$this->topRoles()))
        {
            echo " <li title='Edit Member' onclick='editMember(\"$member_id\",\"$first_name\",\"$last_name\")'><a uk-icon=\"icon: file-edit\" style='color: green !important;'></a></li>";

        }
        echo"<li title='View More Details' onclick='loadMemberDetails(\"$member_id\")'><a uk-icon=\"icon: more\" style='color: green !important;'></a></li>    
      
</ul>
</td><td>
<label class=\"\"><input class=\"uk-checkbox home_check\" type=\"checkbox\" id='$fidx' onclick='tickHome(\"$fidx\")' $ishome $disable>
<span class=\"\"></span></label>
</td></tr>";
    }
    function addAmounts($funeral_id,$total_amount,$expenses)
    {
        try {

            $stmt = $this->conn->prepare('INSERT INTO `daily_transactions`(`funeral_id`,`total_amount`,`expenses`) VALUES (:funeral_id,:total_amount,:expenses)');
            $stmt->bindParam(':funeral_id', $funeral_id, PDO::PARAM_STR);
            $stmt->bindParam(':total_amount', $total_amount, PDO::PARAM_STR);
            $stmt->bindParam(':expenses', $expenses, PDO::PARAM_STR);
            return $stmt->execute();
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }
    function deleteMember($member_id)
    {
        try {

            $stmt = $this->conn->prepare('DELETE FROM members WHERE member_id=:member_id');
            $stmt->bindParam(':member_id', $member_id, PDO::PARAM_STR);

            return $stmt->execute();
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }
    function getmemberType($status="Active")
    {
        try {
            $stmt = $this->conn->prepare('SELECT COUNT(*) FROM members as a INNER JOIN locations as b ON a.location_id=b.location_id WHERE status=:status AND b.group_id=:group_id');
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            $stmt->bindParam(':group_id', $this->group_id, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }
    function getMarkersByDay($funeral_id,$entered_by)
    {
        try {
            $stmt = $this->conn->prepare("SELECT DATE_FORMAT(date_entered, '%Y-%m-%d') as date_entered,COUNT(*) as total FROM `register` WHERE funeral_id=:funeral_id AND entered_by=:entered_by GROUP BY DATE_FORMAT(date_entered, '%Y-%m-%d') ORDER BY date_entered DESC;");
            $stmt->bindParam(':funeral_id', $funeral_id, PDO::PARAM_STR);
            $stmt->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }
    function getMarkersByDayAll($funeral_id)
    {
        try {
            $stmt = $this->conn->prepare("SELECT DATE_FORMAT(date_entered, '%Y-%m-%d') as date_entered,COUNT(*) as total FROM `register` WHERE funeral_id=:funeral_id GROUP BY DATE_FORMAT(date_entered, '%Y-%m-%d') ORDER BY date_entered DESC;");
            $stmt->bindParam(':funeral_id', $funeral_id, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }
    function getMarkersByDayMembers($funeral_id,$entered_by,$date)
    {
        try {
            $date="%".$date."%";
            $stmt = $this->conn->prepare("SELECT b.first_name,b.last_name,a.date_entered,a.status FROM `register` as a INNER JOIN members as b ON a.member_id=b.member_id WHERE funeral_id=:funeral_id AND a.date_entered LIKE :dat AND a.entered_by=:entered_by ORDER BY b.first_name ASC;");
            $stmt->bindParam(':funeral_id', $funeral_id, PDO::PARAM_STR);
            $stmt->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
            $stmt->bindParam(':dat', $date, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }
    function getLocationMembers($location_id)
    {
        try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) as location FROM `members` WHERE location_id=:location_id");
            $stmt->bindParam(':location_id', $location_id, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }
    public function moneyformat($value)
    {
        if ($value !== null) {
    $formattedValue = number_format($value, 2); 
} else {

    $formattedValue = $value; 
}
        return $formattedValue;
    }
    public function formatPhone($input)
    {
        $input=$input==null?"":$input;
        return substr($input, 0, 3)." ".substr($input, 3, 2)  . " " . substr($input, -7, -4) . " " . substr($input, -4);

    }
    function getFuneralsTrans($funeral_id,$location_id)
    {
        try {
            $stmt = $this->conn->prepare("SELECT funeral_id,location_id FROM `funeral_transactions` WHERE funeral_id=:funeral_id AND location_id=:location_id");
            $stmt->bindParam(':funeral_id', $funeral_id, PDO::PARAM_STR);
            $stmt->bindParam(':location_id', $location_id, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }
    function insertFuneralsTrans($funeral_id,$location_id,$amount,$expenses,$entered_by,$ex)
    {
        try {
            $stmt = $this->conn->prepare("INSERT INTO funeral_transactions(funeral_id,location_id,actual_amount,expenses,entered_by,ex) VALUES(:funeral_id,:location_id,:amount,:expenses,:entered_by,:ex)");
            $stmt->bindParam(':funeral_id', $funeral_id, PDO::PARAM_STR);
            $stmt->bindParam(':location_id', $location_id, PDO::PARAM_STR);
            $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
            $stmt->bindParam(':expenses', $expenses, PDO::PARAM_STR);
            $stmt->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
            $stmt->bindParam(':ex', $ex, PDO::PARAM_STR);
            return (int)$stmt->execute();
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }
    function getRoles()
    {
        try {
            $stmt = $this->conn->prepare("SELECT *FROM roles");
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }
    function updateFuneralsTrans($funeral_id,$location_id,$amount,$expenses,$entered_by,$ex)
    {
        try {
            $stmt = $this->conn->prepare("UPDATE funeral_transactions SET actual_amount=:amount,expenses=:expenses,entered_by=:entered_by,ex=:ex WHERE funeral_id=:funeral_id AND location_id=:location_id");
            $stmt->bindParam(':funeral_id', $funeral_id, PDO::PARAM_STR);
            $stmt->bindParam(':location_id', $location_id, PDO::PARAM_STR);
            $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
            $stmt->bindParam(':expenses', $expenses, PDO::PARAM_STR);
            $stmt->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
            $stmt->bindParam(':ex', $ex, PDO::PARAM_STR);
            return (int)$stmt->execute();
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }
    function updateUser($user_id,$key,$value)
    {
        $sql="UPDATE users SET $key=:value WHERE user_id=:user_id";
        $stmt=$this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $stmt->bindParam(':value', $value, PDO::PARAM_STR);
        return $stmt->execute();
    }
    function updateFuneral($funeral_id,$key,$value)
    {
        $sql="UPDATE funerals SET $key=:value WHERE funeral_id=:funeral_id";
        $stmt=$this->conn->prepare($sql);
        $stmt->bindParam(':funeral_id', $funeral_id, PDO::PARAM_STR);
        $stmt->bindParam(':value', $value, PDO::PARAM_STR);
        return $stmt->execute();
    }
       function deleteR($member_id)
    {
        $sql="DELETE FROM active_r WHERE id=:member_id";
        $stmt=$this->conn->prepare($sql);
        $stmt->bindParam(':member_id', $member_id, PDO::PARAM_STR);
        return $stmt->execute();
    }
function getMemberLoader($first_name,$last_name)
    {
        try {
            $stmt = $this->conn->prepare("SELECT member_id,first_name,last_name,b.group_id,a.contact_number FROM `members` as a INNER JOIN locations as b ON a.location_id=b.location_id WHERE first_name=:first_name AND last_name=:last_name AND b.group_id=:group_id LIMIT 1");
            $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
            $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
            $stmt->bindParam(':group_id', $this->group_id, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch();
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }
       function checkMarked($funeral_id,$member_id)
    {
        try {
            $stmt = $this->conn->prepare("SELECT register_id FROM `register` WHERE funeral_id=:funeral_id AND member_id=:member_id LIMIT 1");
            $stmt->bindParam(':funeral_id', $funeral_id, PDO::PARAM_STR);
            $stmt->bindParam(':member_id', $member_id, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch();
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }
    
    function myAddPDF($member_id, $first_name,$last_name,$contact_number)
    {
        
$mem_arr1=array_reverse($this->getPDFFunerals(0,4));    
    $contact_number = $this->formatPhone($contact_number);
    $arr=[];

    foreach ($mem_arr1 as $rowx) {
        $icon = "-";
        $funeral_id = $rowx["funeral_id"];
        $register_arr = $this->getRegister($funeral_id, $member_id);        
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

    $this->addPDF($member_id,$first_name,$last_name,$contact_number,$arr[0],$arr[1],$arr[2],$arr[3]);

}
function getDeceased($funeral_id)
    {
        try {
            $stmt = $this->conn->prepare("SELECT a.member_id,a.first_name,a.last_name,b.group_id,a.contact_number,b.location_name,d._type,date_of_death,contact_person,contact_person_number FROM deceased as d INNER JOIN `members` as a ON d.member_id=a.member_id INNER JOIN locations as b ON a.location_id=b.location_id WHERE d.funeral_id=:funeral_id");
            $stmt->bindParam(':funeral_id', $funeral_id, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }
    function getGroupInfo($group_id)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM `groups` WHERE group_id=:group_id");
            $stmt->bindParam(':group_id', $group_id, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch();
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }
        
        function stampExs($funeral_id,$entered_by)
    {
        try {
            $sql="INSERT INTO register(member_id,funeral_id,entered_by,status) SELECT member_id,$funeral_id,\"$entered_by\",\"unpaid\" FROM members as a INNER JOIN locations as b ON a.location_id=b.location_id WHERE member_id NOT IN(SELECT member_id FROM register WHERE funeral_id=$funeral_id) AND status=\"Active\" AND b.group_id=".$this->group_id;
            $stmt = $this->conn->prepare($sql);
            //$stmt->bindParam(':group_id', $this->group_id, PDO::PARAM_STR);
            //$stmt->bindParam(':funeral_id', $funeral_id, PDO::PARAM_STR);         
            return $stmt->execute();           
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }
     function getIndLogs($member_id)
    {
        try {
            $sql="SELECT m.member_id,m.first_name,m.last_name,m.contact_number,m.new_entered_by,m.new_date_entered,l.location_name,g.group_name FROM `members_logs` as m INNER JOIN locations as l ON m.location_id=l.location_id INNER JOIN groups as g ON l.group_id=g.group_id WHERE m.member_id=:member_id ORDER BY m.new_entered_by DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':member_id', $member_id, PDO::PARAM_STR);         
            $stmt->execute(); 
            return $stmt->fetchAll();          
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }
    function getWebVisitors($date)
    {
        $created_at="%".$date."%";
        try {
            $sql="SELECT v.member_id,v.ipaddr,v.created_at,m.first_name,m.last_name,l.location_name,g.group_name FROM `visit_logs` as v INNER JOIN members as m ON v.member_id=m.member_id INNER JOIN locations as l ON m.location_id=l.location_id INNER JOIN groups as g ON l.group_id=g.group_id WHERE created_at LIKE :created_at";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':created_at', $created_at, PDO::PARAM_STR);         
            $stmt->execute(); 
            return $stmt->fetchAll();          
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }
        function deleteDependent($dependency_id)
    {
              try {
            $sql="DELETE FROM dependencies WHERE dependency_id=:dependency_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':dependency_id', $dependency_id, PDO::PARAM_STR);         
            return $stmt->execute(); 
                  
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }
    function insertDependencyLogs($dependency_id,$entered_by)
    {
              try {
//echo $dependency_id."----".$entered_by;
            $stmt = $this->conn->prepare("INSERT INTO `dependency_logs`(`dependent_id`, `member_id`, `first_name`, `surname`, `status`, `entered_by`) 
SELECT `dependency_id`, `member_id`, `first_name`, `surname`, `status`,:entered_by FROM dependencies WHERE dependency_id=:dependency_id");
        $stmt->bindParam(':dependency_id', $dependency_id, PDO::PARAM_STR);
        $stmt->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
        return $stmt->execute();
                  
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }
    function insertNotification($member_id,$message,$entered_by,$title)
    {
              try {
            $stmt = $this->conn->prepare("INSERT INTO `notifications`(`member_id`, `message`,`entered_by`, `title`) VALUES (:member_id,:message,:entered_by,:title)");

        $stmt->bindParam(':member_id', $member_id, PDO::PARAM_STR);
        $stmt->bindParam(':message', $message, PDO::PARAM_STR);
        $stmt->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        return $stmt->execute();
                  
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }
    function insertTranctions($member_id,$amount,$entered_by,$transaction_name,$funeral_id)
    {
              try {
            $stmt = $this->conn->prepare("INSERT INTO `advance_payments`(`member_id`, `amount`,`entered_by`, `transaction_name`,`funeral_id`) VALUES (:member_id,:amount,:entered_by,:transaction_name,:funeral_id)");

        $stmt->bindParam(':member_id', $member_id, PDO::PARAM_STR);
        $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
        $stmt->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
        $stmt->bindParam(':transaction_name', $transaction_name, PDO::PARAM_STR);
        $stmt->bindParam(':funeral_id', $funeral_id, PDO::PARAM_STR);
        return $stmt->execute();
                  
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }
    function getConfig()
    {
              try {
            $stmt = $this->conn->prepare("SELECT *FROM configs LIMIT 1") ;
            $stmt->execute();
        return $stmt->fetch();
                  
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }
    function getTransaction($member_id)
    {
              try {
            $stmt = $this->conn->prepare("SELECT *FROM advance_payments WHERE member_id=:member_id ORDER BY id DESC LIMIT 100");
            $stmt->bindParam(':member_id', $member_id, PDO::PARAM_STR);
            $stmt->execute();
        return $stmt->fetchAll();
                  
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }
    function getAdvanceSummary()
    {
              try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) as total,SUM(account_balance) as sum_total FROM members WHERE account_balance>0");
           
            $stmt->execute();
        return $stmt->fetch();
                  
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }
    function getTransEx($funeral_id,$location_id)
    {
              try {
            $stmt = $this->conn->prepare("SELECT SUM(amount) as amount,COUNT(a.member_id) as total FROM `advance_payments` as a INNER JOIN members as m ON a.member_id=m.member_id WHERE funeral_id=:funeral_id AND m.location_id=:location_id");
            $stmt->bindParam(':funeral_id', $funeral_id, PDO::PARAM_STR);
            $stmt->bindParam(':location_id', $location_id, PDO::PARAM_STR);
            $stmt->execute();
        return $stmt->fetch();
                  
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }

    function getTransExSummary($funeral_id)
    {
              try {
            $stmt = $this->conn->prepare("SELECT SUM(amount) as amount,COUNT(a.member_id) as total FROM `advance_payments` as a INNER JOIN members as m ON a.member_id=m.member_id WHERE funeral_id=:funeral_id");
            $stmt->bindParam(':funeral_id', $funeral_id, PDO::PARAM_STR);
            $stmt->execute();
        return $stmt->fetch();
                  
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }

    function getTotalExs($funeral_id)
    {
              try {
            $stmt = $this->conn->prepare("SELECT SUM(ex) as amount FROM `funeral_transactions` WHERE funeral_id=:funeral_id");
            $stmt->bindParam(':funeral_id', $funeral_id, PDO::PARAM_STR);
            $stmt->execute();
        return $stmt->fetchColumn();
                  
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }

    function getOpenFunerals()
    {
              try {
            $stmt = $this->conn->prepare("SELECT `funeral_id`, `funeral_name`, `group_id`,`amount_paid` FROM `funerals` WHERE status='Open'");
            $stmt->execute();
        return $stmt->fetchAll();
                  
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }

    function getWithBal($amount_paid,$group_id,$funeral_id)
    {
              try {
            $stmt = $this->conn->prepare("SELECT m.member_id,m.account_balance FROM `members` as m INNER JOIN locations as l ON m.location_id=l.location_id WHERE m.account_balance>=:amount_paid AND l.group_id=:group_id AND m.status='Active' AND m.member_id NOT IN (SELECT member_id FROM register WHERE funeral_id=:funeral_id)");
            $stmt->bindParam(':amount_paid', $amount_paid, PDO::PARAM_STR);
            $stmt->bindParam(':group_id', $group_id, PDO::PARAM_STR);
            $stmt->bindParam(':funeral_id', $funeral_id, PDO::PARAM_STR);
            $stmt->execute();
        return $stmt->fetchAll();
                  
        }
        catch (Exception $e)
        {
            return "There is an error : ".$e->getMessage();
        }
    }

    function registerUsers($funeral_id)
    {
              try {
            $stmt = $this->conn->prepare("SELECT u.member_id FROM `users` as u INNER JOIN members as m ON u.member_id=m.member_id INNER JOIN locations as l ON m.location_id=l.location_id WHERE u.status=1 AND l.group_id=:group_id AND m.member_id NOT IN (SELECT member_id FROM register WHERE funeral_id=:funeral_id)");
            $stmt->bindParam(':group_id', $this->group_id, PDO::PARAM_STR);
            $stmt->bindParam(':funeral_id', $funeral_id, PDO::PARAM_STR);
            $stmt->execute();
        foreach($stmt->fetchAll() as $row)
        {
            $member_id = $row['member_id']; 
            $this->addRegister($member_id,$funeral_id,"System","paid");
        }
                  
        }
        catch (Exception $e)
        {
            echo "There is an error : ".$e->getMessage();
        }
    }
}