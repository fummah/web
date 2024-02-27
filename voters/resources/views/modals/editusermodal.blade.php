<div class="modal fade" id="edit_user" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <hr class="horizontal dark">
                <p class="text-uppercase text-sm">Edit User</p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
			   <div class="card-body">
                            <form method="POST" action="{{ route('createuser') }}" onsubmit="return confirmPassword()">
                                @csrf
								   <div class="row">
                            <div class="col-md-4">
							   <div class="flex flex-col mb-3">
                                    <input type="text" name="firstnameedit" id="firstnameedit" class="form-control" placeholder="First Name" aria-label="First Name" value="" REQUIRED>
                                    @error('firstname') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                </div>
							</div>
							   <div class="col-md-4">
							      <div class="flex flex-col mb-3">
                                    <input type="text" name="lastnameedit" id="lastnameedit" class="form-control" placeholder="Last Name" aria-label="Last Nmae" value="" REQUIRED>
                                    @error('lastname') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                </div>
							</div>
							   <div class="col-md-4">
							      <div class="flex flex-col mb-3">
                                    <input type="text" name="emailedit" id="emailedit" class="form-control" placeholder="Email" aria-label="Email" onkeyup="addUsername()" value="" REQUIRED>
                                    @error('email') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                </div>
							</div>
                                <div class="flex flex-col mb-3" style="display:none">
                                    <input type="text" name="usernameedit" id="usernameedit" class="form-control" placeholder="Username" aria-label="Username" value="" >                                   
                                </div>
                             
                           
								 <hr class="horizontal dark">
                        <p class="text-uppercase text-sm">Contact Information</p>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">                                    
                                    <textarea class="form-control" type="text" name="addressedit" id="addressedit" placeholder="Address (Optional)" aria-label="Address"></textarea>
									@error('address') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">                                   
                                    <input class="form-control" type="text" name="cityedit" id="cityedit" placeholder="City" aria-label="City" value="">
									@error('city') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">                             
                                    <select class="form-control" type="text" name="countryedit" id="countryedit" REQUIRED>
									<option value="">Select State</option>
									<option value="AL">AL</option>
									<option value="AK">AK</option>
									<option value="AZ">AZ</option>
									<option value="AR">AR</option>
									<option value="CA">CA</option>
									<option value="CO">CO</option>
									<option value="CT">CT</option>
									<option value="DE">DE</option>
									<option value="FL">FL</option>
									<option value="GA">GA</option>
									<option value="HI">HI</option>
									<option value="ID">ID</option>
									<option value="IL">IL</option>
									<option value="IN">IN</option>
									<option value="IA">IA</option>
									<option value="KS">KS</option>
									<option value="KY">KY</option>
									<option value="LA">LA</option>
									<option value="ME">ME</option>
									<option value="MD">MD</option>
									<option value="MA">MA</option>
									<option value="MI">MI</option>
									<option value="MN">MN</option>
									<option value="MS">MS</option>
									<option value="MO">MO</option>
									<option value="MT">MT</option>
									<option value="NE">NE</option>
									<option value="NV">NV</option>
									<option value="NH">NK</option>
									<option value="NJ">NJ</option>
									<option value="NM">NM</option>
									<option value="NY">NY</option>
									<option value="NC">ND</option>
									<option value="OH">OH</option>
									<option value="OK">OK</option>
									<option value="OR">OR</option>
									<option value="PA">PA</option>
									<option value="RI">RI</option>
									<option value="SC">SC</option>
									<option value="SD">SD</option>
									<option value="TN">TN</option>
									<option value="TX">TX</option>
									<option value="UT">UT</option>
									<option value="VT">VT</option>
									<option value="VA">VA</option>
									<option value="WA">WA</option>
									<option value="WV">WV</option>
									<option value="WI">WI</option>
									<option value="WY">WY</option>									
									</select>
									@error('country') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">                                 
                                    <input class="form-control" type="text" name="postaledit" id="postaledit" placeholder="Postal Code" aria-label="Postal Code" value="">
									@error('postal') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                </div>
                            </div>
                        </div>
							<div class="row">
						  <div class="col-md-12">
                                <div class="form-group">   								
                                      <select class="form-control" name="congressionaledit" id="congressionaledit" placeholder="Congressional District (Number)" value="" REQUIRED>
									<option value="">Select Congressional District</option>
									<option value="01">01</option>
									<option value="02">02</option>
									<option value="03">03</option>
									<option value="04">04</option>
									<option value="05">05</option>
									<option value="06">06</option>
									<option value="07">07</option>
									<option value="08">08</option>
									<option value="09">09</option>
									<option value="10">10</option>
									<option value="11">11</option>
									<option value="12">12</option>
									<option value="13">13</option>
									<option value="14">14</option>
									<option value="15">15</option>
									<option value="16">16</option>
									<option value="17">17</option>
									<option value="18">18</option>
									<option value="19">19</option>
									<option value="20">20</option>
									<option value="21">21</option>
									<option value="22">22</option>
									<option value="23">23</option>
									<option value="24">24</option>
									<option value="25">25</option>
									<option value="26">26</option>
									<option value="27">27</option>
									<option value="28">28</option>
									<option value="29">29</option>
									<option value="30">30</option>
									<option value="31">31</option>
									<option value="32">32</option>
									<option value="33">33</option>
									<option value="34">34</option>
									<option value="35">35</option>
									<option value="36">36</option>
									<option value="37">37</option>
									<option value="38">38</option>
									<option value="39">39</option>
									<option value="40">40</option>
									<option value="41">41</option>
									<option value="42">42</option>
									<option value="43">43</option>
									<option value="44">44</option>
									<option value="45">45</option>
									<option value="46">46</option>
									<option value="47">47</option>
									<option value="48">48</option>
									<option value="49">49</option>
									<option value="50">50</option>
									<option value="51">51</option>
									<option value="52">52</option>
									<option value="53">53</option>
									<option value="54">54</option>
									<option value="55">55</option>							
									</select>
								@error('congressional') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                </div>
                            </div>
						</div>
                        <hr class="horizontal dark">
                        <p class="text-uppercase text-sm">Credentials</p>
                        <div class="row">
                            <div class="col-md-3">
                                    <div class="flex flex-col mb-3">
                                    <select name="roleedit" id="roleedit" class="form-control" placeholder="Role" aria-label="Role" REQUIRED>
									<option>Select Role</option>
									<option value="Admin">Admin</option>
									<option value="Ordinary">Ordinary</option>
									</select>
                                    @error('role') <p class='text-danger text-xs pt-1' id="pass_1"> {{ $message }} </p> @enderror
                                </div>
                            </div> 
							<div class="col-md-3">
							 <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
            <label><input class="uk-checkbox" type="checkbox" name="changepass" id="changepass"> Change Password?</label>            
        </div>
							</div>
							
							<div class="col-md-3 pp">
                                    <div class="flex flex-col mb-3">
                                    <input type="password" name="passwordedit" id="passwordedit" min="6" class="form-control" placeholder="Password" aria-label="Password" REQUIRED>
                                    @error('password') <p class='text-danger text-xs pt-1' id="pass_1"> {{ $message }} </p> @enderror
                                </div>
                            </div>
							   <div class="col-md-3 pp">
                                    <div class="flex flex-col mb-3">
                                    <input type="password" name="confirm_passwordedit" id="confirm_passwordedit" min="6" class="form-control" placeholder="Confirm Password" aria-label="Confirm Password" REQUIRED>
                                    @error('confirm_password') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                </div>
                            </div>
                        </div>
						<div class="row">
						<div class="col-md-12">
						 <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
            <label><input class="uk-checkbox" type="checkbox" name="ustatus" id="ustatus" checked> Active?</label>            
        </div>
						</div>
						</div>
                    </div>
                              <p class="wait"></p>
                            <div class="modal-footer">
                <button type="submit" class="btn btn-success" onclick="saveedit()">Save Changes</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>

            </div>
                          
                            </form>
                        </div>
        </div>
    </div>
</div>