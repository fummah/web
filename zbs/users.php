<?php
session_start();
define("access",true);
$page_name="Users";
include ("classes/DBConnect.php");
$db=new DBConnect();
if(!in_array($db->myRole(),$db->topRoles()) || $db->isChiefSecretary())
{
    header("Location: logout.php");
    die();
}

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

if(isset($_POST["users_submit"]))
{
    $role=htmlspecialchars($_POST["role"]);
    $confirm_password=htmlspecialchars($_POST["confirm_password"]);
    $password=htmlspecialchars($_POST["password"]);
    $location=(int)$_POST["location"];
    $contact_number=htmlspecialchars($_POST["contact_number"]);
    $last_name=ucfirst(htmlspecialchars($_POST["last_name"]));
    $first_name=ucfirst(htmlspecialchars($_POST["first_name"]));
    $username_user=ucfirst(htmlspecialchars($_POST["username"]));
    $contact_number=str_replace(" ","",$contact_number);

    if(strlen($role)<3 || strlen($password)<7 || $location<1 || $password!=$confirm_password || strlen($contact_number)>11 || strlen($contact_number)<10 || strlen($last_name)<3 || strlen($first_name)<3)
    {
        echo "<div class=\"uk-alert-danger\" uk-alert><a class=\"uk-alert-close\" uk-close></a><p align='center'>Invalid Input</p></div>";

    }
    else{
        $contact_number=substr($contact_number,-9);
        $contact_number="+27".$contact_number;
        if($db->searchUserBy($contact_number,"contact_number","members")==true)
        {
            if($db->searchUserBy($username_user,"username","users")==true) {
                echo "<div class=\"uk-alert-danger\" uk-alert><a class=\"uk-alert-close\" uk-close></a><p align='center'>Duplicate User</p></div>";
            }
            else{
                $password = password_hash($password, PASSWORD_DEFAULT);
                if ($db->addUsers($username_user, $password, $db->loggedAs(), $location, $first_name, $last_name, $role)) {
                    echo "<div class=\"uk-alert-success\" uk-alert><a class=\"uk-alert-close\" uk-close></a><p align='center'>User Successfully loaded.</p></div>";

                } else {
                    echo "<div class=\"uk-alert-danger\" uk-alert><a class=\"uk-alert-close\" uk-close></a><p align='center'>Failed to load a user</p></div>";

                }
            }
        }
        else{
            echo "<div class=\"uk-alert-danger\" uk-alert><a class=\"uk-alert-close\" uk-close></a><p align='center'>The User is not registered as a member of ZBS</p></div>";

        }

    }


}
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
    .inl li {
    display: inline;
}
</style>

