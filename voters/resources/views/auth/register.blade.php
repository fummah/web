@extends('layouts.app')

@section('content')
    @include('layouts.navbars.guest.navbar')
    <main class="main-content  mt-0">
        <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg"
            style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/signup-cover.jpg'); background-position: top;">
            <span class="mask bg-gradient-primary opacity-6"></span>
            <div class="container">
                <div class="row justify-content-center">
                   
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
                <div class="col-xl-8 col-lg-8 col-md-8 mx-auto">
                    <div class="card z-index-0">
                        <div class="card-header text-center pt-4">
                            <h3>Register to be able to vote</h3>
                        </div>                  
                        <div class="card-body background-blue">
                            <form method="POST" action="{{ route('register.perform') }}" onsubmit="return confirmPassword()">
                                @csrf
								   <div class="row">
                            <div class="col-md-4">
							   <div class="flex flex-col mb-3">
                                    <input type="text" name="firstname" class="form-control" placeholder="First Name" aria-label="First Name" value="" REQUIRED>
                                    @error('firstname') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                </div>
							</div>
							   <div class="col-md-4">
							      <div class="flex flex-col mb-3">
                                    <input type="text" name="lastname" class="form-control" placeholder="Last Name" aria-label="Last Nmae" value="" REQUIRED>
                                    @error('lastname') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                </div>
							</div>
							   <div class="col-md-4">
							      <div class="flex flex-col mb-3">
                                    <input type="email" name="email" id="email" class="form-control" placeholder="Email" aria-label="Email" onkeyup="addUsername()" value="" REQUIRED>
                                    @error('email') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                </div>
							</div>
                                <div class="flex flex-col mb-3" style="display:none">
                                    <input type="text" name="username" id="username" class="form-control" placeholder="Username" aria-label="Username" value="" >                                   
                                </div>
                             
                           
								 <hr class="horizontal dark">
                        <p class="text-uppercase text-sm font-white">Contact Information</p>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">                                    
                                    <textarea class="form-control" type="text" name="address" placeholder="Address (Optional)" aria-label="Address"></textarea>
									@error('address') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">                                   
                                    <input class="form-control" type="text" name="city" placeholder="City" aria-label="City" value="">
									@error('city') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">                             
                                    <select class="form-control" type="text" name="country" REQUIRED>
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
                                    <input class="form-control" type="text" name="postal" placeholder="Postal Code" aria-label="Postal Code" value="">
									@error('postal') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                </div>
                            </div>
                        </div>
						<div class="row">
						  <div class="col-md-12">
                                <div class="form-group">                                 
                                    <select class="form-control" name="congressional" placeholder="Congressional District (Number)" value="" REQUIRED>
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
									<p style="margin-top:10px !important"><a href="https://www.house.gov/representatives/find-your-representative" class="text-white"><u>Find congressional district</u></a></p>
                                
                                </div>
                            </div>
						</div>
                        <hr class="horizontal dark">
                        <p class="text-uppercase text-sm font-white">Credentials</p>
                        <div class="row">
                            <div class="col-md-4">
                                    <div class="flex flex-col mb-3">
                                    <input type="password" name="password" id="password" min="6" class="form-control" placeholder="Password" aria-label="Password" REQUIRED>
                                    @error('password') <p class='text-danger text-xs pt-1' id="pass_1"> {{ $message }} </p> @enderror
                                </div>
                            </div>
							   <div class="col-md-4">
                                    <div class="flex flex-col mb-3">
                                    <input type="password" name="confirm_password" id="confirm_password" min="6" class="form-control" placeholder="Confirm Password" aria-label="Confirm Password" REQUIRED>
                                    @error('confirm_password') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                                <div class="form-check form-check-info text-start">
                                    <input class="form-check-input" type="checkbox" name="terms" id="flexCheckDefault" REQUIRED>
                                    <label class="form-check-label font-white" for="flexCheckDefault">
                                        I agree the <a href="javascript:;" class="font-white font-weight-bolder">Terms and
                                            Conditions</a>
                                    </label>
                                    @error('terms') <p class='text-danger text-xs'> {{ $message }} </p> @enderror
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2">Sign up</button>
                                </div>
                                <p class="text-sm mt-3 mb-0 font-white">Already have an account? <a href="{{ route('login') }}"
                                        class="font-white font-weight-bolder">Sign in</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('layouts.footers.guest.footer')
@endsection
