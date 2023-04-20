<?php

namespace Medians\Domain\Orders;

use Medians\Domain\Devices\DeviceModel;


class DeviceOrderModel 
{

	/*
	/ @var int
	*/
	private $id;


	/*
	/ @var int
	*/
	private $device;

	/*
	/ @var int
	*/
	private $providerId;

	/*
	/ @var Float
	*/
	private $deviceCost;


	/*
	/ @var String
	*/
	private $orderCode;


	/*
	/ @var int
	*/
	private $bookingType;


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
	private $lastCheck;


	/*
	/ @var String
	*/
	private $status;

	/*
	/ @var int
	*/
	private $insertedBy;


	/*
	/ @var int
	*/
	private $orderedBy;





	function __construct($deviceId = null)
	{
		$this->device = $deviceId;
	}

	public static function create($data)
	{
		$data = (object) $data;

		$DeviceOrderModel = new self(null);
			
		$DeviceOrderModel->setId(isset($data->id) ? $data->id : 0);
		$DeviceOrderModel->setDevice((!empty($data->device) && is_object($data->device)) ? $data->device : new DeviceModel(null));
		$DeviceOrderModel->setOrderCode(isset($data->orderCode) ? $data->orderCode : null);
		$DeviceOrderModel->setProviderId(isset($data->providerId) ? $data->providerId : null);
		$DeviceOrderModel->setBookingType(isset($data->bookingType) ? $data->bookingType : null);
		$DeviceOrderModel->setStartTime(isset($data->startTime) ? $data->startTime : '');
		$DeviceOrderModel->setEndTime(isset($data->endTime) ? $data->endTime : '');
		$DeviceOrderModel->setLastCheck(isset($data->lastCheck) ? $data->lastCheck : '');
		$DeviceOrderModel->setDeviceCost(isset($data->deviceCost) ? $data->deviceCost : 0);
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

	public function orderCode() : ?String
	{
		return $this->orderCode;
	}

	public function providerId() : ?Int
	{
		return $this->providerId;
	}


	public function bookingType() : String
	{
		return $this->bookingType;
	}


	public function startTime() : ?String
	{
		return $this->startTime;
	}

	public function endTime() : ?String
	{
		return $this->endTime;
	}

	public function lastCheck() : ?String
	{
		return $this->lastCheck;
	}

	public function deviceCost() : ?Float
	{
		return $this->deviceCost;
	}

	public function status() : ?String
	{
		return $this->status;
	}


	public function insertedBy() : ?int
	{
		return $this->insertedBy;
	}

	public function orderedBy() : ?int
	{
		return $this->orderedBy;
	}




	public function setId($id) : void
	{
		$this->id = $id;
	}

	public function setDevice(DeviceModel $device) : void
	{
		$this->device = $device;
	}

	public function setProviderId($providerId) : void
	{
		$this->providerId = $providerId;
	}

	public function setOrderCode($orderCode) : void
	{
		$this->orderCode = $orderCode;
	}

	public function setBookingType($bookingType) : void
	{
		$this->bookingType = $bookingType;
	}

	public function setStartTime($startTime) : void
	{
		$this->startTime = $startTime;
	}

	public function setEndTime($endTime) : void
	{
		$this->endTime = $endTime;
	}

	public function setLastCheck($lastCheck) : void
	{
		$this->lastCheck = $lastCheck;
	}

	public function setDeviceCost($deviceCost) : void
	{
		$this->deviceCost = $deviceCost;
	}

	public function setInsertedBy($insertedBy) : void
	{
		$this->insertedBy = $insertedBy;
	}


	public function setOrderedBy($orderedBy) : void
	{
		$this->orderedBy = $orderedBy;
	}


	public function setStatus($status) : void
	{
		$this->status = $status;
	}


}
