<?php

namespace Medians\Infrastructure\Services;

use Medians\Domain\Services\Service;

use Shared\dbaser\CustomController;
use Medians\Infrastructure\Content\ContentRepository;


class ServicesRepository extends CustomController 
{

	/*
	/ @var String
	*/
	protected $table = 'services';


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



	/**
	 * Relation with content
	 * 
	 */
	public function content()
	{
		return $this->morphMany(ContentRepository::class, 'model' );
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


	/**
	* Get index page services 
	*/
	public function list()
	{
		return $this->where('status', 1)->get();
	}


	/*
	// Create item
	*/
	public function createItem(Service $DeviceData) 
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
	public function saveItem() : Service
	{

		// Insert data and return ID
		$this->id = $this->insert((array) $this->data, $this->table);

		// Return the Service object with the new data
		return new Service($this->getOne($this->id, 'id', null, $this->table, 'object'));
	}

	/*
	// Update item to database
	*/
	public function edit() : Service
	{

		// Update data and return boolen
		$this->updated = $this->update((array) $this->data, 'id', $this->id);

		// Return the Service object with the new data
		return new Service($this->getOne($this->id, 'id', null, $this->table, 'object'));
	}


	/*
	// Delete item to database
	//
	// @Returns Service
	*/
	public function remove() 
	{

		// Delete data and return boolen
		$this->deleted = $this->delete($this->id);

		// Return the Service object with the new data
		return $this->deleted;
	}


}
