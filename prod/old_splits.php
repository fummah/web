<?php
session_start();
define("access",true);
require "classes/controls.php";
include ("templates/claim_templates.php");
$control=new controls();
include ("header.php");
$limit = 10;
$status="completed";
$role=$control->myRole();
$username=$control->loggedAs();
if (isset($_GET["page"])) {
    $page = (int)$_GET["page"];
} else {
    $page = 1;
};
$start_from = ($page - 1) * $limit;
$txt_catergory=0;
if(isset($_POST["search_button"]))
{
    $search_value=validateXss($_POST["search_term"]);
    $txt_catergory=$_POST["catergory_text"];
    $page = 1;
    $start_from = ($page - 1) * $limit;
}
elseif (isset($_GET['search']))
{
    $search_value=validateXss($_GET["search"]);
    $txt_catergory=(int)$_GET["catergory"];
}
else
{
    $search_value="";
}

?>
<title>MCA | Search Claims</title>
<link rel="stylesheet" href="css/simplePagination.css" />
<script src="js/jquery.simplePagination.js"></script>
<script type="text/javascript" src="js/split.js"></script>
<script>
</script>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300&display=swap');
    .pagination li.active{background-color: transparent !important;}
    .light-theme a, .light-theme span {
        border: 1px solid #54bf99 !important;
        background: #fff !important;
    }
    .light-theme .current{background-color: black !important;border: 1px solid #54bf99 !important;}
    .highlight {
        background-color: #54bc9c;
        color: #fff;
        -moz-border-radius: 5px; /* FF1+ */
        -webkit-border-radius: 5px; /* Saf3-4 */
        border-radius: 5px; /* Opera 10.5, IE 9, Saf5, Chrome */
        -moz-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.7); /* FF3.5+ */
        -webkit-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.7); /* Saf3.0+, Chrome */
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.7); /* Opera 10.5+, IE 9.0 */
    }
    .highlight {
        padding:1px 4px;
        margin:0 -4px;
    }
    #btn{

        background: none;
        color: inherit;
        border: none;
        padding: 0;
        font: inherit;
        cursor: pointer;
        outline: inherit;

    }
    thead>tr>th{
        color: #54bc9c !important;
    }


    .mybodxy{
        font-family: 'Montserrat', sans-serif !important;
    }
</style>
<div class="mybody">
<div class="row uk-text-small" style="font-size:10px;padding-left: 20px; padding-right: 20px; padding-top: 20px; border-bottom: 1px solid #54bc9c">
    <div class="col-md-3">
        <form class="uk-search uk-search-default" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <input class="" type="hidden" name="catergory_text" id="catergory_text" value="<?php echo $txt_catergory;?>">
            <input class="uk-search-input" type="search" name="search_term" id="search_term" placeholder="Search Old Claims" value="<?php echo $search_value;?>">
            <button class="uk-search-icon-flip" name="search_button" id="search_button" uk-search-icon></button>
        </form>
    </div>
