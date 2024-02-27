<?php
session_start();
define("access",true);
$page_name="Home";
include ("classes/DBConnect.php");
$db=new DBConnect();
$limit = 5;
$start_from =0;
$txt_catergory=0;
 $search_value="";
$disabled="disabled";
if ($db->isTopLevel())
{
    $disabled="";
}
require_once ("header.php");
?>
<style>

    .et_pb_texta{
        font-family: 'Montserrat',Helvetica,Arial,Lucida,sans-serif;
        font-weight: 300;
        line-height: 1.6em;
        font-size: 20px;

    }
    .et_pb_textr{
        font-family: 'Montserrat',Helvetica,Arial,Lucida,sans-serif;
        font-weight: 300;
        line-height: 1.6em;
        font-size: 14px;

    }
    table{
        font-family: 'Montserrat',Helvetica,Arial,Lucida,sans-serif;
        font-weight: 300;
        line-height: 1.6em;

    }
    @media only screen and (max-width: 949px) {
        .et_pb_texta{
            padding-left: 20px !important;
        }
        table{
            width: 100% !important;
        }
        .uk-card-body {
            padding: 10px 10px !important;
        }
        #main1{
            padding-left: 10px !important;
        }
        .uk-icon-button{
            width: 25px !important;
            height: 25px !important;
        }

    }
    @media only screen and (max-width: 949px) {
        .uk-search-large .uk-search-input{
            font-size: 1.625rem;
        }
        .f{
            color: #0b0b0b;
            border: 1px solid whitesmoke !important;
        }
        .f1{
            border: 1px solid whitesmoke !important;
        }
        .f2{
            border: 1px solid whitesmoke !important;
        }
        .et_pb_textb {
            font-family: 'Montserrat', Helvetica, Arial, Lucida, sans-serif;
            font-weight: 600;
            font-size: 30px !important;
        }
        #page-container,.et-animated-content {
            padding-top: 35px !important;
        }
        .not_mobile {
            display:none !important;
        }
        .isCenter{
            text-align: center !important;
        }
        #last_funeral_name{
            color: #0b8278 !important;
        }
        .mobileLine{
            border-bottom: 1px solid cadetblue;
        }
        #serached_member_infor tr:nth-child(odd){
            background-color: gold !important;
        }
        .uk-checkbox:checked{
            background-color: limegreen !important;
        }
    }
    .maintxt{
        font-family: 'Montserrat',Helvetica,Arial,Lucida,sans-serif !important;
        font-weight: 300 !important;
        line-height: 1.6em !important;
        color: green;
    }


    .uk-badge{
        background-color: #54bf99 !important;
        border: 1px solid white !important;
        font-family: 'Montserrat',Helvetica,Arial,Lucida,sans-serif !important;
        font-weight: 300 !important;
        font-size: 14px !important;
    }

    .rowdetails{
        border-bottom: 1px solid whitesmoke;
        padding-bottom: 15px !important;
    }
    .boldd{
        font-weight: 400;
    }
    @media only screen and (min-width: 480px) {
        .f{
            color: #0b0b0b;
            border: 1px solid indianred !important;
        }
        .f1{
            border: 1px solid whitesmoke !important;
        }
        .f2{
            border: 1px solid goldenrod !important;
        }
        .mycheck {
            display: block;
            position: relative;
            padding-left: 35px;
            margin-bottom: 12px;
            cursor: pointer;
            font-size: 22px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /* Hide the browser's default checkbox */
        .mycheck input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        /* Create a custom checkbox */
        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 25px;
            width: 25px;
            background-color: #eee;
        }

        /* On mouse-over, add a grey background color */
        .mycheck:hover input ~ .checkmark {
            background-color: #ccc;
        }

        /* When the checkbox is checked, add a blue background */
        .mycheck input:checked ~ .checkmark {
            background-color: #2196F3;
        }

        /* Create the checkmark/indicator (hidden when not checked) */
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        /* Show the checkmark when checked */
        .mycheck input:checked ~ .checkmark:after {
            display: block;
        }

        /* Style the checkmark/indicator */
        .mycheck .checkmark:after {
            left: 9px;
            top: 5px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 3px 3px 0;
            -webkit-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);
        }
        .homeck{
            background-color: #cf4522 !important;
        }
        .marktick{
            background-color: green !important;
        }
    }
    .boldd{
        font-weight: bolder;
    }
