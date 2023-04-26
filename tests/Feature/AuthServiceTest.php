<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Auth\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    protected AuthService $authService;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->authService = app(AuthService::class);
        parent::__construct($name, $data, $dataName);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @throws ValidationException
     */
    public function test_auth_service_create_user()
    {


        $userData = [
            'name' => '刘铭熙',
            'email' => 'test@example.com',
            'password' => 'zxabug666.',
            'password_confirmation' => 'zxabug666.'
        ];

        $user = $this->authService->createUser($userData);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }

    public function testUpdateUser()
    {
        $authService = app(AuthService::class);
        $user = Auth::loginUsingId(17);

        $data = [
            'name' => 'New_les',
            'email' => 'new_email@example.com',
        ];

        $this->assertTrue($this->authService->updateUser($user, $data));

        // 检查数据库中是否有用户信息被更新
        $this->assertDatabaseHas('users', $data);
    }

    /**
     *
     * @fun getUserById
     * @date 2023/4/5
     * @author 刘铭熙
     */
    public function testGetUserById()
    {
        $user = $this->authService->getUserById(2);

        $this->assertInstanceOf(User::class, $user);
    }

    public function testDeleteUserById()
    {
        $user = $this->authService->getUserById(2);
        $this->authService->deleteUser($user->id);

        $this->assertNull(User::find($user->id));
    }
}
