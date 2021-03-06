<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	/**
	 * @var array List of fields that are mass assignable.
	 *
	 * We have set both fields as we will not be using user input directly.
	 */
    protected $fillable = ['distance', 'status', 'origin_lat', 'origin_lon', 'destination_lat', 'destination_lon'];

	/**
	 * @var array List of fields that should be public
	 */
    protected $visible = ['id', 'status', 'distance'];
}
