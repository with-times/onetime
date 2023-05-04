<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * 注册
 */
Route::post('/register', [AuthController::class, 'register']);
/**
 * 登录
 */
Route::post('/login', [AuthController::class, 'login']);

/**
 * 登录后的操作
 */
Route::middleware(['auth:api'])->group(function () {
    Route::get('/', [AuthController::class, 'getUser']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/changePassword', [AuthController::class, 'changePassword']);
    Route::post('/email/verification-notification', function (Request $request) {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'code' => 403,
                'message' => '您已经验证过邮箱啦'
            ], 403);
        }
        $request->user()->sendEmailVerificationNotification();
        return response()->json([
            'code' => 200,
            'message' => '重新发送验证邮箱'
        ]);
    })->middleware(['throttle:2,1'])->name('verification.send');
});
