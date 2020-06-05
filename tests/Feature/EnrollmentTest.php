<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EnrollmentTest extends TestCase
{
    private $baseURL = 'api/v1/';
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
    public function user_can_enroll_with_valid_token()
    {
        $this->register();
        $payload = [
            'email' => 'test@email.com',
            'password' => 'testpassword',
        ];
        $response = $this->post($this->baseURL . 'login', $payload);

        $data = $response->decodeResponseJson();
        $token = $data['data']['token'];

        $enrollData = [
            'course_ids' => [1, 4, 5],
            //'user_id' => ,
        ];
        $response = $this->post($this->baseURL . 'enroll', $enrollData, [
            'HTTP_Authorization' => 'Bearer' . $token,
        ]);

        $response->assertJson(['result' => 1]);
        $response->assertStatus(200);
        $this->assertCount(1, App\Enrollment::all());
    }

    /** @test @return void */
    public function user_can_not_enroll_with_valid_token()
    {
        $token = 'invalidtoken';

        $enrollData = [
            'course_ids' => [1, 4, 5],
            //'user_id' => ,
        ];
        $response = $this->post($this->baseURL . 'enroll', $enrollData, [
            'HTTP_Authorization' => 'Bearer' . $token,
        ]);

        $response->assertJson(['result' => 0]);
        $response->assertStatus(200);
    }
}