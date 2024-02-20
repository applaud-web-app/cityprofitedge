<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'SiteController@index')->name('home');

Route::get('get-market-data', 'SiteController@getMarketData')->name('get-market-data');
Route::get('get-top-loser-api-data', 'SiteController@getTopLoserData')->name('get-top-loser-api-data');
Route::get('get-top-gainer-api-data', 'SiteController@getTopGainerApiData')->name('get-top-gainer-api-data');
Route::get('get-pcr-api-data', 'SiteController@getPcrApiData')->name('get-pcr-api-data');
Route::get('get-long-build-api-data', 'SiteController@getLongBuildApiData')->name('get-long-build-api-data');
Route::get('get-short-build-api-data', 'SiteController@getShortBuildApiData')->name('get-short-build-api-data');
Route::get('get-short-covering-api-data', 'SiteController@getShortCoveringApiData')->name('get-short-covering-api-data');
Route::get('get-long-unwilling-api-data', 'SiteController@getLongUnwillingApiData')->name('get-long-unwilling-api-data');

// Store Instruments Data
Route::get('store-token-data', 'SiteController@storeTokenData')->name('storeTokenData');

// Store Historical Data
Route::get('store-api-fetch-data', 'SiteController@storeApiFetchData');

Route::get('fetch-option-greek-data','SiteController@fetchGreeksApiData')->name('fetch-option-greek-data');

Route::get('/clear', function(){
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

Route::controller('CronController')->prefix('cron')->group(function () {
    Route::get('/', 'cron')->name('cron');
    Route::get('/all', 'all')->name('cron.all');
});


// User Support Ticket
Route::controller('TicketController')->prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/', 'supportTicket')->name('index');
    Route::get('new', 'openSupportTicket')->name('open');
    Route::post('create', 'storeSupportTicket')->name('store');
    Route::get('view/{ticket}', 'viewTicket')->name('view');
    Route::post('reply/{ticket}', 'replyTicket')->name('reply');
    Route::post('close/{ticket}', 'closeTicket')->name('close');
    Route::get('download/{ticket}', 'ticketDownload')->name('download');
});

Route::get('app/deposit/confirm/{hash}', 'Gateway\PaymentController@appDepositConfirm')->name('deposit.app.confirm');

Route::controller('SiteController')->group(function () {

    Route::post('/add/device/token', 'getDeviceToken')->name('add.device.token');

    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit');
    Route::get('/change/{lang?}', 'changeLanguage')->name('lang');

    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');

    Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');
    Route::get('/packages', 'packages')->name('packages');
    Route::get('/package-details/{id}', 'packageDetails')->name('packagedetails');
    Route::post('/package-details/{id}', 'storeUserRequest')->name('storeUserRequest');

    Route::get('/blogs', 'blogs')->name('blogs');
    Route::get('blog/{slug}/{id}', 'blogDetails')->name('blog.details');

    Route::post('/subscribe', 'subscribe')->name('subscribe');
    Route::get('policy/{slug}/{id}', 'policyPages')->name('policy.pages');

    Route::get('placeholder-image/{size}', 'placeholderImage')->name('placeholder.image');

    Route::get('/{slug}', 'pages')->name('pages');
    
    // Route::get('/', 'index')->name('home');
});
