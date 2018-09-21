<?php

namespace App\Http\Controllers;

use App\Order;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$take_items = Input::get('limit', 10);

    	$skip_pages = Input::get('page', 1) - 1;
    	$skip_items = $skip_pages * $take_items;

        return Order::skip($skip_items)->take($take_items)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	try {
		    $coords = $request->validate([
			    'origin' => 'required|array|size:2',
			    'destination' => 'required|array|size:2',
			    'origin.*' => 'numeric',
			    'destination.*' => 'numeric',
		    ]);
	    }
	    catch (\Exception $exception) {
    		return response()->json(['error' => 'INVALID_INPUT'], 400);
	    }

	    try {
		    $client = new Client();
		    $res = $client->request( 'GET', config('services.gmaps.endpoint'),
			    [
				    'query' => [
					    'origin' => implode(',', $coords['origin']),
					    'destination' => implode(',', $coords['destination']),
					    'key' => config('services.gmaps.api_key')
				    ]
			    ]);

		    if($res->getStatusCode() != 200) {
			    return response()->json(['error' => 'DISTANCE_API_FAILED'], 500);
		    }

		    $response_body = json_decode((string) $res->getBody());
		    if(count($response_body->routes) == 0) {
			    return response()->json(['error' => 'NO_ROUTE'], 404);
		    }

		    $order = Order::create([
			    'distance' => $response_body->routes[0]->legs[0]->distance->value,
			    'status' => 'UNASSIGNED',
		    ]);


		    return response()->json($order);
	    }
	    catch (GuzzleException $exception) {
		    return response()->json(['error' => 'NETWORK_ERROR'], 500);
	    }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
    	if(Input::get('status') != 'taken') {
    		return response()->json(['error' => 'INVALID_INPUT'], 400);
	    }

	    if($order->status == 'TAKEN') {
		    return response()->json(['error' => 'ORDER_ALREADY_BEEN_TAKEN'], 409);
	    }

	    $order->status = 'TAKEN';
	    $order->save();

	    return response()->json(['status' => 'SUCCESS']);
    }
}