</div>
<div id="myH">
    <div class="row uk-text-small" style="padding-left: 20px; padding-right: 15px;" >

        <div class="col-md-12">
            <?php
            echo "<table class='striped uk-table' style='border: 2px solid whitesmoke'><thead>";
            echo "<tr style=\"color: black\">
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

       
        
    </tr>";
            echo "</thead><tbody>";
            foreach($control->viewAllSplitClaimsDoctors($status,$start_from ,$limit,$search_value,0) as $row)
            {

                $claim_id=$row["claim_id"];
                $loyalty_number=$row["loyalty_number"];
                $membership_number=$row["membership_number"];
                $beneficiary_name=$row["beneficiary_name"];
                $beneficiary_scheme_join_date=$row["beneficiary_scheme_join_date"];
                $beneficiary_id_number=$row["beneficiary_id_number"];
                $beneficiary_date_of_birth=$row["beneficiary_date_of_birth"];
                $co_payment=$row["co_payment"];
                $discharge_date=$row["discharge_date"];
                $admission_date=$row["admission_date"];
                $procedure_date=$row["procedure_date"];
                $filename=$row["file_name"];
                $claim_number=$row["claim_number"];
  $co_payment=implode(' | ', array_map(function ($entry) {
                return $entry['copayment'];
            }, $control->viewSplitCopayments($claim_id)));
                $hospital_name="";
                foreach ($control->viewHospitalNames($claim_id) as $x)
                {
                    $hospital_name.=$x["hospital_name"]." <span style='color: red !important;'>|</span> ";
                }
                echo "<tr title='$filename'><td><span style='color: blue; cursor: pointer' onclick='openModal(\"$claim_id\")'>$loyalty_number</span> ($claim_number)</td><td>$membership_number</td><td>$beneficiary_name</td><td>$beneficiary_scheme_join_date</td>
<td>$beneficiary_id_number</td><td>$beneficiary_date_of_birth</td><td>$procedure_date</td><td>$admission_date</td><td>$discharge_date</td>
<td>$hospital_name</td><td>$co_payment</td></tr>";

            }
            echo "</tbody></table>";
            $total_records=$control->viewAllSplitClaimsDoctors($status,1 ,10,$search_value,1);
            $total_pages = ceil($total_records / $limit);
            $pagLink = "<ul class='pagination'>";
            for ($i=1; $i<=$total_pages; $i++) {
                $pagLink .= "<li><a href='?search=".$search_value."&catergory=".$txt_catergory."&dat=4&page=".$i."'>".$i."</a></li>";
            };
            echo $pagLink . "</ul>";
            ?>
        </div></div>
</div>
</div>
<span class="uk-text-lighter" style="padding-left: 20px;"> <?php echo "Page $page of $total_pages =>  $total_records";?></span>
<script type="text/javascript">
    $(document).ready(function(){
        $('.pagination').pagination({
            items: <?php echo $total_records;?>,
            itemsOnPage: <?php echo $limit;?>,
            cssStyle: 'light-theme',
            currentPage : <?php echo $page;?>,
            hrefTextPrefix : '?search=<?php echo $search_value;?>&catergory=<?php echo $txt_catergory;?>&dat=4545&page='
        });
    });
</script>
<?php

include("templates/split_template.php");
include "footer.php";

?>
<script type="text/javascript" src="js/highlight.js"></script>
<script type="text/javascript">
    $(function() {
        $('#search_term').bind('keyup change', function(ev) {
            // pull in the new value
            var searchTerm = $('#search_term').val();

            // remove any old highlighted terms
            $('#myH').removeHighlight();

            // disable highlighting if empty
            if ( searchTerm ) {
                // highlight the new term
                $('#myH').highlight( searchTerm );
            }
        });

        $(document).ready(function(ev) {
            // pull in the new value
            var searchTerm = $('#search_term').val();

            // remove any old highlighted terms
            $('#myH').removeHighlight();

            // disable highlighting if empty
            if ( searchTerm ) {
                // highlight the new term
                $('#myH').highlight( searchTerm );
            }
        });
    });
</script>
<script type="text/javascript">
    $('input[name="catergory"]').click(function (){
        var val=$('input[name="catergory"]:checked').val();
        $('#catergory_text').val(val);
        document.getElementById("search_button").click();
    });
    function trydwnload() {

        event.preventDefault();
    }
    function dates() {
        var from=$("#from").val();

        var to=$("#to").val();
        if(to=="")
        {

            $("#to").val(from);
        }
    }
    function sendQA(claim_id)
    {

        event.preventDefault();
        event.stopPropagation();
        return false;
        alert('Break');
    }

    $(document).ready(function(){
        $('select').formSelect();
        $('.qa_tick').on('click', function(event) {
            var myid=$(this).attr("id");
            var splitid=myid.split("_");
            var disnabl=splitid[0];
            var claim_id=splitid[1];
            if(disnabl=="no")
            {
                var qa_status=$(this).is(":checked")?1:0;
                var obj={
                    identity_number: 36,
                    claim_id:claim_id,
                    qa_status:qa_status
                };
                $.ajax({
                    url:"ajax/claims.php",
                    type:"POST",
                    data:obj,
                    success:function(data){
                        UIkit.notification({message: data});
                    },
                    error:function(jqXHR, exception)
                    {
                        alert("Connection error");
                    }
                });
                return true;
            }
            else {
                UIkit.notification({message: "Not Allowed"});
                event.preventDefault();
                event.stopPropagation();
                return false;
            }

        });
    });
</script>

