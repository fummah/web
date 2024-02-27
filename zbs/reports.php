<?php
session_start();
define("access",true);
$page_name="Reports";
$_SESSION["admin_main"]=true;
include ("classes/DBConnect.php");
$db=new DBConnect();
require_once ("header.php");
//https://api.ocr.space/parse/imageurl?apikey=helloworld&url=https://pma.medclaimassist.co.za/files/docs/820fc087-3659-4045-9bd0-21f2d419f54d.jpg

?>

<script src="dist/jquery-simple-tree-table.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="js/reports.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = google.visualization.arrayToDataTable(arr_trend);

            var options = {
                title: 'Membership Trend for the last 6 Funerals',
                hAxis: {title: 'Funeral Name',  titleTextStyle: {color: '#333'}},
                vAxis: {minValue: 0}
            };

            var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    });

</script>


<style>
    #results { padding:20px; border:1px solid; background:#ccc; }
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
    .et_pb_text_3 {
        font-family: 'Montserrat',Helvetica,Arial,Lucida,sans-serif;
        font-weight: 300;
        font-size: 14px;
        line-height: 1.6em;
        padding-bottom: 31px!important;
        margin-right: -6px!important;
        margin-bottom: 10px!important;

    }
    @media only screen and (max-width: 600px) {
        .uk-modal-body {
            padding: 0px 0px !important;
        }
    }
    select,input{border-radius: 2px !important;}
.ex{
    display: none;
}
</style>


<div class="et_pb_code_inner" style=" width: 90%; margin-left: auto;margin-right: auto;font-weight: bolder; position: relative; border: 1px solid cadetblue;padding: 5px;border-radius: 7px">
    <div class="row report">
        <div class="col-md-2">
            Total Members : <span class="uk-badge flo" style="background-color: grey"><?php echo $db->getAllMembers(0,0,"",1);?></span>
        </div>

        <div class="col-md-2">
            Active Members : <span class="uk-badge flo" style="background-color: mediumseagreen"><?php echo $db->getmemberType();?></span>
        </div>
        <div class="col-md-2">
            Deactivated Members : <span class="uk-badge flo" style="background-color: goldenrod"><?php echo $db->getmemberType("Deactivated");?></span>
        </div>
        <div class="col-md-2">
            Home Members : <span class="uk-badge flo" style="background-color: cadetblue"><?php echo $db->getmemberType("Home");?></span>
        </div>
        <div class="col-md-4">
            Deceased Members : <span class="uk-badge flo" style="background-color: red"><?php echo $db->getmemberType("Dead");?></span>
            <form class="uk-search uk-search-default" style="width: 100% !important; padding: 5px;">
                <span class="uk-search-icon-flip" uk-search-icon></span>
                <input style="border: 1px solid #0b8278; color: #0b8278" class="uk-search-input" name="search_term_funeral" id="search_term_funeral" type="search" placeholder="Search Funeral">
                <span id="suggesstion-box-funeral" class="et_pb_module et_pb_text et_pb_text_3  et_pb_text_align_left et_pb_bg_layout_light"></span>

            </form>
        </div>
    </div>
    <div id="detf">
        <h4 align="center" style="color: red; display: none" id="spinner">Loading ...</h4>
    </div>
    <div id="chart_div" style="width: 100% !important; height: 500px;"></div>
</div>
<?php
include("footer.php");
?>
<script>
    $('#myTable').simpleTreeTable({
        opened:'none',
    });
</script>
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


</body></html>