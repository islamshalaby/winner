<?php

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

Route::get('/setlocale/{locale}',function($lang){
    Session::put('locale',$lang);
    return redirect()->back();   
});



// Dashboard Routes
Route::group([
    'middleware'=>'language',
    'prefix' => "admin-panel",
    'namespace' => "Admin"  
] , function($router){

    Route::get('' ,'HomeController@show');
    Route::get('login' ,  [ 'as' => 'adminlogin', 'uses' => 'AdminController@getlogin']);
    Route::post('login' , 'AdminController@postlogin');
    Route::get('logout' , 'AdminController@logout');
    Route::get('profile' , 'AdminController@profile');
    Route::post('profile' , 'AdminController@updateprofile');    
    Route::get('databasebackup' , 'AdminController@backup');

    // Users routes for dashboard
    Route::group([
        'prefix' => 'users',
    ] , function($router){
            Route::get('add' , 'UserController@AddGet');
            Route::post('add' , 'UserController@AddPost');
            Route::get('show' , 'UserController@show');
            Route::get('edit/{id}' , 'UserController@edit');
            Route::post('edit/{id}' , 'UserController@EditPost');
            Route::get('details/{id}' , 'UserController@details');
            Route::post('send_notifications/{id}' , 'UserController@SendNotifications');
            Route::get('block/{id}' , 'UserController@block');
            Route::get('active/{id}' , 'UserController@active');
            Route::get('{user_id}/address' , 'UserController@GetAddress');
            Route::get('/address/details/{id}' , 'UserController@GetAddressDetails');
            Route::get('/address/delete/{id}' , 'UserController@DeleteAddress');
        }
    );

    // admins routes for dashboard
    Route::group([
        'prefix' => "managers",
    ], function($router){
        Route::get('add' , 'ManagerController@AddGet');
        Route::post('add' , 'ManagerController@AddPost');
        Route::get('show' , 'ManagerController@show');
        Route::get('edit/{id}' , 'ManagerController@edit');
        Route::post('edit/{id}' , 'ManagerController@EditPost');
        Route::get('delete/{id}' , 'ManagerController@delete');
    });

    // App Pages For Dashboard
    Route::group([
        'prefix' => 'app_pages'
    ] , function($router){
        Route::get('aboutapp' , 'AppPagesController@GetAboutApp');
        Route::post('aboutapp' , 'AppPagesController@PostAboutApp');
        Route::get('termsandconditions' , 'AppPagesController@GetTermsAndConditions');
        Route::post('termsandconditions' , 'AppPagesController@PostTermsAndConditions');
		Route::get('return_policy' , 'AppPagesController@GetReturnPolicy');
        Route::post('return_policy' , 'AppPagesController@PostReturnPolicy');
		Route::get('competition_terms' , 'AppPagesController@getcompetition_terms');
        Route::post('competition_terms' , 'AppPagesController@postcompetition_terms');
    });

    // Setting Route
    Route::get('settings' , 'SettingController@GetSetting');
    Route::post('settings' , 'SettingController@PostSetting');

    // delivery Cost Route
    Route::get('delivery_cost' , 'SettingController@getdeliverycost');
    Route::post('delivery_cost' , 'SettingController@postdeliverycost');

    // meta tags Route
    Route::get('meta_tags' , 'MetaTagController@getMetaTags');
    Route::post('meta_tags' , 'MetaTagController@postMetaTags');

    // Ads Route
    Route::group([
        "prefix" => "ads"
    ],function($router){
        Route::get('add' , 'AdController@AddGet');
        Route::post('add' , 'AdController@AddPost');
        Route::get('show' , 'AdController@show');
        Route::get('edit/{id}' , 'AdController@EditGet');
        Route::post('edit/{id}' , 'AdController@EditPost');
        Route::get('details/{id}' , 'AdController@details');
        Route::get('delete/{id}' , 'AdController@delete');
    });

    // Categories Route
    Route::group([
        "prefix" => "categories"
    ], function($router){
         Route::get('add' , 'CategoryController@AddGet');
         Route::post('add' , 'CategoryController@AddPost');
         Route::get('show' , 'CategoryController@show');
         Route::get('edit/{id}' , 'CategoryController@EditGet');
         Route::post('edit/{id}' , 'CategoryController@EditPost');
         Route::get('delete/{id}' , 'CategoryController@delete');        
    });


    // Contact Us Messages Route
    Route::group([
        "prefix" => "contact_us"
    ] , function($router){
        Route::get('' , 'ContactUsController@show');
        Route::get('details/{id}' , 'ContactUsController@details');
        Route::get('delete/{id}' , 'ContactUsController@delete');
    });

    // Notifications Route
    Route::group([
        "prefix" => "notifications"
    ], function($router){
        Route::get('show' , 'NotificationController@show');
        Route::get('details/{id}' , 'NotificationController@details');
        Route::get('delete/{id}' , 'NotificationController@delete');
        Route::get('send' , 'NotificationController@getsend');
        Route::post('send' , 'NotificationController@send');
        Route::get('resend/{id}' , 'NotificationController@resend');        
    });

    // Users routes for dashboard
    Route::group([
        'prefix' => 'products',
    ] , function($router){
            Route::get('add' , 'ProductController@AddGet');
            Route::post('add' , 'ProductController@AddPost');
            Route::get('show' , 'ProductController@show');
            Route::get('edit/{id}' , 'ProductController@edit');
            Route::post('edit/{id}' , 'ProductController@EditPost');
            Route::get('details/{id}' , 'ProductController@details');
            Route::get('delete/{id}' , 'ProductController@delete');
            Route::get('images/delete/{id}' , 'ProductController@deleteImage');
        }
    );

    // Coupons Route
    Route::group([
        "prefix" => "coupons"
    ] , function($router){
        Route::get('' , 'CouponsController@show');
        Route::get('details/{id}' , 'CouponsController@details');
        Route::get('delete/{id}' , 'CouponsController@delete');
        Route::post('winner/{product_id}' , 'CouponsController@SelectController');
    });

    // orders Route
    Route::group([
        "prefix" => "orders"
    ] , function($router){
        Route::get('' , 'OrderController@show');
        Route::get('details/{id}' , 'OrderController@details');
        Route::get('delivered/{id}' , 'OrderController@delivered');
    });

});

Route::get('.well-known/pki-validation/228115A4BB762FB6A933F861C3478949.txt' , 'WebViewController@getcert');





// Web View Routes 
Route::group([
    'prefix' => "webview"
] , function($router){
    Route::get('aboutapp' , 'WebViewController@getabout');
    Route::get('termsandconditions' , 'WebViewController@gettermsandconditions' );
	 Route::get('returnpolicy' , 'WebViewController@getreturnpolicy' );
	Route::get('competition_terms' , 'WebViewController@competition_terms' );
});