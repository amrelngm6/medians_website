<?php

namespace Medians\Domain\Discounts;


class DiscountModel 
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
	/ @var Int
	*/
	private $useCount;

	/*
	/ @var Int
	*/
	private $value;

	/*
	/ @var Int
	*/
	private $maxDiscount;

	/*
	/ @var FLoat
	*/
	private $orderMinCost;


	/*
	/ @var String
	*/
	private $insertedBy;

	/*
	/ @var Int
	*/
	private $publish;

	/*
	/ @var Timestamp
	*/
	private $endTime;




	function __construct($data)
	{

		$data = (object) $data;

		$this->setId(isset($data->id) ? $data->id : 0);	

		$this->setCode(!empty($data->code) ?  $data->code  : '');

		$this->setValue(isset($data->value) ? $data->value : 0);

		$this->setOrderMinCost(isset($data->orderMinCost) ? $data->orderMinCost : 0);

		$this->setMaxDiscount(isset($data->maxDiscount) ? $data->maxDiscount : 0);

		$this->setUseCount(isset($data->useCount) ? $data->useCount : 0);

		$this->setInsertedBy(isset($data->insertedBy) ? $data->insertedBy : 0);

		$this->setPublish(!empty($data->publish) ? '1' : 0);

		$this->setEndTime(isset($data->endTime) ? $data->endTime : '');

	}

	

	public static function applyId($id) : DiscountModel
	{
		return new self(array('id'=>$id));
	}

	public static function create($data) : DiscountModel
	{
		return new self($data);
	}


	public function id() : ?int
	{
		return $this->id;
	}

	public function code() : ?String
	{
		return $this->code;
	}

	public function useCount() : ?Int
	{
		return $this->useCount;
	}

	public function value() : ?Int
	{
		return $this->value;
	}

	public function maxDiscount() : ?Float
	{
		return $this->maxDiscount;
	}

	public function orderMinCost() : ?Int
	{
		return $this->orderMinCost;
	}


	public function endTime() : ?String 
	{
		return $this->endTime;
	}

	public function publish() : ?String
	{
		return $this->publish;
	}

	public function insertedBy() : ?Int
	{
		return $this->insertedBy;
	}




	public function setId($id) : void
	{
		$this->id = $id;
	}


	public function setCode($code) : void
	{
		$this->code = $code;
	}


	public function setUseCount($useCount) : void
	{
		$this->useCount = $useCount;
	}


	public function setValue($value) : void
	{
		$this->value = $value;
	}

	public function setMaxDiscount($maxDiscount = 0) : void
	{
		$this->maxDiscount = $maxDiscount;
	}

	public function setOrderMinCost($orderMinCost) : void
	{
		$this->orderMinCost = $orderMinCost;
	}

	public function setInsertedBy($insertedBy) : void
	{
		$this->insertedBy = $insertedBy;
	}


	public function setPublish($publish = 0) : void
	{
		$this->publish = $publish;
	}

	public function setEndTime($endTime) : void
	{
		$this->endTime = $endTime;
	}


}
