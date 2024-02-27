<?php
session_start();
define("access",true);
$page_name="Track";
include ("classes/DBConnect.php");
$db=new DBConnect();
if(!in_array($db->myRole(),$db->topRoles()))
{
    header("Location: logout.php");
    die();
}
require_once ("header.php");
$member_id="";

if(isset($_POST["submit_btn"]))
{
    $member_id=(int)$_POST["member_id"];

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
<div class="uk-placeholder">
<h3 align="center">Track user</h3><hr>
  <p align="center" class="row et_pb_texta" style="padding: 10px !important;">
                  <form method="POST">    
                                              
                    <input class="uk-search-input" style="border:1px solid green !important;" type="number" name="member_id" value="<?php echo $member_id; ?>" placeholder="Enter Member ID" REQUIRED>                 
                     <br>
                    <button class="uk-button uk-button-default uk-button-small" name="submit_btn">Search</button>
             

                </form>           
   </p>
    <hr>
    <?php 
if(isset($_POST["submit_btn"]))
   {
    $data=$db->getSingleMember($member_id);
    if($data)
    {
    $full_name=$data["first_name"]." ".$data["last_name"];
    $contact_number=$data["contact_number"];
    $location=$data["location_name"];
    $group=$data["group_name"];
    ?>
    <p align="center">Current Member Details</p>
       <div class="row" style="background-color: red !important; padding: 10px !important; color:white !important">
        <div class="col-md-12">
            Member ID Number : <span class="uk-badge"><?php echo $member_id; ?></span><br>
            Full Name : <b><?php echo $full_name; ?></b><br>
            Contact Number : <b><?php echo $contact_number; ?></b><br>
            Location : <b><?php echo $location; ?></b><br>
            Group : <b><?php echo $group; ?></b><br>            
        </div>
    </div>
<?php
}
else
{
    echo "<h6 align='center' style='color:red !important'>No Active member found</h6>";
}
?>
    <hr>
    
    <h5 align="center">Log Trail</h5>    
                <?php  
                $xdata=$db->getIndLogs($member_id);
                if(count($xdata)>0)  
                {     
                foreach ($xdata as $row) {                    
                    $member_id = $row["member_id"];
                    $full_name = $row["first_name"]." ".$row["last_name"];
                    $contact_number = $row["contact_number"];
                    $date_entered = $row["new_date_entered"];
                    $entered_by = $row["new_entered_by"];
                    $location = $row["location_name"];
                    $group = $row["group_name"];
                    
                    echo "<div class='row'>";
                    echo "<div class='col-md-12'>";
                    echo "Member ID Number : <span class='uk-badge'>$member_id</span><br>";
                    echo "Full Name : <b>$full_name</b><br>";
                    echo "Contact Number : <b>$contact_number</b><br>";
                    echo "Location : <b>$location</b><br>";
                    echo "Group : <b>$group</b><br>";
                    echo "Changed BY : <b>$entered_by</b><br>";
                    echo "Date Entered : <b>$date_entered</b><br>";
                    echo "</div></div><hr>";
                    
                }
            }
            else
            {
                echo "<h6 align='center' style='color:red !important'>No Logs found</h6>";
            }
        }
        ?>
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
                
              