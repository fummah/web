<?php
session_start();
define("access",true);
$page_name="Funerals";

include ("classes/DBConnect.php");
$db=new DBConnect();
if(!in_array($db->myRole(),$db->topRoles()))
{
    if(!$db->isSecretary())
    {
        header("Location: logout.php");
        die();
    }

}

require_once ("header.php");
if(isset($_GET["funeral_id"]))
{
?>
 <table class="uk-table uk-table-responsive uk-table-divider">
                <thead>
                <tr>                   
                    <th>Full Name</th>
                    <th>D.O.D</th>
                    <th>Status</th>
                    <th>Contact Person</th> 
                    <th>Contact Person Number</th>
                    <th>Location</th>
                   
                </tr>
                </thead><tbody>
<?php
    $funeral_id=(int)$_GET["funeral_id"];
    foreach ($db->getDeceased($funeral_id) as $row)
    {
        $first_name = $row["first_name"];                    
                    $last_name = $row["last_name"];
                    $status = $row["_type"];                    
                    $date_of_death = $row["date_of_death"];                    
                    $location_name = $row["location_name"];
                    $contact_person = $row["contact_person"];
                    $contact_person_number = $row["contact_person_number"];
                   echo "<tr><td><span class='not_desktop'>Name : </span>$first_name $last_name</td><td><span class='not_desktop'>D.O.B : </span>$date_of_death</td><td><span class='not_desktop'>Status : </span>$status</td><td><span class='not_desktop'>Contact Person : </span>$contact_person</td><td><span class='not_desktop'>Contact Number: </span>$contact_person_number</td>
				   <td><span class='not_desktop'>Location : </span>$location_name</td></tr>";  
    }   
?>
</tbody></table>
<?php
}
?>


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
</body></html>
