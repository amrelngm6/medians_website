<?php

namespace Medians\Domain\Auth;

use Medians\Domain\Customers\CustomerModel;

class AuthCustomer 
{

	/*
	/ @var Object
	*/
	private $data;

	/*
	/ @var String
	*/
	private $code = 'CustomerAuth';




	function __construct()
	{
	}



	public function setData($data) : void
	{
		$this->data = $data;

		$this->setSession($this->data);
	}


	protected function setSession($data) : void
	{
        setcookie($this->code, $data->id(), time() + (10 * 365 * 24 * 60 * 60), "/");

	}


	public function unsetSession() : void
	{
        setcookie($this->code, null, time() + (10), "/");
		unset($this->code); 
	}



	public function checkSession() 
	{
		if (!empty($_COOKIE[$this->getCode()]))
		{
			return $_COOKIE[$this->getCode()]; 

		} else {

			return null;
			
		}
	}


	protected function getCode() : String
	{
		return $this->code;
	}


}
