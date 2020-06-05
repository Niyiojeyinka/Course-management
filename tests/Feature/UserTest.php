<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User as User;

class UserTest extends TestCase
{
    private $baseURL = 'api/user/';
    use RefreshDatabase;

    public function register()
    {
        return $this->post(
            $this->baseURL . 'register',

            [
                'name' => 'Test Doe',
                'email' => 'test@email.com',
                'password' => 'testpassword',
            ]
        );
    }

    /** @test @return void */
    public function new_user_can_register()
    {
        $response = $this->register();

        $this->assertCount(1, User::all());
        $response->assertStatus(201);
    }

    /** @test @return void */
    public function email_and_username_are_unique()
    {
        $response = $response = $this->register();

        $response = $response = $this->register();

        $response->assertStatus(200);
        $response->assertJSON(['result' => 0]);
        $this->assertCount(1, User::all());
    }

    /** @test @return void */
    public function user_can_login()
    {
        $this->register();
        $this->assertCount(1, User::all());

        $response = $this->post($this->baseURL . 'login', [
            'email' => 'test@email.com',
            'password' => 'testpassword',
        ]);
        $response->assertJSON(['result' => 1]);
        $response->assertOk();
    }
    /** @test @return void */
    public function wrong_user_cannot_login()
    {
        $this->register();
        $this->assertCount(1, User::all());

        $response = $this->post($this->baseURL . 'login', [
            'email' => 'wrong@email.com',
            'password' => 'testpassword',
        ]);
        $response->assertJSON(['result' => 0]);
        $response->assertStatus(401);
    }

    /** @test @return void */
    public function user_can_logout()
    {
        $this->register();
        $response = $this->post($this->baseURL . 'login', [
            'email' => 'test@email.com',
            'password' => 'testpassword',
        ]);

        $logout = $this->post($this->baseURL . 'logout', [
            'token' => $response['data']['token'],
        ]);

        $logout->assertJSON(['result' => 1]);
        $logout->assertOk();
    }
}