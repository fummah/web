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
<title>MCA | Switch Claims</title>
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
    .hideclas{
        display: none;
    }
    .mybodyx{
        font-family: 'Montserrat', sans-serif !important;
    }
</style>
<div class="mybody">
    <div class="row uk-text-small" style="font-size:10px;padding-left: 20px; padding-right: 20px; padding-top: 20px; border-bottom: 1px solid #54bc9c">
        <div class="col-md-3">
            <form class="uk-search uk-search-default" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                <input class="" type="hidden" name="catergory_text" id="catergory_text" value="<?php echo $txt_catergory;?>">
                <input class="uk-search-input" type="search" name="search_term" id="search_term" placeholder="Search Old Switch Claims" value="<?php echo $search_value;?>">
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
               <th>Policy Number</th>
            <th>Membership Number</th>
            <th>Beneficiary Name</th>         
            <th>Beneficiary Id Number</th>                      
            <th>Admission Date</th>
            <th>Discharge Date</th>        
            <th>Date Entered</th>    
            
    </tr>";
                echo "</thead><tbody>";
                foreach($control->viewSeamlessClaims($start_from ,$limit,$search_value,0,$control->loggedAs()) as $row)
                {
                    $claim_id=$row["claim_id"];
                    $claim_number=$row["claim_number"];
                    $scheme_number=$row["scheme_number"];
                    $id_number=$row["id_number"];
                    $service_date=$row["Service_Date"];
                    $medical_scheme=$row["medical_scheme"];
                    $scheme_option=$row["scheme_option"];
                    $first_name=$row["first_name"];
                    $surname=$row["surname"];
                    $end_date=$row["end_date"];
                    $date_entered=$row["date_entered"];
                    $policy_number=$row["policy_number"];
                    $fullname=$first_name." ".$surname;

                    echo "<tr id='$claim_id'><td><span style='color: blue; cursor: pointer' onclick='openModal(\"$claim_id\")'>$policy_number</span></td><td>$scheme_number</td><td>$fullname</td>
<td>$id_number</td><td>$service_date</td><td>$end_date</td><td>$date_entered</td>
</tr>";

                }
                echo "</tbody></table>";
                $total_records=$control->viewSeamlessClaims(1 ,10,$search_value,1,$control->loggedAs());
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

<button style="display: none" class="uk-button uk-button-default clickme" href="#modal-overflow" uk-toggle>Open</button>

<div id="modal-overflow" class="uk-modal-container mybody" uk-modal>
    <div class="uk-modal-dialog">
        <button class="uk-modal-close-default" type="button" uk-close></button>

        <div class="uk-modal-header">
            <h4 class="uk-modal-title">Claim Lines details <span style="color: green !important;" id="closed_by"></span></h4>
            <h5 class="uk-modal-title" style="color: red" id="lod">loading, wait...</h5>
        </div>

        <div class="uk-modal-body" uk-overflow-auto>
            <div class="row uk-placeholder" style="">
                <div class="col-md-3">Policy Number <br> <b><span class="policy_number"></span></b></div>
                <div class="col-md-3">Member Name <br> <b><span class="member_name"><b></b></span></b></div>
                <div class="col-md-3">Membership Number <br> <b><span class="member_number"><b></b></span></b></div>
                <div class="col-md-3">Date Entered <br><b><span class="date"></span> </b></div>
            </div>

            <table class="uk-table uk-table-small uk-table-divider">
                <thead>

                <tr>
                    <th>Procedure Date</th>
                    <th>ICD10 Code</th>
                    <th>Tariff Code</th>
                    <th>Charged Amount</th>
                    <th>Scheme Rate</th>
                    <th>Scheme Paid</th>
                    <th>Mem.Portion</th>

                </tr>
                </thead>
                <tbody id="lines">

                </tbody>
            </table>

        </div>

        <div class="uk-modal-footer uk-text-right">
            <form action="classes/downloadSplit.php" method="POST">
                <input type="hidden" id="xclaim_id" name="xclaim_id">

            </form>
            <div class="row" id="fot">
                <div class="col-md-3"></div>
                <div class="col-md-3">
                    <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
                        <label><input onclick="viewElements()" class="uk-checkbox" id="closetick" type="checkbox"> <span>Close the claim?</span></label>
                    </div>
                </div>
                <div class="col-md-2 hideclas"> <input type="text" id="claim_number" name="claim_number" placeholder="Enter Claim Number"></div>
                <div class="col-md-2 hideclas"><button class="uk-button uk-button-primary hideme" onclick="closeClaim()" type="button"><span uk-icon="check"></span> Save</button></div>

            </div>

        </div>

    </div>
</div>

