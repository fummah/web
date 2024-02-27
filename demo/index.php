<?php
session_start();
define("access",true);
include ("classes/controls.php");
$control=new controls();
include ("header.php");
$_SESSION["admin_main"]=$_SERVER['REQUEST_URI'];
?>
<title>MCA | Home</title>
<script src="js/main.js"></script>
<script src="js/claim_details_js.js"></script>
<script>
    function resizeIframe(obj) {
        obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
    }

</script>
<link rel="stylesheet" type="text/css" href="css/home_graphs.css"/>
<style>
    .black_overlayx {
        display: none;
        position: absolute;
        top: 0%;
        left: 0%;
        width: 100%;
        height: 100%;
        background-color: black;
        z-index: 1001;
        -moz-opacity: 0.6;
        opacity: .60;
        filter: alpha(opacity=80);
    }
    .white_contentx {
        display: none;
        position: relative;
        top: 0%;
        margin-left: auto;
        margin-right: auto;
        width: 100%;
        height: 100%;
        padding: 3px;
        border: 3px solid blue;
        background-color: white;
        z-index: 1002;
        overflow: auto;
    }

</style>

<div id="fade" class="black_overlayx"></div>
<div class="uk-placeholder" style="background-color: white">
    <div class="uk-grid uk-animation-bottom-left">
        <div class="col-md-1">Sch_Savings<hr><span class="badge rounded-pill bg-info text-dark" id="scheme_savings">0.00</span></div>
        <div class="col-md-1">Dis_Savings<hr><span class="badge rounded-pill bg-info text-dark" id="discount_savings">0.00</span></div>
        <div class="col-md-1">Tot_Savings<hr><span class="badge rounded-pill bg-info text-dark" id="total_savings">0.00</span></div>
        <div class="col-md-1">Avg_Days<hr><span class="badge rounded-pill bg-info text-dark" id="average_closed">0</span></div>
        <div class="col-md-1">Closed_Cases<hr><span class="badge rounded-pill bg-info text-dark" id="closed_claims">0</span></div>
        <div class="col-md-1">Claims_Total<hr><span class="badge rounded-pill bg-info text-dark" id="claims_entered">0</span></div>
        <div class="col-md-1"></div>

        <div class="col-md-5 other" style="border-left: 1px solid #54bf99;">
            <?php
            if($control->isInternal())
            {
                ?>
                <div class="row">
                    <div class="col-md-3" style="padding: 3px"><div class="uk-inline" title="Members to be contacted"><span class="uk-badge" style="font-size: 18px;cursor: pointer;padding: 15px !important; background-color:#54bc9c;"><span id="member_total">0</span> <span uk-icon="chevron-right" class="tablethid"></span> <span class="tablethid"> Memb(s)</span></span><div uk-dropdown="mode: hover" id="member_append"></div></div></div>
                    <div class="col-md-2" style="padding: 3px"><div class="uk-inline" title="New Files"><span class="uk-badge" style="font-size: 18px;cursor: pointer;padding: 15px !important;background-color:#54bc9c;"><span id="file_total">0</span> <span uk-icon="chevron-right" class="tablethid"></span><span class="tablethid"> Files</span></span><div uk-dropdown="mode: hover" id="file_append"></div></div></div>
                    <div class="col-md-4" style="padding: 3px"><div class="uk-inline" title="Claims with zero amounts"><span class="uk-badge" style="font-size: 18px;cursor: pointer;padding: 15px !important;background-color:#54bc9c;"><span id="zero_total">0</span> <span uk-icon="chevron-right" class="tablethid"></span> <span class="tablethid">Zero Amts</span></span><div uk-dropdown="mode: hover" id="zero_append"></div></div></div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>
<?php
if($control->isInternal())
{
    ?>
    <div class="row" style="border: 1px solid #54bf99; width: 95%; margin-left: auto;margin-right: auto; position: relative;padding: 10px">
        <div class="col-md-7">
            <div class="row" style="padding-top: 50px">
                <div class="col-md-3" title="Claims with more than 2 days without any notes"><div class="pie animate no-round" id="purple" style="--p:40;--c:purple;"> <span uk-spinner></span></div><br><br><div class="uk-badge" style="font-size: 20px;background-color: purple; color: #fff !important; padding: 20px; width: 50%; position: relative; margin-left: auto; margin-right: auto; cursor: pointer" onclick="window.open('open_claims.php?sla=purple','popup','width=1600,height=1000'); return false;""><span id="purple_num">0</span> <span uk-icon="chevron-right" class="laptophid"></span> <span class="color_total laptophid">0</span></div></div>
            <div class="col-md-3" title="Claims with more than 2 days without notes"><div class="pie animate no-round" id="red" style="--p:70;--c:red;"> <span uk-spinner></span></div><br><br><div class="uk-badge" style="font-size: 20px;background-color: red; color: #fff !important; padding: 20px; width: 50%; position: relative; margin-left: auto; margin-right: auto; cursor: pointer" onclick="window.open('open_claims.php?sla=red','popup','width=1600,height=1000'); return false;""><span id="red_num">0</span> <span uk-icon="chevron-right" class="laptophid"></span> <span class="color_total laptophid">0</span></div></div>
        <div class="col-md-3" title="Claims with exactly 2 days without notes"><div class="pie animate no-round" id="orange" style="--p:80;--c:orange;"> <span uk-spinner></span></div><br><br><div class="uk-badge" style="font-size: 20px;background-color: orange; color: #fff !important; padding: 20px; width: 50%; position: relative; margin-left: auto; margin-right: auto; cursor: pointer" onclick="window.open('open_claims.php?sla=orange','popup','width=1600,height=1000'); return false;"><span id="orange_num">0</span> <span uk-icon="chevron-right" class="laptophid"></span> <span class="color_total laptophid">0</span></div></div>
        <div class="col-md-3" title="Claims with updated notes"><div class="pie animate no-round" id="green" style="--p:90;--c:lightgreen"> <span uk-spinner></span></div><br><br><div class="uk-badge" style="font-size: 20px;background-color: lightgreen; color: #fff !important; padding: 20px; width: 50%; position: relative; margin-left: auto; margin-right: auto; cursor: pointer" onclick="window.open('open_claims.php?sla=green','popup','width=1600,height=1000'); return false;"><span id="green_num">0</span> <span uk-icon="chevron-right" class="laptophid"></span> <span class="color_total laptophid">0</span></div></div>
    </div>
    <div class="row" style="padding-top: 50px">
        <div class="col-md-12">
            <div uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <h3>Notice Board</h3>
                <p>When writing notes please remember that these notes will make up part of a report to the client. Please keep them clear, concise and do not include notes to yourself or reminders. Remember to select the "Close Case? (Yes)" option if you are closing the case.</p>
            </div>
        </div>
    </div>
    </div>
    <div class="col-md-5">
        <div class="uk-card uk-card-default uk-card-body uk-width-1-2@m uk-animation-slide-right uk-animation-slide-right" style="width: 100%">
            <h3 class="uk-card-title"> Current Claim <span uk-icon="chevron-double-right"></span> <b style="color: #54bf99"><span id="claim_number">loading...</span></b></h3>
            <div style="background-color: whitesmoke; padding: 5px">
                <div class="row uk-text-small" style="border-bottom: 1px solid white; padding-bottom: 5px">
                    <div class="col-md-4" title="Member Name">
                        <span uk-icon="user"></span> <span id="full_name" style="word-wrap: break-word;">loading...</span>
                    </div>
                    <div class="col-md-4" title="Contact Number">
                        <span uk-icon="receiver"></span> <span id="contact_number" style="word-wrap: break-word;">loading...</span>
                    </div>
                    <div class="col-md-4" title="Email Address">
                        <span uk-icon="mail"></span> <span id="email" style="word-wrap: break-word;">loading...</span>
                    </div>
                </div>
                <div class="row" style="padding-top: 5px; border-top; 1px solid white">
                    <div class="col-md-4" title="Client Name">
                        <span uk-icon="star"></span> <span id="client_name" style="word-wrap: break-word;">loading...</span>
                    </div>
                    <div class="col-md-4" title="Policy Number">
                        <span uk-icon="nut"></span> <span id="policy_number" style="word-wrap: break-word;">loading...</span>
                    </div>
                    <div class="col-md-4" title="Incident Date">
                        <span uk-icon="history"></span> <span id="incident_date" style="word-wrap: break-word;">loading...</span>
                    </div>
                </div>
            </div>
            <div class="row" style="padding-top: 8px">
                <div class="input-field col s11">
                    <a id="other_form" data-uk-toggle="target: #modal-full"><button onclick="loadClaim()" class="uk-button uk-button-primary uk-button-small" type="submit" name="action" style="background-color: #54bf99;"><span uk-icon="list"></span> View Claim</button></a>
                    <form style="display: none" id="aspen_form" method="post" action="view_aspen.php"><input type="hidden" class="claim_id" name="claim_id" id="next_claim_id" value=""><button class="btn waves-effect waves-light" type="submit" name="btn" style="border: 1px solid #54bf99"><span uk-icon="list"></span> View Aspen Claim</button></form>
                    <form style="display: none" method="post" action="edit_case.php"><input type="hidden" class="claim_id" name="claim_id" id="next_claim_id" value=""><button class="btn waves-effect waves-light" type="submit" name="action" style="border: 1px solid #54bf99"><span uk-icon="pencil"></span> Edit Claim</button></form>
                </div>
            </div>
            <div id="mynotes">
                <div class="card" aria-hidden="true">
                    <div class="card-body">
                        <h5 class="card-title placeholder-glow">
                            <span class="placeholder col-6"></span>
                        </h5>
                        <p class="card-text placeholder-glow">
                            <span class="placeholder col-7"></span>
                            <span class="placeholder col-4"></span>
                            <span class="placeholder col-4"></span>
                            <span class="placeholder col-6"></span>
                            <span class="placeholder col-8"></span>
                        </p>

                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>
    <iframe src="horse_race_users.php" style="width: 100% !important;" scrolling="no" frameborder="0" onload="resizeIframe(this)"></iframe>


    <?php
}
if($control->isGapCover())
{
    ?>
    <div class="row" style="border: 1px solid #54bf99; width: 95%; margin-left: auto;margin-right: auto; position: relative;padding: 10px">
        <div class="col-md-9">
            <?php
            include_once "all_clients.php";
            ?>
        </div>
        <div class="col-md-3">
            <input class="uk-search-input" type="search" name="search_term" id="search_term" placeholder="Search Claim" value="">
            <button class="uk-button uk-button-primary uk-button-small" onclick="clientSearch()" style="background-color: #54bf99;"><span uk-icon="search"></span> Search</button>

            <br>
            <div id="search_res"></div>
        </div>

    </div>
    <?php
}
?>

<div id="modal-full" class="uk-modal-full" uk-modal>
    <div class="uk-modal-dialog">
        <div id="ddd"></div>
        <button class="uk-modal-close-full uk-close-large" type="button" uk-close></button>
        <div class="uk-flex-middle">

            <div id="claim_details">

            </div>

        </div>


    </div>
</div>

<!-- This is the modal with the default close button -->
<div class="footer-copyright" style="padding-left: 20px">
    <?php
    include "footer.php";
    ?>
</div>
<script>
    $(document).ready(function() {
        $('#example').DataTable();
        $('select').formSelect();
    } );
</script>