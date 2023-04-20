<?php

namespace Medians\Infrastructure\Orders;

use Medians\Domain\Orders\ProductOrderModel;

use Shared\dbaser\CustomController;


class ProductOrdersRepository extends CustomController 
{

	/*
	/ @var String
	*/
	protected $table = 'product_orders';


	/*
	/ @var Int
	*/
	private $id;


	/*
	/ @var Int
	*/
	private $device;


	/*
	/ @var Int
	*/
	private $product;


	/*
	/ @var Int
	*/
	private $orderCode;


	/*
	/ @var Array
	*/
	private $data = array();


	/*
	/ @var Array
	*/
	private $editQuery = array();



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
	public function setId($id)
	{
		$this->id = $id;
	}

	/*
	// Set `device` 
	*/
	public function setDevice($deviceId)
	{
		$this->device = $deviceId;
	}


	/*
	// Set `product` 
	*/
	public function setProduct($productId)
	{
		$this->product = $productId;
	}


	/*
	// Set `orderCode` 
	*/
	public function setOrderCode($orderCode)
	{
		$this->orderCode = $orderCode;
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
	// Find item by `product` 
	*/
	public function getByProduct($productId) 
	{
		
		return  $this->get($productId, 'product', null, null, $this->table, 'object');
	}


	/*
	// Find item by `providerId` 
	*/
	public function getByProvider($providerId) 
	{
		
		return  $this->get($providerId, 'providerId', null, null, $this->table, 'object');
	}


	/*
	// Find item by `orderCode` 
	*/
	public function getByOrderCode($orderCode) : Array
	{
		return  $this->get($orderCode, 'orderCode', null, null, $this->table, 'object');
	}


	/*
	// Find active items by 'device' 
	*/
	public function getByDeviceActive($deviceId) : Array
	{
	
		$query = array(
			'device' => $deviceId,
			'status' => 'active'
		);

		return  $this->get(null, $query, null, null, $this->table, 'object');
	}

	/*
	// Find cost of active items by 'device' 
	*/
	public function getByDeviceCost($deviceId) : ?Int
	{
	
		$query = array(
			'device' => $deviceId,
			'status' => 'active'
		);

		$check =  $this->getOne(null, $query, 'SUM(productCost * qty) as totalcost', $this->table, 'object');

		return isset($check->totalcost) ? $check->totalcost : 0;
	}


	/*
	// Find total cost by day
	*/
	public function getSalesByDay($providerId, $day, $nextday )
	{
	  	$return =  $this->getOne(null, 
	  		array( 
	  			'providerId' => $providerId,
	  			'time' =>
	  			array(
	  				array( 
	  					date('Y-m-d H:i:s', strtotime(date($day))),  
	  					date('Y-m-d H:i:s', strtotime(date($nextday))) 
  					),
  					'between'
		  		)
	  		), 'SUM(qty) as totalqty', $this->table, 'object');
	  	
	  	return isset($return->totalqty) ? $return->totalqty : '0';
	}
	
	
	/*
	// Find all items 
	*/
	public function getAll($providerId, $limit = 1000)
	{
		return  $this->get($providerId, 'providerId', $limit, null, $this->table, 'object');
	}

	/*
	// Create item
	*/
	public function createItem(ProductOrderModel $ProductOrderModel) : ProductOrdersRepository
	{
		$this->data = (object) $this->data;

		$this->data->id =  $ProductOrderModel->id();
		$this->data->providerId = $ProductOrderModel->providerId();
		$this->data->device = $ProductOrderModel->device()->id();
		$this->data->product = $ProductOrderModel->product()->id();
		$this->data->orderCode = $ProductOrderModel->orderCode();
		$this->data->productCost = $ProductOrderModel->product()->price();
		$this->data->qty = $ProductOrderModel->qty();
		$this->data->insertedBy = $ProductOrderModel->insertedBy();
		$this->data->time = $ProductOrderModel->time();
		$this->data->status = $ProductOrderModel->status();

		return $this;
	}


	/*
	// Save item to database
	*/
	public function saveItem() 
	{

		// Insert data and return ID
		$this->id = $this->insert((array) $this->data, $this->table);

		// Return the ProductOrderModel object with the new data
		return $this->getById($this->id);
	}


	/*
	// Update item to database
	*/
	public function edit($query) 
	{
		$this->editQuery = (array) $query;

		// Update data and return boolen
		$this->updated = $this->update((array) $this->data, $this->editQuery);

		// Return the ProductOrderModel object with the new data
		return $this->updated;
	}



	/*
	// Delete item to database
	//
	// @Returns ProductOrderModel
	*/
	public function remove() 
	{

		// Delete data and return boolen
		$this->deleted = $this->delete($this->id);

		// Return the ProductOrderModel object with the new data
		return $this->deleted ? true : false;
	}


}
