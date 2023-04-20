<?php

namespace Medians\Infrastructure\Customers;

use Medians\Domain\Customers\CustomerModel;
use Medians\Application\Auth\AuthInterface;

use Shared\dbaser\CustomController as CustomController;

class CustomerRepository extends CustomController  implements AuthInterface
{

	/*
	/ @var String
	*/
	protected $table = 'customers';


	/*
	/ @var Int
	*/
	private $id;


	/*
	/ @var Array
	*/
	private $data = array();



	function __construct()
	{
	}

	/*
	// return $table String 
	*/
	public function getTable() : String
	{
		return $this->table;
	}


	public function getById($customerId)
	{
		$this->id = $customerId;

		$this->data = $this->find($this->id);

		return new CustomerModel($this->data);

	}


	public function getByEmail($email)
	{
		$this->email = $email;

		$this->data = $this->where($this->email, 'email')->first();

		return new CustomerModel($this->data);
	}


	public function checkLogin($email, $password)
	{
		$this->email = $email;
		$this->password = $password;

		$this->data = '';

		return new CustomerModel($this->data);
	}



	public function createItem($customerData)
	{
				
		$this->data['id'] = $customerData->id();

		// Insert data and return ID
		return $this;

	}

	public function saveItem()
	{
		$this->id = $this->insert($this->data, $this->table);

		// Return the CustomerModel object with the new data
		return new CustomerModel($this->getOne($this->id, 'id', null, $this->table, 'object'));

	}

}
