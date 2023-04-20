<?php

namespace Medians\Infrastructure\Devices;

use Medians\Domain\Devices\DeviceTypeModel;

use Shared\dbaser\CustomController;


class DevicesTypesRepository extends CustomController 
{

	/*
	/ @var String
	*/
	protected $table = 'devices_types';


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
	// Find item by `id` 
	*/

	public function getByID($deviceTypeId)
	{
		$this->id = $deviceTypeId;

		$this->data = $this->getOne($this->id, 'id', null, $this->table, 'object');

		return new DeviceTypeModel($this->data);

	}

	/*
	// Find all items 
	*/
	public function getAll()
	{
		return  $this->get(null, null, null, null, $this->table, 'object');
	}

	/*
	// Create item instance
	*/
	public function createItem(DeviceTypeModel $DeviceData) : DevicesTypesRepository
	{
		$this->data = (object) $this->data;

		$this->data->id = empty($DeviceData) ? 0 : $DeviceData->id();
		$this->data->title = empty($DeviceData) ? '' : $DeviceData->title() ;
		$this->data->publish = empty($DeviceData) ? 0 : $DeviceData->publish();

		return $this;
	}

	/*
	// Save item to database
	*/
	public function saveItem() : DeviceTypeModel
	{

		// Insert data and return ID
		$this->id = $this->insert((array) $this->data, $this->table);

		// Return the DeviceTypeModel object with the new data
		return new DeviceTypeModel($this->getOne($this->id, 'id', null, $this->table, 'object'));
	}

	/*
	// Update item to database
	*/
	public function edit() : DeviceTypeModel
	{

		// Update data and return boolen
		$this->updated = $this->update((array) $this->data, 'id', $this->id);

		// Return the DeviceTypeModel object with the new data
		return new DeviceTypeModel($this->getOne($this->id, 'id', null, $this->table, 'object'));
	}


	/*
	// Delete item to database
	//
	// @Returns Boolen
	*/
	public function remove() 
	{

		// Delete data and return boolen
		$this->deleted = $this->delete($this->id);

		// Return the DeviceTypeModel object with the new data
		return $this->deleted;
	}


}
