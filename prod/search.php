<?php
session_start();
define("access",true);
require "classes/controls.php";
include ("templates/claim_templates.php");
$control=new controls();
include ("header.php");
$limit = 10;
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
$txt0=$txt_catergory==0?"checked":"";
$txt1=$txt_catergory==1?"checked":"";
$txt2=$txt_catergory==2?"checked":"";
$txt3=$txt_catergory==3?"checked":"";
$txt4=$txt_catergory==4?"checked":"";
?>
<title>MCA | Search Claims</title>
<link rel="stylesheet" href="css/simplePagination.css" />
<script src="js/jquery.simplePagination.js"></script>
<script>
    function deleteClaim(id)
    {
        if (confirm('Do you really want to delete this Case?')) {
            var display1=id+"x";
            $("#"+display1).show();
            var obj = {
                identity_number:19,
                claim_id: id
            };
            $.ajax({
                url: "ajax/claims.php",
                type: "POST",
                data: obj,
                success: function (data) {
                    if(data.indexOf("Deleted")>-1)
                    {
                        $("#"+display1).html(data);
                        $("#"+id).css("background-color","pink");
                        $("#"+display1).css("color","green");
                    }
                    else
                    {
                        $("#"+display1).hide();
                        alert(data);
                    }
                },
                error: function (jqXHR, exception) {
                    alert(jqXHR.responseText);
                }
            });

        } else {

            return false;
        }
    }
</script>
<style>
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
        font-weight: bolder !important;
    }
</style>

<div class="row uk-text-small" style="padding-left: 20px; padding-right: 20px; padding-top: 20px; border-bottom: 1px solid #54bc9c">
    <div class="col-md-3">
        <form class="uk-search uk-search-default" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <input class="" type="hidden" name="catergory_text" id="catergory_text" value="<?php echo $txt_catergory;?>">
            <input class="uk-search-input" type="search" name="search_term" id="search_term" placeholder="Search Claim" value="<?php echo $search_value;?>">
            <button class="uk-search-icon-flip" name="search_button" id="search_button" uk-search-icon></button>
        </form>
    </div>
    <div class="col-md-1">
        <label>
            <input class="with-gap" name="catergory" type="radio" value="0" <?php echo $txt0;?> />
            <span>All Cases</span>
        </label></div>
    <div class="col-md-1">  <label>
            <input class="with-gap" name="catergory" type="radio" value="1" <?php echo $txt1;?>/>
            <span>Open Claims</span>
        </label></div>
    <div class="col-md-1">  <label>
            <input class="with-gap" name="catergory" type="radio" value="2" <?php echo $txt2;?>/>
            <span>Closed claims</span>
        </label></div>
    <div class="col-md-1">  <label>
            <input class="with-gap" name="catergory" type="radio" value="3" <?php echo $txt3;?>/>
            <span>PMB Claims</span>
        </label></div>
    <div class="col-md-2">  <label>
            <input class="with-gap" name="catergory" type="radio" value="4" <?php echo $txt4;?>/>
            <span>NON-PMB Claims</span>
        </label></div>

    <div class="col-md-3">
        <form class="tab" action="download_cases.php" method="post">
            <input type="hidden" name="download_input" id="download_input" value="<?php echo $search_value;?>"/>
            <input type="hidden" name="cattext" value="<?php echo $txt_catergory; ?>"/>
            <button class="uk-button uk-button-primary uk-button-small" name="download" title="download searched results" style="background-color: #54bc9c"><span uk-icon="download"></span> View Excel</button>
            <?php

            if($control->isGapCoverAdmin())
            {
                ?>
                <button id="download" title="download Open / Closed for specific dates" uk-toggle="target: #my_clients" style="background-color: #54bc9c" class="uk-button uk-button-primary uk-button-small" onclick="trydwnload()"><span uk-icon="download"></span> Download</button>
                <?php
            }
            if($control->isAdmin())
            {
                ?>
                <button id="download" title="download Medswitch claims" uk-toggle="target: #my-id" style="background-color: #54bc9c" class="uk-button uk-button-primary uk-button-small" uk-icon="download" onclick="trydwnload()"></button>
                <?php
            }
            ?>

        </form>
    </div>

