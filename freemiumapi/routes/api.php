<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SchemeController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\QueryController;
use App\Http\Controllers\Api\ClaimsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('guest:sanctum')->group(function(){
    Route::get('/getschemes', [SchemeController::class,'getSchemes'])->name('get_schemes');
    Route::post('/adduser', [MemberController::class,'addUser'])->name('add_user');
    Route::post('/login', [MemberController::class,'login'])->name('login');
    Route::post('/verify-email', [MemberController::class,'verifyEmail'])->name('verify_email');
    Route::post('/updateplan', [MemberController::class,'updatePlan'])->name('update_plan');
     Route::post('/forgot-password', [MemberController::class,'forgotPassword'])->name('forgot_password');
     Route::post('/reset-password', [MemberController::class,'resetPassword'])->name('reset_password');
});

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/getdashboard', [MemberController::class,'getDashbord'])->name('get_dashboard');
    Route::post('/logout', [MemberController::class,'logout'])->name('logout');
    Route::post('/addquery', [QueryController::class,'addQuery'])->name('add_query');
Route::get('/getqueries', [QueryController::class,'getQueries'])->name('get_queries');
Route::get('/getquery', [QueryController::class,'getQuery'])->name('get_query');
Route::get('/getclaims', [ClaimsController::class,'getClaims'])->name('get_claims');
Route::get('/getclaim', [ClaimsController::class,'getClaim'])->name('get_claim');
Route::get('/getuser', [MemberController::class,'getUser'])->name('get_user');
Route::post('/updateprofile', [MemberController::class,'updateUser'])->name('update_profile');
Route::post('/adddocument', [QueryController::class,'addDocument'])->name('add_document');
Route::get('/getdocuments', [QueryController::class,'getDocuments'])->name('get_documents');
Route::get('/getfaqs', [QueryController::class,'getFaq'])->name('get_faq');
Route::get('/getblogs', [QueryController::class,'getBlog'])->name('get_blog');
Route::post('/feedback', [QueryController::class,'addFeedback'])->name('add_feedback');
  });
  
