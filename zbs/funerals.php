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
$display = in_array($db->myRole(),$db->eRoles()) ?"":"ex";
$limit = 3;
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
if(isset($_POST["close_funeral"]))
{

    $xfuneral_id=(int)$_POST["funeral_idx"];
    $dig=$db->stampExs($xfuneral_id,$db->loggedAs());

    if($dig==1)
    {

        $db->editDiff("status","Closed","funeral_id",$xfuneral_id,"funerals");
        echo "<div class=\"uk-alert-success\" uk-alert><a class=\"uk-alert-close\" uk-close></a><p align='center'>Funeral Successfully closed.</p></div>";

    }
    else
    {
        echo "<div class=\"uk-alert-danger\" uk-alert><a class=\"uk-alert-close\" uk-close></a><p align='center'>Failed to close</p></div>";

    }

}
?>
    <link rel="stylesheet" href="css/simplePagination.css" />
    <script src="js/jquery.simplePagination.js"></script>
<style>
.ex{
    display: none;
}
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
    .stcolor{
        background-color:red !important;
    }
    @media only screen and (max-width: 949px) {
        .uk-search-large .uk-search-input {
            font-size: 1.625rem;
        }
    }
    tbody tr:nth-child(odd){
        background-color: whitesmoke !important;
    }
