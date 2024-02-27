@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Dashboard'])

    <div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-4 ucard">
        <div class="card card-body">
        <p class="text-sm text-primary mb-0 text-uppercase font-weight-bold">Username</p>
                                    <h5 class="font-weight-bolder">
                                        {{ old('firstname', auth()->user()->firstname) }} {{ old('lastname', auth()->user()->lastname) }}
                                    </h5>
        </div>
        </div>
        <div class="col-md-4 ucard">
            <div class="card card-body">
        <p class="text-sm text-primary mb-0 text-uppercase font-weight-bold">State</p>
                                    <h5 class="font-weight-bolder">
                                        {{ old('country', auth()->user()->country) }}
                                    </h5>
</div>
        </div>
        <div class="col-md-4 ucard">
        <div class="card card-body">
        <p class="text-sm text-primary mb-0 text-uppercase font-weight-bold">District</p>
                                    <h5 class="font-weight-bolder">
                                        {{ old('congressional', auth()->user()->congressional) }}
                                    </h5>
        </div>
        </div>
</div>
</div>
 
<h3 class="text-primary mb-0 text-uppercase font-weight-bold sectionheading"><u>Topics</u></h3>
<div class="card-body bg-white">
 @foreach($alltopics as $topic)
<article class="uk-comment bg-white" role="comment">
    <header class="uk-comment-header">
        <div class="uk-grid-medium uk-flex-middle" uk-grid>          
            <div class="uk-width-expand">
                <ul><li>
				<h5 class="uk-comment-title uk-margin-remove barlow" style="margin-bottom:10px !important;"><a class="uk-link-reset" href="/details/topic/writtenas/{{$topic['id']}}">{{$topic['topic_name']}}</a></h5>
				<a href="/details/topic/writtenas/{{$topic['id']}}"><span class="as"><button class="btn btn-outline-danger">Read/Vote</button></span></a>   
                </li>
				</ul>
				<!--<ul class="uk-comment-meta uk-subnav uk-subnav-divider uk-margin-remove-top">
                                   <li><a href="/details/topic/writtenas/{{$topic['id']}}"><span class="as"><button class="btn btn-outline-dark">As Written</button></span></a></li>
                    <li><a href="/details/topic/translatedas/{{$topic['id']}}"><span class="as"><button class="btn btn-outline-dark">As Translated</button></span></a></li>
                </ul>
				-->
            </div>
        </div>
    </header>
    <div class="uk-comment-body">
     <hr class="uk-divider-icon">
	 </div>
</article>
@endforeach
</div>

<h3 class="text-primary mb-0 text-uppercase font-weight-bold sectionheading"><u>Legislations</u></h3>
<div class="card-body bg-white">
 @foreach($alllegislations as $legislation)
<article class="uk-comment bg-white" role="comment">
    <header class="uk-comment-header">
        <div class="uk-grid-medium uk-flex-middle" uk-grid>          
            <div class="uk-width-expand">
                 <p class="uk-comment-title uk-margin-remove barlow" style="margin-bottom:10px !important;margin-top:10px !important;margin-left:10px !important">Date to be voted : <span class="text-primary">{{$legislation['vote_date']}}</span></a></p>
              
                <h5 class="uk-comment-title uk-margin-remove barlow" style="margin-bottom:10px !important"><a class="uk-link-reset" href="#">{{$legislation['legislation_name']}}</a></h5>
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
</div>

<h3 class="text-primary mb-0 text-uppercase font-weight-bold sectionheading"><u>Elections</u></h3>
<div class="card-body bg-white">
 @foreach($allelections as $election)
<article class="uk-comment bg-white" role="comment">
    <header class="uk-comment-header">
        <div class="uk-grid-medium uk-flex-middle" uk-grid>          
            <div class="uk-width-expand">
			   <ul><li>
                <h5 class="uk-comment-title uk-margin-remove barlow" style="margin-bottom:10px !important"><a class="uk-link-reset" href="/details/election/writtenas/{{$election['id']}}">{{$election['election_name']}}</a></h5>
                <a href="/details/election/writtenas/{{$election['id']}}"><span class="as"><button class="btn btn-outline-danger">Read/Vote</button></span></a>  
                 </li>
				</ul>
			   <!-- <ul class="uk-comment-meta uk-subnav uk-subnav-divider uk-margin-remove-top">
                 <li><a href="/details/election/writtenas/{{$election['id']}}"><span class="as"><button class="btn btn-outline-dark">As Written</button></span></a></li>
                    <li><a href="/details/election/translatedas/{{$election['id']}}"><span class="as"><button class="btn btn-outline-dark">As Translated</button></span></a></li>
                </ul>
				-->
            </div>
        </div>
    </header>
    <div class="uk-comment-body">
     <hr class="uk-divider-icon">
	 </div>
</article>
@endforeach
</div>
<button data-bs-toggle="modal" data-bs-target="#confirm_vote" id="openvote" hidden>.</button>
<button data-bs-toggle="modal" data-bs-target="#open_legi" id="openlegi" hidden>.</button>
<button data-bs-toggle="modal" data-bs-target="#whovoted_modal" id="whovoted" hidden>.</button>
<button data-bs-toggle="modal" data-bs-target="#open_elect" id="openelect" hidden>.</button>
<button data-bs-toggle="modal" data-bs-target="#open_topic" id="opentopic" hidden>.</button>
<input type="hidden" id="legislation_id">
<input type="hidden" id="page" value="legislation">

        @include('layouts.footers.auth.footer')
    </div>
@include('modals.electionmodal')
@include('modals.topicmodal')
@include('modals.confirmvotemodal')
@include('modals.legislationmodal')
@include('modals.whovotedmodal')

@endsection
