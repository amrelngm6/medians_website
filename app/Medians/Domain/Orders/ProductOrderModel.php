<?php

namespace Medians\Domain\Orders;

use Medians\Domain\Devices\DeviceModel;

use Medians\Domain\Products\ProductModel;


class ProductOrderModel 
{

	/*
	/ @var int
	*/
	private $id;

	/*
	/ @var int
	*/
	private $providerId;

	/*
	/ @var String
	*/
	private $orderCode;

	/*
	/ @var String
	*/
	private $product;

	/*
	/ @var String
	*/
	private $qty;

	/*
	/ @var FLoat
	*/
	private $productCost;

	/*
	/ @var String
	*/
	private $device;

	/*
	/ @var String
	*/
	private $insertedBy;

	/*
	/ @var String
	*/
	private $status;

	/*
	/ @var Timestamp
	*/
	private $time;




	function __construct($data)
	{

		$data = (object) $data;

		$this->setId(isset($data->id) ? $data->id : 0);	

		$this->setProviderId(isset($data->providerId) ? $data->providerId : 0);	

		$this->setOrderCode(!empty($data->orderCode) ?  $data->orderCode  : '');

		!empty($data->product) ?  $this->setProduct(new ProductModel($data->product))  : '';

		$this->setQty(isset($data->qty) ? $data->qty : 0);

		$this->setProductCost(isset($data->productCost) ? $data->productCost : 0);

		!empty($data->device) ? $this->setDevice(new DeviceModel($data->device)) : '';

		$this->setStatus(isset($data->status) ? $data->status : 0);

		$this->setInsertedBy(isset($data->insertedBy) ? $data->insertedBy : 0);

		$this->setTime(isset($data->time) ? $data->time : date('Y-m-d H:i:s'));

	}

	

	public static function applyId($id) : ProductOrderModel
	{
		return new self(array('id'=>$id));
	}

	public static function create($data) : ProductOrderModel
	{
		return new self($data);
	}


	public function id() : ?int
	{
		return $this->id;
	}

	public function providerId() : ?Int
	{
		return $this->providerId;
	}

	public function orderCode() : ?String
	{
		return $this->orderCode;
	}

	public function product() : ProductModel
	{
		return $this->product;
	}

	public function productCost() : ?Float
	{
		return $this->productCost;
	}

	public function device() : ?DeviceModel
	{
		return $this->device;
	}


	public function qty() : ?Int
	{
		return $this->qty;
	}

	public function status() : ?String
	{
		return $this->status;
	}

	public function insertedBy() : ?Int
	{
		return $this->insertedBy;
	}


	public function time() : ?String
	{
		return $this->time;
	}




	public function setId($id) : void
	{
		$this->id = $id;
	}


	public function setProviderId($providerId) : void
	{
		$this->providerId = $providerId;
	}


	public function setOrderCode($orderCode) : void
	{
		$this->orderCode = $orderCode;
	}


	public function setProduct(ProductModel $product) : void
	{
		$this->product = $product;
	}

	public function setProductCost($productCost = 0) : void
	{
		$this->productCost = $productCost;
	}

	public function setQty($qty) : void
	{
		$this->qty = $qty;
	}

	public function setDevice(DeviceModel $device) : void
	{
		$this->device = $device;
	}


	public function setStatus($status = 0) : void
	{
		$this->status = $status;
	}

	public function setInsertedBy($insertedBy = 0) : void
	{
		$this->insertedBy = $insertedBy;
	}

	public function setTime($time) : void
	{
		$this->time = $time;
	}


}
