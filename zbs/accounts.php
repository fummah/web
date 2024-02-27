<?php
session_start();
define("access",true);
$page_name="Accounts";

include ("classes/DBConnect.php");
$db=new DBConnect();
if(!in_array($db->myRole(),$db->topRoles()))
{
 
        header("Location: logout.php");
        die();
    

}
$summary =$db->getAdvanceSummary();
$total_number = $summary["total"];
$total_sum =$summary["sum_total"];
$limit = 10;
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
    $page = 1;
    $start_from = ($page - 1) * $limit;
}
elseif (isset($_GET['search']))
{
    $search_value=validateXss($_GET["search"]);
}
else
{
    $search_value="";
}
require_once ("header.php");

?>
    <link rel="stylesheet" href="css/simplePagination.css" />
    <script src="js/jquery.simplePagination.js"></script>
<style>

    .linkbutton{
        background: none;
        border: none;
        color: #54bc9c;
        text-decoration: underline;
        cursor: pointer;
    }
    table{
        font-family: 'Montserrat',Helvetica,Arial,Lucida,sans-serif;
        font-weight: 300;
        line-height: 1.6em;
        width: 100% !important;
    }


    .uk-badge{
        background-color: #54bf99 !important;
        border: 1px solid white !important;
        font-family: 'Montserrat',Helvetica,Arial,Lucida,sans-serif !important;
        font-weight: 300 !important;
        font-size: 14px !important;
    }
    .uk-icon-button{
        cursor: pointer !important;
    }
    .stcolor{
        background-color:red !important;
    }
    @media only screen and (max-width: 949px) {
        .uk-search-large .uk-search-input {
            font-size: 1.625rem;
        }
    }
    tbody tr:nth-child(odd){
        background-color: whitesmoke !important;
    }
