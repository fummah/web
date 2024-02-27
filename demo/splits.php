<?php
session_start();
define("access",true);
include ("classes/controls.php");
$control=new controls();
include ("header.php");
$_SESSION["admin_main"]=$_SERVER['REQUEST_URI'];
$control->callSFTPNegative();
?>
<title>MCA | Splits</title>
<script type="text/javascript" src="js/split.js"></script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300&display=swap');
    .mybodyx{
        font-family: 'Montserrat', sans-serif !important;
    }
</style>
<div class="mybody">

<div class="uk-placeholder" id="example1">
    <p align="center" style="color: #54bf99"><b><u>Active Claims</u></b></p>
   <table id='empTable' class='display dataTable'>

   <thead>
       <tr>
        <th>ID</th>
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
<script type="text/javascript">
    $(document).ready(function(){
   $('#empTable').DataTable({
      'processing': true,
      'serverSide': true,
      'serverMethod': 'post',
      'order':[[0, 'desc']],
      'ajax': {
          'url':'split_pendingclaims.php'
      },
      'columns': [
         { data: 'claim_id' },
         { data: 'loyalty_number' },
         { data: 'membership_number' },
         { data: 'beneficiary_name' },
         { data: 'beneficiary_scheme_join_date' },
         { data: 'beneficiary_id_number' },
         { data: 'beneficiary_date_of_birth' },
         { data: 'procedure_date' },
         { data: 'admission_date' },
         { data: 'discharge_date' },
         { data: 'hospital_name' },
         { data: 'co_payment' },
      ]
   });


});
</script>