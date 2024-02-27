<?php
session_start();
if(isset($_SESSION['logxged']) && !empty($_SESSION['logxged']))
{

}
else{
    die("Connection error");
}
?>

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
<link rel="stylesheet" href="../css/uikit.min.css" />
<script src="../js/uikit.min.js"></script>
<script src="../js/uikit-icons.min.js"></script>
<script src="main.js"></script>
<script src="dist/jquery-simple-tree-table.js"></script>
<script src="jquery-sortable-min.js"></script>
<style>
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
        width: 98% !important;
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
    td,th{

    }
    th{
        color: #54bc9c;
        font-weight: bolder;
        cursor: pointer;
    }
    .cc{
        background-color: white;
        color: darkblue;
    }
    .icount
</style>
<?php
define("access",true);
include "../classes/apiClass.php";
use mcaAPI\apiClass as myAPI;
$api= new myAPI();
?>
<div class="main">
    <div class="row">
        <div class="col-sm-2 firstPanel">
            <div class="row" uk-sticky="offset: 10">
                <div class="col-sm-12">

                    <div class="form-group">
<hr>
                        <div class="input-group">
                            <button type="button" class="uk-button uk-button-secondary uk-button-small float-right daterange" id="daterange-btn">
                                <span uk-icon="calendar"></span> Date range picker
                                <span uk-icon="chevron-down"></span>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" id="dat1">
                    <input type="hidden" id="dat2">
                    <span style="border-bottom: 1px solid mediumseagreen; text-align: center; color: navajowhite; font-weight: bolder;" class="text" id="datetxt"></span>
                <hr>
                        <input type="text" style="border: 1px solid navajowhite !important;" id="myInput" class="uk-input" onkeyup="myFunction()"  placeholder="Search ...">

                    <div class="uk-placeholder" style="color: #54bf99; font-weight: bolder; padding: 12px 12px 12px 12px !important;">
                        <form id="sort-it">
                            <ol id="top_panel">
                            </ol>

                        </form>
                    </div>
                    <div class="uk-placeholder" id="totals_panel" style="color: white">

                    </div>
                </div>


            </div>
        </div>
        <div class="col-sm-10 uk-placeholder">



<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <div class="icheck-primary d-inline">
                <input type="radio" class="ccval" value="clients" id="radioPrimary1" name="r1" checked>
                <label for="radioPrimary1">
                </label>

            </div>
            <label>Clients</label> <div uk-spinner class="uk-animation-fade spinner" style="display: none; color: red"></div>
          <hr>
            <select class="uk-select select2bs4" multiple="multiple" id="clients" data-placeholder="Select Client" style="width: 100%;">

            </select>

        </div>
    </div>
    <div class="col-sm-6 border-left">
        <div class="form-group">
            <div class="icheck-primary d-inline">
                <input type="radio" id="radioPrimary2" class="ccval" value="users" name="r1">
                <label for="radioPrimary2">
                </label>
            </div>
            <label>Users</label>
            <hr>
            <select class="uk-select select2bs4" multiple="multiple" id="users" data-placeholder="Select User"  style="width: 100%;">

            </select>
        </div>
    </div>
</div>
    <div class="row">
        <div class="col-sm-12" style="background-color: white">

            <table border="1" id="myTable" style="width: 100% !important;">
                <thead>
<tr id="tt" class="header">


</tr>
                </thead>
                <tbody id="infor">
<tr><td colspan="5"><p align="center">No selection yet</p></tr></td>
                </tbody>
            </table>

    </div>
</div>
        </div>
    </div>
</div>

<script>
    $('#myTable').simpleTreeTable({
        opened:'none',
    });
    $(document).on('click','th',function(){
        $("th").css("background-color", "white");
        $(this).css("background-color","black");
        var table = $(this).parents('table').eq(0)
        var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()))
        this.asc = !this.asc
        if (!this.asc){rows = rows.reverse()}
        for (var i = 0; i < rows.length; i++){table.append(rows[i])}
    })
    function comparer(index) {
        return function(a, b) {
            var valA = getCellValue(a, index), valB = getCellValue(b, index)
            return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB)
        }
    }
    function getCellValue(row, index){ return $(row).children('td').eq(index).text() }
</script>





