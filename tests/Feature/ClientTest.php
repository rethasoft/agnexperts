<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClientTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }


    public function test_can_create_client()
    {
        $response = $this->post('/clients', [
            'name' => 'Gökhan',
            'email' => 'gokhandnmz@outlook.com'
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('clients', ['name' => 'Gökhan']);
    }
}
