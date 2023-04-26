<?php

namespace App\Services\Auth;

use App\Jobs\Auth\Register;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * createUser($data)：创建一个新用户。
     *
     * updateUser($userId, $data)：更新一个已存在的用户的信息。
     *
     * deleteUser($userId)：删除一个已存在的用户。
     *
     * getUserById($userId)：根据用户ID获取用户信息。
     *
     * getUsers($filters = [])：获取所有用户列表，支持筛选条件。
     *
     * getUserByEmail($email)：根据电子邮件地址获取用户信息。
     *
     * getUserByUsername($username)：根据用户名获取用户信息。
     *
     * changePassword($userId, $oldPassword, $newPassword)：修改用户密码。
     *
     * resetPassword($userId)：重置用户密码。
     *
     * authenticate($email, $password)：验证用户登录信息。
     */

    /**
     *
     * @fun createUser
     * @date 2023/4/4
     * @throws ValidationException
     * @author 刘铭熙
     */
    public function createUser($data)
    {
        $validator = Validator::make($data, [
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'email' => [
                'required',
                'email',
                Rule::unique(User::class)
            ],
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)->letters()->symbols()->uncompromised()
            ]
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);
        dispatch(new Register($user));

        return $user;
    }

    /**
     *
     * @fun updateUser
     * @param User $user
     * @param $data
     * @return bool
     * @date 2023/4/4
     * @throws ValidationException
     * @author 刘铭熙
     */
    public function updateUser(User $user, $data): bool
    {
        $validator = Validator::make($data, [
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'email' => [
                'required',
                'email',
                Rule::unique(User::class)->ignore($user->id)
            ],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $user->fill($data)->save();
    }

    /**
     *
     * @fun deleteUser
     * @param $userId
     * @return array
     * @date 2023/4/4
     * @author 刘铭熙
     */
    public function deleteUser($userId): mixed
    {
        $user = User::find($userId);

        if (!$user) {
            return ['error' => 'User not found'];
        }

        $user->delete();

        return $user;
    }

    /**
     *
     * @fun getUserById
     * @param $userId
     * @return mixed
     * @date 2023/4/5
     * @author 刘铭熙
     */
    public function getUserById($userId): mixed
    {
        return User::find($userId);
    }

    /**
     *
     * @fun Verify
     * @param string $hash
     * @param User|Authenticatable $user
     * @return string|null
     * @date 2023/4/9
     * @author 刘铭熙
     */
    public function Verify(string $hash, User|Authenticatable $user): ?string
    {
        $message = null;
        if (empty($hash)) {
            $message = 'Hello, friend, verification failed, wrong parameter hash :(';
        } elseif (!hash_equals($hash, sha1($user->getEmailForVerification()))) {
            $message = 'Hello, friend, verification failed, wrong parameter hash :(';
        } elseif ($user->hasVerifiedEmail()) {
            $message = 'Hello, friend, you have already certified :)';
        } elseif ($user->markEmailAsVerified()) {
            $message = 'Hello friend, you have been authenticated successfully! :)';
        }

        return $message;
    }

    /**
     *
     * @fun login
     * @param $data
     * @return Authenticatable|string|null
     * @date 2023/4/9
     * @throws \Exception
     * @throws ValidationException
     * @author 刘铭熙
     */
    public function login($data): Authenticatable|string|null
    {
        $validator = Validator::make($data, [
            'email' => 'required|email',
            'password' => [
                'required',
                'string',
                Password::min(8)->letters()->symbols()->uncompromised()
            ],
            'remember' => 'boolean'
        ]);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        /**
         * 登录
         */
        $credentials = [
            'email' => $data['email'],
            'password' => $data['password']
        ];
        if(Auth::attempt($credentials, $data['remember'])){
            return Auth::user();
        }else{
            throw new \Exception('账号或密码错误，请重试.',401);
        }



    }

}
