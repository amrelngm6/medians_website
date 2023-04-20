<?php

namespace Medians\Domain\Customers;

class CustomerModel 
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
	/ @var String
	*/
	private $password;

	/*
	/ @var String
	*/
	private $providerId;

	/*
	/ @var int
	*/
	private $publish;





	function __construct($data)
	{

		$data = (object) $data;

		$this->setId(isset($data->id) ? $data->id : 0);
		
		$this->setFullname( isset($data->fullname) ? $data->fullname : '');

		$this->setEmail( isset($data->email) ? $data->email : '');

		$this->setProviderId( isset($data->providerId) ? $data->providerId : 0);

		$this->setPublish( !empty($data->publish) ? '1' : '0');
	
	}


	public static function create($data) : CustomerModel
	{
		return new self($data);
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


	public function password() : String
	{
		return $this->password;
	}

	public function providerId() : ?Int
	{
		return $this->providerId;
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

	public function setPassword($password) : void
	{
		$this->password = $password;
	}

	public function setProviderId($providerId) : void
	{
		$this->providerId = $providerId;
	}

	public function setPublish($publish) : void
	{
		$this->publish = $publish;
	}


}