<button href="#change_pass" id="c_change_pass" style="display: none" uk-toggle></button>
<button href="#update_user" id="c_update_user" style="display: none" uk-toggle></button>
<input type="hidden" id="user_id"/>
<div class="et_pb_code_inner" style="padding: 1px !important; border: 1px solid #cf4522 !important;border-radius: 10px; width: 98%; position: relative; margin-left: auto !important; margin-right: auto !important;">
    <div class="row et_pb_texta" style="padding-top: 20px !important; border-bottom: 1px dashed lightgrey !important;">
        <div class="col-md-4">
        <div class="uk-margin"  style="width: 100% !important; padding-top:10px">
                <form class="uk-search uk-search-default" method="POST">   
                                              
                    <input class="uk-search-input" type="search" name="search_term" value="<?php echo $search_value; ?>" placeholder="Search User"> 
                    <div style="padding: 10px;">  
                    <button class="uk-button uk-button-danger uk-button-small" name="search_button">Search</button>
                </div>
                </form>
            </div>
        </div>
        <div class="col-md-4">
             <?php
                    if(in_array($db->myRole(),$db->topRoles()))
                    {
                    ?>
            <ul class="inl">
 <li id="menu-item-60" class="menu-item menu-item-type-post_type menu-item-object-custom menu-item-home menu-item-60"><a href="track.php"><span uk-icon="cog"></span> Track</a></li> | 
                            <li id="menu-item-60" class="menu-item menu-item-type-post_type menu-item-object-custom menu-item-home menu-item-60"><a href="visitors.php"><span uk-icon="server"></span> Web Visitors</a></li>
            </ul>
             <?php
                    }
                    ?>
        </div>
        <div class="col-md-4">
            <div  style="padding-left: 10px;padding-bottom: 5px"><a href="#add_user" uk-toggle> <button class='uk-button uk-button-default'><span uk-icon="plus-circle" ></span> Add System User</button></a></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <table class="uk-table uk-table-responsive uk-table-divider">
                <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Surname</th>
                    <th>Date Entered</th>
                    <th>Entered By</th>
                    <th>Status</th>
                    <th>Location</th>
                    <th>Role</th>
                    <th>Group</th>
                </tr>
                </thead>
                <?php
                //user_id,username,status,date_entered,entered_by,location
                foreach ($db->users($start_from ,$limit,$search_value,0) as $row) {
                    //a.member_id,b.first_name,b.last_name,a.amount_paid,a.status,a.d_o_d,a.date_entered,a.date_closed,a.entered_by,a._type,a.dependency_id
                    $user_id = $row["user_id"];
                    $username = $row["username"];
                    $status = $row["status"];
                    $date_entered = $row["date_entered"];
                    $entered_by = $row["entered_by"];
                    $location = $row["location_name"];
                    $role = $row["role"];
                    $last_name = $row["last_name"];
                    $group_name = $row["group_name"];
                    $status_name="Activate";
                    $color="uk-button-danger";
                    if($status==1)
                    {
                        $status_name="Deactivate";
                        $color="uk-button-primary";
                    }

                    echo "<tr id='$user_id'>
<td><span class='not_desktop'>User ID :</span><span class=\"uk-badge\">$user_id</span></td>
<td class='maintxt'><span class='not_desktop'>Username : </span><span class='colorMobile'>$username</span></td>
<td class='maintxt'><span class='not_desktop'>Surname : </span><span class='colorMobile'>$last_name</span></td>
<td class='maintxt'><span class='not_desktop'>Date Entered : </span><span class='colorMobile'>$date_entered</span></td>
<td td class='maintxt'><span class='not_desktop'>Entered By : </span><span class='colorMobile'>$entered_by</span></td>
<td><span class='not_desktop'>Status : </span>$status</td><td><span class='not_desktop'>Location : </span><span class='colorMobile'>$location</span></td>
<td><span class='not_desktop'>Role : </span><span class='colorMobile'>$role</span></td>
<td><span class='not_desktop'>Group : </span><span class='colorMobile'>$group_name</span></td>
<td><button class='uk-button' onclick='updateUser(\"$user_id\")'><span uk-icon=\"pencil\" style=\"\"></span> Edit</button></td>
<td>
<button class='uk-button $color' onclick='action(\"$user_id\",\"$status\")'> $status_name</button>
</td>

<td>
<button class='uk-button uk-button-danger' onclick='openChange(\"$user_id\")'><span uk-icon=\"refresh\" style=\"\"></span> Change Password</button>
</td>
<td></td>
</tr>";
                }
                ?>
                </tbody>
            </table>
            <?php
            $total_records=$db->users(1 ,10,$search_value,1);
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


