<?php

namespace Tests\Unit;

use App\Models\Brand;
use App\Models\User;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class BrandTest extends TestCase
{

    private $BrandData = [
        "name" => "ohn Doe",
        "display_name" => "test brand",

    ];
    private $BrandStructure = [
        "status",
        "message",
        "data" => [

            'id',
            'name',
            'display_name',

        ]


    ];

    public function testSuccessfulIndex()
    {
        $this->AuthenticateUser();
       Brand::factory(3)->create();
        $response = $this->json('GET', 'api/brands', ['Accept' => 'application/json'])
            ->assertStatus(200);
//            ->assertJsonStructure(
//
//            );


    }

    public function testRequiredFieldsForStore()
    {
        $this->AuthenticateUser();
        $message = [
            "name" => ["The name field is required."],
            "display_name" => ["The display name field is required."],

        ];
        $this->json('POST', 'api/brands', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson($this->getStructureError($message));

    }

    public function testUniqueFieldsForStore()
    {
        $this->AuthenticateUser();
        Brand::factory()->create([
            'display_name' => $this->BrandData['display_name']
        ]);
        $message = [

            "display_name" => ["The display name has already been taken."],

        ];

        $this->json('POST', 'api/brands', $this->BrandData, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson($this->getStructureError($message));
    }

    public function testSuccessfulStore()
    {
        $this->AuthenticateUser();

        $this->json('POST', 'api/brands', $this->BrandData, ['Accept' => 'application/json'])
            ->assertCreated()
            ->assertStatus(201)
            ->assertJsonStructure($this->BrandStructure);
    }

    public function testSuccessfulShow()
    {

        $this->AuthenticateUser();
        $brand = Brand::factory()->create();

        $response = $this->json('GET', 'api/brands/' . $brand->id, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure($this->BrandStructure);
        $this->assertEquals($brand->id, $response['data']['id']);

    }

    public function testNotFoundForShow()
    {
        $this->AuthenticateUser();
        $message = "No query results for model [App\\Models\\Brand] 1";
        $this->json('GET', 'api/brands/1', ['Accept' => 'application/json'])
            ->assertStatus(404)
            ->assertJson($this->getStructureError($message));
    }


    private function AuthenticateUser()
    {
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user
        );
    }

    private function getStructureError($message)
    {
        return [
            "status" => "error",
            "message" => $message,
            "data" => Null,
        ];
    }


}
