<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\AuthService;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     *
     * @fun register
     * @param Request $request
     * @return JsonResponse
     * @date 2023/4/9
     * @author 刘铭熙
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $user = $this->authService->createUser($request->all());
            Auth::login($user);
            return $this->json([
                'user' => Auth::user(),
                'access_token' => Auth::user()->createToken('api')->accessToken
            ]);
        } catch (ValidationException $e) {
            return $this->error($e);
        }
    }

    /**
     *
     * @fun login
     * @param Request $request
     * @return JsonResponse
     * @date 2023/4/9
     * @author 刘铭熙
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $user = $this->authService->login($request->all());
            return $this->json([
                'user' => $user,
                'access_token' => $user->createToken('api')->accessToken
            ]);

        } catch (ValidationException|\Exception $e) {
            return $this->error($e);
        }

    }

    /**
     *
     * @fun logout
     * @param Request $request
     * @return JsonResponse
     * @date 2023/4/9
     * @author 刘铭熙
     */
    public function logout(Request $request): JsonResponse
    {
        \auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return $this->json([], '已退出登录');
    }

    /**
     *
     * @fun verify
     * @param Request $request
     * @return Factory|View|Application
     * @date 2023/4/9
     * @author 刘铭熙
     */
    public function verify(Request $request): Factory|View|Application
    {

        $request->session()->put('info', [
            'id' => $request->route('id'),
            'hash' => $request->route('hash')
        ]);
        return view('verify.verify');
    }

    /**
     *
     * @fun verifyPost
     * @param Request $request
     * @return JsonResponse
     * @date 2023/4/9
     * @author 刘铭熙
     */
    public function verifyPost(Request $request): JsonResponse
    {
        $request->validate([
            'keys' => 'required|max:6'
        ]);

        $data = $request->session()->get('info');
        if (collect($data)->isEmpty()) {
            return $this->json([], '非常规进入验证界面，请重新进入或者刷新页面', 422);
        }

        $user = \auth()->loginUsingId($data['id']);
        if ($request->input('keys') !== substr(base64_encode($user->email), 6, 6)) {
            return $this->json([], '密钥错误', 422);
        }
        \auth()->logout();

        $message = $this->authService->Verify($data['hash'], $user);
        $request->session()->flush();

        return $this->json([], $message);


    }

    /**
     *
     * @fun getUser
     * @date 2023/4/9
     * @author 刘铭熙
     */
    public function getUser(): JsonResponse
    {
        if (Auth::check()) {
            return $this->json([
                'user' => Auth::user()
            ]);
        } else {
            return $this->json([], 'The request error', 422);
        }

    }

    /**
     *
     * @fun changePassword
     * @param Request $request
     * @return JsonResponse
     * @date 2023/5/4
     * @author 刘铭熙
     */
    public function changePassword(Request $request): JsonResponse
    {
        try {
            $message = $this->authService->changePassword(
                $request->input('oldPassword'),
                $request->input('newPassword'));
            return $this->json([],$message);
        } catch (\Exception $e) {
            return $this->error($e);
        }
    }
}
