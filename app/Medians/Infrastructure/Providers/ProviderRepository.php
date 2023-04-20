<?php

namespace Medians\Infrastructure\Providers;

use Medians\Domain\Providers\ProviderModel;

use Shared\dbaser\CustomController as CustomController;

class ProviderRepository  extends CustomController 
{

	/*
	/ @var String
	*/
	protected $table = 'providers';


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


	public function getById($customerId)
	{
		$this->id = $customerId;

		$this->data = $this->getOne($this->id, 'id', null, null, 'object');

		return new ProviderModel($this->data);

	}



	public function createItem($customerData) 
	{
				
		$this->data['id'] = $customerData->id();
		$this->data['title'] = $customerData->title();
		$this->data['publish'] = $customerData->publish();

		return $this;
	}


	/*
	// Save item to database
	*/
	public function saveItem() 
	{

		unset($this->data['id']);

		// Insert data and return ID
		$this->id = $this->insert((array) $this->data, $this->getTable());

		// Return the OrderModel object with the new data
		return $this->getById($this->id);
	}

	/*
	// Update item to database
	*/
	public function edit() : Object
	{

		// Update data and return boolen
		$this->updated = $this->update((array) $this->data, 'id', $this->id);

		// Return the OrderModel object with the new data
		return $this->getById($this->id);
	}



	/*
	// Delete item to database
	//
	// @Returns OrderModel
	*/
	public function remove() 
	{

		// Delete data and return boolen
		$this->deleted = $this->delete($this->id);

		// Return the OrderModel object with the new data
		return $this->deleted ? true : false;
	}



}
