<?php

use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\FaqController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\LoginController;
use App\Http\Controllers\Frontend\PricingController;
use App\Http\Controllers\Frontend\SignupController;

Route::middleware(['landlord'])->group(function () {
    Route::get('/', [HomeController::class, 'home']);
    Route::get('/faq', [FaqController::class, 'index']);
    Route::get('/pricing', [PricingController::class, 'index']);
    Route::get('contact', [ContactController::class, 'index']);
    Route::post('contact', [ContactController::class, 'submitForm']);
    Route::group(['prefix' => 'account'], function () {
        Route::any("/signup", [SignupController::class, 'index']);
        Route::post("/signup", [SignupController::class, 'createAccount']);
        Route::any("/login", [LoginController::class, 'index']);
        Route::post("/login", [LoginController::class, 'getAccount']);
        Route::post("/login", [LoginController::class, 'getAccount']);
    });
});
Route::get('/list-routes', function () {
    $routes = collect(\Route::getRoutes())->map(function ($route) {
        return [
            'uri' => $route->uri(),
            'method' => $route->methods()[0],
            'name' => $route->getName(),
            'action' => $route->getActionName(),
        ];
    });

    return response()->json($routes);
});
