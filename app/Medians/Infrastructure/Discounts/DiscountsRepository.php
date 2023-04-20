<?php

namespace Medians\Infrastructure\Discounts;

use Medians\Domain\Discounts\DiscountModel;

use Shared\dbaser\CustomController;

/**
 * Discounts class database queries
 */
class DiscountsRepository extends CustomController
{
	

	/*
	/ @var String
	*/
	protected $table = 'discounts';


	/*
	/ @var Int
	*/
	private $id;


	/*
	/ @var Int
	*/
	private $code;


	/*
	/ @var Timestamp
	*/
	private $endTime;


	/*
	/ @var Array
	*/
	private $data = array();



	/*
	// return $table String 
	*/
	public function getTable() : String
	{
		return $this->table;
	}

	
	/*
	// Set `id` 
	*/
	public function setId($id)
	{
		$this->id = $id;
	}

	/*
	// Set `code` 
	*/
	public function setCode($code)
	{
		$this->code = $code;
	}

	/*
	// Set `endTime` 
	*/
	public function setEndTime($endTime)
	{
		$this->endTime = $endTime;
	}


	/*
	// Set `data` 
	*/
	public function setData($data)
	{
		$this->data = $data;
	}


	/*
	// Find item by `id` 
	*/
	public function getById($id) 
	{

		$this->data = $this->getOne($id, 'id', null, $this->table, 'object');

		return $this->data;
	}


	
	/*
	// Find count by month
	*/
	public function getByCode($code ) : ?Object
	{

	  	return $this->getOne($code, 'code', null, $this->table, 'object');
	  	
	}


	/*
	// Find all items 
	*/
	public function getAll($limit = 1000)
	{
		return  $this->get(null, null, $limit, null, $this->table, 'object');
	}


	
	/*
	// Find count by month
	*/
	public function getByEndTime($month, $nextmonth )
	{

		$query = array( 'endTIme' =>
  			array(
  				array( 
  					date('Y-m-d H:i:s', strtotime(date($month))),  
  					date('Y-m-d H:i:s', strtotime(date($nextmonth))) 
				),
				'between'
	  		)
  		);

	  	return $this->get(null, $query, null, null, $this->table, 'object');
	  	
	}
	
	

	

	/*
	// Create item
	*/
	public function createItem(DiscountModel $DiscountModel) : DiscountsRepository
	{
		$this->data = (object) $this->data;

		$this->data->id =  $DiscountModel->id();
		$this->data->code = $DiscountModel->code();
		$this->data->value = $DiscountModel->value();
		$this->data->useCount = $DiscountModel->useCount();
		$this->data->maxDiscount = $DiscountModel->maxDiscount();
		$this->data->orderMinCost = $DiscountModel->orderMinCost();
		$this->data->endTime = $DiscountModel->endTime();
		$this->data->insertedBy = $DiscountModel->insertedBy();
		$this->data->publish = $DiscountModel->publish();

		return $this;
	}





	/*
	// Save item to database
	*/
	public function saveItem() 
	{

		// Insert data and return ID
		$this->id = $this->insert((array) $this->data, $this->table);

		// Return the OrderModel object with the new data
		return $this->getById($this->id);
	}


	/*
	// Update item to database
	*/
	public function edit() : Object
	{

		// Update data and return boolen
		$this->updated = $this->update((array) $this->data, 'id', $this->id);

		// Return the OrderModel object with the new data
		return $this->getById($this->id);
	}


	/*
	// Update  item to database " By Code "
	*/
	public function editByCode() : Object
	{

		// Update data and return boolen
		$this->updated = $this->update((array) $this->data, 'id', $this->code);

		// Return the OrderModel object with the new data
		return $this->getByCode($this->code);
	}



	/*
	// Delete item to database
	//
	// @Returns OrderModel
	*/
	public function remove() 
	{

		// Delete data and return boolen
		$this->deleted = $this->delete($this->id);

		// Return the OrderModel object with the new data
		return $this->deleted ? true : false;
	}



}