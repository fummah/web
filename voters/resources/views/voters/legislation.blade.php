@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Legislations'])

    <div class="container-fluid py-4">
		
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
						   <div id="alert">						
        @include('components.alert')
    </div>
                        <div class="row top1" style="padding-bottom:10px !important">
                            <div class="col-md-4"><h6>Legislations</h6></div>
                            <div class="col-md-4">
							@if(auth()->user()->role == "Admin")
							<button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#add_legislation">Add New Legislation</button>
						@endif
							</div>
							 <div class="col-md-4"> 
							 <form class="uk-search uk-search-default" action="{{ route('legislations') }}" method="POST">
          @csrf
            <input class="uk-search-input" type="search" name="search_term" id="search_term" placeholder="Search Legislation" value="{{$search_term}}">
            <button class="uk-search-icon-flip" name="search_button" id="search_button" uk-search-icon></button>
        </form></div>
                        </div>

                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            
                                @foreach($legislations as $legislation)
                               <article class="uk-comment bg-white" role="comment">
    <header class="uk-comment-header">
        <div class="uk-grid-medium uk-flex-middle" uk-grid>          
            <div class="uk-width-expand">
                 <p class="uk-comment-title uk-margin-remove barlow" style="margin-bottom:10px !important;margin-top:10px !important;margin-left:10px !important">Date to be voted : <span class="text-primary">{{$legislation['vote_date']}}</span></a></p>
                <h5 class="uk-comment-title uk-margin-remove barlow" style="margin-bottom:10px !important;margin-left:10px !important;margin-right:10px !important"><a class="uk-link-reset" href="#">{{$legislation['legislation_name']}}</a></h5>
                <ul class="uk-comment-meta uk-subnav uk-subnav-divider uk-margin-remove-top">
                    <li><a href="/details/legislation/writtenas/{{$legislation['id']}}"><span class="as"><button class="btn btn-outline-dark">As Written</button></span></a></li>
                    <li><a href="/details/legislation/translatedas/{{$legislation['id']}}"><span class="as"><button class="btn btn-outline-dark">As Translated</button></span></a></li>
                </ul>
            </div>
        </div>
    </header>
    <div class="uk-comment-body">
     <hr class="uk-divider-icon">
	 </div>
</article>
                                @endforeach

							{{$lest1->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
<button data-bs-toggle="modal" data-bs-target="#confirm_vote" id="openvote" hidden>.</button>
<button data-bs-toggle="modal" data-bs-target="#open_legi" id="openlegi" hidden>.</button>
<button data-bs-toggle="modal" data-bs-target="#whovoted_modal" id="whovoted" hidden>.</button>
<input type="hidden" id="legislation_id">
<input type="hidden" id="page" value="legislation">


        @include('layouts.footers.auth.footer')
    </div>
@include('modals.newlegimodal')
@include('modals.confirmvotemodal')
@include('modals.legislationmodal')
@include('modals.whovotedmodal')

@endsection
