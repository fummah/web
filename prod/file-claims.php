<?php
session_start();
define("access",true);
if(!$_GET["filename"])
{
    die("invalid access");
}
$filename=$_GET["filename"];
include ("classes/controls.php");
$control=new controls();
include ("header.php");
$_SESSION["admin_main"]=$_SERVER['REQUEST_URI'];
?>
<title>MCA | Splits</title>
<script type="text/javascript" src="js/split.js"></script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300&display=swap');
    .mybody{
        font-family: 'Montserrat', sans-serif !important;
    }
</style>
<div class="mybody">

    <div class="uk-placeholder" id="example1">
        <p align="center" style="color: red"><b>Claims for :: <span style="color:green !important;"><?php echo $filename;?></span></b></p>
        <table id="example" class="striped" style="width:100%; border-top: 1px dotted black">

            <thead>
            <tr>
                <th>Loyalty Number</th>
                <th>Membership Number</th>
                <th>Beneficiary Name</th>
                <th>Beneficiary Scheme Join Date</th>
                <th>Beneficiary Id Number</th>
                <th>Beneficiary D.O.B</th>
                <th>Procedure Date</th>
                <th>Admission Date</th>
                <th>Discharge Date</th>
                <th>Hospital Name</th>
                <th>Co Payment</th>

            </tr>
            </thead>
            <tbody>
            <?php
            foreach($control->viewSplitFileClaims($filename) as $row)
            {

                $claim_id=$row["claim_id"];
                $loyalty_number=$row["loyalty_number"];
                $membership_number=$row["membership_number"];
                $beneficiary_name=$row["beneficiary_name"];
                $beneficiary_scheme_join_date=$row["beneficiary_scheme_join_date"];
                $beneficiary_id_number=$row["beneficiary_id_number"];
                $beneficiary_date_of_birth=$row["beneficiary_date_of_birth"];
                //$co_payment=$row["co_payment"];
                $discharge_date=$row["discharge_date"];
                $admission_date=$row["admission_date"];
                $procedure_date=$row["procedure_date"];
                $filename=$row["file_name"];

                $co_payment=implode(' | ', array_map(function ($entry) {
                    return $entry['copayment'];
                }, $control->viewSplitCopayments($claim_id)));

                $hospital_name="";
                foreach ($control->viewHospitalNames($claim_id) as $x)
                {
                    $hospital_name.=$x["hospital_name"]." <span style='color: red !important;'>|</span> ";
                }
                echo "<tr title='$filename' id='$claim_id'><td style='color: blue; cursor: pointer'><span onclick='openModal(\"$claim_id\")'>$loyalty_number</span></td><td>$membership_number</td><td>$beneficiary_name</td><td>$beneficiary_scheme_join_date</td>
<td>$beneficiary_id_number</td><td>$beneficiary_date_of_birth</td><td>$procedure_date</td><td>$admission_date</td><td>$discharge_date</td>
<td>$hospital_name</td><td>$co_payment</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>

</div>
<?php
include ("templates/split_template.php");
?>
<!-- This is the modal with the default close button -->
<div class="footer-copyright" style="padding-left: 20px">
    <?php
    include "footer.php";
    ?>
</div>
<script>
    $(document).ready(function() {
        $('#example').DataTable({
            "ordering": false
        });
        $('.escl').formSelect();
    } );
</script>