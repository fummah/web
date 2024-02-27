<div class="modal fade" id="edit_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <hr class="horizontal dark">
                <p class="text-uppercase text-sm content-center" align="center">Edit</b></p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
			 <div class="modal-body">
			
    <fieldset class="uk-fieldset">

        <legend class="uk-legend">Edit Details</legend>
@if($arr['page']!="legislation")
<div style="display:none">
@endif
        <div class="uk-margin">
		     <label for="example-text-input" class="form-control-label">Date to be voted on</label>
            <input class="uk-input" id="dvote_date" type="date" placeholder="text" aria-label="Input">
        </div>
		@if($arr['page']!="legislation")
        </div>
	@endif
        <div class="uk-margin">
            <input class="uk-input" id="dname" type="text" placeholder="text" aria-label="Input">
        </div>

        <div class="uk-margin">
            <textarea class="uk-textarea" id="ddescription"  rows="5">Test</textarea>
        </div>

    </fieldset>

			 <p align="center" id="save"></p>
            <div class="modal-footer content-center">               
                <button type="button" class="btn btn-success saveedit">Save</button>
                <button type="button" class="btn btn-primary cancel" data-bs-dismiss="modal">Close Window</button>

            </div>
        </div>
    </div>
</div>
</div>