<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/print', function () {
    return view('download.pdf.paymentpdf');
});
Route::post('/sign-in', 'Auth\LoginController@login');
Route::get('/sign-out', 'Auth\LoginController@logout');
Auth::routes();
Route::group(['middleware' => ['auth']], function() {
	// route for header started
	Route::get('/profile', 'AuthController@profile');
	Route::post('/profile-update/{id}', 'AuthController@profile_post');
	Route::get('/timeline-log', 'AuthController@timeline');
	Route::get('/setting', 'AuthController@setting');
	Route::get('/help', 'AuthController@help');
	Route::get('/get-menu', 'AuthController@getmenu');
	Route::get('/permission-data', 'Roles\RoleController@permissionData');
	Route::get('/permission-data/{id}', 'Roles\RoleController@permissionDataEdit');
	Route::post('/cek-post-sysnc-micro', 'Users\UserController@cekpost');
	Route::get('/documentation/{authorization}', 'AuthController@documentation');
	Route::get('/count-notification', 'Notification\Notification@count');
	Route::get('/rendered-notification', 'Notification\Notification@show');
	Route::get('/notif-link-show/show-activity/{code}', 'Notification\Notification@showActivity');
	Route::get('/notif-link-show/show-payment/{code}', 'Notification\Notification@showPayment');
	Route::get('/notif-link-show/show-visit-tempat-tinggal/{code}', 'Notification\Notification@showTempatTinggal');
	Route::get('/notif-link-show/show-visit-jaminan/{code}', 'Notification\Notification@showJaminan');
	// route for header ended

	Route::get('/home', 'HomeController@index');
	Route::get('/check', 'HomeController@userOnlineStatus');
	Route::get('/home-task-assigment', 'HomeController@taskAssigment');
	Route::post('/get-form-tasklist', 'Assigments\AssigmentController@getform');
	Route::get('/check-location', 'Assigments\AssigmentController@locationmap');
	Route::post('/assigments/update/{id}', 'Assigments\AssigmentController@update');
	Route::post('/assigments-visit-store-tempat-tinggal', 'Assigments\VisitController@storeTT');
	Route::post('/assigments-visit-store-jaminan', 'Assigments\VisitController@storeJM');
	Route::get('/assigment-visit-index-temp-tgl', 'Assigments\VisitController@indexTempTgl');
	Route::get('/assigment-visit-index-jaminan', 'Assigments\VisitController@indexJmnan');
	Route::get('/visit-assigment-tt', 'Assigments\VisitController@tempattinggal');
	Route::get('/visit-assigment-jm', 'Assigments\VisitController@jaminan');
	Route::get('/assigments-visit-show/{id}', 'Assigments\VisitController@showtempattinggal');
	Route::post('/tempattinggal-updated/{id}', 'Assigments\VisitController@tempattinggalUpdate');
	Route::post('/jaminan-updated/{id}', 'Assigments\VisitController@jaminanUpdate');
	Route::delete('/assigments-destroy-tt/{id}', 'Assigments\VisitController@tempattinggalDestroy');
	Route::delete('/assigments-destroy-jm/{id}', 'Assigments\VisitController@jaminanDestroy');
	Route::get('/assigments-activity-index', 'Assigments\ActivityController@index');
	Route::post('/assigments-activity-update/{id}', 'Assigments\ActivityController@update');
	Route::post('/assigments-activity-store', 'Assigments\ActivityController@store');
	Route::delete('/assigments-destroy-activity/{id}', 'Assigments\ActivityController@destroy');
	Route::get('/assigments-payment-index', 'Assigments\PaymentController@index');
	Route::post('/assigments-payment-update/{id}', 'Assigments\PaymentController@update');
	Route::post('/assigments-payment-store', 'Assigments\PaymentController@store');
	Route::delete('/assigments-destroy-payment/{id}', 'Assigments\PaymentController@destroy');
	Route::get('/assigments-payment-print/{id}', 'Assigments\PaymentController@print');

	// mailsend
	Route::get('/test-mail','MailController@index');
	// mailsend

    Route::resource('roles','Roles\RoleController');
    Route::resource('users','Users\UserController');
    Route::resource('menus','Menus\MenuController');
    Route::resource('assigments','Assigments\AssigmentController');
    Route::resource('infocolls','Informations\InfocollController');
    Route::resource('documentations','Informations\DocumentationController');
});
