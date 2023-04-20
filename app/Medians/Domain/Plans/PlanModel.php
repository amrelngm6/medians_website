<?php

namespace Medians\Domain\Plans;


class PlanModel 
{

	/*
	/ @var int
	*/
	private $id;

	/*
	/ @var String
	*/
	private $title;

	/*
	/ @var Int
	*/
	private $planType;

	/*
	/ @var String
	*/
	private $description;

	/*
	/ @var String
	*/
	private $insertedBy;

	/*
	/ @var Int
	*/
	private $publish;




	function __construct($data)
	{

		$data = (object) $data;

		$this->setId(isset($data->id) ? $data->id : 0);	

		$this->setTitle(isset($data->title) ?  $data->title  : '');

		$this->setPlanType(isset($data->planType) ? $data->planType : 0);

		$this->setDescription(isset($data->description) ? $data->description : 0);

		$this->setInsertedBy(isset($data->insertedBy) ? $data->insertedBy : 0);

		$this->setPublish(isset($data->publish) ? $data->publish : 0);

	}

	

	public static function applyId($id) : PlanModel
	{
		return new self(array('id'=>$id));
	}

	public static function create($data) : PlanModel
	{
		return new self($data);
	}


	public function id() : ?int
	{
		return $this->id;
	}

	public function title() : ?String
	{
		return $this->title;
	}

	public function description() : ?String
	{
		return $this->description;
	}

	public function planType() : ?Int
	{
		return $this->planType;
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


	public function setTitle($title) : void
	{
		$this->title = $title;
	}


	public function setPlanType($planType) : void
	{
		$this->planType = $planType;
	}


	public function setDescription($description) : void
	{
		$this->description = $description;
	}


	public function setInsertedBy($insertedBy) : void
	{
		$this->insertedBy = $insertedBy;
	}


	public function setPublish($publish = 0) : void
	{
		$this->publish = $publish;
	}



}
