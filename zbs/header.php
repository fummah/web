<?php
if(!defined('access')) {
    header("Location: logout.php");
    die();
}
if(isset($_POST["submitgroup"]))
{
	$group_sid=(int)$_POST["groups"];	
	$gr=$db->getGroupInfo($group_sid);

$_SESSION['group_id']=$gr["group_id"];
$_SESSION['group_name']=$gr["group_name"];
$_SESSION['parent_groups']=$gr["parent_groups"];
$db=new DBConnect();
}
?>

<html lang="en-ZA" class="js"><head>
    <title>ZBS | <?php echo $page_name;?></title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" id="dashicons-css" href="css/dashicons.min.css?ver=5.6.7" type="text/css" media="all">
    <link rel="stylesheet" id="admin-bar-css" href="css/admin-bar.min.css?ver=5.6.7" type="text/css" media="all">
    <link rel="stylesheet" id="elementor-icons-css" href="css/elementor-icons.min.css?ver=5.14.0" type="text/css" media="all">
    <link rel="stylesheet" id="parent-style-css" href="css/style.css?ver=5.6.7" type="text/css" media="all">
    <script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/jquery.min.js?ver=3.5.1" id="jquery-core-js"></script>
    <script type="text/javascript" src="js/jquery-migrate.min.js?ver=3.3.2" id="jquery-migrate-js"></script>
    <script type="text/javascript" src="js/customjs.js" id="customjs"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
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

        @media only screen and (max-width: 949px) {
            #ximg{
                width: 70% !important; height: auto;
            }
            .frmSearch{
                width: 90%;
            }
            .icc{width: 90% !important;}
            .et_pb_contact .et_pb_contact_field_half{
                width: 100% !important;
            }
            .toppadd{
                padding-top: 20px !important;
            }
            .colorMobile{
                color: cadetblue !important; font-weight: bolder !important;
            }
            .mydetails{
                padding: 20px 1px 1px 1px !important;

            }
            .flo{
                float: right;
            }
            .report>.col-md-2,.col-md-4{
                padding-bottom: 4px !important;
                padding-top: 4px !important;
                border-bottom: 1px solid lightgrey;
                width: 90%;
                position: relative;
                margin-right: auto;
                margin-left: auto;
            }
        }

        .et_pb_text_3 {
            font-family: 'Montserrat',Helvetica,Arial,Lucida,sans-serif;
            font-weight: 300;
            font-size: 14px;
            line-height: 1.6em;
            padding-bottom: 31px!important;
            margin-right: -6px!important;
            margin-bottom: 10px!important;

        }
        @media only screen and (min-width: 950px) {
            .et_pb_textb{
                font-family: 'Montserrat',Helvetica,Arial,Lucida,sans-serif;
                font-weight: 600;
                font-size: 40px !important;
            }
            .et-animated-content{
                padding-top:70px !important;
            }
            .not_desktop {
                display:none !important;
            }
            #serached_member_infor tr:nth-child(odd){
                background-color: floralwhite !important;
            }
            .border_class{
                border: 1px solid cadetblue !important;border-radius: 10px;
            }
        }
        #top-menu a {
            color: #cf4522 !important;
        }
        .et_mobile_menu li a {
            color: #cf4522 !important;
        }
        input,select{
            font-family: 'Montserrat',Helvetica,Arial,Lucida,sans-serif !important;
            font-weight: 300 !important;
            line-height: 1.6em !important;
        }
        .btnc{
            color: #54bf99 !important;
            background-color: white !important;
            border: 1px solid #54bf99 !important;
            font-family: 'Montserrat',Helvetica,Arial,Lucida,sans-serif !important;
            font-weight: 300 !important;
            font-size: 14px !important;
        }
        .btnc:hover{
            border: 1px solid lightblue !important;
            color: lightblue !important;
        }
        .et_pb_button:after{
            content: "" !important;
        }
        .mobile_menu_bar:before {
            content: "";
        }
        .et_pb_text_3 {
            font-family: 'Montserrat',Helvetica,Arial,Lucida,sans-serif;
            font-weight: 300;
            font-size: 14px;
            line-height: 1.6em;
            padding-bottom: 31px!important;
            margin-right: -6px!important;
            margin-bottom: 10px!important;

        }
        #country-list{
            float:left;
            list-style:none;
            width:190px;
            z-index: 3;
            padding: 2px;
            position: absolute;
            border:#eee 1px solid;
        }
        #country-list li{
            padding: 10px;
            background: #54bf99;
            border-bottom: #E1E1E1 1px solid;
            z-index: 3;
        }
        #country-list li:hover{
            background:lightblue;
            cursor: pointer;
            -webkit-transition: background-color 300ms linear;
            -ms-transition: background-color 300ms linear;
            transition: background-color 300ms linear;
            color: #54bf99;
        }
        /* Paste this css to your style sheet file or under head tag */
        /* This only works with JavaScript,
        if it's not present, don't show loader */
        .no-js #loader { display: none;  }
        .js #loader { display: block; position: absolute; left: 100px; top: 0; }
        .se-pre-con {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url(images/1488.gif) center no-repeat #fff;
        }
    </style>
    <link rel="icon" href="images/logo.jpg" sizes="32x32">
    <link rel="stylesheet" href="css/uikit.min.css" />
    <script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>
    <script src="js/uikit.min.js"></script>
    <script src="js/uikit-icons.min.js"></script>
    <script>
        //paste this code under the head tag or in a separate js file.
        // Wait for window load
        $(window).load(function() {
            // Animate loader off screen
            $(".se-pre-con").fadeOut("slow");;
        });
    </script>
    <div class="se-pre-con"></div>
