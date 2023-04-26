<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class WebService extends TestCase
{

    public function test_web_create()
    {
        $user = Auth::loginUsingId(1);
        $data = [
            'title' => '灼热',
            'url' => 'https://zhuo.re',
            'feedUrl' => 'https://zhuo.re/feed',
            'description' => '平凡的世界，灼热的心不止'
        ];
        $res = $this->actingAs($user,'api')->postJson('/api/web/create', $data);

        $res->assertJsonStructure();




    }
}
