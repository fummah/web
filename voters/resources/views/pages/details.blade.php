@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Details'])

    <div class="container-fluid py-4">
    <div>
    <div>
        <div class="bg-white">
         
		
                <article class="uk-comment uk-comment-primary uk-visible-toggle bg-white" tabindex="-1" role="comment">
                    <header class="uk-comment-header uk-position-relative">
                        <div class="uk-grid-medium uk-flex-middle" uk-grid>
                            
                            <div class="uk-width-expand">
                                <h4 class="uk-comment-title uk-margin-remove" style="margin-bottom:8px !important"><u>{{$category_name}} Name</u></h4>
                                <h4 class="text-primary barlow myname" id="n{{$arr['id']}}" title="{{$arr['description']}}" data-name="{{$arr['name']}}">{{$arr['name']}}</h4>
                              
							  
						
							   <p class="uk-comment-meta uk-margin-remove-top">
							   <a class="uk-link-reset" href="#">
							   @if($content_type=="writtenas")
								 Written As
							   @else
								 Translated As
							   @endif
                                </a>
								</p>
                            </div>
                        </div>
                        
                    </header>
                    <div class="uk-comment-body">
					 
					 @if($content_type=="writtenas")
								<p class="barlow mydescription">{!! nl2br(e($arr['description'])) !!}</p>
							   @else
								  <p class="barlow mydescription">{!! nl2br(e($arr['translation'])) !!}</p>
							   @endif
							  
                             </div>
                </article>
           

			 
            <div class="modal-footer content-center">
           
            <div class="" style="position:relative;margin-left:auto;margin-right:auto; content-align:center; border:1px dashed orange; padding:10px">
                                                <label for="example-text-input" class="form-control-label">
                                                    <input type="radio" class="uk-radio vote" value="Yes" name="x_{{$arr['id']}}"
													@if($arr['vote']=="Yes")
													checked
													@endif
													>										
													<span class="text-blue archivo"> Yes</span></label>
                                                <label for="example-text-input" class="form-control-label">
                                                    <input class="uk-radio vote" type="radio" value="No" name="x_{{$arr['id']}}"
													@if($arr['vote']=="No")
													checked
													@endif
													>													
                                                    <span class="text-danger archivo"> No</span></label>  
                                                    <label for="example-text-input" class="form-control-label">
                                                    <input type="radio" class="uk-radio vote" value="Proxy" name="x_{{$arr['id']}}"
													@if($arr['vote']=="Proxy")
													checked
													@endif
													>
                                                    <span class="text-success archivo"> Proxy</span></label>
                                            </div>    
  <div class="d-flex align-items-center justify-content-center" style="position:relative;margin-left:auto;margin-right:auto; content-align:center; border:1px dashed orange; padding:10px">                                          
                                                <div>
                                                     <button data="{{$arr['id']}}" title="Yes"
                                                class="btn btn-icon-only btn-rounded btn-outline-blue mb-0 me-3 btn-sm d-flex align-items-center justify-content-center whovote">{{$arr['yes']}}</button>
                                                </div>
												 <div>
                                                     <button data="{{$arr['id']}}" title="No"
                                                class="btn btn-icon-only btn-rounded btn-outline-danger mb-0 me-3 btn-sm d-flex align-items-center justify-content-center whovote">{{$arr['no']}}</button>
                                                </div>
												 <div>
                                                     <button data="{{$arr['id']}}" title="Proxy"
                                                class="btn btn-icon-only btn-rounded btn-outline-success mb-0 me-3 btn-sm d-flex align-items-center justify-content-center whovote">{{$arr['proxy']}}</button>
                                                </div>
												
                                            </div>
@if(auth()->user()->role == "Admin")											
<div class="d-flex align-items-center justify-content-center" style="position:relative;margin-left:auto;margin-right:auto; content-align:center; border:1px dashed white; padding:10px 50px 10px 20px">                                          
  											
     <p align="center" class="uk-subnav uk-subnav-divider"> <span uk-icon="trash" data-id="{{$arr['id']}}" class="delete" style="cursor:pointer"></span><span class="editc" data-id="{{$arr['id']}}" uk-icon="file-edit" style="cursor:pointer"></span></p>

            </div>
			@endif
            </div>
			 <p align="center"><button type="button" class="btn btn-primary" onclick="history.back()">Back</button></p>
			 
	
     
        </div>
    </div>
</div>
<input type="hidden" id="myvote_date" value="{{$arr['vote_date']}}">
<input type="hidden" id="legislation_id">
<input type="hidden" id="page" value="{{$arr['page']}}">
<button data-bs-toggle="modal" data-bs-target="#whovoted_modal" id="whovoted" hidden>.</button>
<button data-bs-toggle="modal" data-bs-target="#confirm_vote" id="openvote" hidden>.</button>
<button data-bs-toggle="modal" data-bs-target="#edit_modal" id="editmodal" hidden>.</button>
 <form method="POST" action="">
                                @csrf
								</form>
<input type="hidden" id="typep" value="{{$arr['typep']}}">
@if($arr['page']=="legislation")
<button data-bs-toggle="modal" data-bs-target="#open_legi" id="openlegi" hidden>.</button>
@elseif($arr['page']=="election")
<button data-bs-toggle="modal" data-bs-target="#open_elect" id="openelect" hidden>.</button>
@elseif($arr['page']=="topic")
<button data-bs-toggle="modal" data-bs-target="#open_topic" id="opentopic" hidden>.</button>
@endif

@include('layouts.footers.auth.footer')
    </div>


@if($arr['page']=="legislation")
@include('modals.legislationmodal')
@include('modals.confirmvotemodal')

@elseif($arr['page']=="election")
@include('modals.confirmelectionvotemodal')
@include('modals.electionmodal')

@elseif($arr['page']=="topic")
@include('modals.confirmtopicvotemodal')
@include('modals.topicmodal')
@endif

@include('modals.editmodal')
@include('modals.whovotedmodal')
@endsection


