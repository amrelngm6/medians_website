<?php

namespace Medians\Application\Orders;

use Medians\Infrastructure\Orders as Repo;
use Medians\Application\Devices\Device;
use Medians\Application\Orders\ProductOrder;
use Medians\Application\Calculator\Calculator;
use Medians\Application\Prices\Prices;

use Medians\Domain\Orders\DeviceOrderModel;
use Medians\Domain\Orders\OrderModel;

class DeviceOrder
{

	/*
	// @var Object
	*/
	protected $repo;

	/*
	// @var Int
	*/
	protected $device;
	
	/*
	// @var String
	*/
	protected $bookingType;
	
	/*
	// @var String
	*/
	protected $playingType;
	
	/*
	// @var Array
	*/
	protected $data = array();

	

	function __construct($deviceId = 0, $bookingType = 'unlimited', $playingType = 'single')
	{

		$this->device = $deviceId;
		$this->bookingType = $bookingType;
		$this->playingType = $playingType;

		$this->repo = new Repo\DeviceOrdersRepository();

	}



	public static function create($deviceId = 0, $bookingType = 'unlimited') 
	{

		return new self($deviceId, $bookingType);
	}



	public function createModel($data) : DeviceOrderModel 
	{

		return DeviceOrderModel::create($data);
	}


	public function setInsertedBy($insertedBy = 0) : void
	{
		$this->DeviceOrderModel->setInsertedBy($insertedBy);
	}

	public function setProviderId($providerId = 0) : void
	{
		$this->DeviceOrderModel->setProviderId($providerId);
	}

	public function setOrderedBy($orderedBy = 0) : void
	{
		$this->DeviceOrderModel->setOrderedBy($orderedBy);
	}

	public function setEndTime($hours = 1, $minutes = 0) : void
	{
		$minutes = empty($minutes) ? 1 : $minutes;
		$this->DeviceOrderModel->setEndTime(date('Y-m-d H:i:s', strtotime("+$hours hours +$minutes minutes ")) );
	}

	public function unsetEndTime() : void
	{
		$this->DeviceOrderModel->setEndTime(0) ;
	}


	public function setDeviceId($deviceId = 0) : void
	{

		$this->device = $deviceId;
		$this->data = (object) $this->getItem($this->device);
		$this->data->device =  (new Device())->getItem($this->device);
		$this->DeviceOrderModel = DeviceOrderModel::create($this->data);
		$this->DeviceOrderModel->setDevice((new Device())->getItem($this->device));

	}



	public function getByOrderCode($orderCode = 0) 
	{

		$this->repo->setOrderCode($orderCode);

		return $this->repo->getByOrderCode();
	}

	public function getItem($deviceId = 0) 
	{

		return $this->repo->getByDevice($deviceId);
	}


	public function getPrices($id, $col)
	{
		$prices = (new Prices())->getItem($id);

		return ($prices->$col()) ? $prices->$col() : 0;
	} 


	public function calculate($cost, $startTime, $endTime) 
	{

		$interval = date_diff(new \DateTime($startTime) , new \DateTime($endTime));

		return (new Calculator
			( 	
				$cost, 
				$interval->format('%h'), 
				$interval->format('%i')
			)
		)->getCost(0);

	}	


	public function calculateCost()  
	{
		
		$this->deviceCost = $this->calculate($this->DeviceOrderModel->deviceCost(), $this->DeviceOrderModel->startTime(),$this->OrderModel->endTime() );

		$this->checkProductsCost();

		return $this->deviceCost;
	}
	
	public function checkProductsCost()
	{
		$this->deviceCost =  ( $this->deviceCost + (new ProductOrder)->getDeviceActiveCost($this->DeviceOrderModel->device()->id()) );
	} 

	public function calculateTime($time1, $time2) 
	{

		$start_date = new \DateTime($time1);

		return array_map(function($a){
			return ($a > 9) ? $a : '0'.$a;
		}, (array) $start_date->diff(new \DateTime($time2)));
	}

	public function submitOrder() 
	{

		if (empty($this->DeviceOrderModel->device()->id()))
		{
			throw new \Exception("Device not defined ", 1);
		}


		// Prepare the OrderModel
		$this->OrderModel = OrderModel::create($this->DeviceOrderModel);
		$this->OrderModel->setDevice($this->DeviceOrderModel->device());
		$this->OrderModel->setProviderId($this->DeviceOrderModel->providerId());
		$this->OrderModel->setCode($this->DeviceOrderModel->orderCode());
		$this->OrderModel->setStartTime($this->DeviceOrderModel->startTime());
		$this->OrderModel->setEndTime(date('Y-m-d H:i:s'));
		$this->OrderModel->setTime(date('Y-m-d H:i:s'));
		$this->OrderModel->setCode($this->OrderModel->genrateCode());
		$this->OrderModel->setCost($this->calculateCost());
		$this->OrderModel->setStatus('pending');

		$checkOrder = new Order();
		$checkOrder->setModel($this->OrderModel);
			
		// Submit the order
		$this->OrderModel =  $checkOrder->handle();

		// Update the orderCode
		$this->DeviceOrderModel->setOrderCode($this->OrderModel->code());

		// Update the order status and times
		$this->finishOrder();

		// Update the products status of this order
		$this->updateProductOrder();

		return $this->OrderModel;
	}


