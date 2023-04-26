<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register()
    {
        $data = [
            'name' => 'John Doe',
            'email' => '2172307677@qq.com',
            'password' => 'secret666.',
            'password_confirmation' => 'secret666.',
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(200)
            ->assertJson([
                'code' => true,
                'data' => [
                    'user' => true,
                    'access_token' => true
                ],
                'message' => true
            ]);
    }

    public function test_login()
    {
        $data = [
            'email' => '2172307677@qq.com',
            'password' => 'zxabug666.'
        ];

        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(200)
            ->assertJson([
                'code' => true,
                'data' => [
                    'user' => true,
                    'access_token' => true
                ],
                'message' => true
            ]);


    }
}
