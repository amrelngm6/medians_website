<?php

namespace Medians\Infrastructure\Settings;

use Medians\Domain\Settings\SettingsModel;

use Shared\dbaser\CustomController;


class SettingsRepository extends CustomController 
{

	/*
	/ @var String
	*/
	protected $table = 'settings';


	/*
	/ @var String
	*/
	private $code;


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
	// Set `code` 
	*/
	public function setCode($code)
	{
		$this->code = $code;
	}

	/*
	// Find item by `id` 
	*/
	public function getByID($id) : SettingsModel
	{

		$this->data = $this->getOne($id, 'id', null, $this->table, 'object');

		return SettingsModel::create($this->data);

	}

	/*
	// Find item by `id` 
	*/
	public function getByCode($code) : SettingsModel
	{
		$this->code = $code;

		$this->data = $this->getOne($this->code, 'code', null, $this->table, 'object');

		return SettingsModel::create($this->data);

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
	public function createItem(SettingsModel $DeviceModel) : SettingsRepository
	{
		$this->data = (object) $this->data;

		$this->data->id =  $DeviceModel->id();
		$this->data->code = $DeviceModel->code();
		$this->data->value = $DeviceModel->value();

		
		
		return $this;
	}

	/*
	// Save item to database
	*/
	public function saveItem() : SettingsModel
	{

		// Insert data and return ID
		$this->id = $this->insert((array) $this->data, $this->table);

		// Return the SettingsModel object with the new data
		return $this->getByID($this->id);
	}

	/*
	// Update item to database
	*/
	public function edit() : SettingsModel
	{
		unset($this->data->id);
		// Update data and return boolen
		$this->updated = $this->update((array) $this->data, 'code', $this->code);

		// Return the SettingsModel object with the new data
		return $this->getByCode($this->code);
	}


	/*
	// Delete item to database
	//
	// @Returns SettingsModel
	*/
	public function remove() : SettingsModel
	{

		// Delete data and return boolen
		$this->delete($this->code, 'code');

		// Return the SettingsModel object with the new data
		return $this->getByCode($this->code);
	}


}
