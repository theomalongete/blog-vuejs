<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User\User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();
        if (!defined('LARAVEL_START')) {
            define('LARAVEL_START', microtime(true));
        }

        $this->withoutExceptionHandling();
       // $this->withoutMiddleware();

        $apiUserEmail = "testthe23232o@gmail.com";
        $apiUserPassword = "123456";

        $data = [
            'email' => $apiUserEmail,
            'password' => $apiUserPassword
        ];

        $this->postJson('api/auths/login',$data);
        $user = User::where('id', 1)->first();
        $token = JWTAuth::fromUser($user);
        $this->actingAs($user,'api');
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => "Bearer ".$token
        ];

        $this->withHeaders($headers);
    }
}
