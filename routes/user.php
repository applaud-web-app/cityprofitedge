<?php

use Illuminate\Support\Facades\Route;

Route::namespace('User\Auth')->name('user.')->group(function () {

    Route::controller('LoginController')->group(function(){
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login');
        Route::get('logout', 'logout')->middleware('auth')->name('logout');
    });

    Route::controller('RegisterController')->group(function(){
        Route::get('register', 'showRegistrationForm')->name('register');
        Route::post('register', 'register')->middleware('registration.status');
        Route::post('check-mail', 'checkUser')->name('checkUser');
    });

    Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function(){
        Route::get('reset', 'showLinkRequestForm')->name('request');
        Route::post('email', 'sendResetCodeEmail')->name('email');
        Route::get('code-verify', 'codeVerify')->name('code.verify');
        Route::post('verify-code', 'verifyCode')->name('verify.code');
    });
    Route::controller('ResetPasswordController')->group(function(){
        Route::post('password/reset', 'reset')->name('password.update');
        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');
    });
});

Route::middleware('auth')->name('user.')->group(function () {
    //authorization
    Route::namespace('User')->controller('AuthorizationController')->group(function(){
        Route::get('authorization', 'authorizeForm')->name('authorization');
        Route::get('resend-verify/{type}', 'sendVerifyCode')->name('send.verify.code');
        Route::post('verify-email', 'emailVerification')->name('verify.email');
        Route::post('verify-mobile', 'mobileVerification')->name('verify.mobile');
        Route::post('verify-g2fa', 'g2faVerification')->name('go2fa.verify');
    });

    Route::middleware(['check.status'])->group(function () {

        Route::get('user-data', 'User\UserController@userData')->name('data');
        Route::post('user-data-submit', 'User\UserController@userDataSubmit')->name('data.submit');

        Route::middleware('registration.complete')->namespace('User')->group(function () {

            Route::controller('UserController')->group(function(){
                Route::get('dashboard', 'home')->name('home');

                Route::post('purchase/package', 'purchasePackage')->name('purchase.package');
                Route::post('renew/package', 'renewPackage')->name('renew.package');
                Route::get('signals', 'signals')->name('signals');
                Route::get('referrals', 'referrals')->name('referrals');

                //2FA
                Route::get('twofactor', 'show2faForm')->name('twofactor');
                Route::post('twofactor/enable', 'create2fa')->name('twofactor.enable');
                Route::post('twofactor/disable', 'disable2fa')->name('twofactor.disable');

                //Report
                Route::any('deposit/history', 'depositHistory')->name('deposit.history');
                Route::get('transactions','transactions')->name('transactions');
                Route::get('ledgers', 'ledgers')->name('ledgers');
                Route::get('stock-portfolios', 'stockPortfolios')->name('stock.portfolios');
                Route::get('thematic-portfolios', 'thematicPortfolios')->name('thematic.portfolios');
                Route::get('global-stock-portfolio', 'globalStockPortfolio')->name('global.stock.portfolio');
                Route::get('fo-portfolio-hedging', 'foPortfolioHedging')->name('fo.portfolio.hedging');
                Route::get('metals-portfolio', 'metalsPortfolio')->name('metals.portfolio');
                Route::get('portfolio-top-gainers', 'portfolioTopGainers')->name('portfolio.top.gainers');
                Route::get('portfolio-top-losers', 'portfolioTopLosers')->name('portfolio.top.losers');
                Route::get('broker-details', 'brokerDetails')->name('portfolio.broker-details');
                Route::post('store-broker-details', 'storeBrokerDetails')->name('portfolio.store-broker-details');
                Route::post('update-broker-details/{id}', 'updateBrokerDetails')->name('portfolio.update-broker-details');
                Route::get('get-broker-details/{id}', 'getBrokerDetails')->name('portfolio.get-broker-details');
                Route::get('remove-broker-details/{id}', 'removeBrokerDetails')->name('portfolio.remove-broker-details');

                Route::get('attachment-download/{fil_hash}','attachmentDownload')->name('attachment.download');

            });

            //Profile setting
            Route::controller('ProfileController')->group(function(){
                Route::get('info', 'userInfo')->name('info');
                Route::get('profile-setting', 'profile')->name('profile.setting');
                Route::post('profile-setting', 'submitProfile');
                Route::get('change-password', 'changePassword')->name('change.password');
                Route::post('change-password', 'submitPassword');
            });

        });

        // Payment
        Route::middleware('registration.complete')->prefix('deposit')->name('deposit.')->controller('Gateway\PaymentController')->group(function(){
            Route::any('/', 'deposit')->name('index');
            Route::post('insert', 'depositInsert')->name('insert');
            Route::get('confirm', 'depositConfirm')->name('confirm');
            Route::get('manual', 'manualDepositConfirm')->name('manual.confirm');
            Route::post('manual', 'manualDepositUpdate')->name('manual.update');
        });
    });
});
