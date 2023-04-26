<?php

use App\Http\Controllers\IndexController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', [IndexController::class, 'index']);

/**
 * 用户操作路由
 */
Route::prefix('user')->group(function (){
    require_once __DIR__.'/auth/user.php';
});

/**
 * 网站操作路由
 */
Route::prefix('web')->middleware(['auth:api'])->group(function (){
    require_once __DIR__.'/web/web.php';
});

/**
 * 无权限操作路由
 */
Route::prefix('no')->group(function (){
    require_once __DIR__.'/noPermissions/user.php';
    require_once __DIR__.'/noPermissions/web.php';
});




