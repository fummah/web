<?php
session_start();
if(isset($_SESSION['logxged']) && !empty($_SESSION['logxged']))
{

}
else{
    die("Connection error");
}
?>
<title>MCA BI Tool</title>
<link rel="stylesheet" href="../../admin/admin_main/plugins/fontawesome-free/css/all.min.css">
<link rel="stylesheet" href="../../admin/admin_main/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
<link rel="stylesheet" href="../../admin/admin_main/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="../../admin/admin_main/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="../../admin/admin_main/dist/css/adminlte.min.css">
<link rel="stylesheet" href="../../admin/admin_main/plugins/daterangepicker/daterangepicker.css">
<script src="../../admin/admin_main/plugins/jquery/jquery.min.js"></script>
<script src="../../admin/admin_main/plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="../../admin/admin_main/plugins/moment/moment.min.js"></script>
<script src="../../admin/admin_main/plugins/daterangepicker/daterangepicker.js"></script>
<script src="../../admin/admin_main/plugins/select2/js/select2.full.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?sensor=false&callback=myMap"></script>
<link rel="stylesheet" href="../css/uikit.min.css" />
<script src="../js/uikit.min.js"></script>
<script src="../js/uikit-icons.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="bi.js"></script>
<script src="dist/jquery-simple-tree-table.js"></script>
<script src="jquery-sortable-min.js"></script>
<script src="jquery.tablesorter.min.js"></script>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@250&display=swap');
    .in { font-size: 1em; } /* prevent zoom in mobile */

    #sort-it>ol {
        /* list style is faked with number inputs */
        list-style: none;
        padding: 0;
    }

    #sort-it>ol>li {
        position: relative;
        min-height: 1em;
        cursor: move;
        padding: .5em .5em .5em 2.5em;
        background: #eee;
        border: 1px solid #ccc;
        margin: .25em 0;
        border-radius: .25em;
        max-width: 14em;
    }

    #sort-it>ol>li .in {
        /* Move these to visually fake the ol numbers */
        position: absolute;
        width: 1.75em;
        left: .25em;
        top: .25em;
        border: 0;
        text-align: center;
        background: transparent;
        color: cadetblue;
    }

    #sort-it>ol>li label {
        /* visually hidden offscreen so it still benefits screen readers */
        position: absolute;
        left: -9999px;
    }

    /* sortable plugin styles when dragged */
    .dragged {
        position: absolute;
        opacity: 0.5;
        z-index: 2000;
    }

    #sort-it>ol>li.placeholder {
        position: relative;
        background: red;
    }
    .main{
        border: 1px solid #54bc9c;
        width: 100% !important;
        padding: 10px;
        position: relative;
        margin-left: auto;
        margin-right: auto;
        border-radius: 10px;
    }
    .firstPanel{
        background-color: #54bc9c
    }
    .daterange{
        border: 1px solid #54bc9c;
    }
    th{
        color: #54bc9c;
        font-weight: bolder;
        cursor: pointer;
    }
    .opt1{
        width: 100% !important;
        color: white;
        margin-bottom: 4px !important;
        margin-top: 4px !important;
    }
    .opt1:hover{
        color : darkblue !important;
        border:1px solid cadetblue !important;
        transition: 0.9s;
    }
    .downmenu{
        background-color: white !important;
        color: #54bf99;
        padding: 8px !important;
        display: none;
    }
    .togglein{
        display: block;
    }

    .main{
        font-family: 'Montserrat', sans-serif !important;
    }
    ::-webkit-scrollbar {
        width: 10px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
        background: lightgrey;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
        background: lightgrey;
    }
    table{
        border:1px solid black;border-collapse:collapse;
        width: 100%;
    }
    td,th{
        border:1px solid #54bf99;
        padding: 7px;
    }
    .mydivs{
        position: relative;
        margin-right: auto;
        margin-left: auto;
    }
    div.scroll {


        overflow-x: auto;
        overflow-y: hidden;
        white-space: nowrap;
    }
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
</style>
<!--http://localhost/demo/coding/bi.php-->
<?php
define("access",true);
//include "../classes/apiClass.php";
//use mcaAPI\apiClass as myAPI;
//$api= new myAPI();
?>
<div class="main">
    <div class="row">
        <div class="col-sm-2 firstPanel">
            <div class="row" uk-sticky="offset: 10">
                <div class="col-sm-12">

                    <div class="form-group">
                        <hr>
                        <div class="input-group" style="width:100% !important;">
                            <button style="width:100% !important;" type="button" class="uk-button uk-button-secondary uk-button-small float-right daterange" id="daterange-btn">
                                <span uk-icon="calendar"></span> Date range picker
                                <span uk-icon="chevron-down"></span>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" id="dat1">
                    <input type="hidden" id="dat2">
                    <p align="center" style="border-bottom: 1px solid mediumseagreen; text-align: center; color: white; font-weight: bolder;" class="text" id="datetxt"></p>
                    <hr>
                    <div class="uk-margin">
                        <form class="uk-search uk-search-default">
                            <span class="uk-search-icon-flip" uk-search-icon></span>
                            <input style="background-color: white !important; width:100% !important;" class="uk-search-input" type="search" id="myInput" placeholder="Search..." onkeyup="searchTable()" aria-label="Search">
                        </form>
                    </div>
                    <div class="uk-placeholder" style="color: #54bf99; font-weight: bolder; padding: 12px 12px 12px 12px !important; height: 550px !important; overflow-y:scroll !important;">

                        <ol id="top_panel">
                        </ol>


                    </div>

                </div>


            </div>
        </div>
        <div class="col-sm-10 uk-placeholder">

            <div class="row" style="width: 100% !important;">
                <div class="col-sm-12" style="background-color: white; width: 100% !important;">


                                       <input type="hidden" id="lastclicked">
                    <h3 align="center" id="bb1" style="background-color: whitesmoke;color: #54bc9c;">Display Panel</h3>
                    <p id="bb" align="center" style="color: red;"></p>

                    <div id="mymap" style="width: 900px; height: 500px; background-color: red; display: none" class="mydivs"></div>
                    <div id="mygraph" style="width: 900px; height: 500px;display: none" class="mydivs"></div>

                    <div class="scroll" id="info" style="width: 100% !important; margin-left: auto; margin-right: auto;height: 600px;overflow:scroll"></div>
                </div>
            </div>
        </div>
        <div class="col-sm-2 uk-placeholder sidebar" style="display: none">

            <div class="row" style="padding-bottom: 10px !important;">

                <div class="col-sm-12 sidebarcontent" style="color:cadetblue">
                  <p><u>Make selection for <span id="sidename"></span></u></p>
                    <div class="uk-margin"><form class="uk-search uk-search-default">
                        <span class="uk-search-icon-flip" uk-search-icon></span>
                        <input style="background-color: white !important; width:100% !important;" class="uk-search-input" type="search" id="searching" placeholder="Search">
                            <span id="suggesstion-box" class="et_pb_module et_pb_text et_pb_text_3  et_pb_text_align_left et_pb_bg_layout_light"></span>
                        </form>
                        </div>
