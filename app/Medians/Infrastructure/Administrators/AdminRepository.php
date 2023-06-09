<?php

namespace Medians\Infrastructure\Administrators;

use Medians\Domain\Customers\CustomerModel;
use Medians\Application\Auth\AuthInterface;

use Shared\dbaser\CustomController as CustomController;

class AdminRepository extends CustomController implements AuthInterface
{

	/*
	/ @var String
	*/
	protected $table = 'administrators';


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


	public function getByID($customerId)
	{
		$this->id = $customerId;

		$this->data = $this->getOne($this->id, 'id', null, $this->table, 'object');

		return new CustomerModel($this->data);

	}


	public function getByEmail($email)
	{
		$this->email = $email;

		$this->data = $this->getOne($this->email, 'email', null, $this->table, 'object');

		return new CustomerModel($this->data);
	}


	public function checkLogin($email, $password)
	{
		$this->email = $email;
		$this->password = $password;

		$this->data = $this->getOne(null, array('password'=> $this->password, 'email' => $this->email), null, $this->table, 'object');

		return new CustomerModel($this->data);
	}



	public function createItem($customerData) 
	{
				
		$data['id'] = $customerData->getId();
		$data['fullname'] = $customerData->getFullname();
		$data['email'] = $customerData->getEmail();
		$data['publish'] = $customerData->getPublish();

		// Insert data and return ID
		$this->newItem = parent::create($data);

		// Return the CustomerModel object with the new data
		return new CustomerModel($this->find($this->newItem->id));
	}


}
