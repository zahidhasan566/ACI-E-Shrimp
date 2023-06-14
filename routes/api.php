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


Route::group(['middleware' => ['jwt']], function () {
    Route::post('logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout']);
    Route::post('refresh', [\App\Http\Controllers\Auth\AuthController::class, 'refresh']);
    Route::post('me', [\App\Http\Controllers\Auth\AuthController::class, 'me']);
    Route::get('app-supporting-data', [\App\Http\Controllers\Common\HelperController::class, 'appSupportingData']);
});


Route::group(['middleware' => ['jwt:api']], function () {

    // ADMIN USERS
    Route::group(['prefix' => 'user'],function () {
        Route::post('list', [\App\Http\Controllers\Admin\Users\AdminUserController::class, 'index']);
        //User Modal Data
        Route::get('modal',[\App\Http\Controllers\Admin\Users\AdminUserController::class,'userModalData']);
        Route::post('add', [\App\Http\Controllers\Admin\Users\AdminUserController::class, 'store']);
        Route::get('get-user-info/{UserId}',[\App\Http\Controllers\Admin\Users\AdminUserController::class,'getUserInfo']);
        Route::post('update', [\App\Http\Controllers\Admin\Users\AdminUserController::class, 'update']);
        Route::post('password-change',[\App\Http\Controllers\Common\HelperController::class,'passwordChange']);
    });

    // ADMIN ACTION
    Route::group(['prefix' => 'admin'],function () {
        //Setting
        Route::post('setting/advisoryList', [\App\Http\Controllers\Admin\Setting\Advisory\AdvisoryController::class, 'index']);

//        //Event
        Route::post('setting/eventList/add-event-list-data', [\App\Http\Controllers\Admin\Setting\Advisory\AdvisoryController::class,'store']);
        Route::get('setting/eventList/get-event-list-info/{EventID}', [\App\Http\Controllers\Admin\Setting\Advisory\AdvisoryController::class,'getEventInfo']);
        Route::post('update/setting/eventList/add-event-list-data', [\App\Http\Controllers\Admin\Setting\Advisory\AdvisoryController::class,'updateEventData']);

    });

});

