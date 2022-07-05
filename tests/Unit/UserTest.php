<?php

namespace Tests\Unit;

use App\Models\Province;
use Database\Factories\ProvinceFactory;
use Laravel\Sanctum\Sanctum;
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
        $userData = $this->getArrayUserData();
        $userData['c_password']=111111;

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
        $userData = $this->getArrayUserData();


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
        $user = User::factory()->create();


        $loginData = ['email' => $user->email, 'password' => 'password'];

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
//        Sanctum::actingAs(
//            $user
//        );


//        $this->assertAuthenticated();
    }








    private function getArrayUserData()
    {
        $province = Province::factory()->create();
        $city = City::factory()->create();
        $userData = [
            "name" => "ohn Doe",
            "email" => "oeggllg@example.com",
            "password" => "demo12345",
            "c_password" => "demo12345",
            "address" => "fvhgcfggfdfg",
            "cellphone" => "09195264875",
            "postal_code" => "5264875",
            "province_id" => $province->id,
            "city_id" => $city->id,
        ];
        return $userData;
    }




}
