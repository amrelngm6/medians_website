<?php

namespace Medians\Infrastructure\Products;

use Medians\Infrastructure\Products\ProductsRepository;
use Medians\Domain\Products\ProductModel;
use Medians\Domain\Products\StockModel;
use Shared\dbaser\CustomController;

/**
 * Stock class database queries
 */
class StockRepository extends CustomController
{
	

	/*
	/ @var String
	*/
	protected $table = 'stock';


	/*
	/ @var Int
	*/
	private $id;


	/*
	/ @var Int
	*/
	private $product;


	/*
	/ @var Int
	*/
	private $stock;


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
	// Set `id` 
	*/
	public function setId($id)
	{
		$this->id = $id;
	}

	/*
	// Set `product` 
	*/
	public function setProductId($product)
	{
		$this->product = $product;
	}

	/*
	// Set `stock` 
	*/
	public function setStock($stock)
	{
		$this->stock = $stock;
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
	public function getById($id) 
	{

		$this->data = $this->getOne($id, 'id', null, $this->table, 'object');

		return $this->data;
	}



	/*
	// Find item by `id` 
	*/
	public function getByProvider($providerId, $limit) 
	{

		$query = "SELECT st.* From " . $this->getTable() . " st LEFT JOIN  " . (new ProductsRepository)->getTable() . " pr " ;
		$query .= " ON pr.id =  st.product " ;
		$query .= " AND pr.providerId = $providerId " ;
		$query .= " WHERE pr.publish = 1 " ;

		$this->data = $this->rawQuery($query);

		return $this->data;
	}


	/*
	// Find all items 
	*/
	public function getAll($limit = 1000)
	{
		return  $this->get(null, null, $limit, null, $this->table, 'object');
	}



	/*
	// Find available stock 
	*/
	public function getStockObject($product, $qty = 1) : ?Object
	{
		return  $this->getOne(null, array(
			'stock' => array($qty, '>='),
			'product' => $product
		), '*', $this->table, 'object');
	}


	/*
	// Find available stock 
	*/
	public function getStock($product, $qty = 1) : ?Int
	{
		$return =  $this->getStockObject($product, $qty);

		return isset($return->stock) ? $return->stock : 0;
	}


	
	/*
	// Find count by month
	*/
	public function getByMonth($month, $nextmonth )
	{

		$query = array( 'time' =>
  			array(
  				array( 
  					date('Y-m-d H:i:s', strtotime(date($month))),  
  					date('Y-m-d H:i:s', strtotime(date($nextmonth))) 
				),
				'between'
	  		)
  		);

	  	return $this->get(null, $query, null, null, $this->table, 'object');
	  	
	}
	
	
	/*
	// Find count by month
	*/
	public function getByProduct($product ) : Array
	{

	  	return $this->get($product, 'product', null, null, $this->table, 'object');
	  	
	}
	

	

	/*
	// Create item
	*/
	public function createItem(StockModel $StockModel) : StockRepository
	{
		$this->data = (object) $this->data;

		$this->data->id =  $StockModel->id();
		$this->data->product = $StockModel->product()->id();
		$this->data->startStock = $StockModel->startStock();
		$this->data->stock = $StockModel->stock();
		$this->data->insertedBy = $StockModel->insertedBy();
		$this->data->time = $StockModel->time();

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