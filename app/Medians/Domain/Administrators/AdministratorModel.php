<?php

namespace Medians\Domain\Administrators;

class AdministratorModel 
{

	/*
	/ @var int
	*/
	private $id;

	/*
	/ @var String
	*/
	private $fullname;

	/*
	/ @var String
	*/
	private $email;

	/*
	/ @var int
	*/
	private $publish;





	function __construct($data)
	{

		$data = (object) $data;

		$this->id = isset($data->id) ? $data->id : 0;
		
		$this->fullname = isset($data->fullname) ? $data->fullname : '';
		
		$this->email = isset($data->email) ? $data->email : '';
		
		$this->publish = isset($data->publish) ? '1' : '0';
	
	}


	public static function create($data) : AdministratorModel
	{
		return self($data);
	}

	public function id() : String
	{
		return $this->id;
	}


	public function fullname() : String
	{
		return $this->fullname;
	}


	public function email() : String
	{
		return $this->email;
	}

	public function publish() : ?String
	{
		return $this->publish;
	}


	public function setId($id) : void
	{
		$this->id = $id;
	}

	public function setFullname($fullname) : void
	{
		$this->fullname = $fullname;
	}

	public function setEmail($email) : void
	{
		$this->email = $email;
	}

	public function setPublish($publish) : void
	{
		$this->publish = $publish;
	}


}
