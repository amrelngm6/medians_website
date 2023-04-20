<?php

namespace Medians\Application\Auth;

use Medians\Domain\Customers\CustomerModel;

use Medians\Application\AuthInterface;

use Medians\Application\Customers\Customer;

use Medians\Application\Administrators\Admin;

use Medians\Domain\Auth\AuthCustomer;

class AuthProvider 
{

	/*
	/ @var String
	*/
	private $email;

	/*
	/ @var String
	*/
	private $password ;

	/*
	/ @var Instance
	*/
	private $repo;



	function __construct($repo)
	{

		$this->repo = $repo;
	}


	public function checkLogin($email, $password) : CustomerModel
	{

		$checkLogin = $this->repo->checkLogin($email, $password);

		if (empty($checkLogin->id()))
		{
			throw new \Exception("User credentials not valid", 1);
		}

		if (empty($checkLogin->publish()))
		{
			throw new \Exception("User account is not active", 1);
			
		}

		return $checkLogin;
	}


	public function checkSession($code) 
	{
		$AuthCustomer = new AuthCustomer();

		if (!empty ( $AuthCustomer->checkSession($code) ))
		{
			return (new Admin($AuthCustomer->checkSession($code)))->getItem();
		}
	}



	public function setSession($data) 
	{
		$AuthCustomer = new AuthCustomer();

		if ($AuthCustomer->setData($data)) 
		{
			return $AuthCustomer->checkSession($code);
		}
	}


	public function unsetSession() 
	{
		$AuthCustomer = new AuthCustomer();
		$AuthCustomer->unsetSession();
	}


	public function encrypt($value) : String 
	{
		return sha1(md5($value));

	}



}
