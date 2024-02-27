<?php
session_start();
//error_reporting(0);
define("access",true);
## Database configuration
include ("classes/controls.php");
$control=new controls();

$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value

$fields="DISTINCT a.id as claim_id,b.id as member_id,loyalty_number,a.file_name,membership_number,beneficiary_name,beneficiary_scheme_join_date,beneficiary_id_number,beneficiary_date_of_birth,co_payment,discharge_date,admission_date,procedure_date,a.claim_number";

## Total number of records without filtering
$status="Pending";
$all=$control->viewPendingSplitClaims($status,$fields,$row,$rowperpage,$columnIndex,$columnName,$columnSortOrder,$searchValue);

## Response

$response = array(
  "draw" => intval($draw),
  "iTotalRecords" => $all["totalRecords"],
  "iTotalDisplayRecords" => $all["totalRecordwithFilter"],
  "aaData" => $all["data"]
);

echo json_encode($response);