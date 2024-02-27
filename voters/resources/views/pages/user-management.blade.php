@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'User Management'])
    <div class="row mt-4 mx-4">
        <div class="col-12">

            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div id="alert">						
        @include('components.alert')
    </div>
                        <div class="row top1" style="padding-bottom:10px !important">
                            <div class="col-md-4"><h6>System Users <span class="uk-badge">{{$count}}</span></h6></div>
                            <div class="col-md-4"><button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#add_user">Add New User</button></div>
							 <div class="col-md-4"> 
							 <form class="uk-search uk-search-default" action="{{ route('user-management') }}" method="POST">
          @csrf
            <input class="uk-search-input" type="search" name="search_term" id="search_term" placeholder="Search User" value="{{$search_term}}">
            <button class="uk-search-icon-flip" name="search_button" id="search_button" uk-search-icon></button>
        </form></div>
                        </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0 uk-table-responsive uk-table-divider">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Username
                                    </th> 
									<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Role
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Create Date</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Casted Votes</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr id="x{{$user['user_id']}}">
                                    <td>
                                 
                                                <p class="text-sm font-weight-bold mb-0"><span class='not_desktop' >Full Name : </span> {{$user["firstname"]}} {{$user["lastname"]}}</p>
                                      
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-bold mb-0"><span class='not_desktop' >Username : </span> {{$user["username"]}}</p>
                                    </td>  
									<td>
                                        <p class="text-sm font-weight-bold mb-0"><span class='not_desktop' >Role : </span> {{$user["role"]}}</p>
                                    </td>
                                    <td class="text-sm font-weight-bold mb-0">
                                        <p class="text-sm font-weight-bold mb-0"><span class='not_desktop' >Created At : </span> {{$user["created_at"]}}</p>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex px-3 py-1 justify-content-center align-items-center">
										<span class='not_desktop' ></span>  
                                            <button
                                                class="btn btn-icon-only btn-rounded btn-outline-danger mb-0 me-3 btn-sm d-flex align-items-center justify-content-center">{{$user["votes"]}}</button>
                                        </div>
                                    </td>
                                    <td class="align-middle text-end">
                                        <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                            <span uk-icon="pencil" class="uk-icon-button edit" style="color:red; cursor:pointer" data="{{$user['user_id']}}" data-name='{{$user["firstname"]}}' data-surname='{{$user["lastname"]}}' data-status='{{$user["status"]}}' data-postal='{{$user["postal"]}}' data-username='{{$user["username"]}}' data-email='{{$user["email"]}}' data-address='{{$user["address"]}}' data-role='{{$user["role"]}}' data-city='{{$user["city"]}}' data-country='{{$user["country"]}}' data-congressional='{{$user["congressional"]}}' title="Edit User"></span>
                                            <span uk-icon="trash" class="uk-icon-button deleteuser" data="{{$user['user_id']}}" style="color:red; cursor:pointer" title="Delete User"></span>
                                        </div>
                                    </td>
                                </tr>

                                @endforeach

                            </tbody>
                        </table>
						
						{{$userslist1->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
	<button data-bs-toggle="modal" data-bs-target="#edit_user" id="userstatus" hidden>.</button>
	<input type="hidden" id="user_id">
	<input type="hidden" id="passstatus" value="0">
	      @include('layouts.footers.auth.footer')
		  @include('modals.newusermodal')
		  @include('modals.editusermodal')
@endsection
