<div class="modal fade" id="add_legislation" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <hr class="horizontal dark">
                <p class="text-uppercase text-sm">Add New Legislation</p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
			   <form method="POST" action="{{ route('create_legislation') }}">
                                @csrf
            <div class="modal-body">
                 <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="example-text-input" class="form-control-label">Date to be voted on</label>
                            <input class="form-control" type="date" name="vote_date" value="" REQUIRED>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="example-text-input" class="form-control-label">Legislation Name</label>
                            <input class="form-control" type="text" name="legislation_name" value="" REQUIRED>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="example-text-input" class="form-control-label">Legislation Description</label>
                            <textarea class="form-control" type="text" name="legislation_description" REQUIRED></textarea>
							<input class="form-control" type="hidden" name="username" value="{{ old('username', auth()->user()->username) }}">
                        </div>
                    </div>

                </div>
				    <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="example-text-input" class="form-control-label">Translation</label>
                            <textarea class="form-control" type="text" name="translation"></textarea>							
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Save</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>

            </div>
			</form>
        </div>
    </div>
</div>