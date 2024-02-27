<?php
$title="View System Users";
require_once("top.php");
?>
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
<h3 class="m-0"><?php echo $title;?></h3>

</div>
<div class="header_more_tool">
<div class="float-lg-right float-none sales_renew_btns justify-content-end">
<ul class="nav">
<li class="nav-item">
<select class="form-control" id="broker" onchange="selectBroker()">
                        <option value="">[select broker]</option>
                    </select>
</li>


</ul>
</div>
</div>
</div>
</div>
<div class="white_card_body QA_section" style="padding: 1px 1px 1px !important">
<div class="QA_table ">

<div id="DataTables_Table_1_wrapper" class="dataTables_wrapper no-footer">
<table id="example" class="table table-striped dataTable" cellspacing="0" width="100%" role="grid" aria-describedby="example_info" style="width: 100%;"><thead><tr role="row"><th class="sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Claim Number: activate to sort column descending" style="width: 66px;">Claim Number</th><th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Claim Number: activate to sort column ascending" style="width: 58px;">Claim Number</th><th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Date Reopened: activate to sort column ascending" style="width: 71px;">Date Reopened</th><th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Date Closed: activate to sort column ascending" style="width: 48px;">Date Closed</th><th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Last Savings: activate to sort column ascending" style="width: 55px;">Last Savings</th><th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Reason: activate to sort column ascending" style="width: 52px;">Reason</th><th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label=": activate to sort column ascending" style="width: 26px;"></th></tr></thead><tfoot><tr><th rowspan="1" colspan="1">Claim Number</th><th rowspan="1" colspan="1">Client Name</th><th rowspan="1" colspan="1">Date Reopened</th><th rowspan="1" colspan="1">Date Closed</th><th rowspan="1" colspan="1">Last Savings</th><th rowspan="1" colspan="1">Reason</th><th rowspan="1" colspan="1"></th></tr></tfoot><tbody><tr role="row" class="odd"><td class="sorting_1">CG1437</td><td>Cinagi</td><td>2023-08-07 08:44:59</td><td>2023-08-01 15:41:18</td><td>0.00</td><td>System User. </td><td><form action="../../demo/case_details.php" method="post"><input type="hidden" name="claim_id" value="80144"><button title="View Claim" name="btn" class="btn fa fa-eye"></button></form></td></tr><tr role="row" class="even"><td class="sorting_1">GAP0208260 / 11</td><td>Western</td><td>2023-06-27 14:11:14</td><td>2023-06-19 17:03:52</td><td>3822.38</td><td>New Claim Lines</td><td><form action="../../demo/case_details.php" method="post"><input type="hidden" name="claim_id" value="76347"><button title="View Claim" name="btn" class="btn fa fa-eye"></button></form></td></tr><tr role="row" class="odd"><td class="sorting_1">GAP0208260 / 11</td><td>Western</td><td>2023-06-29 08:21:37</td><td>2023-06-29 08:11:57</td><td>3822.38</td><td>System User. </td><td><form action="../../demo/case_details.php" method="post"><input type="hidden" name="claim_id" value="76347"><button title="View Claim" name="btn" class="btn fa fa-eye"></button></form></td></tr><tr role="row" class="even"><td class="sorting_1">GAP0208260 / 11</td><td>Western</td><td>2023-07-03 08:15:24</td><td>2023-07-03 08:10:38</td><td>3822.38</td><td>System User. </td><td><form action="../../demo/case_details.php" method="post"><input type="hidden" name="claim_id" value="76347"><button title="View Claim" name="btn" class="btn fa fa-eye"></button></form></td></tr><tr role="row" class="odd"><td class="sorting_1">GAP0300593 / 3</td><td>Western</td><td>2023-08-07 12:47:24</td><td>2023-06-14 09:41:50</td><td>14794.12</td><td>New Claim Lines</td><td><form action="../../demo/case_details.php" method="post"><input type="hidden" name="claim_id" value="76927"><button title="View Claim" name="btn" class="btn fa fa-eye"></button></form></td></tr><tr role="row" class="even"><td class="sorting_1">GAP0308868 / 4</td><td>Western</td><td>2023-08-11 08:34:24</td><td>2023-06-06 15:19:39</td><td>0.00</td><td>New Claim Lines</td><td><form action="../../demo/case_details.php" method="post"><input type="hidden" name="claim_id" value="77193"><button title="View Claim" name="btn" class="btn fa fa-eye"></button></form></td></tr><tr role="row" class="odd"><td class="sorting_1">KGP0011590 / 4</td><td>Kaelo</td><td>2023-08-08 08:56:37</td><td>2023-07-27 15:59:32</td><td>0.00</td><td>New Claim Lines</td><td><form action="../../demo/case_details.php" method="post"><input type="hidden" name="claim_id" value="79959"><button title="View Claim" name="btn" class="btn fa fa-eye"></button></form></td></tr><tr role="row" class="even"><td class="sorting_1">KGP0697872 / 2</td><td>Kaelo</td><td>2023-08-01 12:09:07</td><td>2023-07-27 15:18:01</td><td>4441.80</td><td>New Claim Lines</td><td><form action="../../demo/case_details.php" method="post"><input type="hidden" name="claim_id" value="79129"><button title="View Claim" name="btn" class="btn fa fa-eye"></button></form></td></tr><tr role="row" class="odd"><td class="sorting_1">KGP1060372 / 1</td><td>Kaelo</td><td>2023-08-16 10:44:35</td><td>2023-07-18 13:29:19</td><td>0.00</td><td>New Claim Lines</td><td><form action="../../demo/case_details.php" method="post"><input type="hidden" name="claim_id" value="79469"><button title="View Claim" name="btn" class="btn fa fa-eye"></button></form></td></tr><tr role="row" class="even"><td class="sorting_1">KGP1074754 / 11</td><td>Kaelo</td><td>2023-06-05 13:03:02</td><td>2023-05-16 13:10:51</td><td>0.00</td><td>New Claim Lines</td><td><form action="../../demo/case_details.php" method="post"><input type="hidden" name="claim_id" value="76208"><button title="View Claim" name="btn" class="btn fa fa-eye"></button></form></td></tr></tbody></table>
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
<?php
require_once("footer.php");
?>
