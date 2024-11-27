<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class BreweryControllerTest extends TestCase
{
    /** @test */
    public function it_requires_authentication_to_access_breweries()
    {
        $response = $this->getJson('/api/breweries');

        $response->assertStatus(401)
                 ->assertJson([
                     'message' => 'Unauthenticated.'
                 ]);
    }

    /** @test */
    public function it_returns_paginated_breweries_with_default_parameters()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->getPaginatedBreweries($token);

        $response->assertStatus(200)
                 ->assertJsonStructure($this->getBreweryJsonStructure());

        $this->assertCount(10, $response->json(), 'The response should contain 10 breweries by default.');
        $this->assertBreweryData($response->json()[0]);

        $user->delete();
    }

    /** @test */
    public function it_returns_paginated_breweries_with_custom_parameters()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $page = 2;
        $perPage = 5;

        $response = $this->getPaginatedBreweries($token, $page, $perPage);

        $response->assertStatus(200)
                 ->assertJsonStructure($this->getBreweryJsonStructure());

        $this->assertCount($perPage, $response->json(), "The response should contain $perPage breweries.");
        $this->assertEquals($page, 2, 'The response should return the correct page.');
        $this->assertBreweryData($response->json()[0]);

        $user->delete();
    }

    private function getPaginatedBreweries(string $token, int $page = 1, int $perPage = 10)
    {
        return $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->getJson("/api/breweries?page=$page&per_page=$perPage");
    }

    private function getBreweryJsonStructure(): array
    {
        return [
            '*' => [
                'id',
                'name',
                'brewery_type',
                'address_1',
                'address_2',
                'address_3',
                'city',
                'state_province',
                'postal_code',
                'country',
                'longitude',
                'latitude',
                'phone',
                'website_url',
                'state',
                'street',
            ]
        ];
    }
    
    private function assertBreweryData(array $brewery)
    {
        $this->assertNotNull($brewery['id'], 'The brewery should have an ID');
        $this->assertNotNull($brewery['name'], 'The brewery should have a name');
    }
}