</style>
<button href="#funeral" id="clickFunerals" style="display: none" uk-toggle></button>
<button href="#funeral_option" id="clickFuneralOptions" style="display: none" uk-toggle></button>
<div class="et_pb_code_inner" style="padding: 1px !important; border: 1px solid #cf4522 !important;border-radius: 10px; width: 98%; position: relative; margin-left: auto !important; margin-right: auto !important;">
    <div class="row et_pb_texta" style="padding-top: 20px !important;padding-left: 15px !important; border-bottom: 1px dashed lightgrey !important;">
 <div class="col-md-2">
           
        </div>
        <div class="col-md-2">
            Total Funerals : <span class="uk-badge" style="background-color: cadetblue !important;"><?php echo $db->getTotalFunerals();?></span>
        </div>
        <div class="col-md-2">
          
                  </div>
        <div class="col-md-3" >
            <?php
            if(!$db->isOpenFuneral())
            {
                if(in_array($db->myRole(),$db->topRoles()))
                {
                ?>
                <span class="uk-button uk-button-danger ppr" onclick="preparePdf()" style="cursor: pointer"><span uk-icon="icon: cloud-download"></span> <span id="prepdf">Prepare PDF</span></span>
                <span style="display: none" id="pfd">
            <a href='pdf/viewdownload.php'>
                <button class="uk-button uk-button-secondary" title="Download PDF">
                    <span uk-icon="icon: cloud-download"></span>
                    Download PDF
                </button>
            </a>
</span>
                <?php
            }
            }
            ?>
        </div>
        <div class="col-md-3">
        <div class="uk-margin"  style="width: 100% !important; padding-top:10px">
                <form class="uk-search uk-search-default" method="POST">   
                                              
                    <input class="uk-search-input" type="search" name="search_term" value="<?php echo $search_value; ?>" placeholder="Search Funeral"> 
                    <div style="padding: 10px;">  
                    <button class="uk-button uk-button-danger uk-button-small" name="search_button">Search</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <table class="uk-table uk-table-responsive uk-table-divider">
                <thead>
                <tr>                   
                    <th>Funeral Name</th>
                    <th>Funeral Type</th>
                    <th>Date Entered</th>
                    <th>Amount Paid</th>                    
                    <?php
                    if(in_array($db->myRole(),$db->topRoles()))
                    {
                    ?>
                    <th>Green Tick</th>
                    <th>Red X</th>
                    <th>Home</th>
                    <th>Advance Ticks</th>
                    <th>Total Amnt</th>
                    <th>Actual Amnt</th>
                    <th>Advance Amnt</th>
                    <?php
                    }
                    ?>
                    <th>State</th>
                    <th></th>
                </tr>
                </thead><tbody>
                <?php

                foreach ($db->funerals($start_from ,$limit,$search_value,0) as $row) {
                    //a.member_id,b.first_name,b.last_name,a.amount_paid,a.status,a.d_o_d,a.date_entered,a.date_closed,a.entered_by,a._type,a.dependency_id
                    $funeral_id = $row["funeral_id"];                    
                    $funeral_name = $row["funeral_name"];                    
                    $amount_paid = $row["amount_paid"];
                    $status = $row["status"];                    
                    $date_entered = $row["date_entered"];
                    $date_closed = $row["date_closed"];
                    $entered_by = $row["entered_by"];
                    $funeral_type = $row["funeral_type"];                   
                    $contact_person = $row["contact_person"];
                    $contact_person_number = $row["contact_person_number"];
                    $stcolor=$status=="Open"?"stcolor":"";
                    $arrx=$db->sumAmounts($funeral_id);
                   
                    $ex = (int)$db->getTotalExs($funeral_id);
                    $paid=$db->getMarkTotal($funeral_id,"paid")-$ex;
                    $unpaid=$db->getMarkTotal($funeral_id,"unpaid");
                    $home=$db->getMarkTotal($funeral_id,"home");
                    
                    $expected_amount=$paid*$amount_paid;

                    $mxx = $db->getTransExSummary($funeral_id);
                    
                    $advanceticks=0;
        $advancetotal=0;
        $xamnt = $ex*$amount_paid;
        if($mxx != false)
        {
            $advancetotal=$mxx["amount"]+$xamnt;
        $advanceticks=$mxx["total"]-$ex;        
        }
        $xrt=$db->getFuneraAmt($funeral_id)-$advancetotal;
        $amountz=$db->moneyformat($xrt);
        $advancetotal=$db->moneyformat($advancetotal);
        
                    $expected_amount=$db->moneyformat($expected_amount);

                    $advanceticks=" $advanceticks <span class='$display'> | $ex | $xamnt</span>";
                    echo "<tr><td><span class='not_desktop'>Funeral Name : </span><span class=\"uk-badge\" style='padding:5px !important; background-color:black !important'><a href='deceased.php?funeral_id=$funeral_id'>$funeral_name</a></span></td>
<td class='maintxt'><span class='not_desktop'>Funeral Type : </span><span class='colorMobile'>$funeral_type</span></td>
<td td class='maintxt'><span class='not_desktop'>Date Entered : </span><span class='colorMobile'>$date_entered</span></td>
<td><span class='not_desktop'>Charged Amount : </span><span class='colorMobile'>$amount_paid</span></td>";
                    if(in_array($db->myRole(),$db->topRoles())){
                    echo "<td><span class='not_desktop'>Green ticks : </span>
<span class=\"uk-badge\" style=\"background-color: whitesmoke !important; color: mediumseagreen !important;\"><span uk-icon=\"check\" style=\"color: mediumseagreen !important;\"></span> $paid</span>
</td>
<td>
<span class='not_desktop'>Red ticks : </span>
<span class=\"uk-badge\" style=\"background-color: whitesmoke !important; color: red !important;\"><span uk-icon=\"close\" style=\"color: red !important;\"></span> $unpaid</span>
</td>
<td>
<span class='not_desktop'>Home ticks : </span>
<span class=\"uk-badge\" style=\"background-color: whitesmoke !important; color: grey !important;\"><span uk-icon=\"home\" style=\"color: grey !important;\"></span> $home</span>
</td>
<td>
<span class='not_desktop'>Home ticks : </span>
<span class=\"uk-badge\" style=\"background-color: whitesmoke !important; color: purple !important;\"><span uk-icon=\"cart\" style=\"color: grey !important;\"></span> $advanceticks</span>
</td>
<td><span class='not_desktop'>Expected Amount : </span><span class='colorMobile'>R $expected_amount</span></td>
<td><span class='not_desktop'>Actual Amount : </span><span class='colorMobile'>R $amountz</span></td>
<td><span class='not_desktop'>Advance Amnt : </span><span class='colorMobile'>R $advancetotal</span></td>";

                }
echo"<td>";
                    if($status=="Open")
                    {
                        if(in_array($db->myRole(),$db->topRoles()))
                        {
                            echo"<span style='cursor: pointer' class=\"uk-badge $stcolor\" onclick='myFOp(\"$funeral_id\")'>$status</span>";

                        }
                        else
                        {
                            echo"<span style='cursor: pointer' class=\"uk-badge $stcolor\">$status</span>";

                        }
                           }
                    else
                    {
                        echo"<span class=\"uk-badge $stcolor\">$status</span>";
                    }


                    echo"</td>
<td><span class='uk-icon-button' style='cursor: pointer; color: #0b8278' uk-icon='settings' onclick='funeralOption(\"$funeral_name\",\"$funeral_id\")'></span></td>

<td></td>
<td></td>
</tr>";
                }
                ?>
                </tbody>
            </table>
            <?php
            $total_records=$db->funerals(1 ,10,$search_value,1);
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

