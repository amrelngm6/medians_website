<?php

namespace Medians\Domain\Orders;

use Medians\Domain\Devices\DeviceModel;


class OrderModel 
{

	/*
	/ @var int
	*/
	private $id;


	/*
	/ @var String
	*/
	private $code;


	/*
	/ @var Float
	*/
	private $cost;


	/*
	/ @var Float
	*/
	private $totalCost;


	/*
	/ @var int
	*/
	private $device;


	/*
	/ @var int
	*/
	private $providerId;


	/*
	/ @var String
	*/
	private $discountCode;


	/*
	/ @var String
	*/
	private $startTime;


	/*
	/ @var String
	*/
	private $endTime;


	/*
	/ @var String
	*/
	private $time;


	/*
	/ @var String
	*/
	private $status;



	function __construct($deviceId = null)
	{
		$this->device = $deviceId;
	}

	public static function create($data)
	{
		$data = (object) $data;

		$DeviceOrderModel = new self(null);
			
		$DeviceOrderModel->setId(isset($data->id) ? $data->id : 0);
		$DeviceOrderModel->setCost(isset($data->cost) ? $data->cost : 0);
		$DeviceOrderModel->setTotalCost(isset($data->totalCost) ? $data->totalCost : 0);
		$DeviceOrderModel->setCode(isset($data->code) ? $data->code : '');
		$DeviceOrderModel->setDevice((isset($data->device) && is_object($data->device)) ? $data->device : new DeviceModel(null));
		$DeviceOrderModel->setProviderId(isset($data->providerId) ? $data->providerId : 0);
		$DeviceOrderModel->setDiscountCode(isset($data->discountCode) ? $data->discountCode : '');
		$DeviceOrderModel->setStartTime(isset($data->startTime) ? $data->startTime : '');
		$DeviceOrderModel->setEndTime(isset($data->endTime) ? $data->endTime : '');
		$DeviceOrderModel->setTime(isset($data->time) ? $data->time : '');
		$DeviceOrderModel->setOrderedBy(isset($data->orderedBy) ? $data->orderedBy : '');
		$DeviceOrderModel->setStatus(isset($data->status) ? $data->status : '');

		return $DeviceOrderModel;
	}


	public function id() : ?int
	{
		return $this->id;
	}


	public function device() : DeviceModel
	{
		return $this->device;
	}

	public function discountCode() : ?String
	{
		return $this->discountCode;
	}
	
	public function providerId() : ?String
	{
		return $this->providerId;
	}

	public function code() : String
	{
		return $this->code;
	}


	public function cost() : Float
	{
		return $this->cost;
	}

	public function totalCost() : ?Float
	{
		return $this->totalCost;
	}


	public function startTime() : ?String
	{
		return $this->startTime;
	}

	public function endTime() : ?String
	{
		return $this->endTime;
	}

	public function time() : ?String
	{
		return $this->time;
	}

	public function orderedBy() : ?int
	{
		return $this->orderedBy;
	}

	public function status() : ?String
	{
		return $this->status;
	}




	public function setId($id) : void
	{
		$this->id = $id;
	}

	public function setDevice(DeviceModel $device) : void
	{
		$this->device = $device;
	}

	public function setCode($code) : void
	{
		$this->code = $code;
	}

	public function setProviderId($providerId) : void
	{
		$this->providerId = $providerId;
	}


	public function setDiscountCode($discountCode) : void
	{
		$this->discountCode = $discountCode;
	}

	public function setCost($cost) : void
	{
		$this->cost = $cost;
	}

	public function setTotalCost($totalCost) : void
	{
		$this->totalCost = $totalCost;
	}

	public function setStartTime($startTime) : void
	{
		$this->startTime = $startTime;
	}

	public function setEndTime($endTime) : void
	{
		$this->endTime = $endTime;
	}

	public function setTime($time) : void
	{
		$this->time = $time;
	}

	public function setOrderedBy($orderedBy) : void
	{
		$this->orderedBy = $orderedBy;
	}

	public function setStatus($status) : void
	{
		$this->status = $status;
	}



	/*
	// Genrate unique code 
	*/
	public function genrateCode() : String
	{
		return time().rand(9,99);
	}
}
