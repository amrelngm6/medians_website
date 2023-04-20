<?php

namespace Medians\Application\Customers;

use Medians\Infrastructure\Customers as Repo;

use Medians\Domain\Customers as Customers;

use Medians\Domain\Customers\CustomerModel as CustomerModel;

class Customer
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

		$this->repo = new Repo\CustomerRepository();

	}


	public function getItem() : CustomerModel
	{

		return $this->repo->getById($this->id);
	}


	public function createItem(CustomerModel $customer) : CustomerModel
	{

		$customer->setId(0);	

		return new Customers\CustomerModel($this->repo->createItem($customer));

	}

}
