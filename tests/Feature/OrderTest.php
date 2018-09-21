<?php

namespace Tests\Feature;

use App\Order;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreate()
    {
        $response = $this->json('POST', '/order', [
		    'origin' => ['23.773214','90.409008'],
            'destination' => ['23.752657', '90.375742']
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                 	 'id', 'status', 'distance'
                 ]);

        Order::find($response->json('id'))->delete();
    }

    public function testTake()
    {
    	$order = Order::create([
    		'distance' => 1000,
		    'status' => 'UNASSIGNED'
	    ]);

        $response_1 = $this->json('PUT', '/order/'.$order->id, [
		    'status' => 'taken'
        ]);

        $response_1->assertStatus(200)
                   ->assertExactJson([
					    'status' => 'SUCCESS',
					 ]);


        // Same order should not be possible to take twice
        $response_2 = $this->json('PUT', '/order/'.$order->id, [
		    'status' => 'taken'
        ]);

        $response_2->assertStatus(409)
                   ->assertExactJson([
                   	     'error' => 'ORDER_ALREADY_BEEN_TAKEN',
                   ]);

        // Cleanup
        $order->delete();
    }

    public function testIndex()
    {
    	$orders = factory('App\Order', 100)->create();

	    $response = $this->json('GET', '/orders');

	    $response->assertStatus(200)
		         ->assertJsonStructure([
		         	'*' => [
		         		'id', 'status', 'distance'
		            ]
		         ]);

	    Order::whereIn('id', $orders->pluck('id'))->delete();
    }
}
