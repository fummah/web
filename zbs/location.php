<?php
session_start();
define("access",true);
$page_name="Locations";
include ("classes/DBConnect.php");
$db=new DBConnect();
if(!in_array($db->myRole(),$db->topRoles()))
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
if (isset($_POST["submit_location"]))
{
    $location_name=ucfirst(htmlspecialchars($_POST["location_name"]));
    if(strlen($location_name)>2) {
        if ($db->getLocation($location_name) == true) {

        } else {
            if ($db->addLocation($location_name, $db->loggedAs())) {
                echo "<div class=\"uk-alert-success\" uk-alert><a class=\"uk-alert-close\" uk-close></a><p align='center'>Location Successfully loaded.</p></div>";
            } else {
                echo "<div class=\"uk-alert-danger\" uk-alert><a class=\"uk-alert-close\" uk-close></a><p align='center'>Failed to add location</p></div>";
            }
        }
    }
    else{
        echo "<div class=\"uk-alert-danger\" uk-alert><a class=\"uk-alert-close\" uk-close></a><p align='center'>Invalid Location name</p></div>";

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
</style>
<div class="et_pb_code_inner" style="padding: 1px !important; border: 1px solid #cf4522 !important;border-radius: 10px; width: 98%; position: relative; margin-left: auto !important; margin-right: auto !important;">
    <div class="row et_pb_texta" style="padding-top: 20px !important; border-bottom: 1px dashed lightgrey !important;">
        <div class="col-md-4">
        <div class="uk-margin"  style="width: 100% !important; padding-top:10px">
                <form class="uk-search uk-search-default" method="POST">   
                                              
                    <input class="uk-search-input" type="search" name="search_term" value="<?php echo $search_value; ?>" placeholder="Search Location"> 
                    <div style="padding: 10px;">  
                    <button class="uk-button uk-button-danger uk-button-small" name="search_button">Search</button>
                </div>
                </form>
            </div>
        </div>
        <div class="col-md-4">

        </div>
        <div class="col-md-4">
            <div  style="padding-left: 10px;padding-bottom: 5px"><a href="#add_location" uk-toggle> <button class='uk-button uk-button-default'><span uk-icon="plus-circle" ></span> Add New Location</button></a></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <table class="uk-table uk-table-responsive uk-table-divider">
                <thead>
                <tr>
                    <th>Location ID</th>
                    <th>Location Name</th>
                    <th>Date Entered</th>
                    <th>Entered By</th>
                    <th>Number of Members</th>
                    <th>Group</th>
                </tr>
                </thead>
                <?php
                //user_id,username,status,date_entered,entered_by,location
                foreach ($db->locations($start_from ,$limit,$search_value,0) as $row) {
                    //a.member_id,b.first_name,b.last_name,a.amount_paid,a.status,a.d_o_d,a.date_entered,a.date_closed,a.entered_by,a._type,a.dependency_id
                    $location_id = $row["location_id"];
                    $location_name = $row["location_name"];
                    $date_entered = $row["date_entered"];
                    $entered_by = $row["entered_by"];
                    $group_name = $row["group_name"];
                    $number=$db->getLocationMembers($location_id);

                    echo "<tr>
<td><span class='not_desktop'>Location ID : </span><span class=\"uk-badge\"><span class='colorMobile'>$location_id</span></span></td>
<td class='maintxt'><span class='not_desktop'>Location Name : </span> <span class='colorMobile'>$location_name</span></td>
<td class='maintxt'><span class='not_desktop'>Date Entered : </span> <span class='colorMobile'>$date_entered</span></td>
<td td class='maintxt'><span class='not_desktop'>Location Name : </span><span class='colorMobile'>$entered_by</span></td>
<td><span class='not_desktop'>Total Members : </span><span class='colorMobile'>$number</span> </td>
<td><span class='not_desktop'>Group : </span><span class='colorMobile'> Group $group_name</span> </td>

</tr>";
                }
                ?>
                </tbody>
            </table>
            <?php
            $total_records=$db->locations(1 ,10,$search_value,1);
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


<!-- Add Location -->

<div id="add_location" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title" align="center" style="color: green; padding-top: 30px !important">Add New Location</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
            <div class="et_pb_code_inner">
                <div class="et_pb_contact">
                    <div class="et_pb_contact_form clearfix" id="main1">

                        <div class="uk-margin">
                            <textarea class="uk-textarea" rows="5" name="location_name" placeholder="Type Location" REQUIRED></textarea>
                        </div>

                        <hr>

                        <div class="et_contact_bottom_container" id="msg"></div>

                    </div>
                    <span style="color: #70a0d0; font-size: 16px; font-weight: bolder; margin-left: 100px" id="demo"></span>
                </div> <!-- .et_pb_code -->
            </div> <!-- .et_pb_column -->
            <p class="uk-text-right">
                <button class="uk-button uk-button-primary" type="submit" name="submit_location">Save</button>
                <button class="uk-button uk-button-default uk-modal-close" type="button">Close</button>
            </p>
        </form>
    </div>
</div>
