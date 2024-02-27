<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect("/login");
});

//Two factor

Route::view('/profile/edit', 'profile.edit')->middleware('auth');
Route::view('/profile/password', 'profile.password')->middleware('auth');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('home');
Route::get('/enable2fa', [App\Http\Controllers\HomeController::class, 'enable'])->middleware(['auth', 'verified'])->name('enable2fa');

//  Auth::routes();
//Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');
Route::get('/unsubscribe', 'App\Http\Controllers\Auth\RegisterController@unsubscribe')->name('unsubscribe');
Route::get('/checkreceive/{campaign_id}/{subscriber_id}', 'App\Http\Controllers\Auth\RegisterController@checkreceive')->name('checkreceive');
Route::get('/activation', 'App\Http\Controllers\Auth\RegisterController@activation')->name('activation');
Route::post('/activation', 'App\Http\Controllers\Auth\RegisterController@activate')->name('activate');


//CRM Authentication
Route::group(['middleware' => 'crmauth'], function () {
	Route::get('item/{action}/{item}/{item_id}', ['as' => 'create', 'uses' => 'App\Http\Controllers\CRMController@create_item']);
	Route::get('getcustomers', ['as' => 'getcustomers', 'uses' => 'App\Http\Controllers\CRMController@getcustomers']);
	Route::post('getotherdetails', ['as' => 'getotherdetails', 'uses' => 'App\Http\Controllers\CRMController@getotherdetails']);
    Route::post('item-post', ['as' => 'itemchange', 'uses' => 'App\Http\Controllers\CRMController@itemchange']);
    Route::post('deleteitemlist', ['as' => 'deleteitemlist', 'uses' => 'App\Http\Controllers\CRMController@deleteitemlist']);
    Route::post('getfortabledata', ['as' => 'getfortabledata', 'uses' => 'App\Http\Controllers\CRMController@getfortabledata']);
    Route::get('quotes', ['as' => 'quotes', 'uses' => 'App\Http\Controllers\CRMController@quotes']);
    Route::get('invoices', ['as' => 'invoices', 'uses' => 'App\Http\Controllers\CRMController@invoices']);
    Route::get('orders', ['as' => 'orders', 'uses' => 'App\Http\Controllers\CRMController@orders']);
    Route::get('customer_details/{customer_id}', ['as' => 'customer_details', 'uses' => 'App\Http\Controllers\CRMController@customer_details']);
    Route::post('action_customer', ['as' => 'action_customer', 'uses' => 'App\Http\Controllers\CRMController@action_customer']);
    Route::post('deleteentity/{entity}/{entity_id}', ['as' => 'deleteentity', 'uses' => 'App\Http\Controllers\CRMController@deleteentity']);
    Route::get('getdashrecords', ['as' => 'getdashrecords', 'uses' => 'App\Http\Controllers\CRMController@getdashrecords']);
    Route::get('cancellead/{lead_id}', ['as' => 'cancellead', 'uses' => 'App\Http\Controllers\CRMController@cancellead']);
    Route::get('print/{entity_name}/{entity_id}', ['as' => 'print', 'uses' => 'App\Http\Controllers\PdfController@print']);
});

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('system_users', ['as' => 'system_users', 'uses' => 'App\Http\Controllers\UserController@users']);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);	
	Route::get('projects', ['as' => 'projects', 'uses' => 'App\Http\Controllers\PageController@projects']);
	Route::post('create_project', ['as' => 'create_project', 'uses' => 'App\Http\Controllers\PageController@create_project']);
	Route::get('campaigns', ['as' => 'campaigns', 'uses' => 'App\Http\Controllers\MarketingController@campaigns']);
	Route::get('new_campaign/{mytype}', ['as' => 'new_campaign', 'uses' => 'App\Http\Controllers\MarketingController@new_campaign']);
	Route::post('add_campaign', ['as' => 'add_campaign', 'uses' => 'App\Http\Controllers\MarketingController@add_campaign']);
	Route::post('content_generator', ['as' => 'content_generator', 'uses' => 'App\Http\Controllers\aiController@query_ai']);
	Route::get('content-management', ['as' => 'content-management', 'uses' => 'App\Http\Controllers\aiController@content_management']);
	Route::get('facebook_analysis', ['as' => 'facebook_analysis', 'uses' => 'App\Http\Controllers\aiController@facebook_analysis']);
	Route::get('image_editor', ['as' => 'image_editor', 'uses' => 'App\Http\Controllers\aiController@image_editor']);
	Route::get('keyword_suggestion', ['as' => 'keyword_suggestion', 'uses' => 'App\Http\Controllers\aiController@keyword_suggestion']);
	Route::get('view_campaign/{campaign_id}', ['as' => 'view_campaign', 'uses' => 'App\Http\Controllers\MarketingController@view_campaign']);
	Route::get('content_generator', ['as' => 'generate_content', 'uses' => 'App\Http\Controllers\MarketingController@generate_content']);
	Route::post('add_socialpost', ['as' => 'add_socialpost', 'uses' => 'App\Http\Controllers\aiController@add_socialpost']);
	Route::get('audience', ['as' => 'audience', 'uses' => 'App\Http\Controllers\MarketingController@subscribers']);
	Route::get('getsubscribers', ['as' => 'getsubscribers', 'uses' => 'App\Http\Controllers\MarketingController@getsubscribers']);
	Route::get('seo', ['as' => 'seo', 'uses' => 'App\Http\Controllers\aiController@seo']);
	Route::post('getmarketingtable', ['as' => 'getmarketingtable', 'uses' => 'App\Http\Controllers\MarketingController@getfortabledata']);
	Route::get('subscriber_details/{subscriber_id}', ['as' => 'subscriber_details', 'uses' => 'App\Http\Controllers\MarketingController@subscriber_details']);
	Route::post('add_subscriber', ['as' => 'add_subscriber', 'uses' => 'App\Http\Controllers\MarketingController@add_subscriber']);
	Route::get('create_brief/{project_id}', ['as' => 'create_brief', 'uses' => 'App\Http\Controllers\PageController@create_page']);
	Route::post('upload_file', ['as' => 'upload_file', 'uses' => 'App\Http\Controllers\PageController@upload']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
	Route::get('{page}', ['as' => 'page.index', 'uses' => 'App\Http\Controllers\PageController@index']);
});
