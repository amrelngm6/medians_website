<?php

namespace Medians\Domain\Products;



class ProductModel 
{

	/*
	/ @var int
	*/
	private $id;

	/*
	/ @var Int
	*/
	private $providerId;

	/*
	/ @var String
	*/
	private $title;

	/*
	/ @var String
	*/
	private $description;

	/*
	/ @var String
	*/
	private $price;

	/*
	/ @var String
	*/
	private $picture;

	/*
	/ @var String
	*/
	private $type;

	/*
	/ @var String
	*/
	private $stock;

	/*
	/ @var Int
	*/
	private $publish;


	function __construct($data)
	{

		$data = (object) $data;

		$this->setId(isset($data->id) ? $data->id : 0);	

		$this->setProviderId(isset($data->providerId) ? $data->providerId : 0);	

		$this->setTitle(isset($data->title) ? $data->title : '');

		$this->setDescription(isset($data->description) ? $data->description : '');

		$this->setPrice(isset($data->price) ? $data->price : 0);

		$this->setPicture(isset($data->picture) ? $data->picture : 0);

		$this->setType(isset($data->type) ? $data->type : '');

		$this->setStock(isset($data->stock) ? $data->stock : 0);

		$this->setPublish(!empty($data->publish) ? 1 : 0);

	}

	

	public static function applyId($id) : ProductModel
	{
		return new self(array('id'=>$id));
	}

	public static function create($data) : ProductModel
	{
		return new self($data);
	}


	public function id() : String
	{
		return $this->id;
	}


	public function title() : String
	{
		return $this->title;
	}

	public function providerId() : ?String
	{
		return $this->providerId;
	}


	public function description() : String
	{
		return $this->description;
	}


	public function picture() : ?String
	{
		return $this->picture;
	}

	public function price() : String
	{
		return $this->price;
	}

	public function type() : String
	{
		// return !is_object($this->type) ? DeviceTypeModel::applyId($this->type) : $this->type;
		return $this->type;
	}

	public function stock() : ?int
	{
		return $this->stock;
	}

	public function publish() : ?String
	{
		return $this->publish;
	}



	public function setId($id) : void
	{
		$this->id = $id;
	}

	public function setProviderId($providerId) : void
	{
		$this->providerId = $providerId;
	}

	public function setTitle($title) : void
	{
		$this->title = $title;
	}

	public function setDescription($description) : void
	{
		$this->description = $description;
	}

	public function setPicture($picture = 0) : void
	{
		$this->picture = $picture;
	}

	public function setPrice($price = 0) : void
	{
		$this->price = $price;
	}

	public function setStock($stock = 0) : void
	{
		$this->stock = $stock;
	}

	public function setType($type) : void
	{
		$this->type = $type;
	}

	public function setPublish($publish = '0') 
	{
		$this->publish = $publish;
	}


}