<body class="page-template-default page page-id-994 logged-in admin-bar theme-Divi woocommerce-js et_color_scheme_green et_pb_button_helper_class et_fixed_nav et_show_nav et_primary_nav_dropdown_animation_fade et_secondary_nav_dropdown_animation_fade et_header_style_left et_pb_footer_columns3 et_cover_background et_pb_gutter windows et_pb_gutters3 et_pb_pagebuilder_layout et_no_sidebar et_divi_theme et-db et_minified_js et_minified_css elementor-default elementor-kit-703 customize-support chrome" data-new-gr-c-s-check-loaded="14.1052.0" data-gr-ext-installed="">

<!-- End Google Tag Manager (noscript) -->
<div id="page-container" class="et-animated-content" style="padding-top: 40px !important; margin-top: -1px;">

    <header id="main-header" data-height-onload="102" data-height-loaded="true" data-fixed-height-onload="0" style="top: 46px;">
        <div class="container clearfix et_menu_container">
            <div class="logo_container">
                <span class="logo_helper"></span>
                <a href="home.php">
                    <img src="images/logo.jpg" alt="ZBS Home" id="logo" data-height-percentage="100" data-actual-width="1263.33" data-actual-height="430.375">
                </a>
            </div>
            <div id="et-top-navigation" data-height="87" data-fixed-height="40" style="padding-left: 329px; color: #cf4522 !important;">
                <nav id="top-menu-nav">
                    <ul id="top-menu" class="nav">
                          <?php
                        if($db->isSecretary())
                        {
                        ?>
                            <li id="menu-item-554" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-554"><a href="funerals.php"><span uk-icon="tag"></span> Funerals</a></li>

                            <?php
                        }
                        if(in_array($db->myRole(),$db->topRoles()))
                        {

                            ?>
                            <li id="menu-item-554" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-554"><a href="funerals.php"><span uk-icon="tag"></span> Funerals</a></li>
                            <li id="menu-item-60" class="menu-item menu-item-type-post_type menu-item-object-custom menu-item-home menu-item-60"><a href="reports.php"><span uk-icon="database"></span> Reports</a></li>
                            <li id="menu-item-60" class="menu-item menu-item-type-post_type menu-item-object-custom menu-item-home menu-item-60"><a href="location.php"><span uk-icon="location"></span> Locations</a></li>
                            <li id="menu-item-60" class="menu-item menu-item-type-post_type menu-item-object-custom menu-item-home menu-item-60"><a href="accounts.php"><span uk-icon="cart"></span> Accounts</a></li>
                            <?php
							if(!$db->isChiefSecretary())
							{
							?>
							<li id="menu-item-60" class="menu-item menu-item-type-post_type menu-item-object-custom menu-item-home menu-item-60"><a href="users.php"><span uk-icon="users"></span> Admin Users</a></li>
                            <?php
							}
                        }
                        else
                        {
                            ?>
                            <li id="menu-item-554" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-554"><a href="#add_member" uk-toggle><span uk-icon="plus-circle"></span> Add Member</a></li>
                     
                            <?php
                        }
                        ?>
                        <li id="menu-item-303" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-303"><a href="logout.php" style="color: red !important;">Logout</a></li>
                    </ul>						</nav>




                <div id="et_mobile_nav_menu" style="color: #cf4522 !important;">
                    <div class="mobile_nav closed">
                        <span class="select_page">TTTTT</span>
                        <span class="mobile_menu_bar mobile_menu_bar_toggle">
                            <span class="" uk-icon="icon: menu;ratio: 2" style="padding-bottom: 10px; color: #54bf99"></span>
                        </span>
                        <ul id="mobile_menu" class="et_mobile_menu" style="color: #cf4522 !important;">
                            <li id="menu-item-554" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-554"><a href="#add_member" uk-toggle><span uk-icon="plus-circle"></span> Add Member</a></li>
                            <?php
                            if(in_array($db->myRole(),$db->topRoles()))
                            {

                                ?>
                                <li id="menu-item-60" class="menu-item menu-item-type-post_type menu-item-object-custom menu-item-home menu-item-60"><a href="funerals.php"><span uk-icon="tag"></span> View Funerals</a></li>
                                <li id="menu-item-303" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-303" ><a href="reports.php" style="color: red !important;"><span uk-icon="git-branch"></span> Reports</a></li>
                                <li id="menu-item-303" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-303" ><a href="location.php" style="color: red !important;"><span uk-icon="location"></span> Locations</a></li>
                                <li id="menu-item-303" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-303" ><a href="users.php" style="color: red !important;"><span uk-icon="users"></span> Admin Users</a></li>
                                <?php
                            }
                            ?>
                            <li id="menu-item-303" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-303" ><a href="logout.php" style="color: red !important;">Logout</a></li>
                        </ul></div>
                </div>				</div> <!-- #et-top-navigation -->
        </div>
        <!-- .container -->
        <a href="" style="text-decoration: #01ff70; pointer-events: auto!important; cursor: pointer; color:deepskyblue"></a>

    </header> <!-- #main-header -->

    <!-- Add Member -->

    <div id="add_member" uk-modal>
        <div class="uk-modal-dialog uk-modal-body">
            <h2 class="uk-modal-title" align="center" style="color: green;padding-top: 30px !important">Add New Member</h2>

            <form method="post" action="home.php" onsubmit="return addMember()">
                <div class="et_pb_code_inner">
                    <div class="et_pb_contact">
                        <div class="et_pb_contact_form clearfix" id="main1">
                            <p class="et_pb_contact_field et_pb_contact_field_0 et_pb_contact_field_half" data-id="first_name" data-type="input">
                                <label class="et_pb_contact_form_label">First Name</label>
                                <input type="text" class="input" style="width: 100% !important;" value="" id="member_first_name" name="member_first_name" data-required_mark="required" data-field_type="input" placeholder="First Name" REQUIRED>
                            </p>
                            <p class="et_pb_contact_field et_pb_contact_field_1 et_pb_contact_field_half et_pb_contact_field_last" data-id="last_name" data-type="input">
                                <label class="et_pb_contact_form_label">Last Name</label>
                                <input type="text" class="input" value="" name="member_last_name" data-required_mark="required" data-field_type="input" id="member_last_name" placeholder="Last Name" REQUIRED>
                            </p>   <p class="et_pb_contact_field et_pb_contact_field_0 et_pb_contact_field_half" data-id="last_name" data-type="input">
                                <label class="et_pb_contact_form_label">Contact Number</label>
                                <input type="text" class="input" value="" name="member_contact_number" data-required_mark="required" data-field_type="input" id="member_contact_number" placeholder="Contact Number">
                            </p>
                            <p class="et_pb_contact_field et_pb_contact_field_1 et_pb_contact_field_half et_pb_contact_field_last" data-id="last_name" data-type="input">
                                <label class="et_pb_contact_form_label">ID Number</label>
                                <input type="text" class="input" value="" name="member_id_number" data-required_mark="required" data-field_type="input" id="member_id_number" placeholder="ID Number">
                            </p>
                            <p class="et_pb_contact_field et_pb_contact_field_0 et_pb_contact_field_half" data-id="first_name" data-type="input">
                                <label class="et_pb_contact_form_label">Email Address</label>
                                <input type="email" class="input" value="" name="member_email_address" data-required_mark="required" data-field_type="input" id="member_email_address" placeholder="Email Address">
                            </p>
                            <p class="et_pb_contact_field et_pb_contact_field_1 et_pb_contact_field_half et_pb_contact_field_last" data-id="last_name" data-type="input">

                                <label class="et_pb_contact_form_label">Location</label>
                                <select id="member_location" name="member_location" class="input location" data-required_mark="required" data-field_type="input" style="-webkit-border-radius: 0;-webkit-appearance: none;background-color: #eee;width: 100%;border-width: 0;border-radius: 0;color: #999;font-size: 14px;padding: 16px;" REQUIRED>
                                    <option value="">Select Location</option></select>
                            </p>
                            <p class="et_pb_contact_field et_pb_contact_field_1 et_pb_contact_field_half et_pb_contact_field_last" data-id="last_name" data-type="input">
                            <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
                                <label><input class="uk-checkbox" type="checkbox" name="paid" id="paid" checked> Paid</label>
                            </div>
                            </p>

                        </div>
                        <span style="color: red; font-size: 16px; font-weight: bolder; margin-left: 100px"  id="member_msg"></span>
                    </div> <!-- .et_pb_code -->
                </div> <!-- .et_pb_column -->
                <p class="uk-text-right" style="border-top:1px solid lightgrey !important;">
                    <button class="uk-button uk-button-primary" type="submit">Save</button>
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Close</button>
                </p>
            </form>
        </div>
    </div>