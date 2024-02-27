<?php
session_start();
if(isset($_SESSION['logxged']) && !empty($_SESSION['logxged']))
{

}
else{
    die("Connection error");
}
/*
$start_date=date("Y-m-01");
$end_date=date("Y-m-t");
$start_date1=date("01 M Y");
$end_date1=date("t M Y");
*/
$start_date=date("Y-m-d");
$end_date=date("Y-m-d");
$start_date1=date("d M Y");
$end_date1=date("d M Y");
?>
<title>MCA | Analysis</title>
<link rel="shortcut icon" href="../images/favicon.ico"/>
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
<link rel="stylesheet" href="../css/style1.css" />
<script src="../js/uikit.min.js"></script>
<script src="../js/uikit-icons.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="//www.google.com/jsapi"></script>
<script>
    google.load('visualization', '1', {packages: ['imagechart']});
</script>
<script src="analysis.js"></script>
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
 
    .mydivs{
        position: relative;
        margin-right: auto;
        margin-left: auto;
    }
    .load-bar {
  position: relative; 
  width: 100%;
  height: 6px;
  background-color: #fdba2c;
}
.bar {
  content: "";
  display: inline;
  position: absolute;
  width: 0;
  height: 100%;
  left: 50%;
  text-align: center;
}
.bar:nth-child(1) {
  background-color: #54bc9c;
  animation: loading 3s linear infinite;
}
.bar:nth-child(2) {
  background-color: #84c4dc;
  animation: loading 3s linear 1s infinite;
}
.bar:nth-child(3) {
  background-color: #fdba2c;
  animation: loading 3s linear 2s infinite;
}
@keyframes loading {
    from {left: 0; width: 0;z-index:100;}
    33.3333% {left: 0; width: 100%;z-index: 10;}
    to {left: 0; width: 100%;}
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
    .intxt{
        font-family: 'Montserrat',Helvetica,Arial,Lucida,sans-serif;
        font-weight: 300;
        line-height: 1.6em;
        font-size: 14px;
        color: grey;
    }
table{
    border: 1px solid white !important;
}
td{
    border-bottom: 1px solid whitesmoke;
}
.hover-color{
    background-color: black !important; 
    color: white !important;  
}
.yu {
            background: none;
            border: none;
            color: #0066ff;
            text-decoration: underline;
            cursor: pointer;
        }
        .google-visualization-tooltip { pointer-events: none; }
        .uk-search-input{
            border-top: #f1f1f1 !important;
            border-left: #f1f1f1 !important;
            border-right: #f1f1f1 !important;
        }
</style>
<?php
define("access",true);
?>
<div class="main">
    <div class="row">
        <div class="col-sm-2">
            
        <div class="form-group">
                        <hr>
                        <div class="input-group" style="width:100% !important;">
                            <button style="width:100% !important;" type="button" class="uk-button uk-button-secondary uk-button-small float-right daterange" id="daterange-btn">
                                <span uk-icon="calendar"></span> Date range picker
                                <span uk-icon="chevron-down"></span>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" id="dat1" value="<?php echo $start_date;?>">
                    <input type="hidden" id="dat2" value="<?php echo $end_date;?>">
                    <p align="center" style="border-bottom: 1px solid mediumseagreen; text-align: center; font-weight: bolder;" class="text" id="datetxt">
                <?php echo $start_date1 ." - ". $end_date1; ?>
                </p>
                <div class="load-bar">
  <div class="bar"></div>
  <div class="bar"></div>
  <div class="bar"></div>
</div>
                <div class="uk-margin">
            <select class="uk-select" aria-label="Select" id="typ">
                <option value="claims">Claims</option>              
               
            </select>
        </div>
                    All<div class="icheck-turquoise d-inline">
                <input type="radio" class="ccval" value="all" id="radioPrimary3" name="r1" checked>
                <label for="radioPrimary3">
                </label>
              </div>
       Open<div class="icheck-turquoise d-inline">
                <input type="radio" class="ccval" value="1" id="radioPrimary1" name="r1">
                <label for="radioPrimary1">
                </label>
              </div>
               Closed<div class="icheck-turquoise d-inline">
                <input type="radio" id="radioPrimary2" class="ccval" value="0" name="r1">
                <label for="radioPrimary2">
                </label>
              </div>
            <div class="uk-placeholder">
                <div id="ourmain">
                </div>
                    <img src="../images/Med ClaimAssist Logo_1000px.png" alt="">
        </div>
        </div>
        <div class="col-sm-4">        
            <div class="uk-placeholder">
            <h3>Values <span class="badge badge-dark" id="tot_val" title="Grouped Values">0</span> | <span class="badge badge-secondary" id="tot_val1" title="Claims">0</span></h3>
       
            <div class="uk-margin">
                        <form class="uk-search uk-search-default">
                            <span class="uk-search-icon-flip" uk-search-icon></span>
                            <input onkeyup="searchTable()" style="background-color: white !important; width:100% !important;" class="uk-search-input" type="search" id="myInput" placeholder="Search..." onkeyup="searchTable()" aria-label="Search">
                        </form>
                    </div>
                    <div style="overflow-y: scroll; height:70vh;">
                    <table id="myTable" bolder="0">
          
                    </table>
                </div>
            </div>

        </div>
        <div class="col-sm-6">  
       
        <div><span id="submain"></span> | <button class="uk-button-small uk-button-secondary" href="#modal-container" uk-toggle><span uk-icon="grid"></span> View Related Claims </button>  
        <hr> <span id="subval" style="font-weight:bolder; color:#54bc9c"></span></div>
              <hr> 
        <div class="row">
        <div class="col-sm-6">
        <div class="uk-margin">
                        <form class="uk-search uk-search-default">
                            <span class="uk-search-icon-flip" uk-search-icon></span>
                            <input onkeyup="searchTable1()" style="background-color: white !important; width:100% !important;" class="uk-search-input" type="search" id="myInput1" placeholder="Search Panel..." onkeyup="searchTable()" aria-label="Search">
                        </form>
                    </div>
          <div style="overflow-y: scroll; height:70vh; padding: 10px !important;">
          <table id="final" bolder="0">
                    </table>          
          </div>
          <hr>
        </div>   
        <div class="col-sm-6">
        <div style="overflow-y: scroll; height:70vh; padding: 10px !important;">
        <div id="cs_claims" style="width: auto; height: auto;"></div>  
        <div id="clients_claims" style="width: auto; height: auto;"></div>
        <div id="cs_savings" style="width: auto; height: auto;"></div>
        <div id="clients_savings" style="width: auto; height: auto;"></div>
        <div id="cs_claim_value" style="width: auto; height: auto;"></div>
        <div id="clients_claim_value" style="width: auto; height: auto;"></div>
        </div>
        </div>
        </div> 
        <div>
            <hr>
            </div> 
        <hr>     
           </div>
        </div>
    </div>
<!-- Modal Here -->
<div id="modal-container" class="uk-modal-container" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <h2 class="uk-modal-title">Related Claims</h2>
        <table id="example" class="table table-striped no-footer dataTable" cellspacing="0" width="100%" role="grid" aria-describedby="example_info" style="width: 100%;">
    <thead>

      <tr><th>Claim Number</th><th>Full Name</th><th>Username</th><th>Client</th><th>Claim Value</th><th>Sch.Savings</th><th>Disc.Savings</th><th>Total Savings</th></tr>     
</thead>
<tbody id="info">
 </tbody>
</table>
    </div>
</div>
  
