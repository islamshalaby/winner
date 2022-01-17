<?php

use Illuminate\Http\Request;

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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login/{v}', [ 'as' => 'login', 'uses' => 'AuthController@login'])->middleware('checkguest');
    Route::post('logout/{v}', 'AuthController@logout');
    Route::post('refresh/{v}', 'AuthController@refresh');
    Route::post('me/{v}', 'AuthController@me');
    Route::post('register/{v}' , [ 'as' => 'register', 'uses' => 'AuthController@register'])->middleware('checkguest');
});

Route::get('/invalid/{v}', [ 'as' => 'invalid', 'uses' => 'AuthController@invalid']);


// users apis group
Route::group([
    'middleware' => 'api',
    'prefix' => 'user'
], function($router) {
    Route::get('profile/{v}' , 'UserController@getprofile');
    Route::put('updateprofile/{v}' , 'UserController@updateprofile');
    Route::put('resetpassword/{v}' , 'UserController@resetpassword');
    Route::put('resetforgettenpassword/{v}' , 'UserController@resetforgettenpassword')->middleware('checkguest');
    Route::post('checkphoneexistance/{v}' , 'UserController@checkphoneexistance')->middleware('checkguest');

    Route::get('address/{v}' , 'UserController@getaddress');
    Route::get('address/{id}/{v}' , 'UserController@addressDetails');
    Route::post('address/{v}' , 'UserController@addaddress');
    Route::delete('address/{id}/{v}' , 'UserController@deleteaddress');

    Route::get('notifications/{v}' , 'UserController@notifications');
});

// send contact us message
Route::post('/contactus/{v}' , 'ContactUsController@SendMessage')->middleware('checkguest');

Route::get('/deliverycost/{v}' , 'SettingController@deliverycost')->middleware('checkguest');

// home apis
Route::group([
    'middleware' => 'api',
    "prefix" => "home"
] , function(){
    Route::get('ads/{v}' , 'HomeController@getads')->middleware('checkguest');
    Route::get('stories/{v}' , 'HomeController@getstories')->middleware('checkguest');
    Route::get('products/{v}' , 'HomeController@getproducts')->middleware('checkguest');
});

// products apis
Route::group([
    'middleware' => 'api',
    "prefix" => "products"
] , function(){
    Route::get('details/{id}/{v}' , 'ProductController@details')->middleware('checkguest');
});

// favorites 
Route::group([
    'middleware' => 'api',
    "prefix" => "favorites"
] , function(){
    Route::get('{v}' , 'FavoriteController@get');
    Route::post('{id}/{v}' , 'FavoriteController@add');
    Route::delete('{id}/{v}' , 'FavoriteController@remove');
});

// orders
Route::group([
    'middleware' => 'api',
    "prefix" => "orders"
] , function(){
    Route::post('{v}' , 'OrderController@create');
    Route::get('{v}' , 'OrderController@GetOrders');
    Route::get('{id}/{v}' , 'OrderController@details');
    Route::get('{id}/coupons/{v}' , 'OrderController@coupons');
});

Route::get('/excute_pay' , 'OrderController@excute_pay');
Route::get('/pay/success' , 'OrderController@pay_sucess');
Route::get('/pay/error' , 'OrderController@pay_error');

// get app number
Route::get('/getappnumber/{v}' , 'SettingController@getappnumber')->middleware('checkguest');

// get whats app number
Route::get('/getwhatsappnumber/{v}' , 'SettingController@getwhatsapp')->middleware('checkguest');

 // coupons 
Route::group([
    'middleware' => 'api',
    "prefix" => "coupons"
] , function(){
    Route::get('products/{v}' , 'CouponController@products');
    Route::get('products/{id}/{v}' , 'CouponController@productCoupons');
});