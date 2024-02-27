<?php
session_start();
define("access",true);
$page_name="Web Visitors";
include ("classes/DBConnect.php");
$db=new DBConnect();
if(!in_array($db->myRole(),$db->topRoles()))
{
    header("Location: logout.php");
    die();
}
require_once ("header.php");
$date=date("Y-m-d");
if(isset($_POST["submit_btn"]))
{
    $date=$_POST["date"];
}
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
    .inl li {
    display: inline;
}
</style>

<h3 align="center">Web Portal Visitors</h3><hr>
<div class="uk-placeholder">
  <div class="row et_pb_texta" style="padding-top: 20px !important;">
                  <form method="POST">    
                   <div class="col-md-3"> </div>  
        <div class="col-md-3">                               
                    <input class="uk-search-input" style="border:1px solid green !important;" type="date" name="date" value="<?php echo $date; ?>" placeholder="Select Date" REQUIRED>                 
                     </div>
                      <div class="col-md-3"> 
                    <button class="uk-button uk-button-default uk-button-small" name="submit_btn">Search</button>
                     </div>
    <div class="col-md-3"> </div> 
                </form>           
   </div>
    <hr>
    
    <?php 
    $data=$db->getWebVisitors($date);
    $count=count($data);
    echo "Total Number : <span class='uk-badge'>$count</span><br>";
    echo "Date : <b>$date</b><hr>";
    foreach($data as $row)
    {
    $member_id=(int)$row["member_id"];
    $ipaddr=$row["ipaddr"];
    $created_at=$row["created_at"];
    $first_name=$row["first_name"];
    $last_name=$row["last_name"];
    $location_name=$row["location_name"];
    $group_name=$row["group_name"];
    $full_name=$first_name." ".$last_name;
    echo "<div class='row'>";
    echo "<div class='col-md-12'>";
    echo "Member ID Number : <b>$member_id</b><br>";
    echo "Full Name : <b>$full_name</b><br>";
    echo "IP Address : <b>$ipaddr</b><br>";
    echo "Location : <b>$location_name</b><br>";
    echo "Group : <b>$group_name</b><br>";
    echo "Date Entered : <b>$created_at</b><br>";
    echo "</div></div><hr>";
 }
        ?>
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
        <?php
                include("footer.php");
                ?>
                
              