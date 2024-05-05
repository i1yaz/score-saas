<?php


Route::group(['middleware' => ['auth:web,proctor']], function () {
    getProctorRoutes();
});
