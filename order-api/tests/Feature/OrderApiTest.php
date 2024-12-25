<?php

namespace Tests\Feature;

use Tests\TestCase;

class OrderApiTest extends TestCase
{
    private $baseOrderData = [
        'id' => 'A0000001',
        'name' => 'Melody Holiday Inn',
        'address' => [
            'city' => 'taipei-city',
            'district' => 'da-an-district',
            'street' => 'fuxing-south-road'
        ],
        'price' => '1050',
        'currency' => 'TWD'
    ];

    public function test_successful_order_processing()
    {
        $response = $this->postJson('/api/orders', $this->baseOrderData);
        $response->assertStatus(200);
    }

    public function test_name_with_non_english_characters()
    {
        $data = $this->baseOrderData;
        $data['name'] = 'Melody休閒旅店';
        
        $response = $this->postJson('/api/orders', $data);
        
        $response->assertStatus(400)
            ->assertJson([
                'status' => 400,
                'errors' => ['400 - Name contains non-English characters']
            ]);
    }

    public function test_name_not_capitalized()
    {
        $data = $this->baseOrderData;
        $data['name'] = 'melody holiday inn';
        
        $response = $this->postJson('/api/orders', $data);
        
        $response->assertStatus(400)
            ->assertJson([
                'status' => 400,
                'errors' => ['400 - Name is not capitalized']
            ]);
    }

    public function test_price_over_limit()
    {
        $data = $this->baseOrderData;
        $data['price'] = 2500;
        
        $response = $this->postJson('/api/orders', $data);
        
        $response->assertStatus(400)
            ->assertJson([
                'status' => 400,
                'errors' => ['400 - Price is over 2000']
            ]);
    }

    public function test_invalid_currency()
    {
        $data = $this->baseOrderData;
        $data['currency'] = 'EUR';
        
        $response = $this->postJson('/api/orders', $data);
        
        $response->assertStatus(400)
            ->assertJson([
                'status' => 400,
                'errors' => ['400 - Currency format is wrong']
            ]);
    }

    public function test_usd_to_twd_conversion()
    {
        $data = $this->baseOrderData;
        $data['price'] = 50;
        $data['currency'] = 'USD';
        
        $response = $this->postJson('/api/orders', $data);
        
        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'data' => [
                    'price' => 1550,
                    'currency' => 'TWD'
                ]
            ]);
    }
}