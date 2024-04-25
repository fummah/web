<?php
session_start();
error_reporting(0);
?>
<div class="row">
    <div class="col-sm-12">
        <?php require_once ("myHeader.php")?>
    </div>
</div>


<hr>
<?php


require_once('../admin/dbconn.php');
/* check connection */
error_reporting(0);
$conn = connection("mca", "MCA_admin");
if(isset($_SESSION['mca_user_id']))
{
$name_id = $_SESSION['mca_user_id'];
$name_role = $_SESSION['mca_role'];
if (isset($_POST['edit'])) {
    $name_id = validateXss($_POST['client_id']);
    $name_role = "client";
}
function insertLogs($client_id,$entered_by)
    {
              try {
global $conn;
            $stmt = $conn->prepare("INSERT INTO `web_clients_logs`(`client_id`, `email`, `id_number`, `medical_scheme`, `name`, 
            `surname`, `dob`, `contact_number`, `scheme_option`, `medical_aid_number`, `physical_address1`, `physical_address2`, `role`, 
            `broker_id`, `entered_by`, `date_entered`, `password`, `temp_code`, `temp_code_time`, `coun`, `session_code`, `account_holder`, 
            `account_number`, `bank_name`, `branch_code`, `subscription_rate`, `status`, `insurer`, `option_in`, `new_entered_by`) 
SELECT `client_id`, `email`, `id_number`, `medical_scheme`, `name`, 
            `surname`, `dob`, `contact_number`, `scheme_option`, `medical_aid_number`, `physical_address1`, `physical_address2`, `role`, 
            `broker_id`, `entered_by`, `date_entered`, `password`, `temp_code`, `temp_code_time`, `coun`, `session_code`, `account_holder`, 
            `account_number`, `bank_name`, `branch_code`, `subscription_rate`, `status`, `insurer`, `option_in`,:entered_by FROM web_clients WHERE client_id=:client_id");
        $stmt->bindParam(':client_id', $client_id, PDO::PARAM_STR);
        $stmt->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
        $stmt->execute();
                  
        }
        catch (Exception $e)
        {
           $exx="There is an error : ".$e->getMessage();
        }
    }
if (isset($_POST['save'])) {
    $sys_id = "XXX";
    $sys_broker_id = "JJJ";
    $name_id = validateXss($_POST['name_id']);
    $name_role = validateXss($_POST['name_role']);
    $stmtj = $conn->prepare('SELECT client_id,broker_id FROM web_clients WHERE client_id=:id LIMIT 1');
    $stmtj->bindParam(':id', $name_id, PDO::PARAM_STR);
    $stmtj->execute();
    $ccc = $stmtj->rowCount();
    if ($ccc == 1) {
        $myall = $stmtj->fetch();
        $sys_id=$myall[0];
        $sys_broker_id=$myall[1];
    }
    if ($name_role == "client" || $name_role == "broker" || $name_role == "admin") {
        try {
            $client_id = $_SESSION['mca_user_id'];
            if ($client_id == $sys_id || $client_id==$sys_broker_id) {
                $first_name = validateXss($_POST['first_name']);
                $last_name = validateXss($_POST['last_name']);
                $id_num = validateXss($_POST['id_num']);
                $dob = validateXss($_POST['dob']);
                $email = validateXss($_POST['email']);
                $phone = validateXss($_POST['phone']);
                $insurer = validateXss($_POST['insurer']);
                $policy_number = validateXss($_POST['policy_number']);
                $medical_scheme = "--";
                $scheme_option = "--";
                $account_holder = "--";
                $account_number = "--";
                $bank_name = "--";
                $branch_code = "--";
                insertLogs($client_id,$_SESSION['mca_user_id']);
                if ($name_role == "client") {
                    $medical_scheme = filter_var($_POST['medical_scheme'], FILTER_SANITIZE_STRING);
                    $scheme_option = validateXss($_POST['scheme_option']);
                    $account_holder = validateXss($_POST['account_holder']);
                    $account_number = validateXss($_POST['account_number']);
                    $bank_name = validateXss($_POST['bank_name']);
                    $branch_code = validateXss($_POST['branch_code']);
                }

                $addr_1 = filter_var($_POST['addr_1'], FILTER_SANITIZE_STRING);
                $addr_2 = filter_var($_POST['addr_2'], FILTER_SANITIZE_STRING);


                $update = $conn->prepare('UPDATE web_clients SET name=:name1,surname=:surname,id_number=:idN,dob=:dob,email=:email,contact_number=:contact,medical_scheme=:scheme,
scheme_option=:option1,physical_address1=:addr1,physical_address2=:addr2,account_holder=:account_holder,account_number=:account_number,bank_name=:bank_name,branch_code=:branch_code,insurer=:insurer,option_in=:policy_number WHERE client_id=:id');
                $update->bindParam(':id', $name_id, PDO::PARAM_STR);
                $update->bindParam(':name1', $first_name, PDO::PARAM_STR);
                $update->bindParam(':surname', $last_name, PDO::PARAM_STR);
                $update->bindParam(':idN', $id_num, PDO::PARAM_STR);
                $update->bindParam(':dob', $dob, PDO::PARAM_STR);
                $update->bindParam(':email', $email, PDO::PARAM_STR);
                $update->bindParam(':contact', $phone, PDO::PARAM_STR);
                $update->bindParam(':scheme', $medical_scheme, PDO::PARAM_STR);
                $update->bindParam(':option1', $scheme_option, PDO::PARAM_STR);
                $update->bindParam(':addr1', $addr_1, PDO::PARAM_STR);
                $update->bindParam(':addr2', $addr_2, PDO::PARAM_STR);
                $update->bindParam(':account_holder', $account_holder, PDO::PARAM_STR);
                $update->bindParam(':account_number', $account_number, PDO::PARAM_STR);
                $update->bindParam(':bank_name', $bank_name, PDO::PARAM_STR);
                $update->bindParam(':branch_code', $branch_code, PDO::PARAM_STR);
                $update->bindParam(':insurer', $insurer, PDO::PARAM_STR);
                $update->bindParam(':policy_number', $policy_number, PDO::PARAM_STR);
                $nu = $update->execute();

                if ($nu == 1) {

                    $display = " <div class=\"alert alert-success\"><b><p align=\"center\"> Changes Successfully saved</p></b></div>";
                } else {
                    $display = " <div class=\"alert alert-danger\"><b><p align=\"center\"> Changes not saved</p></b></div>";
                }
            } else {
                $display = " <div class=\"alert alert-danger\"><b><p align=\"center\"> Invalid Access</p></b></div>";
            }
        } catch (Exception $e) {
            $display = " <div class=\"alert alert-danger\"><b><p align=\"center\"> There is an Error</p></b></div>";
        }
    } else {
        $display = " <div class=\"alert alert-danger\"><b><p align=\"center\"> Invalid access</p></b></div>";
    }
}

$stmt = $conn->prepare('SELECT *FROM web_clients WHERE client_id=:id LIMIT 1');
$stmt->bindParam(':id', $name_id, PDO::PARAM_STR);
$stmt->execute();
$row = $stmt->fetch();
$client_id = validateXss($row[0]);
$first_name = validateXss($row[1]);
$last_name = validateXss($row[2]);
$id_num = validateXss($row[3]);
$dob = validateXss($row[4]);
$email = validateXss($row[5]);
$phone = validateXss($row[6]);
$medical_scheme = htmlspecialchars($row[7]);
$scheme_option = validateXss($row[8]);
$aid_id = validateXss($row[9]);
$addr_1 = htmlspecialchars($row[10]);
$addr_2 = htmlspecialchars($row[11]);
$role = validateXss($row[12]);
$entered_by = validateXss($row[14]);
$broker_id = validateXss($row[13]);
$account_holder = validateXss($row[21]);
$account_number = validateXss($row[22]);
$bank_name = validateXss($row[23]);
$branch_code = validateXss($row[24]);
$insurer = validateXss($row["insurer"]);
$policy_number = validateXss($row["option_in"]);
?>
<html>
<head>
    <title>
        Edit Details
    </title>
    <link rel="stylesheet" href="../admin/bootstrap3/css/bootstrap.min.css">

    <script src="../admin/bootstrap3/js/bootstrap.min.js"></script>
    <script src="../admin/jquery/jquery.min.js"></script>
    <script src="../admin/js/jquery-1.12.4.js"></script>
    <link href="../admin/w3/w3.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../admin/css/animate.min.css">
    <style>
        .row {
            padding-top: 20px;
        }

        .container {

            position: relative;
            margin-right: auto;
            margin-left: auto;
            font-weight: bolder;
            border-radius: 5px;
            -webkit-box-shadow: -2px -2px 9px 9px rgb(102, 204, 153);
            -moz-box-shadow: -2px -2px 9px 9px rgb(102, 204, 153);
            box-shadow: -1px -2px 9px 3px rgb(102, 204, 153);
            padding-bottom: 10px;
        }

        input[type=text], textarea, input[type=date], input[type=email], select {
            padding-left: 10px;
            border-radius: 3px;
            border: none;
            background-color: lightgrey;
            outline: none;
            width: 200px;

        }

    </style>

</head>

<body>


<h2 align="center">Edit your details</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <?php echo $display; ?>
    <div class="container">
        <div></div>
        <div class="row">
            <div class="col-sm-6">First Name<input type="hidden" value="<?php echo $name_id; ?>" name="name_id"><input
                        type="hidden" value="<?php echo $name_role; ?>" name="name_role"></div>
            <div class="col-sm-6"><input type="text" name="first_name" id="first_name"
                                         value="<?php echo ucwords($first_name); ?>" REQUIRED></div>
        </div>
        <div class="row">
            <div class="col-sm-6">Surname</div>
            <div class="col-sm-6"><input type="text" name="last_name" id="last_name" value="<?php echo ucwords($last_name); ?>"
                                         REQUIRED></div>
        </div>
        <div class="row">
            <div class="col-sm-6">D.O.B</div>
            <div class="col-sm-6"><input type="date" name="dob" id="dob" value="<?php echo $dob; ?>"></div>
        </div>
        <div class="row">
            <div class="col-sm-6">ID Number</div>
            <div class="col-sm-6"><input type="text" id="id_num" name="id_num" value="<?php echo $id_num; ?>"></div>
        </div>
        <div class="row">
            <div class="col-sm-6">Email Address</div>
            <div class="col-sm-6"><input type="email" id="email" name="email" value="<?php echo $email; ?>"></div>
        </div>

        <div class="row">
            <div class="col-sm-6">Contact Number</div>
            <div class="col-sm-6"><input type="text" id="phone" name="phone" value="<?php echo $phone; ?>"></div>
        </div>
        <?php
        if ($name_role == "client") {
            ?>
            <div class="row">
                <div class="col-sm-6">Medical Scheme</div>
                <div class="col-sm-6">
                    <select id="medical_scheme" name="medical_scheme">
                        <option value="<?php echo $medical_scheme; ?>"><?php echo $medical_scheme; ?></option>
                        <?php

                        $conn = connection("mca", "MCA_admin");
                        $sql = 'SELECT DISTINCT name FROM schemes ORDER BY name ASC';
                        $r = $conn->query($sql);
                        foreach ($r as $row) {

                            ?>
                            <option value="<?php echo $row['name']; ?>"><?php echo $row['name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">Medical Scheme Option</div>
                <div class="col-sm-6"><input type="text" value="<?php echo $scheme_option ?>" id="scheme_option"
                                             name="scheme_option"></div>
            </div>
            <div class="row">
                <div class="col-sm-6">Medical Aid Number</div>
                <div class="col-sm-6"><b style="color: #00b3ee"><?php echo $aid_id; ?></b></div>
            </div>
            <div class="row">
                <div class="col-sm-6">Gap Insurer</div>
                <div class="col-sm-6"><input type="text" id="insurer" name="insurer" value="<?php echo $insurer; ?>"> </div>
            </div>
            <div class="row">
                <div class="col-sm-6">Policy Number</div>
                <div class="col-sm-6"><input type="text" id="policy_number" name="policy_number" value="<?php echo $policy_number; ?>"> </div>
            </div>
            
            <div style="display: none">
            <div class="row">
                <div class="col-sm-6">Account Holder</div>
                <div class="col-sm-6"><input type="text" id="account_holder" name="account_holder" value="<?php echo $account_holder; ?>"> </div>
            </div>
            <div class="row">
                <div class="col-sm-6">Account Number</div>
                <div class="col-sm-6"><input type="text" id="account_number" name="account_number" value="<?php echo $account_number; ?>"> </div>
            </div>
            <div class="row">
                <div class="col-sm-6">Bank Name</div>
                <div class="col-sm-6"><input type="text" id="bank_name" name="bank_name" value="<?php echo $bank_name; ?>"> </div>
            </div>
            <div class="row">
                <div class="col-sm-6">Branch Code</div>
                <div class="col-sm-6"><input type="text" id="branch_code" name="branch_code" value="<?php echo $branch_code; ?>"> </div>
            </div>
            </div>
            <?php
        }
        ?>
        <div class="row">
            <div class="col-sm-6">Physical Address 1</div>
            <div class="col-sm-6"><textarea id="addr_1" name="addr_1"><?php echo ucwords($addr_1); ?></textarea></div>
        </div>
        <div class="row">
            <div class="col-sm-6">Physical Address 2</div>
            <div class="col-sm-6"><textarea id="addr_2" name="addr_2"><?php echo ucwords($addr_2); ?></textarea></div>
        </div>
        <div class="row">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <button type="submit" name="save" style="background-color: rgb(102, 204, 153);color: white" class="btn"><span class="glyphicon glyphicon-ok"
                                                                                                                              style="color: white"></span> Save Changes
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <span id="result1"></span>
            </div>

        </div>

    </div>
</form>
<hr>
<?php
}
else
{
    echo "Invalid access";
}
include('footer.php');
?>
</body>
</html>