.tt>.uk-badge{
    border:1px solid red !important;
}
.status-closed{
background-color: black !important; 
}
.status-open{
background-color: red !important;   
}
</style>

<div class="et_pb_code_inner">
    <!--<hr class="uk-divider-icon">-->
    <div class="row">
        <div class="col-md-4 mobileLine">
<input type="hidden" id="member_id" name="member_id">
            <h1 align="center" class="et_pb_textb" id="maintxt" style="color: green !important;">Welcome to ZBS (<span style="color:red !important"><?php echo $db->getGroupName();?></span>)</h1>
        </div>
        <div class="col-md-4 et_pb_texta tt" style="border-left: 1px solid lightgrey !important; border-right: 1px solid lightgrey !important;">
            <div class="row mobileLine">
                <div class="col-md-5 isCenter"><span class="not_mobile">Last Funeral</span> <hr class="not_mobile">
                    <span style="color: green; font-weight: bold" id="last_funeral_name"></span>

                </div>
                <div class="col-md-7 isCenter"><span class="not_mobile">Current Total Amount<hr></span>
                    <span class="uk-badge status-open" id="last_funeral_status"></span>
                    <span class="uk-badge" style="background-color: black !important;" id="last_amount"></span>
                    <span class="uk-badge" style="background-color: black !important;"><span uk-icon="check" style="color: white !important;"></span> <span id="last_tik"></span></span>
                    <a href=""> <span class="uk-badge" style="background-color: black !important;"><span uk-icon="refresh" style="color: white !important; cursor: pointer"></span> </span></a>
 <a href="loader.php"> <span class="uk-badge" style="background-color: black !important;"><span uk-icon="cloud-upload" style="color: white !important; cursor: pointer"></span> </span></a>               
                    <a href="#add_member" uk-toggle><span uk-icon="plus-circle" style="color: black !important; cursor: pointer"></span></a>
                </div>
            </div>
            <button href="#add_funeral" id="clickFuneral" style="display: none" uk-toggle></button>
            <button href="#details" id="clickDetails" style="display: none" uk-toggle></button>
            <button href="#edit_member" id="clickEditDetails" style="display: none" uk-toggle></button>
        </div>
        <div class="col-md-4">
            <form class="uk-search uk-search-large" onsubmit="return false;">
                <span uk-search-icon><span style="color:gold; display: none" class="uk-animation-fade" id="spinner" uk-spinner></span></span>

                <input class="uk-search-input" name="search_term_txt" id="search_term_txt" type="search" placeholder="Search Member">
                <span id="suggesstion-box-member" class="et_pb_module et_pb_text et_pb_text_3  et_pb_text_align_left et_pb_bg_layout_light"></span>
            </form>
            <input type="hidden" id="orv" value="0">
        </div>
    </div>

    <div class="border_class" style="padding: 1px !important; width: 98%; position: relative; margin-left: auto !important; margin-right: auto !important;">
        <div class="row">
            <div class="col-md-12">
                <table class="uk-table uk-table-responsive uk-table-divider" style="width: 100%">
                    <thead>
                    <tr>
                        <th>Mem.ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Contact Number</th>
                        <th>Status</th>
                        <?php
                        $mem_arr=array_reverse($db->getIndividualFunerals(0,11));
                        foreach($mem_arr as $row)
                        {
                            $funeral_name=$row["funeral_name"];
                            echo "<th class=\"f\" style='transform:rotate(315deg)'><span>$funeral_name</span></th>";
                        }
                        ?>

                        <th class="f2"><a href="#"> <button class="uk-button uk-button-secondary" title="Download PDF"><span uk-icon="icon: download"></span> Download PDF</button></a></th>
                        <td></td>
                    </tr>
                    </thead>
                    <tbody id="serached_member_infor">
                    <?php
                    //`member_id`,`first_name`,`last_name`,`contact_number`,`id_number`,`email_number`,`status`,`entered_by`,`date_entered`,`location`
                    foreach ($db->getAllMembers($start_from ,$limit,$search_value,0) as $row) {
                        $member_id = $row["member_id"];
                        $first_name = $row["first_name"];
                        $last_name = $row["last_name"];
                        $contact_number = $row["contact_number"];
                        $status = $row["status"];
                        $db->loadMemberT($member_id,$first_name,$last_name,$contact_number,$status,$mem_arr);
                    }
                    ?>
                    </tbody>
                </table>
                <?php
