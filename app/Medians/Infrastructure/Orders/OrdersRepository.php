<?php

namespace Medians\Infrastructure\Orders;

use Medians\Domain\Orders\OrderModel;

use Shared\dbaser\CustomController;


class OrdersRepository extends CustomController 
{

	/*
	/ @var String
	*/
	protected $table = 'orders';


	/*
	/ @var Int
	*/
	private $id;


	/*
	/ @var String
	*/
	private $code;


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
	// Set `code` 
	*/
	public function setCode($code)
	{
		$this->code = $code;
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
	public function getByDevice($deviceId) 
	{
		$this->device = $deviceId;

		return  $this->getOne($this->device, 'device', null, $this->table, 'object');

	}

	/*
	// Find item by `code` 
	*/
	public function getByCode($code) 
	{
		$this->code = $code;

		return  $this->with('DeviceModel')
			->with('DeviceOrder')
			->with('Provider')
			->with('Products')
			->with('ProductsT')
			->where('code', $this->code)
			->first();

	}

	/*
	// Find item by `discountCode` 
	*/
	public function getByDiscountCode($discountCode) : Array
	{
		return  $this->get($discountCode, 'discountCode', null, null, $this->table, 'object');
	}

	/*
	// Find all items 
	*/
	public function getAll($providerId, $limit = null)
	{
		return  $this->get($providerId, 'providerId', $limit, null, $this->table, 'object');
	}


	/*
	// Find all items by month & provider
	*/
	public function getByMonth($providerId, $month, $nextmonth )
	{
	  	return  $this->where('providerId' , $providerId)
	  			->whereDate('endTime' , '>=', date('Y-m-d H:i:s', strtotime(date($month)))) 
	  			->whereDate('endTime' , '<', date('Y-m-d H:i:s', strtotime(date($nextmonth))))
	  			->get(); 
	}
	
	
	/*
	// Find all items by month
	*/
	public function getTotalByMonth($month, $nextmonth )
	{
	  	return  $this->with('DeviceModel')
	  			->with('DeviceOrder')
	  			->with('Provider')
	  			->whereDate( 'endTime' , '>=', date('Y-m-d H:i:s', strtotime(date($month))))
	  			->whereDate( 'endTime' , '<', date('Y-m-d H:i:s', strtotime(date($nextmonth))))
	  			->get();
	}
	
	
	/*
	// Find total cost by month
	*/
	public function getCostByMonth($providerId, $month, $nextmonth )
	{

	  	return  $this->where('providerId' , $providerId)
	  			->whereDate('endTime' , '>=', date('Y-m-d H:i:s', strtotime(date($month)))) 
	  			->whereDate('endTime' , '<', date('Y-m-d H:i:s', strtotime(date($nextmonth))))
	  			->select('SUM(cost) as totalcost')
	  			->value('totalcost');
	}
	
	
	
	/*
	// Find total cost by day
	*/
	public function getSalesByDay($providerId, $day, $nextday )
	{

	  	return  $this->where('providerId' , $providerId)
			->whereDate('endTime' , '>=', date('Y-m-d H:i:s', strtotime(date($day)))) 
			->whereDate('endTime' , '<', date('Y-m-d H:i:s', strtotime(date($nextday))))
			->select('SUM(cost) as totalcost')
			->value('totalcost');
	}
	
	

	/*
	// Find all items between two days By ProviderId
	*/
	public function getByDate($providerId, $date1, $date2 )
	{

	  	return  $this->where('providerId' , $providerId)
			->whereDate('endTime' , '>=', date('Y-m-d H:i:s', strtotime(date($date1)))) 
			->whereDate('endTime' , '<', date('Y-m-d H:i:s', strtotime(date($date12)))) 
			->get();
	}

	/*
	// Find all items between two days
	*/
	public function getTotalByDate($date1, $date2 )
	{

	  	return  $this->with('DeviceModel')
	  		->whereDate('endTime' , '>=', date('Y-m-d H:i:s', strtotime(date($date1)))) 
			->whereDate('endTime' , '<', date('Y-m-d H:i:s', strtotime(date($date2)))) 
			->get();
	}

	

	/*
	// Create item
	*/
	public function createItem(OrderModel $OrderModel) : OrdersRepository
	{
		$this->data = (object) $this->data;

		$this->data->id =  $OrderModel->id();
		$this->data->device = $OrderModel->device()->id();
		$this->data->code = $OrderModel->code();
		$this->data->providerId = $OrderModel->providerId();
		$this->data->cost = $OrderModel->cost();
		$this->data->totalCost = $OrderModel->totalCost();
		$this->data->discountCode = $OrderModel->discountCode();
		$this->data->startTime = $OrderModel->startTime();
		$this->data->endTime = $OrderModel->endTime();
		$this->data->time = $OrderModel->time();
		$this->data->status = $OrderModel->status();

		return $this;
	}

	/*
	// Set data field
	*/
	public function setField($field, $value) : void
	{
		$this->data->$field =  $value;
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
		return $this->getByDevice($this->device);
	}


	/*
	// Update item by 'code' column
	*/
	public function editByCode() : Object
	{

		// Update data and return boolen
		$this->updated = $this->update((array) $this->data, 'code', $this->code);

		// Return the OrderModel object with the new data
		return $this->getByCode($this->code);
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


	/**
	* Relationship with Devices Repo 
	* 
	*/
	public function DeviceModel()
	{
		return $this->hasOne('Medians\Infrastructure\Devices\DevicesRepository', 'id', 'device');
	}
	

	/**
	* Relationship with DeviceOrders Repo 
	* 
	*/
	public function DeviceOrder()
	{
		return $this->hasOne('Medians\Infrastructure\Orders\DeviceOrdersRepository', 'orderCode', 'code');
	}
	

	/**
	* Relationship with Provider Repo 
	* 
	*/
	public function Provider()
	{
		return $this->hasOne('Medians\Infrastructure\Providers\ProviderRepository', 'id', 'providerId');
	}
	

	/**
	* Relationship with Products Repo 
	* 
	*/
	public function Products()
	{
		return $this->hasMany('Medians\Infrastructure\Orders\ProductOrdersRepository', 'orderCode', 'code');
	}
	
	/**
	* Relationship with Products Repo 
	* 
	*/
	public function ProductsT()
	{
		return $this->hasOneThrough('Medians\Infrastructure\Orders\ProductOrdersRepository', 'orderCode', 'code');
	}
	


}
