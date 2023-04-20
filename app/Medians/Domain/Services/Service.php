<?php

namespace Medians\Domain\Services;


class Service 
{

	/*
	/ @var int
	*/
	private $id;

	/*
	/ @var String
	*/
	private $name;

	/*
	/ @var Int
	*/
	private $status;


	function __construct($data)
	{

		$data = (object) $data;


		$this->setId(isset($data->id) ? $data->id : 0);	

		$this->setName(isset($data->name) ? $data->name : '');

		$this->setStatus(!empty($data->status) ? $data->status : 0) ;

	}

	


	public function setId($id) : void
	{
		$this->id = $id;
	}

	public function setName($name) : void
	{
		$this->name = $name;
	}


	public function setStatus($status = '0') 
	{
		$this->status = $status;
	}



}
