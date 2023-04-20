<?php

namespace Medians\Infrastructure\Prices;

use Medians\Domain\Prices\PricesModel;

use Shared\dbaser\CustomController;


class PricesRepository extends CustomController 
{

	/*
	/ @var String
	*/
	protected $table = 'prices';


	/*
	/ @var Int
	*/
	private $device;


	/*
	/ @var Array
	*/
	private $data = array();



	function __construct()
	{
	}

	/*
	// return $table String 
	*/
	public function getTable() : String
	{
		return $this->table;
	}


	/*
	// Set `device` 
	*/

	public function setDevice($deviceId)
	{
		$this->device = $deviceId;
	}

	/*
	// Find item by `id` 
	*/

	public function getByID($id) : PricesModel
	{

		$this->data = $this->getOne($id, 'device', null, $this->table, 'object');

		return new PricesModel($this->device, $this->data);

	}


	/*
	// Find item by `device` 
	*/

	public function getByDevice($deviceId) : PricesModel
	{
		$this->device = $deviceId;

		$this->data = $this->getOne($this->device, 'device', null, $this->table, 'object');

		return new PricesModel($this->device, $this->data);

	}

	/*
	// Find all items 
	*/
	public function getAll()
	{
		return  $this->get(null, null, null, null, $this->table, 'object');
	}

	/*
	// Create item
	*/
	public function createItem(PricesModel $DeviceData) : PricesRepository
	{
		$this->data = (object) $this->data;

		$this->data->id = !empty($DeviceData) ? $DeviceData->id() : null;
		$this->data->device = $DeviceData->device()->id();
		$this->data->single_price = $DeviceData->single_price();
		$this->data->multi_price = $DeviceData->multi_price();

		return $this;
	}

	/*
	// Save item to database
	*/
	public function saveItem() : PricesModel
	{

		// Insert data and return ID
		$this->id = $this->insert((array) $this->data, $this->table);

		// Return the PricesModel object with the new data
		return $this->getByID($this->id);
	}

	/*
	// Update item to database
	*/
	public function edit() : PricesModel
	{

		unset($this->data->id);
		unset($this->data->device);
		
		// Update data and return boolen
		$this->updated = $this->update((array) $this->data, 'device', $this->device);

		// Return the PricesModel object with the new data
		return $this->getByDevice($this->device);
	}


	/*
	// Delete item to database
	//
	// @Returns PricesModel
	*/
	public function remove() : PricesModel
	{

		// Delete data and return boolen
		$this->deleted = $this->delete($this->id);

		// Return the PricesModel object with the new data
		return $this->getByDevice($this->id);

	}


}
