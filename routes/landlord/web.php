<?php

use App\Http\Controllers\Landlord\Auth\AuthenticateController;
use App\Http\Controllers\Landlord\CustomerController;
use App\Http\Controllers\Landlord\DashboardController;
use App\Http\Controllers\Landlord\PackageController;
use App\Http\Controllers\Landlord\Settings\CompanyDetailController;
use App\Http\Controllers\Landlord\Settings\CronJobController;
use App\Http\Controllers\Landlord\Settings\EmailController;
use App\Http\Controllers\Landlord\Settings\EmailTemplateController;
use App\Http\Controllers\Landlord\Settings\GatewayController;
use App\Http\Controllers\Landlord\Settings\Gateways\StripeController;
use App\Http\Controllers\Landlord\Settings\GeneralController;
use App\Http\Controllers\Landlord\Settings\SmtpController;
use App\Http\Controllers\Landlord\SubscriptionController;
use App\Models\Landlord\Package;

Route::middleware(['landlord'])->group(function () {
    Route::group(['prefix' => 'app-admin','as' => 'landlord.'], function () {
        //LOGIN & SIGNUP
        Route::get("/login", [AuthenticateController::class, 'logIn'])->name('login');
        Route::post("/login", [AuthenticateController::class, 'logInAction'])->name('login-action');
        Route::get("/forgot-password", [AuthenticateController::class, 'forgotPassword'])->name('forgot-password');
        Route::post("/forgot-password", [AuthenticateController::class, 'forgotPasswordAction'])->name('forgot-password-action');
        Route::get("/signup", [AuthenticateController::class, 'signUp'])->name('signup');
        Route::post("/signup", [AuthenticateController::class, 'signUpAction'])->name('signup-action');
        Route::get("/reset-password", [AuthenticateController::class, 'resetPassword'])->name('reset-password');
        Route::post("/reset-password", [AuthenticateController::class, 'resetPasswordAction'])->name('reset-password-action');
        Route::any('logout', function () {
            Auth::logout();
            return redirect('/app-admin/login');
        })->name('logout');
    });
});

