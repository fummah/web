<?php
session_start();
define("access",true);
$page_name="Add Funeral";

include ("classes/DBConnect.php");
$db=new DBConnect();
if(!in_array($db->myRole(),$db->topRoles()))
{    
        header("Location: logout.php");
        die();   

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

</style>
<?php
 if($db->isOpenFuneral())
    {
        echo("<div class='uk-card uk-card-body'><p align='center'>There is an open Funeral, you cannot add another one. Please close the current funeral and try again.</p></div>");
    }
	else
	{
?>
<input type="hidden" id="member_id" name="member_id">
<div class="et_pb_code_inner " style="padding: 1px !important; border: 1px solid #cf4522 !important;border-radius: 10px; width: 98%; position: relative; margin-left: auto !important; margin-right: auto !important;">
   <h3 align="center">Add New Funeral</h3>
    <div class="row uk-placeholder">
        <div class="col-md-3">

<div class="uk-margin">
    <label class="uk-form-label" for="form-stacked-select">Select Funeral Type</label>
    <div class="uk-form-controls">
        <select class="uk-select" id="funeral_type">
            <option value="Single">Single</option>
            <option value="Combined">Combined</option>
        </select>
    </div>
</div>           
        </div>
		  <div class="col-md-3">   
  <div class="uk-margin">
  <label class="uk-form-label" for="form-stacked-text">Price (R)</label>
  <div class="uk-form-controls">  
                               <input class="uk-input" type="number" id="price" name="price" placeholder="Enter price" REQUIRED>
                            </div>		
                            </div>		
        </div>
        <div class="col-md-3">
		<div class="uk-margin">
                                Final Date for Payments : <input class="uk-input" type="date" id="final_payment_date" name="final_payment_date" placeholder="" REQUIRED>
                            </div>
		</div>
        <div class="col-md-3">

<div class="uk-margin">
<label class="uk-form-label" for="form-stacked-text">Select Member</label>
        <div class="uk-form-controls">  
        
            <input class="uk-input" name="search_term_txt" id="search_term_txt" type="search" placeholder="Search Member">
            <span id="suggesstion-box-member" class="et_pb_module et_pb_text et_pb_text_3  et_pb_text_align_left et_pb_bg_layout_light"></span>
       
        </div>
        <input type="hidden" id="orv" value="0">
</div>           
        </div>
      
    </div>
    <div id="deceased">
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

                        <th></th>
                        <td></td>
                    </tr>
                    </thead>
                    <tbody id="serached_member_infor">
                    
                    </tbody>
                </table>
				
				<div class="row uk-placeholder" id="fuinfo" style="display:none">
				<div class="col-md-2">
				<div class="uk-margin">
                                Date of Death : <input class="uk-input" type="date" id="d_o_d" name="d_o_d" placeholder="" REQUIRED>
                            </div>
				</div>
				
				<div class="col-md-2">
				 <div class="uk-margin">
                                Family Member(Contact Person) : <input class="uk-input" type="text" placeholder="" name="family_member" id="family_member" REQUIRED>
                            </div>
				</div>
				
				<div class="col-md-2">
				 <div class="uk-margin">
                                Phone Number : <input class="uk-input" type="text" placeholder="" id="family_member_phone" name="family_member_phone" REQUIRED>
                            </div>
				</div>
				
			
				
				<div class="col-md-2">
				 <div class="uk-margin" id="ddep">
                                Select Status :
                                <select class="uk-select" id="state_mem" name="state_mem">
                                    <option value="Owner">Owner</option>
                                    <option value="Dependency">Dependency</option>
                                </select>
                            </div>
				</div>
				
				<div class="col-md-2">
				  <button class="uk-button uk-button-primary" type="submit" id="submit_funeral" onclick="addFuneralList()">Add Deceased</button>
				</div>
				</div>
		
</div>
<h6 align="center">Deceased Added</h6>
<div class="uk-placeholder">
<table class='uk-table uk-table-divider' style="color:redss" id="deceased_names">

</table>

<p align="center" id="xbtn" style="display:none" uk-margin>
    <button class="uk-button uk-button-danger" onclick="createFuneral()">Create Funeral</button>
 
</p>
<p align="center" id="result" uk-margin>  
 
</p>
</div>
</div>
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

<!-- Add Member -->

