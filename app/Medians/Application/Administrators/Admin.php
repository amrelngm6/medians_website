<?php

namespace Medians\Application\Administrators;

use Medians\Infrastructure\Administrators as Repo;

use Medians\Domain\Customers\CustomerModel as CustomerModel;

class Admin
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

	/*
	/ @var new CustomerRepository
	*/
	private $repo;


	function __construct($id)
	{

		$this->id = $id;	

		$this->repo = new Repo\AdminRepository();

	}


	public function checkLogin()
	{
		return $this->repo->getByID($this->id);
	}


	public function getItem() : CustomerModel
	{

		return $this->repo->getByID($this->id);
	}


	public function createItem(CustomerModel $customer) : CustomerModel
	{

		$customer->setId(0);	

		return new CustomerModel($this->repo->createItez($customer));

	}

}
