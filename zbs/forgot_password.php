<?php
require_once ("header.php");
?>
<style>
    .password{
        width: 50% !important;
        margin-left: auto;
        margin-right: auto;
        position: relative;
    }
    .username{
        width: 50% !important;
        margin-left: auto;
        margin-right: auto;
        position: relative;
    }
    .pbtn{
        width: 50% !important;
        margin-left: auto;
        margin-right: auto;
        position: relative;
    }
    .et_pb_contact_submit{
        margin-left: 35% !important;
    }
    .talign{
        margin-left: 35% !important;
        padding-top: 5px;
        font-family: 'Montserrat',Helvetica,Arial,Lucida,sans-serif;
        font-weight: 300;
        line-height: 1.6em;
    }

    @media only screen and (max-width: 600px) {
        .password{
            width: 100% !important;
        }
        .username{
            width: 100% !important;
        }
        .et_pb_contact_submit{
            margin-left: 18px !important;
        }
        .talign{
            margin-left: 18px !important;
            padding-top: 5px;
            font-family: 'Montserrat',Helvetica,Arial,Lucida,sans-serif;
            font-weight: 300;
            line-height: 1.6em;
        }
    }
</style>
<div class="et_pb_code_inner">
    <div class="et_pb_contact">
        <div class="et_pb_contact_form frmSearch" id="main1">
            <h1 align="center" class="et_pb_textb">Enter your email address to reset your password</h1>
            <p class="username" data-id="first_name" data-type="input">
                <input type="text" class="input et_pb_textb" id="email" placeholder="Enter you Email"/>

            </p>

            <div class="pbtn">
               <a href="reset_password.php"> <button type="submit" name="et_builder_submit_button" class="et_pb_contact_submit et_pb_button" onclick="changePassword()">Send</button></a>

                <div class="uk-alert-success" uk-alert>
                    <a class="uk-alert-close" uk-close></a>
                    <p>Email successfully sent!!!</p>
                </div>

            </div>


        </div>

    </div> <!-- .et_pb_code -->
    <!-- #page-container -->

    <script type="text/javascript" id="divi-custom-script-js-extra">
        /* <![CDATA[ */
        var DIVI = {"item_count":"%d Item","items_count":"%d Items"};
        var et_shortcodes_strings = {"previous":"Previous","next":"Next"};
        var et_pb_custom = {"ajaxurl":"https:\/\/medclaimassist.co.za\/wp-admin\/admin-ajax.php","images_uri":"https:\/\/medclaimassist.co.za\/wp-content\/themes\/Divi\/images","builder_images_uri":"https:\/\/medclaimassist.co.za\/wp-content\/themes\/Divi\/includes\/builder\/images","et_frontend_nonce":"f5e56487fb","subscription_failed":"Please, check the fields below to make sure you entered the correct information.","et_ab_log_nonce":"a297160c78","fill_message":"Please, fill in the following fields:","contact_error_message":"Please, fix the following errors:","invalid":"Invalid email","captcha":"Captcha","prev":"Prev","previous":"Previous","next":"Next","wrong_captcha":"You entered the wrong number in captcha.","wrong_checkbox":"Checkbox","ignore_waypoints":"no","is_divi_theme_used":"1","widget_search_selector":".widget_search","ab_tests":[],"is_ab_testing_active":"","page_id":"994","unique_test_id":"","ab_bounce_rate":"5","is_cache_plugin_active":"yes","is_shortcode_tracking":"","tinymce_uri":""}; var et_builder_utils_params = {"condition":{"diviTheme":true,"extraTheme":false},"scrollLocations":["app","top"],"builderScrollLocations":{"desktop":"app","tablet":"app","phone":"app"},"onloadScrollLocation":"app","builderType":"fe"}; var et_frontend_scripts = {"builderCssContainerPrefix":"#et-boc","builderCssLayoutPrefix":"#et-boc .et-l"};
        var et_pb_box_shadow_elements = [];
        var et_pb_motion_elements = {"desktop":[],"tablet":[],"phone":[]};
        var et_pb_sticky_elements = [];
        /* ]]> */
    </script>
    <script type="text/javascript" src="https://medclaimassist.co.za/wp-content/themes/Divi/js/custom.unified.js?ver=4.7.6" id="divi-custom-script-js"></script>

    <!-- This is the modal -->

</div>
</body></html>