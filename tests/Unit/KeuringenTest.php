<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Keuringen;

class KeuringenTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_method()
{
    // Define the data to be stored
    $data = [
        'tenant_id' => 1,
        'client_id' => 1,
        'file_id' => 1,
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '123456789',
        'street' => '123 Main St',
        'number' => '1',
        'bus' => 'A',
        'postal_code' => '12345',
        'authority' => 'Health Department',
        'status' => 'Pending',
        'text' => 'Lorem ipsum dolor sit amet',
        'paid' => false,
        'payment_status' => 'Unpaid',
        'province_id' => 1,
    ];

    // Send a POST request to the store endpoint with the data
    $response = $this->post('/tenant/keuringen/store', $data);
    // Assert that the request was successful (status code 201)
    $response->assertStatus(201);

    // Assert that the data was stored in the database
    $this->assertDatabaseHas('keuringens', $data);
}

}
