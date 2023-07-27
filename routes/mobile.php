<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('login', [\App\Http\Controllers\Auth\AuthController::class, 'login']);
Route::post('', [\App\Http\Controllers\Mobile\Auth\MobileLoginController::class, 'index']);
Route::post('otp-verification', [\App\Http\Controllers\Mobile\Auth\MobileLoginController::class, 'otpVerification']);


Route::group(['middleware' => ['jwt']], function () {

    //FARMER USERS
    Route::group(['prefix' => 'farmer'],function () {
        //POND
        Route::post('store-pond-preparation-data',[\App\Http\Controllers\Mobile\Farmer\PondController::class,'storePondPreparationData']);
        Route::post('store-pond-operation-data',[\App\Http\Controllers\Mobile\Farmer\PondController::class,'storePondOperationData']);
        Route::post('get-all-pond-preparation-data',[\App\Http\Controllers\Mobile\Farmer\PondController::class,'getAllPondPreparationData']);
        Route::post('get-all-pond-operation-data',[\App\Http\Controllers\Mobile\Farmer\PondController::class,'getAllPondOperationData']);
        //HARVEST
        Route::post('store-harvest-data',[\App\Http\Controllers\Mobile\Farmer\HarvestController::class,'storeHarvestData']);
        Route::post('get-all-harvest-data',[\App\Http\Controllers\Mobile\Farmer\HarvestController::class,'getAllHarvestData']);

        //get dashboardData
        Route::post('get-all-shrimp-advisory-information',[\App\Http\Controllers\Mobile\Farmer\ShrimpAdvisoryController::class,'getAllShrimpAdvisoryInformation']);

       // Route::post('get-dashboard-info',[\App\Http\Controllers\Mobile\La\Dashborad\DashboardController::class,'index']);
    });

    //FACTORY USERS
    Route::group(['prefix' => 'factory'],function () {
        //FARMER LIST
        Route::post('get-all-farmers-data',[\App\Http\Controllers\Mobile\Factory\FarmerController::class,'getAllFarmerInformation']);
    });


});

//BUYERS USERS
Route::group(['prefix' => 'buyer'],function () {
    //FARMER LIST
    Route::post('get-all-farmers-data',[\App\Http\Controllers\Mobile\Buyer\BuyerFarmerController::class,'getAllFarmerInformation']);

    //Product Create
    Route::post('store-product-data',[\App\Http\Controllers\Mobile\Buyer\BuyerProductController::class,'storeProductInformation']);
    Route::post('get-all-products-data',[\App\Http\Controllers\Mobile\Buyer\BuyerProductController::class,'getAllProductInformation']);
});