</div><br>
<?php
include("footer.php");
?>
</body></html>

<!-- Add Member -->
<div id="funeral" class="uk-modal-container" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title" align="center" style="color: green">Details Funeral</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
            <input id="funeral_idx" name="funeral_idx" type="hidden">
            <div>
                <div>
                    <div>
                        <table class="uk-table uk-table-responsive uk-table-divider">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Total Amount</th>
                                <th>Expenses</th>
                            </tr>
                            </thead>
                            <tbody id="xfuneral">
                            <tr id="mainx" style="background-color: darkseagreen">
                                <td>
                                    <span class="uk-badge">Today</span>
                                <td>
                                    <div class="uk-margin">
                                        Amount : <input class="uk-input" type="text" id="paid_amount" placeholder="Amount">
                                    </div>
                                </td>

                                <td>
                                    <div class="uk-margin">
                                        Expenses : <input class="uk-input" type="text" id="paid_expenses" placeholder="Expenses">
                                    </div>
                                </td>
                                <td><br><button class="uk-button uk-button-primary uk-button-small" onclick="return addAmounts()">Add Amounts</button></td>

                            </tr>

                            </tbody>
                        </table>
                        <hr>
                    </div>
                    <span style="color: red; font-size: 16px; font-weight: bolder; margin-left: 100px" id="ffuneral_msg"></span>
                </div> <!-- .et_pb_code -->
            </div> <!-- .et_pb_column -->
            <p class="uk-text-right">
                <button class="uk-button uk-button-danger" type="submit" name="close_funeral">Save and Close Funeral</button>
                <button class="uk-button uk-button-default uk-modal-close" type="button">Back</button>
            </p>
        </form>
    </div>
</div>

<div id="funeral_option" class="uk-modal-container" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h3 class="uk-modal-title" align="center" style="color: green; padding-top: 30px"><span id="funeral_name" style="color: #0b8278"></span></h3>
        <hr>
        <div class="uk-margin"  style="width: 100% !important; padding: 5px;right: auto;left: auto;position: relative; background-color: #0b8278">
            <form class="uk-search uk-search-default" style="width: 100% !important;">
                <span class="uk-search-icon-flip" uk-search-icon></span>
                <input style="border: 1px solid gold; color: white" class="uk-search-input" name="search_term_txt" id="search_term_txt" type="search" placeholder="Search Member">
                <span id="suggesstion-box-member" class="et_pb_module et_pb_text et_pb_text_3  et_pb_text_align_left et_pb_bg_layout_light"></span>

            </form>
            <hr>
            <div id="serached_member_infor" style="border-radius: 10px; background-color: white; width: 100%; padding-left:5px;right: auto;left: auto;position: relative;"></div>

        </div>

        <button class="uk-button uk-button-default uk-modal-close" type="button">Back</button>

    </div>
    <input type="hidden" id="orv" value="3">
</div>
