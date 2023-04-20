<?php

namespace Medians\Infrastructure\Devices;

use Medians\Domain\Devices\DeviceModel;

use Shared\dbaser\CustomController;


class DevicesRepository extends CustomController 
{

	/*
	/ @var String
	*/
	protected $table = 'devices';


	/*
	/ @var Int
	*/
	private $id;


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
	// Set `id` 
	*/

	public function setId($deviceId)
	{
		$this->id = $deviceId;
	}



	/*
	// Set `data` 
	*/
	public function setData($data)
	{
		$this->data = $data;
	}


	/*
	// Find item by `id` 
	*/
	public function getByID($deviceId)
	{
		$this->id = $deviceId;

		$this->data = $this->getOne($this->id, 'id', null, $this->table, 'object');

		return new DeviceModel($this->data);

	}


	/*
	// Find item by `provider` 
	*/
	public function getByProvider($providerId)
	{

		return $this->get($providerId->id(), 'providerId', null, null, $this->table, 'object');

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
	public function createItem(DeviceModel $DeviceData) : DevicesRepository
	{
		
		$this->data = (object) $this->data;
		
		$this->data->id = $DeviceData->id();
		$this->data->title = $DeviceData->title();
		$this->data->providerId = $DeviceData->providerId();
		$this->data->playing = $DeviceData->playing();
		$this->data->type = $DeviceData->type()->id();
		$this->data->publish = $DeviceData->publish();

		return $this;
	}

	/*
	// Save item to database
	*/
	public function saveItem() : DeviceModel
	{

		// Insert data and return ID
		$this->id = $this->insert((array) $this->data, $this->table);

		// Return the DeviceModel object with the new data
		return new DeviceModel($this->getOne($this->id, 'id', null, $this->table, 'object'));
	}

	/*
	// Update item to database
	*/
	public function edit() : DeviceModel
	{

		// Update data and return boolen
		$this->updated = $this->update((array) $this->data, 'id', $this->id);

		// Return the DeviceModel object with the new data
		return new DeviceModel($this->getOne($this->id, 'id', null, $this->table, 'object'));
	}


	/*
	// Delete item to database
	//
	// @Returns DeviceModel
	*/
	public function remove() 
	{

		// Delete data and return boolen
		$this->deleted = $this->delete($this->id);

		// Return the DeviceModel object with the new data
		return $this->deleted;
	}


}
