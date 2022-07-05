<?php

namespace Tests\Unit;

use App\Models\Province;
use Tests\TestCase;

use App\Models\User;
use App\Models\City;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{

    public function testRequiredFieldsForRegistration()
    {
        $this->json('POST', 'api/register', ['Accept' => 'application/json'])

            ->assertStatus(422)
            ->assertJson([
                "status" => "error",
                "message" => [
                    "name" => ["The name field is required."],
                    "email" => ["The email field is required."],
                    "password" => ["The password field is required."],
                    'c_password' => ["The c password field is required."],
                    'address' => ["The address field is required."],
                    'cellphone' => ["The cellphone field is required."],
                    'postal_code' => ["The postal code field is required."],
                    'province_id' => ["The province id field is required."],
                    'city_id' => ["The city id field is required."],
                ],
                "data" => Null,
            ]);
    }
    public function testRepeatPassword()
    {
        $userData = [
            "name" => "John Doe",
            "email" => "doe@example.com",
            "password" => "demo12345",
            "c_password" =>"44444444",
            "address" => "fvhgcfggfdfg",
            "cellphone" => "09195264875",
            "postal_code" => "5264875",
            "province_id" => 1,
            "city_id" => 1,
        ];

        $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                "status" => "error",
                "message" => [
                    "c_password" => ["The c password and password must match."]
                ],
                "data" => Null,
            ]);
    }
    public function testSuccessfulRegistration()
    {
        $province= Province::create([
            "name" => "alborz",

        ]);
        $city= City::create([
            "province_id" => $province->id,
            "name" => "karaj",

        ]);
        $userData = [
            "name" => "ohn Doe",
            "email" => "oeggllg@example.com",
            "password" => "demo12345",
            "c_password" =>"demo12345",
            "address" => "fvhgcfggfdfg",
            "cellphone" => "09195264875",
            "postal_code" => "5264875",
            "province_id" => $province->id,
            "city_id" => $city->id,
        ];


        $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJsonStructure([
                "status",
                "message",
                "data"=>[
                    "user" => [
                        'id',
                        'name',
                        'email',
                        'address',
                        'cellphone',
                        'postal_code',
                        'province_id',
                        'city_id',
                        'created_at',
                        'updated_at',
                    ],
                    "token",
                ]


            ]);
    }


    public function testMustEnterEmailAndPassword()
    {
        $this->json('POST', 'api/login', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                "status" => "error",
                "message" => [
                    "email" => ["The email field is required."],
                    "password" => ["The password field is required."],
                ],
                "data" => Null,
            ]);
    }
    public function testSuccessfulLogin()
    {
        $province= Province::create([
            "name" => "alborz",

        ]);
        $city= City::create([
            "province_id" => $province->id,
            "name" => "karaj",

        ]);
        $user = User::create([
            'name'=>'ali',
            'email' => 'sample@test.com',
            'password' => bcrypt('sample123'),
            "address" => "fvhgcfggfdfg",
            "cellphone" => "09195264875",
            "postal_code" => "5264875",
            "province_id" => $province->id,
            "city_id" => $city->id,
        ]);


        $loginData = ['email' => 'sample@test.com', 'password' => 'sample123'];

        $this->json('POST', 'api/login', $loginData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                "status",
                "message",
                "data"=>[
                    "user" => [
                        'id',
                        'name',
                        'email',
                        'address',
                        'cellphone',
                        'postal_code',
                        'province_id',
                        'city_id',
                        'created_at',
                        'updated_at',
                    ],
                    "token",
                ]


            ]);

        $this->assertAuthenticated();
    }






}
