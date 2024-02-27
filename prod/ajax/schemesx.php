<?php
session_start();
error_reporting(0);

if(isset($_SESSION['logxged']) && !empty($_SESSION['logxged'])) {
    require_once('../dbconn1.php');
    $conn = connection("mca", "MCA_admin");
    $mess = "";
    $identity = validateXss($_GET['identityNum']);
    if ($identity == 1) {
        try {
            $schemeid = validateXss($_GET['schemeid']);
            $txt = htmlspecialchars($_GET['txt']);
            $desc = $_SESSION['user_id'];           
            $stmtD = $conn->prepare('INSERT INTO `scheme_options`(`scheme_id`, `option_name`, `description`) VALUES(:id,:nam,:des)');
            $stmtD->bindParam(':id', $schemeid, PDO::PARAM_STR);
            $stmtD->bindParam(':nam', $txt, PDO::PARAM_STR);
            $stmtD->bindParam(':des', $desc, PDO::PARAM_STR);
            $num = $stmtD->execute();
            if ($num == 1) {
                $mess = "<b class='alert-success'>Added!</b>";
            } else {
                $mess = "<b class='alert-danger'>Failed!</b>";
            }

        } catch (Exception $e) {
            $mess = "<b class='alert-danger'>Error!!!</b>";
        }
        echo $mess;
    }
}
else
{
    echo "Invalid access";
}

?>