<div class="myrad">
<label><input class="uk-radio" type="radio" name="myrad" value='Top 10' checked> Top 10</label><br>
<label><input class="uk-radio" type="radio" name="myrad" value='Bottom 10'> Bottom 10</label>
</div>

                </div>

            </div>
            <div class="row" style="border-top: 1px dashed lightgrey;padding-top: 10px !important;">
                <p>Select Medical Aid</p>
                <div class="col-sm-12 sidebarcontent1" >
                    <select class="uk-select select2bs4" multiple="multiple" id="medical_scheme" style="width: 100% !important;">

                    </select>
                </div>
                <input type="hidden" id="txtup" value="Top 10">
                <input type="hidden" id="txtstatus" value="All Claims">
            </div><hr>
            <p>Select Status</p>
            <div class="mystatus">
                <label><input class="uk-radio" type="radio" name="mystatus" value='All Claims' checked> All Claims</label><br>
                <label><input class="uk-radio" type="radio" name="mystatus" value='Approved Claims'> Approved Claims</label>
                <label><input class="uk-radio" type="radio" name="mystatus" value='Rejected Claims'> Rejected Claims</label>
            </div>
            <hr>
            <p align="center"></p> <a href="bi.php"><button title="Reset Everything" class="uk-button uk-button-secondary" uk-icon="refresh"></button></a> <a href="../logout.php"><button title="Logout" class="uk-button uk-button-danger" uk-icon="unlock"></button></a></div>
        </div>
    </div>

    <script>
        $('#myTable').simpleTreeTable({
            opened:'none',
        });
        $(document).on('click','.myTable th',function(){
            $("th").css("background-color", "white");
            $(this).css("background-color","black");
            console.log("click 1");
            $(".myTable").tablesorter();
        });
    </script>
