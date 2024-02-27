<?php
session_start();
define("access",true);
include ("classes/controls.php");
include ("templates/claim_templates.php");
$control=new controls();
if(!isset($_POST["quick_view"])){
    include ("header.php");
    ?>
    <title>MCA | Claim Details</title>
    <?php
}
?>
<script src="js/claim_details_js.js"></script>

<div class="row" style="margin-bottom: 3px !important;padding-top: 10px !important;box-shadow: 1px 1px 5px 2px white;border: 1px solid #e6e6e6; font-size: 14px !important; border-radius: 5px">
            <div class="col-md-10">
                <div class="row rowdetails">
                    <div class="col-md-4">
                        <b><span style="color: red"><span uk-icon="close" class="uk-icon"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill="none" stroke="#000" stroke-width="1.06" d="M16,16 L4,4"></path><path fill="none" stroke="#000" stroke-width="1.06" d="M16,4 L4,16"></path></svg></span> [] Non-PMB, <span style="color: orange !important;">Non-Emergency</span></span></b>
                    </div> <div class="col-md-4">
                        Claim Number : <b>KGP1106071 / 10</b> 
                    </div>
                    <div class="col-md-4">
                        Date Opened/Closed : <b style="color: red"><span style="color: red" class="uk-text-small">2024-02-27 12:45:14 / Open</span> </b>
                    </div>
                </div>
                <div class="row rowdetails">
                    <div class="col-md-4">
                    Policy Number : <b>KGP1106071</b>                 
                       
                    </div>
                    <div class="col-md-4 ">
                         Client Name : <b uk-tooltip="title: ; pos: top-right" style="color: #00b3ee" title="" aria-expanded="false" tabindex="0">Kaelo</b>
                    </div>
                    <div class="col-md-4">
                        Created By : <b>mersham</b>
                    </div>
                </div>
                <div class="row rowdetails">
                    <div class="col-md-4">
                        Full Name : <b>LEIGH-ANNE PALMER</b>
                    </div>
                    <div class="col-md-4">
                        ID Number : <b></b>
                    </div>
                    <div class="col-md-4">
                        Email : <b><a href="mailto:LJOHNSON@WATERFRONT.CO.ZA">LJOHNSON@WATERFRONT.CO.ZA</a></b>
                    </div>
                </div>
                <div class="row rowdetails">
                    <div class="col-md-4">
                        Contact Number(s) : <b>0790602603 / </b>
                    </div>
                    <div class="col-md-4">
                        Incident Date : From <b>2023-08-25</b> To <b>2023-08-25</b>
                    </div>
                    <div class="col-md-4">
                        Patient(s) : <b>Cai Johnson [1601125670087]</b>
                    </div>
                </div>
                <div class="row rowdetails">
                    <div class="col-md-4">
                        Scheme Name : <b>Discovery Health Medical Scheme</b>
                    </div>
                    <div class="col-md-4">
                        Scheme Option : <b>Essential Delta Saver</b>
                    </div>
                    <div class="col-md-4">
                        Member Number : <b>283060</b>
                    </div>
                </div>
            </div>
           
            <div class="col-md-2">
                <div>
                  <div class="uk-placeholder"> 
                        <p>Shirley Adams</p>
                        <p><a href="shirley@medclaimassist.co.za" style="word-wrap: break-word;"> shirley@medclaimassist.co.za</a></p>
                        <p>0210074520</p>
                    </div>
                </div>
            </div>
        </div>