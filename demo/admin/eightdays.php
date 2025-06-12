<?php
session_start();
define("access",true);
$title="Claims with 8 days";
require_once("top.php");
$db=new reportsClass();
if(!$db->isTopLevel()){
   header("Location: ../logout.php");
            die();
}
?>
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
<h3 class="f_s_30 f_w_700 text_white"><?php echo $title;?> <a href="eightdays_archive.php">[Archive]</a></h3>

</div>

</div>
</div>


</div>
<div class="col-lg-12">
<div class="white_card card_height_100 mb_20 ">
<div class="white_card_header">
<div class="box_header m-0">
<div class="main-title">
<!-- Header -->
</div>
</div>
</div>
<div class="white_card_body QA_section" style="padding: 1px 1px 1px !important">
<div class="QA_table ">

<div id="DataTables_Table_1_wrapper" class="dataTables_wrapper no-footer">
<?php
          echo "<table id=\"example\" class=\"table table-striped display\" cellspacing=\"0\" width=\"100%\">";
          echo "<thead>";
          echo "<tr>";
          echo "<th>";
          echo "</th>";
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
       
        

          echo "<tr>";
          echo "<th>";
          echo "</th>";
             echo "<th>";
          echo "</th>";
             echo "<th>";        
          
          echo "</th>";
          echo "<th>";
          echo "<select class='filter-dropdown' id='filter-days'><option value=''>All</option></select>";
          echo "</th>";
           echo "<th>";
           echo "<select class='filter-dropdown' id='filter-scheme'><option value=''>All</option></select>";
          echo "</th>";
            echo "<th>";
            echo "<select class='filter-dropdown' id='filter-client'><option value=''>All</option></select>";
          echo "</th>";
          echo "<th>";
          echo "<select class='filter-dropdown' id='filter-username'><option value=''>All</option></select>";
          echo "</th>";
          echo "<th>";
          echo "<select class='filter-dropdown' id='filter-destination'><option value=''>All</option></select>";
          echo "</th>";
          echo "<th>";
          echo "<select class='filter-dropdown' id='filter-subdestination'><option value=''>All</option></select>";
          echo "</th>";    
          echo "<th>";
          echo "</th>";
          echo "</tr>";
          
          echo "</thead>";
          try {
            date_default_timezone_set('Africa/Johannesburg');
            $holidays=$db->getHolidays();
           
            $date = new DateTime(date("Y-m-d")); // For today/now, don't pass an arg.
            $date->modify("-1 day");
            $xdate= $date->format("Y-m-d");            
              foreach ($db->eightDays1() as $row) {
                $record_index = $row["claim_id"];
                $claim_number = $row["claim_number"];
                $first_name = $row["first_name"];
                $surname = $row["surname"];
                $medical_scheme = $row["medical_scheme"];
                $policy_number=$row["policy_number"];
                $username = $row["username"];
                $client=$row["client_name"];
                $date_entered=$row["date_entered"];
                $destination=$row["destination"];
                $sub_destination=$row["sub_destination"];
                //$date_closed=$row["date_closed"]!== null?$row["date_closed"]:"-";
                //$date_entered = strlen($date_closed)>10?$db->getSingleReopened($record_index):$date_entered;
                $fullname=$first_name." ".$surname;
                $today=date("Y-m-d H:i:s");
                $days=round($db->getWorkingDays($date_entered,$today,$holidays));
                if($days>7) {                  
                  echo "<tr>";
                  echo "<td>";
                  echo "<input class=\"uk-checkbox\" onclick='updateme(\"$record_index\")' id='x$record_index' type=\"checkbox\">";
                  echo "</td>";
                  echo "<td>";
                  echo $fullname;
                  echo "</td>";
                  /*
                  echo "<td>";
                  echo "<div uk-tooltip=\"title: $policy_number\">$policy_number</div>";
                  echo "</td>";
                  */
                  echo "<td>";
                  echo $claim_number;
                  echo "</td>";
                  echo "<td>";
                  echo $days;
                  echo "</td>";
                  echo "<td>";
                  echo $medical_scheme;
                  echo "</td>";
                  echo "<td>";
                  echo $client;
                  echo "</td>";
                  echo "<td>";
                  echo $username;
                  echo "</td>";
                  echo "<td>";
                  echo $destination;
                  echo "</td>";
                  echo "<td>";
                  echo $sub_destination;
                  echo "</td>";
                                
                  echo "<td><button title='View Trail' name='btn' onclick='openDrawer($record_index)' class=\"btn ti-comment\"></button>";

                  echo "<form action='../case_details.php' method='post' target=\"print_popup\" onsubmit=\"window.open('#','print_popup','width=1000,height=800');\"/>";
                  echo "<input type=\"hidden\" name=\"claim_id\" value=\"$record_index\" />";
                  echo "<button title='View Claim' name='btn' class=\"btn ti-arrow-circle-right\"></button></form>";

                  echo "</td>";
                  echo "</tr>";
                }
              }           
          } catch (Exception $re) {
            echo "There is an error : ".$re->getMessage();
          }
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
<script src="./js/users.js"></script>
<script type="text/javascript">
 $(document).ready(function() {
   // $('#example').DataTable();
    var table = $('#example').DataTable({
        //paging: false,
        //info: false
    });

    function populateFilters() {
    $('#example thead tr:eq(1) th').each(function (index) {
        var select = $(this).find('select'); // Get the select element inside the column
        if (select.length === 0) return; // Skip columns without a <select> filter

        var column = table.column(index);

        select.empty().append('<option value="">All</option>'); // Add default "All" option

        column.data().unique().sort().each(function (d) {
            if (select.find('option[value="' + d + '"]').length === 0) {
                select.append('<option value="' + d + '">' + d + '</option>');
            }
        });

        select.on('change', function () {
            var val = $.fn.dataTable.util.escapeRegex($(this).val());
            column.search(val ? '^' + val + '$' : '', true, false).draw();
        });
    });
}


    populateFilters();
  } );
  
  
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