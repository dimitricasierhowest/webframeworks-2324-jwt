<?php

use App\Http\Controllers\JwtController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post("register", [JwtController::class, "register"]);
Route::post("login", [JwtController::class, "login"]);

Route::group([
    "middleware" => ["auth:api"]
], function(){

    Route::get("profile", [JwtController::class, "profile"]);
    Route::get("refresh", [JwtController::class, "refreshToken"]);
    Route::get("logout", [JwtController::class, "logout"]);
});
