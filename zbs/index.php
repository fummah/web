<html lang="en-ZA" class="js"><head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="stylesheet" id="dashicons-css" href="css/dashicons.min.css?ver=5.6.7" type="text/css" media="all">
    <link rel="stylesheet" id="admin-bar-css" href="css/admin-bar.min.css?ver=5.6.7" type="text/css" media="all">
    <link rel="stylesheet" id="elementor-icons-css" href="css/elementor-icons.min.css?ver=5.14.0" type="text/css" media="all">
    <link rel="stylesheet" id="parent-style-css" href="css/style.css?ver=5.6.7" type="text/css" media="all">
    <script type="text/javascript" src="js/jquery.min.js?ver=3.5.1" id="jquery-core-js"></script>
    <script type="text/javascript" src="js/jquery-migrate.min.js?ver=3.3.2" id="jquery-migrate-js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="preload" href="fonts/modules.ttf" as="font" crossorigin="anonymous">
    <noscript>
        <style>
            .woocommerce-product-gallery{ opacity: 1 !important; }
        </style>
    </noscript>
    <style type="text/css" media="print">#wpadminbar { display:none; }</style>
    <style type="text/css" media="screen">
        html { margin-top: 32px !important; }
        * html body { margin-top: 32px !important; }
        @media screen and ( max-width: 782px ) {
            html { margin-top: 46px !important; }
            * html body { margin-top: 46px !important; }
        }
        .frmSearch {

            width: 50%;
            position: relative;
            margin-left: auto;
            margin-right: auto;
        }

        @media only screen and (max-width: 600px) {
            #ximg{
                width: 70% !important; height: auto;
            }
            .frmSearch{
                width: 90%;
            }
            .icc{width: 90% !important;}
        }
        .et_pb_contact_submit {
            color: #cf4522 !important;
        }
        .et_pb_textb {
            font-family: 'Montserrat',Helvetica,Arial,Lucida,sans-serif;
            font-weight: 300;
            font-size: 25px;
            line-height: 1.6em;
            color: #cf4522 !important;
        }
        #top-menu a {
            color: rgb(84, 191, 153) !important;
        }
        .et_mobile_menu li a {
            color: rgb(84, 191, 153) !important;
        }
        input,select{
            text-align: center !important;
            font-family: 'Montserrat',Helvetica,Arial,Lucida,sans-serif !important;
            font-weight: 300 !important;
            line-height: 1.6em !important;
        }

        .et_color_scheme_green a {
            color: green !important;
        }
        .et_pb_button:after{
            content: "" !important;
        }
    </style>
    <link rel="icon" href="images/logo.jpg" sizes="32x32">
    <link rel="icon" href="images/logo.jpg" sizes="192x192">
    <link rel="apple-touch-icon" href="images/logo.jpg">
    <meta name="msapplication-TileImage" content="images/logo.jpg">
    <link rel="stylesheet" href="css/uikit.min.css" />
    <script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>
    <script src="js/uikit.min.js"></script>
    <script src="js/uikit-icons.min.js"></script>


<body class="page-template-default page page-id-994 logged-in admin-bar theme-Divi woocommerce-js et_color_scheme_green et_pb_button_helper_class et_fixed_nav et_show_nav et_primary_nav_dropdown_animation_fade et_secondary_nav_dropdown_animation_fade et_header_style_left et_pb_footer_columns3 et_cover_background et_pb_gutter windows et_pb_gutters3 et_pb_pagebuilder_layout et_no_sidebar et_divi_theme et-db et_minified_js et_minified_css elementor-default elementor-kit-703 customize-support chrome" data-new-gr-c-s-check-loaded="14.1052.0" data-gr-ext-installed="">

<!-- End Google Tag Manager (noscript) -->
<div id="page-container" class="et-animated-content" style="padding-top: 40px !important; margin-top: -1px;">

    <header id="main-header" data-height-onload="102" data-height-loaded="true" data-fixed-height-onload="0" style="top: 46px;">
        <div class="container clearfix et_menu_container">
            <div class="logo_container">
                <span class="logo_helper"></span>
                <a href="home.php">
                    <img src="images/logo.jpg" alt="Med Claim Assist Home" id="logo" data-height-percentage="100" data-actual-width="1263.33" data-actual-height="430.375">
                </a>
            </div>
            <!-- #et-top-navigation -->
        </div>
        <!-- .container -->
        <a href="" style="text-decoration: #01ff70; pointer-events: auto!important; cursor: pointer; color:deepskyblue"></a>

    </header> <!-- #main-header -->
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
           display: none;
        }
    }
</style>
<script>
    $(document).ready(function(){
        $("#login").click(function(){
            $("#im").show();
            var user=$("#username").val();
            var pass=$("#password").val();
            $.ajax({
                url:"validatelogin.php",
                type:"POST",
                data:{
                    validnum:99,
                    username:user,
                    password:pass
                },
                success:function(data)
                {
                    console.log(data);
                    var validate=data;
                    if(validate=="Success")
                    {
                        //$("#im").html();
                        $("#im").hide();
                        $("#showerror").hide();
                        location.href = "home.php"
                    }
                    else
                    {
                        $("#im").hide();
                        $("#showerror").show();
                        $("#msg").html(data);

                    }
                },
                error:function(jqXHR, exception)
                {
                    $("#showerror").show();
                    $("#im").hide();
                    $("#msg").html("There is an error : "+jqXHR.responseText);
                }

            });
        });
    });
</script>
<div class="et_pb_code_inner">
        <div class="et_pb_contact">
            <div class="et_pb_contact_form frmSearch" id="main1">
                <h1 align="center" class="et_pb_textb"><img src="images/logo.jpg" width="150" height="150"><br>ZBS Login</h1>
                <p class="username" data-id="first_name" data-type="input">
                    <input type="text" class="input et_pb_textb" id="username" placeholder="Enter Username"/>
                </p>
                <p class="password" data-id="first_name" data-type="input">
                    <input type="password" class="input et_pb_textb" id="password" placeholder="Enter Password"/>
                </p>

                <div class="pbtn">
                    <button type="submit" name="et_builder_submit_button" class="et_pb_contact_submit et_pb_button" id="login">Sign In</button>
                    <p id="icct" align="" class="uk-animation-scale-up talign"><a href="#">Terms & Conditions</a></p>
                    <p align="center" style="display: none;" id="im"><span uk-spinner></span></p>
                    <div class="uk-alert-danger" id="showerror" style="display: none;" uk-alert>
                        <a class="uk-alert-close" uk-close></a>
                        <p id="msg"></p>
                    </div>

                </div>


            </div>

        </div> <!-- .et_pb_code -->
        <!-- #page-container -->

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