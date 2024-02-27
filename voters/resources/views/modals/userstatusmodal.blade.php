<div class="modal fade" id="user_status" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <hr class="horizontal dark">
                <p class="text-uppercase text-sm content-center" align="center"><b>Full Name : <span class="text-danger" id="fullname"></span></b></p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
			 <div class="modal-body">
			 <p id="mystatus" class="text-primary"></p>

			 </div>
			 
            <div class="modal-footer content-center">
                <button type="submit" class="btn btn-success confirm" onclick="voteElectionNow()">Activate</button>
                <button type="button" class="btn btn-primary">Deactivate</button>

            </div>
			 <div class="modal-footer content-center">          
                <button type="button" class="btn" data-bs-dismiss="modal">Close Window</button>
            </div>
        </div>
    </div>
</div>