<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\GroupController;
use App\Http\Controllers\api\ContactController;
use App\Http\Controllers\api\UserController;

use App\Http\Controllers\api\LoginController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->group(function() {

    /**
     * Login
     */
    Route::prefix('auth')->group(function() {
        Route::post('login', [LoginController::class, 'login']);
        Route::post('logout', [LoginController::class, 'logout']);
    });

    /**
    * Groups
    */
    Route::apiResources([
        'groups' => GroupController::class,
    ],[
        'only' => ['index']
    ]);
    Route::apiResources([
        'group' => GroupController::class,
    ],[
        'except' => ['index']
    ]);

    /**
    * Contacts
    */
    Route::apiResources([
        'contacts' => ContactController::class,
    ],[
        'only' => ['index']
    ]);
    Route::apiResources([
        'contact' => ContactController::class,
    ],[
        'except' => ['index']
    ]);

    /**
    * Users
    */
    Route::apiResources([
        'users' => UserController::class,
    ],[
        'only' => ['index']
    ]);
    Route::apiResources([
        'user' => UserController::class,
    ],[
        'except' => ['index']
    ]);

});