</style>
<button href="#funeral" id="clickFunerals" style="display: none" uk-toggle></button>
<button href="#funeral_option" id="clickFuneralOptions" style="display: none" uk-toggle></button>
<div class="et_pb_code_inner" style="padding: 1px !important; border: 1px solid #cf4522 !important;border-radius: 10px; width: 98%; position: relative; margin-left: auto !important; margin-right: auto !important;">
    <div class="row et_pb_texta" style="padding-top: 20px !important;padding-left: 15px !important; border-bottom: 1px dashed lightgrey !important;">
 <div class="col-md-2">
           
        </div>
        <div class="col-md-2">
            Total Balance : <span class="uk-badge" style="background-color: cadetblue !important;">R<?php echo $db->moneyformat($total_sum);?></span>
        </div>
        <div class="col-md-2">
            Subscribing Members : <span class="uk-badge" style="background-color: cadetblue !important;"><?php echo $total_number;?></span>
        </div>
        <div class="col-md-3" >
      
        </div>
        <div class="col-md-3">
            <div class="uk-margin"  style="width: 100% !important; padding-top:10px">
                <form class="uk-search uk-search-default" method="POST">   
                                              
                    <input class="uk-search-input" type="search" name="search_term" value="<?php echo $search_value; ?>" placeholder="Search Member"> 
                    <div style="padding: 10px;">  
                    <button class="uk-button uk-button-danger uk-button-small" name="search_button">Search</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <table class="uk-table uk-table-responsive uk-table-divider">
                <thead>
                <tr>  
                    <th>Member ID</th>                 
                    <th>Full Name</th>
                    <th>Location</th>
                    <th>Balance</th>
                    <th>Deposit</th>
                    <th>Transaction</th>
                    <th></th>
                </tr>
                </thead><tbody>
                <?php

                foreach ($db->subscribedMembers($start_from ,$limit,$search_value,0) as $row) {
                    $member_id = $row["member_id"];                    
                    $full_name = $row["first_name"]." ".$row["last_name"];                    
                    $location = $row["location_name"];
                    $balance = $row["account_balance"]; 
                    echo "<tr><td><span class='not_desktop'>ID : </span><span class='uk-badge' style='background-color: cadetblue !important;'>$member_id</span></td>";
                    echo "<td><span class='not_desktop'>Name : </span>$full_name</td>";
                    echo "<td><span class='not_desktop'>Location : </span>$location</td>";
                    echo "<td><span class='not_desktop'>Balance : </span><span class='uk-badge'>R $balance</span></td>";
                    echo "<td><button member_id='$member_id' balance='$balance' full_name='$full_name' class='uk-button uk-button-danger deposit' uk-toggle='target: #deposit'>Deposit</button></td>";
                    echo "<td><button member_id='$member_id' full_name='$full_name' class='uk-button uk-button-secondary mtrans' uk-toggle='target: #trans'>Transactions</button></td>";

                }
                ?>

                </tbody>
            </table>
            <?php
            $total_records=$db->subscribedMembers(1 ,10,$search_value,1);
            $total_pages = ceil($total_records / $limit);
            $pagLink = "<ul class='pagination'>";
            for ($i=1; $i<=$total_pages; $i++) {
                $pagLink .= "<li><a href='?search=".$search_value."&page=".$i."'>".$i."</a></li>";
            };
            echo $pagLink . "</ul>";
            ?>
        </div>

    </div>
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

    <script type="text/javascript" id="divi-custom-script-js-extra">
        $(document).on("click",".deposit",function(){
const member_id = $(this).attr("member_id");
const current_deposit = $(this).attr("balance");
const full_name = $(this).attr("full_name");
            $("#member_id").val(member_id);
            $("#current_deposit").text(current_deposit);
            $("#full_name").text(full_name);
        }
        );

        $(document).on("click","#save_deposit",function(){
            $("#deposit_msg").empty();
const member_id = $("#member_id").val();
const deposit_amount = $("#deposit_amount").val();
const obj = {
    member_id:member_id,
    deposit_amount:deposit_amount,
    identity_number:30
}
$.ajax({
            type: "POST",
            url: "ajax/process.php",
            data:obj,
            beforeSend: function(){
                $("#deposit_msg").text("please wait ...");
            },
            success: function(data){
                $("#deposit_msg").html(data)
            },
        error:function(jqXHR, exception)
        {
            console.log("There is an error : "+jqXHR.responseText);
        }
        });

        }
        );

        $(document).on("click",".mtrans",function(){
const member_id = $(this).attr("member_id");
const full_name = $(this).attr("full_name");
$("#full_name1").text(full_name);
const obj = {
    member_id:member_id,
    identity_number:31
}
$.ajax({
            type: "POST",
            url: "ajax/process.php",
            data:obj,
            beforeSend: function(){
               
            },
            success: function(data){
                $("#transactions").html(data)
            },
        error:function(jqXHR, exception)
        {
            console.log("There is an error : "+jqXHR.responseText);
        }
        });
        }
        );
        /* <![CDATA[ */
        var DIVI = {"item_count":"%d Item","items_count":"%d Items"};
        var et_shortcodes_strings = {"previous":"Previous","next":"Next"};
        var et_pb_custom = {
            "ajaxurl":"",
            "images_uri":"",
            "builder_images_uri":"",
            "et_frontend_nonce":"f5e56487fb","subscription_failed":"Please, check the fields below to make sure you entered the correct information.",
            "et_ab_log_nonce":"a297160c78","fill_message":"Please, fill in the following fields:",
            "contact_error_message":"Please, fix the following errors:","invalid":"Invalid email","captcha":"Captcha",
            "prev":"Prev","previous":"Previous","next":"Next","wrong_captcha":"You entered the wrong number in captcha.",
            "wrong_checkbox":"Checkbox","ignore_waypoints":"no","is_divi_theme_used":"1","widget_search_selector":".widget_search",
            "ab_tests":[],"is_ab_testing_active":"","page_id":"994","unique_test_id":"","ab_bounce_rate":"5","is_cache_plugin_active":"yes",
            "is_shortcode_tracking":"","tinymce_uri":""};
        var et_builder_utils_params = {"condition":{"diviTheme":true,"extraTheme":false},
            "scrollLocations":["app","top"],"builderScrollLocations":{"desktop":"app","tablet":"app","phone":"app"},"onloadScrollLocation":"app",
            "builderType":"fe"};
        var et_frontend_scripts = {"builderCssContainerPrefix":"#et-boc","builderCssLayoutPrefix":"#et-boc .et-l"};
        var et_pb_box_shadow_elements = [];
        var et_pb_motion_elements = {"desktop":[],"tablet":[],"phone":[]};
        var et_pb_sticky_elements = [];
        /* ]]> */
    </script>
    <script type="text/javascript" src="js/custom.unified.js?ver=4.7.6" id="divi-custom-script-js"></script>

    <!-- This is the modal -->

</div>
<?php
include("footer.php");
?>
</body></html>

<!-- Add Deposit -->
<div id="deposit" class="uk-modal-container" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
         <hr>
            <div>
                <div>
                    <div>
                        <h4 class="uk-modal-title uk-text-danger">Full Name : <span id="full_name"></span></h3>
                        <h5>Current Balance : <b>R<span id="current_deposit"></span></b></h5>
                        <input  id="member_id" type="hidden"/>
                    <div class="uk-margin">
                                        Deposit Amount : <input class="uk-input" type="text" id="deposit_amount" placeholder="Deposit Amount">
                                    </div>
                        <hr>
                    </div>
                    <span style="color: red; font-size: 16px; font-weight: bolder; margin-left: 100px" id="deposit_msg"></span>
                </div> <!-- .et_pb_code -->
            </div> <!-- .et_pb_column -->
            <p class="uk-text-right">
                <button class="uk-button uk-button-danger" type="submit" id="save_deposit">Save</button>
                <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
            </p>
    
    </div>
</div>

<div id="trans" uk-modal>
    <div class="uk-modal-dialog">

        <button class="uk-modal-close-default" type="button" uk-close></button>

        <div class="uk-modal-header"><hr>
            <h2 class="uk-modal-title">Transactions : <span id="full_name1"></span></h2>
        </div>

        <div class="uk-modal-body" uk-overflow-auto>
<div id="transactions">

</div>
                   </div>

        <div class="uk-modal-footer uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
        </div>

    </div>
</div>
