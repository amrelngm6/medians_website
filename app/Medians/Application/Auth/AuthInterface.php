<?php

namespace Medians\Application\Auth;

use Medians\Domain\Customers\CustomerModel;

interface AuthInterface
{
	// public function getTable();

	public function getById($id);

	public function getByEmail($email);

	public function checkLogin($email, $password);

	public function createItem($customerData);
}