</div>
<div id="myH">
    <div class="row uk-text-small" style="padding-left: 20px; padding-right: 15px;" >

        <div class="col-md-12">
            <?php
            echo "<table class='striped uk-table' style='border: 2px solid whitesmoke'><thead>";
            echo "<tr style=\"color: black\">
        <th>Name  and Surname</th>
        <th>Policy Number</th>
        <th>Claim Number</th>
 <th>Medical Scheme</th>
        <th>Scheme Number</th>
       
        <th>Date Entered</th>
        <th>Date Closed</th>
        <th>Client</th>
        <th>Files</th>
        <th>Scheme Savings</th>
        <th>Discount Savings</th>
        <th>Owner</th>";
            if($control->isInternal()) {
                echo "<th>QA?</th>";
            }
            echo "</tr></thead><tbody>";

            foreach($control->viewAllClaims($role,$start_from ,$limit,$username,$search_value,0,$txt_catergory) as $row)
            {
                $name = htmlspecialchars(strtoupper($row[0] . " " . $row[1]));
                $policy = htmlspecialchars(strtoupper($row[2]));
                $claim_number = htmlspecialchars(strtoupper($row[3]));
                $scheme_savings = htmlspecialchars($row[4]);
                $medical_scheme = htmlspecialchars($row[5]);
                $date_entered = htmlspecialchars($row["date_entered"]);
                $date_closed = $row["date_closed"]!==null?$row["date_closed"]:"-";
                $entered = htmlspecialchars($row[9]);
                $client_id = htmlspecialchars($row[8]);
                $option = htmlspecialchars($row[6]);
                $claim_status = htmlspecialchars($row[10]);
                $claim_id = htmlspecialchars($row[11]);
                $owner = htmlspecialchars($row[12]);
                $discount_savings = htmlspecialchars($row[13]);
                $scheme_number = htmlspecialchars($row[14]);
                $quality = (int)htmlspecialchars($row["quality"]);
                $client_name = htmlspecialchars($row["client_name"]);
                $sla = (int)htmlspecialchars($row[15])==2?1:(int)htmlspecialchars($row[15]);
                $path=(int)$client_id==31?"view_aspen.php":"case_details.php";
                $disabled = "disabled";
                $qa_disabled = "yes";
                $quality=$quality>0?"checked":"";
                $date = "<span style='color:red'>Open</span>";
                if ($claim_status == 0) {
                    $date=$date_closed;
                    if($control->isTopLevel())
                    {
                        $qa_disabled="no";
                    }
                }
                elseif ($claim_status == 1) {
                    $disabled = "";
                }
                elseif ($claim_status == 2)
                {
                    $date = "<span style='color:orange'>On Hold</span>";
                    $disabled = "";
                }
                elseif ($claim_status == 4)
                {
                    $date = "<span style='color:darkolivegreen'>Clinical Review</span>";
                    $disabled = "disabled";
                }
                elseif ($claim_status == 5)
                {
                    $date = "<span style='color:yellowgreen'>Pre-Assessment</span>";
                    $disabled = "";
                }
                $myID="$claim_id"."x";
                $qa_tick_id=$qa_disabled."_".$claim_id;
                echo "<tr style=\"color: black\" id='$claim_id'>
        <th>$name</th>
        <th>$policy</th>
        <th>$claim_number</th>
        <th>$medical_scheme</th>
        <th>$scheme_number</th>
        <th>$date_entered</th>
        <th>$date</th>
        <th>$client_name</th>        
        <th>";
                viewDocuments($control->viewDocuments($claim_id));
                echo"</th>
        <th>$scheme_savings</th>
        <th>$discount_savings</th>
        <th>$owner</th>";
                if($control->isInternal()) {
                    echo "<th><label title='QA Box'><input type='checkbox' class='uk-checkbox qa_tick' id='$qa_tick_id' $quality><span></span><label></th> ";
                }
        echo "<th><ul class=\"uk-iconnav\">";
                if($control->isGapCover() || $client_name=="Aspen" || $control->isTopLevel())
                {
                    echo "<li title='view claim' style='width: 26px;height: 28px !important;' ><form action='$path' method='post'>";
                    echo "<input type=\"hidden\" name=\"claim_id\" value=\"$claim_id\" />";
                    echo "<button name=\"btn\" uk-icon=\"icon: link\" style=\"color: #0b8278;\"></button>";
                    echo "</form></li>";
                }
                else
                {
                    echo "<li><a href=\"#modal-container\" title='view claim' uk-icon=\"icon: link\" onclick=\"viewClaim('$claim_id')\" uk-toggle></a></li>";
                }


                if(($control->isTopLevel() || $claim_status!=0) && $control->isInternal()) {
                    echo "<li title='edit claim' style='width: 26px;height: 28px !important;' ><form action='edit_case.php' method='post'>";
                    echo "<input type=\"hidden\" name=\"claim_id\" value=\"$claim_id\" />";
                    echo "<button name=\"btn\" uk-icon=\"icon: pencil\" style=\"color: #0b8278;\"></button>";
                    echo "</form></li>";
                }
                if($control->isAdmin())
                {
                    echo"<li title='delete claim'><span style=\"color:purple;display: none\" id=\"$myID\">deleting...</span><a href=\"#\" uk-icon=\"icon: trash\" style=\"color: red\" onclick=\"deleteClaim('$claim_id')\"></a></li>";
                }
                echo "</ul></th></tr>";

            }
            echo "</tbody></table>";
            $total_records=$control->viewAllClaims($role,1 ,10,$username,$search_value,1,$txt_catergory);
            $total_pages = ceil($total_records / $limit);
            $pagLink = "<ul class='pagination'>";
            for ($i=1; $i<=$total_pages; $i++) {
                $pagLink .= "<li><a href='?search=".$search_value."&catergory=".$txt_catergory."&dat=4&page=".$i."'>".$i."</a></li>";
            };
            echo $pagLink . "</ul>";
            ?>
        </div></div>
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
include "footer.php";
escalation();
?>



