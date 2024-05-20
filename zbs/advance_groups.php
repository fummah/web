<?php
session_start();
define("access",true);
$page_name="Accounts";

include ("classes/DBConnect.php");
$db=new DBConnect();
if(!in_array($db->myRole(),$db->eRoles()))
{
 
        header("Location: accounts.php");
        die();
    

}

require_once ("header.php");

?>

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

    <div class="row">
        <div class="col-md-12">
<h3 align="center">Advance Groups</h3>
            <table class="uk-table uk-table-responsive uk-table-divider">
                <thead>
                <tr>  
                    <th>Location Name</th>                 
                    <th>Total Number</th>
                    <th>Total Amount</th>
                </tr>
                </thead><tbody>
                <?php

                foreach ($db->getAdvanceGroupsG() as $row) {
                    $location_name = $row["location_name"];                    
                    $total = $row["total"];
                    $total_amount = $db->moneyformat($row["total_amount"]); 
                    echo "<tr><td><span class='not_desktop'>Location Name : </span>$location_name</td>";
                    echo "<td><span class='not_desktop'>Total : </span><span class='uk-badge' style='background-color: cadetblue !important;'>$total</span></td>";                   
                    echo "<td><span class='not_desktop'>Balance : </span><span class='uk-badge'>R $total_amount</span></td></tr>";
                   
                }
                ?>

                </tbody>
            </table>
           
        </div>

    </div>


    <script type="text/javascript" id="divi-custom-script-js-extra">


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

