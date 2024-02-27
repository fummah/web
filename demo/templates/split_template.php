<style>
    .pending{
        color: red !important;
        background-color: gold !important;
    }
    .completed{
        background-color: green !important;
        color: white !important;
    }
    .status{
        padding: 12px;
    }
.hideclas{
    display: none;
}
</style>

<button style="display: none" class="uk-button uk-button-default clickme" href="#modal-overflow" uk-toggle>Open</button>

<div id="modal-overflow" class="uk-modal-container mybody" uk-modal>
    <div class="uk-modal-dialog">
        <button class="uk-modal-close-default" type="button" uk-close></button>

        <div class="uk-modal-header">
            <h4 class="uk-modal-title">Claim Lines details <span style="color: green !important;" id="closed_by"></span></h4>
            <h5 class="uk-modal-title" style="color: red" id="lod">loading, wait...</h5>
        </div>

        <div class="uk-modal-body" uk-overflow-auto>
            <div class="row uk-placeholder" style="">
                <div class="col-md-3">Loyalty Number <br> <b><span class="loyalty_number"></span></b></div>
                <div class="col-md-3">Member Name <br> <b><span class="member_name"><b></b></span></b></div>
                <div class="col-md-3">Membership Number <br> <b><span class="member_number"><b></b></span></b></div>
                <div class="col-md-3">Date Entered <br><b><span class="date"></span> </b></div>
            </div>

            <table class="uk-table uk-table-small uk-table-divider">
                <thead>

                <tr>
                    <th>Procedure Date</th>
                    <th>ICD10 Code</th>
                    <th>Tariff Code</th>
                    <th>Charged Amount</th>
                    <th>Scheme Rate</th>
                    <th>Scheme Paid</th>
                    <th>Mem.Portion</th>
                    <th>Copayment</th>

                </tr>
                </thead>
                <tbody id="lines">

                </tbody>
            </table>

        </div>

        <div class="uk-modal-footer uk-text-right">
            <form action="classes/downloadSplit.php" method="POST">
            <input type="hidden" id="xclaim_id" name="xclaim_id">

            </form>
            <div class="row" id="fot">
                <div class="col-md-3"></div>
                <div class="col-md-3">
                    <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
                        <label><input onclick="viewElements()" class="uk-checkbox" id="closetick" type="checkbox"> <span>Close the claim?</span></label>
                    </div>
                </div>
                <div class="col-md-2 hideclas"> <input type="text" id="claim_number" name="claim_number" placeholder="Enter Claim Number"></div>
                <div class="col-md-2 hideclas"><button class="uk-button uk-button-primary hideme" onclick="closeClaim()" type="button"><span uk-icon="check"></span> Save</button></div>

            </div>

        </div>

    </div>
</div>
