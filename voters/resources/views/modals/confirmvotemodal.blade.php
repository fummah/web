<div class="modal modal-child fade" id="confirm_vote" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <hr class="horizontal dark">
                <p class="text-uppercase text-sm content-center" align="center"><b>Are you sure you want to vote <span class="text-danger" id="confirm">Yes</span>?</b></p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
			 <div class="modal-body">
			 <p id="modalbody"></p>
			 <p class="wait"></p>
			 </div>
			 
            <div class="modal-footer content-center">
                <button type="submit" class="btn btn-success confirm" onclick="voteNow()">Confirm</button>
                <button type="button" class="btn btn-primary cancel" data-bs-dismiss="modal">Cancel</button>

            </div>
        </div>
    </div>
</div>