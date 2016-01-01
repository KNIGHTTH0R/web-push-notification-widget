<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
  return view('home');  
});

Route::get('dashboard', 'DashboardController@index');


// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

Route::resource('subscriber', 'SubscriberController');
Route::resource('notification', 'NotificationController');
Route::resource('segment', 'SegmentController');

Route::post('notification/analytics', 'NotificationController@updateSentNotification');
Route::get('notification/{user_id}/latest', 'NotificationController@getLatestNotification');

Route::get('delivery.js', 'DeliveryController@index');