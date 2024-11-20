<?php
namespace Tests\Feature;

use Tests\TestCase;

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
        // Genera un token di esempio
        $user = \App\Models\User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;
    
        // Effettua una richiesta autenticata
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->getJson('/api/breweries');
    
        $response->assertStatus(200);
    
        $response->assertJsonStructure([
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
        ]);
    
        $this->assertCount(10, $response->json(), 'The response should contain 10 breweries by default.');  
        $this->assertNotNull($response->json()[0]['id'], 'The first brewery should have an ID');
        $this->assertNotNull($response->json()[0]['name'], 'The first brewery should have a name');
    
        $user->delete();
    }
    
    /** @test */
    public function it_returns_paginated_breweries_with_custom_parameters()
    {
        // Genera un token di esempio
        $user = \App\Models\User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;
    
        // Parametri personalizzati
        $page = 2;
        $perPage = 5;
    
        // Effettua una richiesta autenticata con parametri personalizzati
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->getJson("/api/breweries?page=$page&per_page=$perPage");
    
        // Verifica lo stato della risposta
        $response->assertStatus(200);
    
        // Verifica la struttura della risposta
        $response->assertJsonStructure([
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
        ]);
    
        // Verifica il numero di elementi restituiti
        $this->assertCount($perPage, $response->json(), "The response should contain $perPage breweries.");
    
        // Verifica che alcuni valori specifici siano presenti
        $this->assertEquals($page, 2, 'The response should return the correct page.');
    
        // Verifica che il primo elemento abbia i campi principali
        $this->assertNotNull($response->json()[0]['id'], 'The first brewery should have an ID');
        $this->assertNotNull($response->json()[0]['name'], 'The first brewery should have a name');
    
        // Cancella l'utente per pulizia
        $user->delete();
    }
    
}