<div id="my-id" uk-modal>

    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title">Download Medswitch claims<hr class="uk-divider-icon"></h2>
        <form action="download_cases.php" method="post">
            <div class="row">
                <div class="col-lg-6">From Date : <input name="from" id="from" class="uk-input" type="date" placeholder="from" onchange="dates()"></div>
                <div class="col-lg-6">To Date : <input name="to" id="to" class="uk-input" type="date" placeholder="to"></div>
            </div>
            <hr>
            <p align="center">
                <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                <button class="uk-button uk-button-primary" name="medswitch" type="submit">Download</button>

            </p>
        </form>
    </div>

</div>
<?php
$mnarr=[];
for($x=11; $x>=0;$x--){
    $datt= date('Y-m', strtotime(date('Y-m')." -" . $x . " month"));
    array_push($mnarr,$datt);
}
$zarr=array_reverse($mnarr);
?>
<div id="my_clients" uk-modal>

    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title"><hr class="uk-divider-icon"></h2>
        <form action="classes/downloadClass.php" method="post">
            <div class="row">
                <div class="col-lg-6">From Month : <select class="" name="from_client">
                        <?php
                        for($i=0;$i<count($zarr);$i++)
                        {
                            $newdate=$zarr[$i];
                            echo "<option value='$newdate'>$newdate</option>";
                        }
                        ?>
                    </select></div>
                <div class="col-lg-6">To Month :
                    <select class="" name="to_client">
                        <?php
                        for($i=0;$i<count($zarr);$i++)
                        {
                            $newdate=$zarr[$i];
                            echo "<option value='$newdate'>$newdate</option>";
                        }
                        ?>
                    </select></div>
            </div>
            <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
                <label>
                    <input class="with-gap" type="radio" name="status" value="0" checked/>
                    <span>Open</span>
                </label>
                <label>
                    <input class="with-gap" type="radio" name="status" value="1"/>
                    <span>Closed</span>
                </label>
            </div>
            <hr>
            <p align="center">
                <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                <button class="uk-button uk-button-primary" name="claims_client" type="submit">Download</button>

            </p>
        </form>
    </div>

</div>

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