<!-- Add Member -->
<div id="add_user" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title" align="center" style="color: green;padding-top: 30px !important">Add New System User</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" onsubmit="return addUsers()">
            <div class="et_pb_code_inner">
                <div class="et_pb_contact">
                    <div class="et_pb_contact_form clearfix" id="main1">
                        <p class="et_pb_contact_field et_pb_contact_field_0 et_pb_contact_field_half" data-id="first_name" data-type="input">
                           Enter Username :
                            <input type="text" class="input" value="" id="username" name="username" data-required_mark="required" data-field_type="input" min="4" placeholder="Username" REQUIRED>
                            <span id="suggesstion-box" class="et_pb_module et_pb_text et_pb_text_3  et_pb_text_align_left et_pb_bg_layout_light"></span>
                        </p>
                        <p class="et_pb_contact_field et_pb_contact_field_0 et_pb_contact_field_half" data-id="first_name" data-type="input">
                            <label class="et_pb_contact_form_label">First Name</label>
                            <input type="text" class="input" value="" id="first_name" name="first_name" data-required_mark="required" data-field_type="input" placeholder="First Name" REQUIRED>
                            <span id="suggesstion-box" class="et_pb_module et_pb_text et_pb_text_3  et_pb_text_align_left et_pb_bg_layout_light"></span>
                        </p>
                        <p class="et_pb_contact_field et_pb_contact_field_1 et_pb_contact_field_half et_pb_contact_field_last" data-id="last_name" data-type="input">
                            <label class="et_pb_contact_form_label">Last Name</label>
                            <input type="text" class="input" value="" name="last_name" data-required_mark="required" data-field_type="input" id="last_name" placeholder="Last Name" REQUIRED>
                        </p>   <p class="et_pb_contact_field et_pb_contact_field_0 et_pb_contact_field_half" data-id="last_name" data-type="input">
                            <label class="et_pb_contact_form_label">Contact Number</label>
                            <input type="text" class="input" value="" name="contact_number" data-required_mark="required" data-field_type="input" id="contact_number" placeholder="Contact Number" REQUIRED>
                        </p>
                        <p class="et_pb_contact_field et_pb_contact_field_1 et_pb_contact_field_half et_pb_contact_field_last" data-id="last_name" data-type="input">
                            <label class="et_pb_contact_form_label">Location</label>
                            <select id="location" name="location" class="input location" data-required_mark="required" data-field_type="input" style="-webkit-border-radius: 0;-webkit-appearance: none;background-color: #eee;width: 100%;border-width: 0;border-radius: 0;color: #999;font-size: 14px;padding: 16px;" REQUIRED>
                                <option>Select Location</option>
                                <?php

                                ?>
                            </select>
                        </p>
                        <p class="et_pb_contact_field et_pb_contact_field_0 et_pb_contact_field_half" data-id="first_name" data-type="input">
                            <label class="et_pb_contact_form_label">Password</label>
                            <input type="password" class="input" value="" name="password" data-required_mark="required" data-field_type="input" id="password" placeholder="Password" REQUIRED>
                        </p>
                        <p class="et_pb_contact_field et_pb_contact_field_1 et_pb_contact_field_half et_pb_contact_field_last" data-id="last_name" data-type="input" >
                            <label class="et_pb_contact_form_label">Confirm Password</label>
                            <input type="password" class="input" value="" name="confirm_password" data-required_mark="required" data-field_type="input" id="confirm_password" placeholder="Confirm Password" REQUIRED>
                        </p>
                        <p class="et_pb_contact_field et_pb_contact_field_1 et_pb_contact_field_half et_pb_contact_field_last" data-id="last_name" data-type="input">
                            <label class="et_pb_contact_form_label">Role</label>
                            <select id="role" name="role" class="input" data-required_mark="required" data-field_type="input" style="-webkit-border-radius: 0;-webkit-appearance: none;background-color: #eee;width: 100%;border-width: 0;border-radius: 0;color: #999;font-size: 14px;padding: 16px;" REQUIRED>
                                <option value="">Select Role</option>
                                <?php
                                foreach ($db->getRoles() as $rro)
                                {
                                    $role_name=$rro["role_name"];
                                    $role_value=$rro["role_value"];
                                    echo "<option value='$role_value'>$role_name</option>";
                                }

                                ?>
                            </select>
                        </p>
                    </div>

                    <span style="color: red; font-size: 16px; font-weight: bolder; margin-left: 100px" id="msg"></span>
                </div> <!-- .et_pb_code -->
            </div> <!-- .et_pb_column -->
            <p class="uk-text-right">
                <button class="uk-button uk-button-primary" type="submit" name="users_submit">Save</button>
                <button class="uk-button uk-button-default uk-modal-close" type="button">Close</button>
            </p>
        </form>
    </div>
</div>

