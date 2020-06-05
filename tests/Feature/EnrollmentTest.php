<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Courses_column as Courses_column;

class EnrollmentTest extends TestCase
{
    private $baseURL = 'api/v1/user/';
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
        factory(Courses_column::class, 50)->create();

        $this->register();
        $payload = [
            'email' => 'test@email.com',
            'password' => 'testpassword',
        ];
        $response = $this->post($this->baseURL . 'login', $payload);

        $data = $response->decodeResponseJson();
        $token = $data['data']['token'];

        $enrollData = [
            'course_ids' => [1, 2, 3],
            //'user_id' => ,
        ];
        $response = $this->post($this->baseURL . 'enroll', $enrollData, [
            'HTTP_Authorization' => 'Bearer' . $token,
        ]);
        $data = $response->decodeResponseJson();

        // dd(Courses_column::all());

        $response->assertStatus(201);
        $this->assertCount(3, \App\Enrollment::all());
    }

    /** @test @return void */
    public function user_can_not_enroll_with_valid_token()
    {
        $token = 'invalidtoken';

        $enrollData = [
            'course_ids' => [1, 2, 3],
            //'user_id' => ,
        ];
        $response = $this->post($this->baseURL . 'enroll', $enrollData, [
            'HTTP_Authorization' => 'Bearer' . $token,
        ]);

        $response->assertJson(['result' => 0]);
        $response->assertStatus(401);
    }

    /** @test @return void */
    public function user_can_get_list_of_courses_test()
    {
        $this->withOutExceptionHandling();
        factory(Courses_column::class, 10)->create();

        $this->register();
        $payload = [
            'email' => 'test@email.com',
            'password' => 'testpassword',
        ];
        $response = $this->post($this->baseURL . 'login', $payload);

        $data = $response->decodeResponseJson();
        $token = $data['data']['token'];
        //enroll
        $enrollData = [
            'course_ids' => [1, 4, 5],
            //'user_id' => ,
        ];
        $response = $this->post($this->baseURL . 'enroll', $enrollData, [
            'HTTP_Authorization' => 'Bearer' . $token,
        ]);
        $response = $this->get($this->baseURL . 'courses', $enrollData, [
            'HTTP_Authorization' => 'Bearer' . $token,
        ]);
        $data = $response->decodeResponseJson();
        //dd($data);
        $response->assertStatus(200);
    }
}