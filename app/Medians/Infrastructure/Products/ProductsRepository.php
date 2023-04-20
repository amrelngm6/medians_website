<?php

namespace Medians\Infrastructure\Products;

use Medians\Domain\Products\ProductModel;
use Shared\dbaser\CustomController;

/**
 * Product class database queries
 */
class ProductsRepository extends CustomController
{

	/*
	/ @var String
	*/
	protected $table = 'products';


	/*
	/ @var Int
	*/
	private $id;


	/*
	/ @var Int
	*/
	private $providerId;


	/*
	/ @var Array
	*/
	private $data = array();



	/*
	// return $table String 
	*/
	public function getTable() : String
	{
		return $this->table;
	}



	/*
	// Set `Id` 
	*/
	public function setId($id)
	{
		$this->id = $id;
	}


	/*
	// Set `providerId` 
	*/
	public function setProviderId($providerId)
	{
		$this->providerId = $providerId;
	}


	/*
	// Find item by `id` 
	*/
	public function getById($id) 
	{

		$this->data = $this->getOne($id, 'id', null, $this->table, 'object');

		return $this->data;

	}


	/*
	// Find items by `providerId` 
	*/
	public function getByProvider($providerId) 
	{

		$this->data = $this->get($providerId, 'providerId', null, null, $this->table, 'object');

		return $this->data;

	}


	/*
	// Find all items 
	*/
	public function getAll($limit = null)
	{
		return  $this->get(null, null, $limit, null, $this->table, 'object');
	}


	/*
	// Create item
	*/
	public function createItem(ProductModel $ProductModel) : ProductsRepository
	{
		$this->data = (object) $this->data;

		$this->data->id =  $ProductModel->id();
		$this->data->providerId = $ProductModel->providerId();
		$this->data->stock = $ProductModel->stock();
		$this->data->title = $ProductModel->title();
		$this->data->description = $ProductModel->description();
		$this->data->price = $ProductModel->price();
		$this->data->picture = $ProductModel->picture();
		$this->data->type = $ProductModel->type();
		$this->data->publish = $ProductModel->publish();

		return $this;
	}






	/*
	// Save item to database
	*/
	public function saveItem() 
	{

		// Insert data and return ID
		$this->id = $this->insert((array) $this->data, $this->table);

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