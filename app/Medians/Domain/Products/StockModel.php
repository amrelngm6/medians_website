<?php

namespace Medians\Domain\Products;


use Medians\Domain\Products\ProductModel;


class StockModel 
{

	/*
	/ @var int
	*/
	private $id;

	/*
	/ @var String
	*/
	private $product;

	/*
	/ @var String
	*/
	private $startStock;

	/*
	/ @var String
	*/
	private $stock;

	/*
	/ @var String
	*/
	private $insertedBy;

	/*
	/ @var String
	*/
	private $time;



	function __construct($data)
	{

		$data = (object) $data;

		$this->setId(isset($data->id) ? $data->id : 0);	

		!empty($data->product) ?  $this->setProduct(new ProductModel($data->product))  : '';

		$this->setStartStock(isset($data->startStock) ? $data->startStock : '');

		$this->setStock(isset($data->stock) ? $data->stock : 0);

		$this->setTime(isset($data->time) ? $data->time : date('Y-m-d H:i:s'));

		$this->setInsertedBy(isset($data->insertedBy) ? $data->insertedBy : 0);

	}

	

	public static function applyId($id) : StockModel
	{
		return new self(array('id'=>$id));
	}

	public static function create($data) : StockModel
	{
		return new self($data);
	}


	public function id() : String
	{
		return $this->id;
	}


	public function product() : ProductModel
	{
		return $this->product;
	}

	public function startStock() : String
	{
		return $this->startStock;
	}


	public function stock() : ?String
	{
		return $this->stock;
	}

	public function time() : String
	{
		return $this->time;
	}

	public function insertedBy() : String
	{
		return $this->insertedBy;
	}




	public function setId($id) : void
	{
		$this->id = $id;
	}

	public function setProduct(ProductModel $product) : void
	{
		$this->product = $product;
	}

	public function setStock($stock) : void
	{
		$this->stock = $stock;
	}

	public function setStartStock($startStock = 0) : void
	{
		$this->startStock = $startStock;
	}

	public function setTime($time = 0) : void
	{
		$this->time = $time;
	}

	public function setInsertedBy($insertedBy = 0) : void 
	{
		$this->insertedBy = $insertedBy;
	}


}
