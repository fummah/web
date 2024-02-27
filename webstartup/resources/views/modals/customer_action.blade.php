<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
     <div class="modal-header">
        <h5 class="modal-title text-primary" id="exampleModalLongTitle"><b><span id="spannae"></span> Customer</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form action="{{ route('action_customer') }}" method="POST" enctype="multipart/form-data">
      <div class="modal-body">
       

              @csrf 
              
              <input type="hidden" value="0" id="customer_id" name="customer_id">
            <div class="row">
                    <div class="col-md-6 pr-1">
                        <div class="form-group">               
                            <label>Customer Full Name</label>
                             <div style="margin: 6px 6px;">
                                <input name="customer_name" id="customer_name" class="form-control" placeholder="Name" required>                               
                            </div>
                        </div>
                    </div>
                       <div class="col-md-6 pr-1">
                        <div class="form-group">             
             <label>Customer Email</label>
                             <div style="margin: 6px 6px;">
                                <input type="email" name="customer_email" id="customer_email" class="form-control" placeholder="Email" required>                               
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="row">
                    <div class="col-md-6 pr-1">
                        <div class="form-group">               
                            <label>Contact Number</label>
                            <div style="margin: 6px 6px;">
                                <input name="contact_number" id="contact_number" class="form-control" placeholder="Contact Number" required>                               
                            </div>
                        </div>
                    </div>
                       <div class="col-md-6 pr-1">
                        <div class="form-group">             
             <label>Company Name</label>
                            <div style="margin: 6px 6px;">
                                <input name="company_name" id="company_name" class="form-control" placeholder="Company Name">                               
                            </div>
                        </div>
                    </div>
                </div>
            <div class="row">
                    <div class="col-md-12 pr-1">
                        <div class="form-group">
                           <label>Customer Address</label>
                            <div style="margin: 10px 10px;">
                                <textarea name="customer_address" id="customer_address" class="form-control" style="border: 1px solid lightgrey; border-radius: 5px;" placeholder="Address ..."></textarea>                               
                            </div>
                        </div>
                    </div>
                </div>

                      <div class="row pps" style="display:none">
                           <div class="col-md-4 pr-1">
                        <div class="form-group">               
                            <label class="text-primary">Change Password?</label>
                             <input type="checkbox" name="change_pass" class="form-control" id="change_pass" value="change_pass">
                        </div>
                    </div>
                    <div class="col-md-4 pr-1">
                        <div class="form-group">               
                            <label>Password</label>
                             <div style="margin: 6px 6px;">
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password" >                               
                            </div>
                        </div>
                    </div>
                       <div class="col-md-4 pr-1">
                        <div class="form-group">             
             <label>Confirm Password</label>
                             <div style="margin: 6px 6px;">
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password">                               
                            </div>
                        </div>
                    </div>
                </div>
             
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Submit Now</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        
      </div>
       </form>
    </div>
  </div>
</div>