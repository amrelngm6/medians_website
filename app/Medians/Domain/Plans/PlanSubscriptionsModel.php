<?php

namespace Medians\Domain\Plans;


class PlanPricesModel 
{

	/*
	/ @var int
	*/
	private $id;


	/*
	/ @var Int
	*/
	private $planId;


	/*
	/ @var Int
	*/
	private $months;


	/*
	/ @var Float
	*/
	private $cost;




	function __construct($data)
	{

		$data = (object) $data;

		$this->setId(isset($data->id) ? $data->id : 0);	

		$this->setPlanId(isset($data->planId) ? $data->planId : 0);

		$this->setMonths(isset($data->months) ? $data->months : 0);

		$this->setCost(isset($data->cost) ? $data->cost : 0);

		$this->setPublish(!empty($data->publish) ? '1' : 0);

	}

	

	public static function applyId($id) : PlanPricesModel
	{
		return new self(array('id'=>$id));
	}

	public static function create($data) : PlanPricesModel
	{
		return new self($data);
	}


	public function id() : ?int
	{
		return $this->id;
	}

	public function planId() : ?Int
	{
		return $this->planId;
	}

	public function months() : ?Int
	{
		return $this->months;
	}

	public function cost() : ?Float
	{
		return $this->cost;
	}



	public function setId($id) : void
	{
		$this->id = $id;
	}


	public function setPlanId($planId) : void
	{
		$this->planId = $planId;
	}



	public function setMonths($months) : void
	{
		$this->months = $months;
	}


	public function setCost($cost = 0) : void
	{
		$this->cost = $cost;
	}



}