/*
                $total_records=$db->getAllMembers(1 ,10,$search_value,1);
                $total_pages = ceil($total_records / $limit);
                $pagLink = "<ul class='pagination'>";
                for ($i=1; $i<=$total_pages; $i++) {
                    $pagLink .= "<li><a href='?search=".$search_value."&page=".$i."'>".$i."</a></li>";
                };
                echo $pagLink . "</ul>";
*/
                ?>
            </div>
<?php
include("footer.php");
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

    <!-- This is the modal -->

</div>

</body></html>
<!-- Add Member -->
<div id="edit_member" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title" align="center" style="color: green;padding-top: 30px !important">Edit : <span id="edit_fullname"></span></h2>
        <div class="et_pb_code_inner">
            <div class="et_pb_contact">
                <div class="et_pb_contact_form clearfix" id="main1">

                    <p class="et_pb_contact_field et_pb_contact_field_0 et_pb_contact_field_half" data-id="first_name" data-type="input">
                        <label class="et_pb_contact_form_label">First Name</label>
                        <input type="text" class="input" value="" id="edit_first_name" name="edit_first_name" data-required_mark="required" data-field_type="input" placeholder="First Name">
                        <span id="suggesstion-box" class="et_pb_module et_pb_text et_pb_text_3  et_pb_text_align_left et_pb_bg_layout_light"></span>
                    </p>
                    <p class="et_pb_contact_field et_pb_contact_field_1 et_pb_contact_field_half et_pb_contact_field_last" data-id="last_name" data-type="input">
                        <label class="et_pb_contact_form_label">Last Name</label>
                        <input type="text" class="input" value="" name="edit_last_name" data-required_mark="required" data-field_type="input" id="edit_last_name" placeholder="Last Name">
                    </p>   <p class="et_pb_contact_field et_pb_contact_field_0 et_pb_contact_field_half" data-id="last_name" data-type="input">
                        <label class="et_pb_contact_form_label">Contact Number</label>
                        <input type="text" class="input" value="" name="edit_contact_number" data-required_mark="required" data-field_type="input" id="edit_contact_number" placeholder="Contact Number">
                    </p>
                    <p class="et_pb_contact_field et_pb_contact_field_1 et_pb_contact_field_half et_pb_contact_field_last" data-id="last_name" data-type="input">
                        <label class="et_pb_contact_form_label">ID Number</label>
                        <input type="text" class="input" value="" name="edit_id_number" data-required_mark="required" data-field_type="input" id="edit_id_number" placeholder="ID Number">
                    </p>
                    <p class="et_pb_contact_field et_pb_contact_field_0 et_pb_contact_field_half" data-id="first_name" data-type="input">
                        <label class="et_pb_contact_form_label">Email Address</label>
                        <input type="text" class="input" value="" name="edit_email_address" data-required_mark="required" data-field_type="input" id="edit_email_address" placeholder="Email Address">
                    </p>
                    <p class="et_pb_contact_field et_pb_contact_field_1 et_pb_contact_field_half et_pb_contact_field_last" data-id="last_name" data-type="input">

                        <label class="et_pb_contact_form_label">Location</label>
                        <select id="edit_location" name="edit_location" class="input location" data-required_mark="required" data-field_type="input" style="-webkit-border-radius: 0;-webkit-appearance: none;background-color: #eee;width: 100%;border-width: 0;border-radius: 0;color: #999;font-size: 14px;padding: 16px;"></select>
                    </p>

                    <p class="et_pb_contact_field et_pb_contact_field_1 et_pb_contact_field_half et_pb_contact_field_last" data-id="last_name" data-type="input">

                        <label class="et_pb_contact_form_label">Status</label>
                        <select id="edit_status" name="edit_status" class="input" data-required_mark="required" data-field_type="input" style="-webkit-border-radius: 0;-webkit-appearance: none;background-color: #eee;width: 100%;border-width: 0;border-radius: 0;color: #999;font-size: 14px;padding: 16px;" <?php echo $disabled;?>>
