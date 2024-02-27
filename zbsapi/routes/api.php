<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\AuthController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('guest:sanctum')->group(function(){
Route::post('/signup', [AuthController::class,'Signup'])->name('signup');
Route::post('/login', [AuthController::class,'Login'])->name('login');
});

Route::middleware('auth:sanctum')->group(function(){
Route::get('/getdetails', [MemberController::class,'getDetails'])->name('get_details');
Route::get('/getdependents', [MemberController::class,'getDependents'])->name('get_dependents');
Route::get('/getaccounts', [MemberController::class,'getAccounts'])->name('get_accounts');
Route::get('/getnotifications', [MemberController::class,'getNotifications'])->name('get_notifications');
Route::get('/getadmins', [MemberController::class,'getAdmins'])->name('get_admins');
Route::get('/getprofile', [MemberController::class,'getProfile'])->name('get_profile');
Route::get('/getregister/{member_id}', [MemberController::class,'getRegister'])->name('get_register');
Route::get('/getdeceased/{funeral_id}', [MemberController::class,'getDeceased'])->name('get_deceased');
});