<div id="change_pass" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title" align="center" style="color: green;padding-top: 30px !important">Change Password</h2>
        <div class="et_pb_code_inner">
            <div class="et_pb_contact">
                <div class="et_pb_contact_form clearfix" id="c_main1">
                    <p class="et_pb_contact_field et_pb_contact_field_0 et_pb_contact_field_half" data-id="first_name" data-type="input">
                        <label class="et_pb_contact_form_label">Password</label>
                        <input type="password" class="input" value="" name="c_password" data-required_mark="required" data-field_type="input" id="c_password" placeholder="Password" REQUIRED>
                    </p>
                    <p class="et_pb_contact_field et_pb_contact_field_1 et_pb_contact_field_half et_pb_contact_field_last" data-id="last_name" data-type="input" >
                        <label class="et_pb_contact_form_label">Confirm Password</label>
                        <input type="password" class="input" value="" name="c_confirm_password" data-required_mark="required" data-field_type="input" id="c_confirm_password" placeholder="Confirm Password" REQUIRED>
                    </p>
                </div>
                <span style="color: red; font-size: 16px; font-weight: bolder; margin-left: 100px" id="c_msg"></span>
            </div> <!-- .et_pb_code -->
        </div> <!-- .et_pb_column -->
        <p class="uk-text-right">
            <button class="uk-button uk-button-primary" type="submit" name="users_submit" onclick="changePassword()">Change Password</button>
            <button class="uk-button uk-button-default uk-modal-close" type="button">Close</button>
        </p>

    </div>
</div>
<div id="update_user" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title" align="center" style="color: green;padding-top: 30px !important">Edit User User</h2>
            <div class="et_pb_code_inner">
                <div class="et_pb_contact">
                    <div class="et_pb_contact_form clearfix" id="main1">

                        <p class="et_pb_contact_field et_pb_contact_field_0 et_pb_contact_field_half" data-id="first_name" data-type="input">
                            <label class="et_pb_contact_form_label">First Name</label>
                            <input type="text" class="input" value="" id="edit_first_name" name="edit_first_name" data-required_mark="required" data-field_type="input" placeholder="First Name" REQUIRED>
                            <span id="suggesstion-box" class="et_pb_module et_pb_text et_pb_text_3  et_pb_text_align_left et_pb_bg_layout_light"></span>
                        </p>
                        <p class="et_pb_contact_field et_pb_contact_field_1 et_pb_contact_field_half et_pb_contact_field_last" data-id="last_name" data-type="input">
                            <label class="et_pb_contact_form_label">Last Name</label>
                            <input type="text" class="input" value="" name="edit_last_name" data-required_mark="required" data-field_type="input" id="edit_last_name" placeholder="Last Name" REQUIRED>
                        </p>   <p class="et_pb_contact_field et_pb_contact_field_0 et_pb_contact_field_half" data-id="last_name" data-type="input">
                            <label class="et_pb_contact_form_label">Contact Number</label>
                            <input type="text" class="input" value="" name="edit_contact_number" data-required_mark="required" data-field_type="input" id="edit_contact_number" placeholder="Contact Number" REQUIRED>
                        </p>
                        <p class="et_pb_contact_field et_pb_contact_field_1 et_pb_contact_field_half et_pb_contact_field_last" data-id="last_name" data-type="input">
                            <label class="et_pb_contact_form_label">Location</label>
                            <select id="edit_location" name="edit_location" class="input location" data-required_mark="required" data-field_type="input" style="-webkit-border-radius: 0;-webkit-appearance: none;background-color: #eee;width: 100%;border-width: 0;border-radius: 0;color: #999;font-size: 14px;padding: 16px;" REQUIRED>

                                <?php

                                ?>
                            </select>
                        </p>
                        <p class="et_pb_contact_field et_pb_contact_field_1 et_pb_contact_field_half et_pb_contact_field_last" data-id="last_name" data-type="input">
                            <label class="et_pb_contact_form_label">Role</label>
                            <select id="edit_role" name="edit_role" class="input" data-required_mark="required" data-field_type="input" style="-webkit-border-radius: 0;-webkit-appearance: none;background-color: #eee;width: 100%;border-width: 0;border-radius: 0;color: #999;font-size: 14px;padding: 16px;" REQUIRED>

                                <?php
                                foreach ($db->getRoles() as $rro)
                                {
                                    $role_name=$rro["role_name"];
                                    $role_value=$rro["role_value"];
                                    echo "<option value='$role_value'>$role_name</option>";
                                }

                                ?>
                            </select>
                        </p>
                    </div>

                    <span style="color: red; font-size: 16px; font-weight: bolder; margin-left: 100px" id="msg"></span>
                </div> <!-- .et_pb_code -->
            </div> <!-- .et_pb_column -->
            <p class="uk-text-right">
                <button class="uk-button uk-button-primary" type="submit" onclick="saveUser()">Save Changes</button>
                <button class="uk-button uk-button-default uk-modal-close" type="button">Close</button>
            </p>
    <span id="infor"></span>
    </div>
</div>