<?php


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
    function openModal(claim_id)
    {
        var obj={identity_number:4,claim_id:claim_id};
        $("#lod").show("fast");
        $("#lines").empty();
        $("#closed_by").empty();
        $.ajax({
            url:"ajax/split_ajax.php",
            type:"POST",
            data:obj,
            success:function(data){
                $("#lod").hide("fast");
                var json=JSON.parse(data);
                let policy_number=json["policy_number"];
                let membership_number=json["membership_number"];
                let beneficiary_name=json["beneficiary_name"];
                let date_entered=json["date_entered"];
                let claim_id=json["claim_id"];
                $(".policy_number").text(policy_number);
                $(".member_name").text(beneficiary_name);
                $(".member_number").text(membership_number);
                $("#xclaim_id").val(claim_id);
                $(".date").text(date_entered);

                let hospital_lines=json["hospital_lines"];
                for (let key0 in hospital_lines)
                {
                    let oicd10="";
                    let practice_number=hospital_lines[key0]["practice_number"];
                    let practice_name=hospital_lines[key0]["practice_name"];
                    let claim_lines=hospital_lines[key0]["claim_lines"];
                    let proname=practice_name+"("+practice_number+")";
                    let forndata=" <form action=\"classes/downloadSplit.php\" method=\"POST\">\n" +
                        "            \n" +
                        "            <input type=\"hidden\" id=\"claim_id\" name=\"claim_id\" value='"+claim_id+"'>\n" +
                        "            <input type=\"hidden\" id=\"policy_number\" name=\"policy_number\" value='"+policy_number+"'>\n" +
                        "            <input type=\"hidden\" id=\"practice_number\" name=\"practice_number\" value='"+practice_number+"'> <button class=\"uk-button uk-button-primary\" name=\"pracdwn\" type=\"submit\"><span uk-icon=\"cloud-download\"></span> Download</button>\n" +
                        "           </form>";

                    $("#lines").append("<tr style='background-color: black; color: white'><td colspan='3'>"+proname+"</td><td colspan='1'><span class='status pending'></span></td><td></td><td colspan='2'>"+forndata+"</td></tr>");

                    let totcharged=0;
                    let totrate=0;
                    let totscheme=0;
                    let totpart=0;
                    for(let key in claim_lines)
                    {
                        let servicedate=claim_lines[key]["treatmentDate"];
                        let icdcode=claim_lines[key]["primaryICDCode"];
                        let procedurecode=claim_lines[key]["tariff_code"];
                        let amountcharged=parseFloat(claim_lines[key]["clmnline_charged_amnt"]);
                        let medicalschemerateinput=parseFloat(claim_lines[key]["memberLiability"]);
                        let medicalschemepaidinput=parseFloat(claim_lines[key]["clmline_scheme_paid_amnt"]);

                        let portion=(amountcharged-medicalschemepaidinput);
                        totcharged+=amountcharged;
                        totrate+=medicalschemerateinput;
                        totscheme+=medicalschemepaidinput;
                        totpart+=portion;
                        portion=portion.toFixed(2);


                        $("#lines").append("<tr><td>"+servicedate+"</td><td>"+icdcode+"</td><td>"+procedurecode+"</td><td>"+amountcharged+"</td><td>"+medicalschemerateinput+"</td><td>"+medicalschemepaidinput+"</td><td>"+portion+"</td></tr>");

                    }
                    totcharged=totcharged.toFixed(2);
                    totrate=totrate.toFixed(2);
                    totscheme=totscheme.toFixed(2);
                    totpart=totpart.toFixed(2);
                    $("#lines").append("<tr style='color: cornflowerblue !important; font-weight: bolder !important;'><td colspan='3'>Totals : </td><td>"+totcharged+"</td><td>"+totrate+"</td><td>"+totscheme+"</td><td colspan='2'>"+totpart+"</td></tr>");

                }

            },
            error:function(jqXHR, exception)
            {
                console.log("Error here");
            }
        });
        console.log(claim_id+"--");
        $(".clickme").click();
    }

    $(document).ready(function(){
        $('select').formSelect();

    });

    function viewElements() {
        if (document.getElementById("closetick").checked)
        {
            $(".hideclas").show("fast");
        }
        else
        {
            $(".hideclas").hide("fast");
        }
    }
    function closeClaim() {
        if(confirm("You are about to close the claim, are you sure?")) {
            let note = "-";
            let claim_id = $("#xclaim_id").val();
            let claim_number = $("#claim_number").val();

            var obj = {identity_number: 5, claim_id: claim_id, note: note,claim_number:claim_number};

            $.ajax({
                url: "ajax/split_ajax.php",
                type: "POST",
                data: obj,
                success: function (data) {
                    $("#" + claim_id).remove();
                    $(".uk-modal-close-default").click();
                    UIkit.notification({message: data});
                },
                error: function (jqXHR, exception) {
                    console.log("Error here");
                }
            });
        }
    }
</script>

