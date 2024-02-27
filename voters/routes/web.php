<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VotersController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\ChangePassword;
use App\Http\Controllers\ReportsController;

Route::get('/', function () {
    return redirect('/dashboard');
})->middleware('auth');
	Route::get('/public-legislations', [VotersController::class, 'showPublicLegislations'])->middleware('guest')->name('public-legislations');
	Route::get('/register', [RegisterController::class, 'create'])->middleware('guest')->name('register');
	Route::post('/register', [RegisterController::class, 'store'])->middleware('guest')->name('register.perform');
	Route::get('/login', [LoginController::class, 'show'])->middleware('guest')->name('login');
	Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.perform');
	Route::get('/reset-password', [ResetPassword::class, 'show'])->middleware('guest')->name('reset-password');
	Route::post('/reset-password', [ResetPassword::class, 'send'])->middleware('guest')->name('reset.perform');
	Route::get('/change-password', [ChangePassword::class, 'show'])->middleware('guest')->name('change-password');
	Route::post('/change-password', [ChangePassword::class, 'update'])->middleware('guest')->name('change.perform');
	Route::get('/dashboard', [HomeController::class, 'index'])->name('home')->middleware('auth');
	Route::get('/home', [HomeController::class, 'index'])->name('dashboard')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
	Route::get('/virtual-reality', [PageController::class, 'vr'])->name('virtual-reality');
	Route::get('/rtl', [PageController::class, 'rtl'])->name('rtl');
	Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
	Route::get('/legislations', [VotersController::class, 'showLegislations'])->name('legislations');
	Route::get('/elections', [VotersController::class, 'showElections'])->name('elections');
	Route::get('/topics', [VotersController::class, 'showTopics'])->name('topics');
	Route::post('/legislations', [VotersController::class, 'showSearchedLegislations'])->name('legislations1');
	Route::post('/elections', [VotersController::class, 'showSearchedElections'])->name('elections1');
	Route::post('/topics', [VotersController::class, 'showSearchedTopics'])->name('topics1');
	Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');
	Route::post('/create_legislation', [VotersController::class, 'createLegislation'])->name('create_legislation');
	Route::post('/create_election', [VotersController::class, 'createElection'])->name('create_election');
	Route::post('/create_topic', [VotersController::class, 'createTopic'])->name('create_topic');
	Route::post('/vote', [VotersController::class, 'voteNow'])->name('vote');
	Route::post('/whovote', [VotersController::class, 'whoVoted'])->name('whovote');
	Route::post('/vote_election', [VotersController::class, 'voteElectionNow'])->name('vote_election');
	Route::post('/vote_topic', [VotersController::class, 'voteTopicNow'])->name('vote_topic');
	Route::get('/details/{category_name}/{content_type}/{id}', [VotersController::class, 'singleCategory'])->name('details');
	Route::get('/profile-static', [PageController::class, 'profile'])->name('profile-static');
	Route::get('/sign-in-static', [PageController::class, 'signin'])->name('sign-in-static');
	Route::get('/sign-up-static', [PageController::class, 'signup'])->name('sign-up-static');
	Route::get('/user-management', [PageController::class, 'userManagement'])->name('user-management');
	Route::get('/reports', [ReportsController::class, 'show'])->name('reports');
	Route::get('/getcategorylist', [ReportsController::class, 'searched'])->name('getcategorylist');
	Route::get('/getanalysis', [ReportsController::class, 'analysis'])->name('getanalysis');	
	Route::get('/gettrends/{trend}', [ReportsController::class, 'trends'])->name('gettrends');
	Route::post('/editcategory', [VotersController::class, 'editCategory'])->name('editcategory');
	Route::post('/deletecategory', [VotersController::class, 'deleteCategory'])->name('deletecategory');
	Route::post('/createuser', [RegisterController::class, 'creteInternalUser'])->name('createuser');
	Route::post('/saveedit', [RegisterController::class, 'saveEdit'])->name('saveedit');
	Route::post('/deleteuser', [RegisterController::class, 'deleteUser'])->name('deleteuser');
	Route::get('/test', [VotersController::class, 'test'])->name('test');
	Route::get('/{page}', [PageController::class, 'index'])->name('page');
	Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});

