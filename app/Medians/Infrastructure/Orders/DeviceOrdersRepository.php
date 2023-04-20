<?php

namespace Medians\Infrastructure\Orders;

use Medians\Domain\Orders\DeviceOrderModel;

use Shared\dbaser\CustomController;


class DeviceOrdersRepository extends CustomController 
{

	/*
	/ @var String
	*/
	protected $table = 'device_orders';


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
	private $orderCode;


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
	public function getByID($id) 
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

		return  $this->getOne(null, array('device' => $this->device, 'status' => 'active'), null, $this->table, 'object');
	}


	/*
	// Find item by `orderCode` 
	*/
	public function getByOrderCode() 
	{
		return  $this->getOne($this->orderCode, 'orderCode', null, $this->table, 'object');
	}

	
	/*
	// Find count by month
	*/
	public function getCountByMonth($providerId, $month, $nextmonth )
	{
	  	$return =  $this->getOne(null, 
	  		array( 
	  			'providerId' => $providerId, 
	  			'endTime' =>
	  			array(
	  				array( 
	  					date('Y-m-d H:i:s', strtotime(date($month))),  
	  					date('Y-m-d H:i:s', strtotime(date($nextmonth))) 
  					),
  					'between'
		  		)
	  		), 'count(id) as total', $this->table, 'object');
	  	
	  	return isset($return->total) ? $return->total : '0';
	}
	

	/*
	// Find all items 
	*/
	public function getAll($limit = 1000)
	{
		return  $this->get(null, null, $limit, null, $this->table, 'object');
	}

	/*
	// Create item
	*/
	public function createItem(DeviceOrderModel $DeviceOrderModel) : DeviceOrdersRepository
	{
		$this->data = (object) $this->data;

		$this->data->id =  $DeviceOrderModel->id();
		$this->data->device = $DeviceOrderModel->device()->id();
		$this->data->providerId = $DeviceOrderModel->providerId();
		$this->data->orderCode = $DeviceOrderModel->orderCode();
		$this->data->startTime = $DeviceOrderModel->startTime();
		$this->data->endTime = $DeviceOrderModel->endTime();
		$this->data->lastCheck = $DeviceOrderModel->lastCheck();
		$this->data->insertedBy = $DeviceOrderModel->insertedBy();
		$this->data->deviceCost = $DeviceOrderModel->deviceCost();
		$this->data->orderedBy = $DeviceOrderModel->orderedBy();
		$this->data->status = $DeviceOrderModel->status();

		return $this;
	}

	/*
	// Save item to database
	*/
	public function saveItem() 
	{

		// Insert data and return ID
		$this->id = $this->insert((array) $this->data, $this->table);

		// Return the DeviceOrderModel object with the new data
		return $this->getByID($this->id);
	}

	/*
	// Update item to database
	*/
	public function edit() 
	{
		unset($this->data->insertedBy);
		unset($this->data->deviceCost);

		// Update data and return boolen
		$this->updated = $this->update((array) $this->data, 'id', $this->id);

		// Return the DeviceOrderModel object with the new data
		return $this->getByDevice($this->device);
	}


	/*
	// Delete item to database
	//
	// @Returns DeviceOrderModel
	*/
	public function remove() 
	{

		// Delete data and return boolen
		$this->deleted = $this->delete($this->id);

		// Return the DeviceOrderModel object with the new data
		return $this->deleted ? true : false;
	}


}
