<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
     <div class="modal-header">
        <h5 class="modal-title text-primary" id="exampleModalLongTitle"><b><span id="spannae"></span> Subscriber</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>
        <form action="{{ route('add_subscriber') }}" method="POST" enctype="multipart/form-data">
      <div class="modal-body">
       

              @csrf 
              
              <input type="hidden" value="0" id="customer_id" name="customer_id">
            <div class="row">
                    <div class="col-md-6 pr-1">
                        <div class="form-group">               
                            <label>First Name</label>
                             <div style="margin: 6px 6px;">
                                <input name="first_name" id="first_name" class="form-control" placeholder="First Name" required>                               
                            </div>
                        </div>
                    </div>
                       <div class="col-md-6 pr-1">
                        <div class="form-group">             
             <label>Last Name</label>
                             <div style="margin: 6px 6px;">
                                <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" required>                               
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="row">
                        <div class="col-md-6 pr-1">
                        <div class="form-group">             
             <label>Email</label>
                             <div style="margin: 6px 6px;">
                                <input type="email" name="subscriber_email" id="subscriber_email" class="form-control" placeholder="Email" required>                               
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 pr-1">
                        <div class="form-group">               
                            <label>Contact Number</label>
                            <div style="margin: 6px 6px;">
                                <input name="contact_number" id="contact_number" class="form-control" placeholder="Contact Number" required>                               
                            </div>
                        </div>
                    </div>
                     
                </div>
            <div class="row">
                    <div class="col-md-12 pr-1">
                        <div class="form-group">
                           <label>Subscriber Address</label>
                            <div style="margin: 10px 10px;">
                                <textarea name="subscriber_address" id="subscriber_address" class="form-control" style="border: 1px solid lightgrey; border-radius: 5px;" placeholder="Address ..."></textarea>                               
                            </div>
                        </div>
                    </div>
                </div>

             
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Create Subscriber</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        
      </div>
       </form>
    </div>
  </div>
</div>