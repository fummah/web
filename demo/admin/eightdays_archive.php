<?php
session_start();
define("access",true);
$title="8 days - Archive";
require_once("top.php");
$db=new reportsClass();
if(!$db->isTopLevel()){
   header("Location: ../logout.php");
            die();
}
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style>
    .filter-dropdown {
            width: 100%;
            padding: 5px;
            margin-bottom: 5px;
        }
        /* Base Select Styling */
select {
    appearance: none; /* Removes default browser styling */
    -webkit-appearance: none;
    -moz-appearance: none;
    
    background-color: #ffffff;
    border: 2px solid #ddd;
    border-radius: 8px;
    padding: 10px 12px;
    font-size: 16px;
    color: #333;
    cursor: pointer;
    outline: none;
    transition: all 0.3s ease;
    
    /* Adds space for the dropdown arrow */
    background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='gray'><path d='M7 10l5 5 5-5H7z'/></svg>");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 18px;
    padding-right: 40px; /* Ensures text does not overlap the arrow */
}

/* Hover Effect */
select:hover {
    border-color: #aaa;
}

/* Focus Effect */
select:focus {
    border-color: #4CAF50;
    box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
}

/* Disabled Styling */
select:disabled {
    background-color: #f2f2f2;
    color: #aaa;
    cursor: not-allowed;
}

/* For Dropdown Options */
select option {
    background: #fff;
    color: #333;
    padding: 10px;
}

/* Mobile-Friendly Adjustments */
@media (max-width: 600px) {
    select {
        font-size: 14px;
        padding: 8px 10px;
    }
}


.drawer {
  position: fixed;
  top: 0;
  right: -500px; /* Hidden by default */
  width: 500px;
  height: 100%;
  background-color: #fff;
  box-shadow: -2px 0 10px rgba(0,0,0,0.1);
  transition: right 0.3s ease-in-out;
  z-index: 1000;
}

.drawer.open {
  right: 0;
}

.drawer-content {
  padding: 20px;
}

.close-btn {
  background: none;
  border: none;
  font-size: 24px;
  float: right;
  cursor: pointer;
}

</style>
<body class="crm_body_bg">
<?php
require_once("side.php");
?>

<section class="main_content dashboard_part large_header_bg">
<?php
require_once("top_nav.php");
?>

<div class="main_content_iner overly_inner ">
<div class="container-fluid p-0 ">

<div class="row">
<div class="col-4">
<div class="page_title_box d-flex align-items-center justify-content-between">
<div class="page_title_left">
<h3 class="f_s_30 f_w_700 text_white"><?php echo $title;?></h3>

</div>

</div>
</div>


</div>
<div class="col-lg-12">
<div class="white_card card_height_100 mb_20 ">
<div class="white_card_header">
<div class="box_header m-0">
<div class="main-title">


</div>
</div>
</div>
<div class="white_card_body QA_section" style="padding: 1px 1px 1px !important">
<div class="QA_table ">

<!-- Your existing HTML -->
<div class="row">
  <div class="col-sm-6">
    <div class="form-group">
      <div class="input-group">
        <button type="button" class="btn btn-default float-right daterange" id="daterange-btn">
          <i class="far fa-calendar-alt"></i> Date range picker
          <i class="fas fa-caret-down"></i>
        </button>
      </div>
    </div>
  </div>
  <div class="col-sm-6">
    <input type="hidden" id="dat1">
    <input type="hidden" id="dat2">
    <div class="form-group">
      <span class="text text-orange" id="datetxt"></span>
    </div>
  </div>
</div>
<br/>
<div id="DataTables_Table_1_wrapper" class="dataTables_wrapper no-footer">
<?php
          echo "<table id=\"example\" class=\"table table-striped display\" cellspacing=\"0\" width=\"100%\">";
          echo "<thead>";         
          echo "<tr>";   
          echo "<th>";
          echo "Name And Surname";
          echo "</th>";
        
          echo "<th>";
          echo "Claim Number";
          echo "</th>";
           echo "<th>";
          echo "Days Open";
          echo "</th>";
            echo "<th>";
          echo "Medical Scheme";
          echo "</th>";
          echo "<th>";
          echo "Client";
          echo "</th>";
          echo "<th>";
          echo "Username";
          echo "</th>";
          echo "<th>";
          echo "Destination";
          echo "</th>";
          echo "<th>";
          echo "Sub Destination";
          echo "</th>";
          echo "<th>";
          echo "</th>";
       
          echo "</tr>";
       
          echo "</thead>";

          echo "<tbody id='ik'></tbody>";
          
          echo "</table>";

          ?>
</div>
</div>
</div>
</div>
</div>

</div>
</div>
</div>
</div>

</div>
</div>
</div>


<div id="sideDrawer" class="drawer">
  <div class="drawer-content">
    <button onclick="closeDrawer()" class="close-btn">&times;</button>
    <h2>Trail</h2>
    <div id="inffo" style="height: 500px; overflow-y: auto;">
  </div>
</div>

<?php
require_once("footer.php");
?>
<!-- Required JS -->
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript">
 $(function () {
  getData("","");
  $('#daterange-btn').daterangepicker(
    {
      ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      startDate: moment().subtract(29, 'days'),
      endDate: moment()
    },
    function (start, end) {
      $('#datetxt').text(start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
      $('#dat1').val(start.format('YYYY-MM-DD'));
      $('#dat2').val(end.format('YYYY-MM-DD'));
      getData(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
    }
  );
});

function getData(start_date, end_date) {
  $("#ik").html("Loading...");
  $('#example').DataTable().destroy();
  console.log({start_date: start_date,
    end_date: end_date})

  $.ajax({
    url: "../ajax/reports.php",
    type: "GET",
    data: {
      identityNum: 49,
      start_date: start_date,
      end_date: end_date
    },
    dataType: "html", // optional, depending on what your PHP returns
    success: function (data) {
      $("#ik").html(data);
    },
    error: function (jqXHR, exception) {
      console.error("Error loading data:", jqXHR.responseText);
      $("#ik").html("<div class='text-danger'>Failed to load data.</div>");
    },
    complete: function () {
      if ($.fn.DataTable.isDataTable('#example')) {
        $('#example').DataTable().destroy(); // destroy existing instance if needed
      }
      $('#example').DataTable(); // reinitialize
    }
  });
}

  
  function openDrawer(claim_id) {
  document.getElementById("sideDrawer").classList.add("open");

  $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:{
        identityNum:48,
        claim_id:claim_id
          },
      async: true,
      success:function (data) {    
 $("#inffo").html(data);

      },
      error:function (jqXHR, exception) {
        reject(jqXHR.responseText);
      }
    });
}

function closeDrawer() {
  document.getElementById("sideDrawer").classList.remove("open");
}

</script>