Route::middleware(['landlord','auth'])->group(function () {
    Route::group(['prefix' => 'app-admin','as' => 'landlord.'], function () {
        //HOME
        Route::any('/', [DashboardController::class,'index'])->name('root');
        Route::any('/home', [DashboardController::class,'index'])->name('home');
        //CUSTOMERS
        Route::group(['as'=>'customers.'], function () {
            Route::get('customers/', [CustomerController::class, 'index'])->name('index');
            Route::get('customers/create', [CustomerController::class, 'create'])->name('create');
            Route::post('customers/', [CustomerController::class, 'store'])->name('store');
            Route::get('customers/{customer}', [CustomerController::class, 'show'])->name('show');
            Route::get('customers/{customer}/edit', [CustomerController::class, 'edit'])->name('edit');
            Route::patch('customers/{customer}', [CustomerController::class, 'update'])->name('update');
            Route::delete('customers/{customer}', [CustomerController::class, 'destroy'])->name('destroy');
        });
        //PACKAGES
        Route::group(['as'=>'packages.'], function () {
            Route::get('packages/', [PackageController::class, 'index'])->name('index');
            Route::get('packages/create', [PackageController::class, 'create'])->name('create');
            Route::post('packages/', [PackageController::class, 'store'])->name('store');
            Route::get('packages/{package}', [PackageController::class, 'show'])->name('show');
            Route::get('packages/{package}/edit', [PackageController::class, 'edit'])->name('edit');
            Route::patch('packages/{package}', [PackageController::class, 'update'])->name('update');
            Route::delete('packages/{package}', [PackageController::class, 'destroy'])->name('destroy');
            Route::post("packages/{package}/archive", [PackageController::class,'archive'])->where('package', '[0-9]+')->name('archive');
        });
        //SUBSCRIPTIONS
        Route::group(['as'=>'subscriptions.'], function () {
            Route::get("subscriptions/{subscription}/info", "Landlord\Subscriptions@subscriptionInfo")->where('subscription', '[0-9]+');
            Route::post("subscriptions/{subscription}/cancel", "Landlord\Subscriptions@cancelSubscription")->where('subscription', '[0-9]+');
            Route::get("subscriptions/{customer}/create-edit-plan", "Landlord\Subscriptions@createEditPlan")->where('customer', '[0-9]+');
            Route::post("subscriptions/{customer}/create-edit-plan", "Landlord\Subscriptions@storeUpdatePlan")->where('customer', '[0-9]+');
            Route::get('subscriptions/', [SubscriptionController::class, 'index'])->name('index');
            Route::get('subscriptions/create', [SubscriptionController::class, 'create'])->name('create');
            Route::post('subscriptions/', [SubscriptionController::class, 'store'])->name('store');
            Route::get('subscriptions/{subscription}', [SubscriptionController::class, 'show'])->name('show');
            Route::get('subscriptions/{subscription}/edit', [SubscriptionController::class, 'edit'])->name('edit');
            Route::patch('subscriptions/{subscription}', [SubscriptionController::class, 'update'])->name('update');
            Route::delete('subscriptions/{subscription}', [SubscriptionController::class, 'destroy'])->name('destroy');
        });

//
//        //PAYMENTS
//        Route::group(['prefix' => 'payments'], function () {
//            Route::any("/search", "Landlord\Payments@index");
//        });
//        Route::resource('payments', 'Landlord\Payments');
//
//        //OFFLINE PAYMENTS
//        Route::get("/offline-payments", "Landlord\OfflinePayments@index");
//        Route::delete("/offline-payments/{id}", "Landlord\OfflinePayments@destroy")->where('id', '[0-9]+');
//
//        //BLOGS
//        Route::resource('blogs', 'Landlord\Blogs');
//
//        //FILE UPLOADS
//        Route::post("/upload-tinymce-image", "Landlord\Fileupload@saveTinyMCEImage");
//
//        //LOGO UPLOADS
//        Route::post("/upload-logo", "Landlord\Fileupload@saveAppLogo");
//
//        //LOGO IMAGES
//        Route::post("/upload-image", "Landlord\Fileupload@saveImage");
//
//        //AVATAR FILEUPLOAD
//        Route::post("/avatarupload", "Landlord\Fileupload@saveAvatar");
//
//        //FRONTEND
//        Route::group(['prefix' => 'frontend'], function () {
//            Route::get("/general", "Landlord\Frontend\General@show");
//            Route::post("/general", "Landlord\Frontend\General@update")->middleware(['demoModeCheck']);
//        });
//
//        //EVENTS
//        Route::get("/events", "Landlord\Events@index");
//
//        //TEAM
//        Route::resource('team', 'Landlord\Team');
//
//        //SETTINGS
        Route::group(['prefix' => 'settings','as' => 'settings.'], function () {
            Route::get("/general", [GeneralController::class, "show"])->name('general.show');
            Route::post("/general", [GeneralController::class, "update"])->name('general.update');
//            Route::get("/domain", "Landlord\Settings\Domain@show")->name('domain.show');
//            Route::post("/domain", "Landlord\Settings\Domain@update")->name('domain.update');
            Route::get("/company", [CompanyDetailController::class, "show"])->name('company.show');
            Route::post("/company", [CompanyDetailController::class, "update"])->name('company.update');
            Route::get("/free-trial", "Landlord\Settings\Freetrial@show")->name('free-trial.show');
            Route::post("/free-trial", "Landlord\Settings\Freetrial@update")->name('free-trial.update');
            Route::get("/offline-payments", "Landlord\Settings\Offlinepayments@show")->name('offline-payments.show');
            Route::post("/offline-payments", "Landlord\Settings\Offlinepayments@update")->name('offline-payments.update');
//            Route::get("/currency", "Landlord\Settings\Currency@show")->name('currency.show');
//            Route::post("/currency", "Landlord\Settings\Currency@update")->name('currency.update');
            Route::get("/email-templates", [EmailTemplateController::class,'show'])->name('email-templates.show');
//            Route::post("/email-templates", [EmailTemplateController::class,'update'])->name('email-templates.update');
            Route::get("/email-templates/{id}", [EmailTemplateController::class, "showTemplate"])->name('email-templates.showTemplate')->where('id', '[0-9]+');
            Route::post("/email-templates/{id}", [EmailTemplateController::class, "updateTemplate"])->name('email-templates.update')->where('id', '[0-9]+');
            Route::post("/email-templates/upload-image/{id}", [EmailTemplateController::class, "uploadImage"])->name('email-templates.update')->where('id', '[0-9]+');
            Route::get("/email", [EmailController::class,'show'])->name('email.show');
            Route::post("/email", [EmailController::class,'update'])->name('email.update');
            Route::get("/smtp", [SmtpController::class,'show'])->name('smtp.show');
            Route::post("/smtp", [SmtpController::class,"update"])->name('smtp.update');
            Route::get("/test-smtp", "Landlord\Settings\Smtp@testSMTP")->name('smtp.test-smtp');
            Route::get("/logo", "Landlord\Settings\Logo@show")->name('logo.show');
            Route::get("/logo/upload", "Landlord\Settings\Logo@edit")->name('logo.edit');
            Route::put("/logo/upload", "Landlord\Settings\Logo@update")->name('logo.update');
//            Route::get("/cronjob", [CronJobController::class,'show'])->name('cronjob.show');
//            Route::post("/cronjob", [CronJobController::class,'update'])->name('cronjob.update');
            Route::get("/email/test-email", [EmailController::class,'testEmail'])->name('email.test-email');
            Route::post("/email/test-email", [EmailController::class,'testEmailAction'])->name('email.test-email-action');
            Route::get("/gateways", [GatewayController::class,'show'])->name('gateways.show');
            Route::post("/gateways", [GatewayController::class,'update'])->name('gateways.update');
            Route::get("/database", "Landlord\Settings\Database@show")->name('database.show');
            Route::post("/database/user", "Landlord\Settings\Database@updateUser")->name('database.update-user');
            Route::get("/system", "Landlord\Settings\System@show")->name('system.show');
            Route::get("/email-log", [EmailController::class,"logShow"])->name('email-log.show');
            Route::get("/email-log/{id}", [EmailController::class,"logRead"])->name('email-log.read')->where('id', '[0-9]+');
            Route::delete("/email-log/{id}", "Landlord\Settings\Email@logDelete")->name('email-log.delete')->where('id', '[0-9]+');
            Route::delete("/email-log/purge", "Landlord\Settings\Email@logPurge")->name('email-log.purge');
            Route::get("/updates-log", "Landlord\Settings\Updateslog@logShow")->name('update-log.show');
            Route::get("/updates-log/{id}", "Landlord\Settings\Updateslog@logRead")->name('update-log.read')->where('id', '[0-9]+');
            Route::get("/error-logs", "Landlord\Settings\Errorlogs@index")->name('error-logs.index');
            Route::delete("/error-logs/delete", "Landlord\Settings\Errorlogs@delete")->name('error-log.delete')->where('id', '[0-9]+');
            Route::get("/error-logs/download", "Landlord\Settings\Errorlogs@download")->name('update-log.download');
            Route::get("/defaults", "Landlord\Settings\Defaults@show")->name('defaults.show');
            Route::post("/defaults", "Landlord\Settings\Defaults@update")->name('defaults.update');

            //payment gateways
            Route::get("/stripe", [StripeController::class,'show'])->name('stripe.show');
            Route::post("/stripe", [StripeController::class,'update'])->name('stripe.update');

//            Route::get("/paypal", "Landlord\Settings\Gateways\Paypal@show");
//            Route::post("/paypal", "Landlord\Settings\Gateways\Paypal@update")->middleware(['demoModeCheck']);
//
//            Route::get("/paystack", "Landlord\Settings\Gateways\Paystack@show");
//            Route::post("/paystack", "Landlord\Settings\Gateways\Paystack@update")->middleware(['demoModeCheck']);
//
//            Route::get("/razorpay", "Landlord\Settings\Gateways\Razorpay@show");
//            Route::post("/razorpay", "Landlord\Settings\Gateways\Razorpay@update")->middleware(['demoModeCheck']);
        });
//
//        //FRONTEND
//        Route::group(['prefix' => 'frontend'], function () {
//
//            //start
//            Route::get("/start", "Landlord\Frontend\Start@show");
//            Route::post("/start", "Landlord\Frontend\Start@update")->middleware(['demoModeCheck']);
//
//            //hero
//            Route::get("/hero", "Landlord\Frontend\Heroheader@edit");
//            Route::post("/hero", "Landlord\Frontend\Heroheader@update")->middleware(['demoModeCheck']);
//
//            //image-content sections
//            Route::get("/section/{id}/image-content", "Landlord\Frontend\ImageContent@edit")->where('id', '[0-9]+');
//            Route::post("/section/{id}/image-content", "Landlord\Frontend\ImageContent@update")->where('id', '[0-9]+')->middleware(['demoModeCheck']);
//
//            //list features section
//            Route::get("/section/{id}/list", "Landlord\Frontend\SectionList@edit")->where('id', '[0-9]+');
//            Route::post("/section/{id}/list", "Landlord\Frontend\SectionList@update")->where('id', '[0-9]+')->middleware(['demoModeCheck']);
//
//            //image-content sections
//            Route::get("/section/{id}/feature", "Landlord\Frontend\SectionFeature@edit");
//            Route::post("/section/{id}/feature", "Landlord\Frontend\SectionFeature@update")->middleware(['demoModeCheck']);
//
//            //image-content sections
//            Route::get("/section/{id}/cta", "Landlord\Frontend\SectionCTA@edit");
//            Route::post("/section/{id}/cta", "Landlord\Frontend\SectionCTA@update")->middleware(['demoModeCheck']);
//
//            //list features section
//            Route::get("/section/splash", "Landlord\Frontend\SectionSplash@edit");
//            Route::post("/section/splash", "Landlord\Frontend\SectionSplash@update")->middleware(['demoModeCheck']);
//            Route::get("/section/{id}/splash", "Landlord\Frontend\SectionSplash@editImage")->where('id', '[0-9]+');
//            Route::post("/section/{id}/splash", "Landlord\Frontend\SectionSplash@updateImage")->where('id', '[0-9]+')->middleware(['demoModeCheck']);
//
//            //mainmenu
//            Route::resource('mainmenu', 'Landlord\Frontend\MainMenu');
//            Route::post("/mainmenu/update-positions", "Landlord\Frontend\MainMenu@updatePositions")->middleware(['demoModeCheck']);
//
//            //pricing
//            Route::get("/pricing", "Landlord\Frontend\Pricing@edit");
//            Route::post("/pricing", "Landlord\Frontend\Pricing@update")->middleware(['demoModeCheck']);
//
//            //contact us
//            Route::get("/contact", "Landlord\Frontend\Contact@edit");
//            Route::post("/contact", "Landlord\Frontend\Contact@update")->middleware(['demoModeCheck']);
//
//            //faq
//            Route::resource('faq', 'Landlord\Frontend\Faq');
//            Route::post("/faq/update-positions", "Landlord\Frontend\Faq@updatePositions");
//            Route::post("/faq/update", "Landlord\Frontend\Faq@updateDetails")->middleware(['demoModeCheck']);
//
//            //pages
//            Route::resource('pages', 'Landlord\Frontend\Pages');
//
//            //footer
//            Route::get("/footer", "Landlord\Frontend\Footer@edit");
//            Route::post("/footer", "Landlord\Frontend\Footer@update")->middleware(['demoModeCheck']);
//
//            //footer cta
//            Route::get("/footercta", "Landlord\Frontend\Footercta@edit");
//            Route::post("/footercta", "Landlord\Frontend\Footercta@update")->middleware(['demoModeCheck']);
//
//            //logo
//            Route::get("/logo", "Landlord\Frontend\Logo@show");
//            Route::get("/logo/uploadlogo", "Landlord\Frontend\Logo@edit");
//            Route::put("/logo/uploadlogo", "Landlord\Frontend\Logo@update")->middleware(['demoModeCheck']);
//
//            //meta tags
//            Route::get("/metatags", "Landlord\Frontend\Metatags@edit");
//            Route::post("/metatags", "Landlord\Frontend\Metatags@update")->middleware(['demoModeCheck']);
//
//            //customer code
//            Route::get("/customcode", "Landlord\Frontend\Customcode@edit")->middleware(['demoModeCheck']);
//            Route::post("/customcode", "Landlord\Frontend\Customcode@update")->middleware(['demoModeCheck']);
//
//            //signup
//            Route::get("/signup", "Landlord\Frontend\Signup@edit");
//            Route::post("/signup", "Landlord\Frontend\Signup@update")->middleware(['demoModeCheck']);
//
//            //login
//            Route::get("/login", "Landlord\Frontend\Login@edit");
//            Route::post("/login", "Landlord\Frontend\Login@update")->middleware(['demoModeCheck']);
//        });
//
//        //ADMIN USER
//        Route::group(['prefix' => 'users'], function () {
//            Route::get("/preferences/leftmenu", "Landlord\Users@preferenceMenu");
//            Route::get("/profile", "Landlord\Profile@show");
//            Route::put("/profile", "Landlord\Profile@update")->middleware(['demoModeCheck']);
//            Route::get("/avatar", "Landlord\Users@avatar");
//            Route::put("/avatar", "Landlord\Users@updateAvatar")->middleware(['demoModeCheck']);
//        });
//
//        //AUTOCOMPLETE AJAX FEED
//        Route::group(['prefix' => 'feed'], function () {
//            Route::get("/customers", "Landlord\Feed@customerNames");
//        });
//
        //PAYMENT GATEWAY WEB HOOKS
        Route::group(['prefix' => 'webhooks'], function () {

            //NOTE - must add any new routes (names) to this file to avoid error - ..\Middleware\General\StripHtmlTags.php
            Route::any("/stripe", "Landlord\Webhooks\Stripe\Stripe@index")->name('webhooks-stripe');
            Route::any("/paypal", "Landlord\Webhooks\Paypal\Paypal@index")->name('webhooks-paypal');
            Route::any("/paystack", "Landlord\Webhooks\Paystack\Paystack@index")->name('webhooks-paystack');
            //Route::any("/razorpay", "Landlord\Webhooks\Razorpay\Razorpay@index")->name('webhooks-razorpay');
            Route::any("/razorpay2", "Landlord\Webhooks\Razorpay\Razorpay@index")->name('webhooks-razorpay'); //temp

            //NOTE - must add any new routes (names) to this file to avoid error - ..\Middleware\General\StripHtmlTags.php

        });

    });

});