<option value="Active">Active</option>
<option value="Deactivated">Deactivated</option>
<option value="Dead">Dead</option>
<option value="Home">Home</option>
                        </select>
                    </p>
                    <hr>

                </div>

            </div> <!-- .et_pb_code -->
            <div class="et_contact_bottom_container" id="edit_msg" style="color: red"></div>
        </div> <!-- .et_pb_column -->
        <p class="uk-text-right">
            <button class="uk-button uk-button-primary" type="button" onclick="saveEdit()">Save</button>
            <button class="uk-button uk-button-default uk-modal-close" type="button">Close</button>

        </p>
    </div>
</div>


<div id="details" class="uk-modal-container" uk-modal>
    <div class="uk-modal-dialog uk-modal-body mydetails">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header">
            <h2 class="uk-modal-title" style="color: green;padding-top: 30px !important"><u>Details</u></h2>
        </div>

        <div class="uk-modal-body uk-overflow-auto">
            <div class="row rowdetails">
                <div class="col-md-4">First Name : <span class="boldd" id="details_first_name"></span></div>
                <div class="col-md-4">Last Name : <span class="boldd" id="details_last_name"></span></div>
                <div class="col-md-4">Contact Number : <span class="boldd" id="details_contact_number"></span></div>
            </div>
            <div class="row rowdetails">
                <div class="col-md-4">ID Number : <span class="boldd" id="details_id_number"></span></div>
                <div class="col-md-4">Email Number : <span class="boldd" id="details_email_address"></span></div>
                <div class="col-md-4">Status : <span class="boldd" id="details_status"></span></div>
            </div>
            <div class="row rowdetails">
                <div class="col-md-4">Date Entered : <span class="boldd" id="details_date_entered"></span></div>
                <div class="col-md-4">Entered By : <span class="boldd" id="details_entered_by"></span></div>
                <div class="col-md-4">Location : <span class="boldd" id="details_location"></span>

                </div>
            </div>
            <h4 style="color: green"><u>Dependencies</u></h4>
            <div class="row">
                <div class="col-md-13">
                    <table class="uk-table uk-table-responsive uk-table-divider">
                        <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Surname</th>
                            <th>Date of Birth</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                        </thead>
                        <?php
                        //if(in_array($db->myRole(),$db->topRoles())) {
                            ?>
                            <tr id="mainx" style="background-color: whitesmoke">
                                <td>
                                    <div class="uk-margin">
                                        First Name: <input class="uk-input" type="text" id="dependency_first_name" placeholder="First Name">
                                    </div>
                                <td>
                                    <div class="uk-margin">
                                        Surname : <input class="uk-input" type="text" id="dependency_last_name" placeholder="Surname">
                                    </div>
                                </td>

                                <td>
                                    <div class="uk-margin">
                                        Date of Birth : <input class="uk-input" type="date" id="dependency_dob" placeholder="DOB">
                                    </div>
                                </td>
                                <td>
                                    <div class="uk-margin">
                                        Status :
                                        <select class="uk-select" id="dependency_status">
                                            <option>Wife</option>
                                            <option>Husband</option>
                                            <option>Child</option>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <button class="uk-button uk-button-secondary" onclick="addDependent()">Add</button>
                                    <span id="ddp"></span>
                                </td>

                            </tr>
                            <?php
                       // }
                        ?>
                        <tbody id="mydependencies">
                        </tbody>
                    </table>

                </div>
            </div>
            <hr>
            <h4 style="color: green"><u>Payment History</u></h4>
            <div class="row">
                <div class="col-md-13 xx">
                    <table class="uk-table uk-table-responsive uk-table-divider">
                        <thead>
                        <tr>
                            <th>Funeral</th>
                            <th>Date of Death</th>
                            <th>Amount Paid</th>
                            <th>Date Entered</th>
                            <th>Entered By</th>
                            <th>Payment Status</th>
                        </tr>
                        </thead>

                        <tbody id="myfunerals">
                        </tbody>

                    </table>
                    <p align="center"><button class="uk-button uk-button-primary" onclick="loadMore()">Load More</button>
                        <button class="uk-button uk-button-default uk-modal-close" type="button">Close</button>
                    </p>
                </div>

            </div>
        </div> <!-- .et_pb_code -->
    </div> <!-- .et_pb_column -->