	public function finishOrder() : void
	{

		// Update the order information
		$this->DeviceOrderModel->setLastCheck(date('Y-m-d H:i:s'));
		$this->DeviceOrderModel->setEndTime(date('Y-m-d H:i:s'));
		$this->DeviceOrderModel->setStatus('completed');
		

		// Prepare the repo
		$this->repo = $this->repo->createItem($this->DeviceOrderModel);	
		$this->repo->setDevice($this->DeviceOrderModel->device()->id());	
		$this->repo->setId($this->DeviceOrderModel->id());	
		
		// Update the database
		$this->repo->edit();

	}

	
	public function updateProductOrder() : void
	{


		$ProductOrder = new ProductOrder();

		// Update the items by device and status
		$query = array(
			'providerId'=> $this->DeviceOrderModel->providerId(),
			'device'=> $this->DeviceOrderModel->device()->id(),
			'status'=> 'active'
		);

		// Fields that will be Updated 
		$updatedData = array('status'=>'completed', 'orderCode' => $this->OrderModel->code());

		// Update through the Repo 
		$ProductOrder->editItemStatus($updatedData, $query, $this->OrderModel->code());

	}


	public function updateLastCheck() : DeviceOrderModel
	{

		if ($this->DeviceOrderModel->status() == 'active')
		{
			$this->DeviceOrderModel->setLastCheck(date('Y-m-d H:i:s'));
			$this->DeviceOrderModel->setStatus('active');
		}

		return $this->executeEdit();
	}


	public function handle() : DeviceOrderModel
	{

		$this->DeviceOrderModel->setLastCheck(date('Y-m-d H:i:s'));

		if (!empty($this->DeviceOrderModel->status()) && $this->DeviceOrderModel->status() == 'active')
		{
			return $this->editItem();
		} else {
			return $this->saveItem();
		}
	}

	public function saveItem() : DeviceOrderModel
	{

		$this->DeviceOrderModel->setStatus('active');
		$this->DeviceOrderModel->setId(0);	
		$this->DeviceOrderModel->setBookingType($this->bookingType);	
		$this->DeviceOrderModel->setStartTime(date('Y-m-d H:i:s'));
		$this->DeviceOrderModel->setDeviceCost($this->getPrices($this->DeviceOrderModel->device()->id(), $this->playingType.'_price'));

		$this->repo = $this->repo->createItem($this->DeviceOrderModel);	
		$this->repo->setDevice($this->DeviceOrderModel->device()->id());	

		$this->respone = $this->repo->saveItem();	
		$this->respone->device = $this->DeviceOrderModel->device();	
		return DeviceOrderModel::create($this->respone);
	}


	public function editItem() : DeviceOrderModel
	{

		$this->DeviceOrderModel->setId($this->DeviceOrderModel->id());	

		return $this->executeEdit();
	}


	public function executeEdit() : DeviceOrderModel
	{

		$this->DeviceOrderModel->setLastCheck(date('Y-m-d H:i:s'));
		$this->repo = $this->repo->createItem($this->DeviceOrderModel);	
		$this->repo->setDevice($this->DeviceOrderModel->device()->id());	
		$this->repo->setId($this->DeviceOrderModel->id());	

		$this->respone = $this->repo->edit();	
		$this->respone->device = ((new Device())->getItem($this->respone->device));	
		return DeviceOrderModel::create($this->respone);

	}


	public function deleteItem() : DeviceOrderModel
	{

		$this->repo->setDevice($this->DeviceOrderModel->device()->id());	

		return $this->repo->remove();

	}

	public function validate() 
	{

		if (empty($this->data->device))
		{
			throw new \Exception("Empty device id ", 1);
		}

	}




	/*
	// Count methods
	*/
	public function getCountByMonth($providerId, $month = null)
	{
		$month = empty($month) ? date('Y-m') : $month;
		$nextmonth = date('Y-m', strtotime('+1 month', strtotime($month))) ;
		return $this->repo->getCountByMonth($providerId, $month, $nextmonth);
	} 


}
