<?php

namespace Medians\Domain\Prices;

use Medians\Domain\Devices\DeviceModel;

class PricesModel 
{

	/*
	/ @var int
	*/
	private $id;


	/*
	/ @var Int
	*/
	private $device;


	/*
	/ @var Float
	*/
	private $single_price;


	/*
	/ @var float
	*/
	private $multi_price;



	function __construct(Int $device = 0, $data)
	{

		$data = (object) $data;

		$this->device = $device;

		$this->id =  isset($data->id) ? $data->id : 0;

		$this->single_price =  isset($data->single_price) ? $data->single_price : '';

		$this->multi_price =  isset($data->multi_price) ? $data->multi_price : '';

	}


	public function id() : ?int
	{
		return $this->id;
	}


	public function device() : DeviceModel
	{
		return DeviceModel::applyId($this->device);
	}


	public function single_price() : ?Int
	{
		return (int) $this->single_price;
	}


	public function multi_price() : ?Int
	{
		return (int) $this->multi_price;
	}



	public function setId($id) : void
	{
		$this->id = $id;
	}

	public function setDevice($deviceId) : void
	{
		$this->device = $deviceId;
	}

	public function setSinglePrice($singlePrice) : void
	{
		$this->single_price = $singlePrice;
	}

	public function setMultiPrice($multiPrice) : void
	{
		$this->multi_price = $multiPrice;
	}


}
