<?php

namespace Medians\Application\Discounts;

use Medians\Infrastructure\Discounts as Repo;

use Medians\Domain\Discounts\DiscountModel;

class Discount
{

	/*
	// @var Object
	*/
	protected $repo;

	/*
	// @var DiscountModel
	*/
	protected $DiscountModel;

	
	/*
	// @var Array
	*/
	protected $data = array();

	

	function __construct($data = null)
	{

		$this->data = (object) $data;

		$this->setModel($this->createModel($this->data));
		
		$this->repo = new Repo\DiscountsRepository();
	}


	public static function createModel($DiscountObject) : DiscountModel
	{
		return DiscountModel::create($DiscountObject);
	}

	public static function create($DiscountObject) : Discount
	{
		return new self($DiscountObject);
	}


	public function validatePublishModel($DiscountObject) 
	{
		if (!empty($DiscountObject->publish))
		{
			return $this->createModel($DiscountObject);
		}
	}


	public function getItem($id = 0) 
	{
		return $this->repo->getById($id);
	}

	public function getByCode($code) 
	{
		return $this->repo->getByCode($code);
	}


	public function getAll($limit = null, $publish = null) : Array
	{
		return array_filter(array_map(function($data) use ($publish) {
				return $publish ? $this->validatePublishModel($data) :  $this->createModel($data);
		}, $this->repo->getAll( $limit )));
	}


	public function setModel(DiscountModel $DiscountModel) : void
	{

		$this->DiscountModel = $DiscountModel;
	}


	public function saveItem() : DiscountModel
	{

		$this->DiscountModel->setId(0);	

		$this->DiscountModel->setEndTime(date("Y-m-d H:i:s", strtotime(date($this->DiscountModel->endTime()))));	
		$this->DiscountModel->setInsertedBy(1);	
		$this->DiscountModel->setPublish(1);	

		$this->repo = $this->repo->createItem($this->DiscountModel);	

		return $this->createModel($this->repo->saveItem());
	}


	public function editItem() : DiscountModel
	{

		$this->DiscountModel->setId($this->DiscountModel->id());	

		return $this->executeEdit();
	}


	public function executeEdit() : DiscountModel
	{

		$this->repo = $this->repo->createItem($this->DiscountModel);	
		$this->repo->setId($this->DiscountModel->id());	

		return $this->createModel($this->repo->edit());

	}


	public function deleteItem($id = 0) 
	{

		$this->repo->setId($id);	

		return $this->repo->remove();

	}







	public function generateCode()
	{
		return 'D-'.rand(999, 9999);
	} 

	public function validate() 
	{

		if (empty($this->DiscountModel->code()) || empty($this->getByCode($this->data->code)))
		{
			$this->DiscountModel->setCode($this->generateCode());
		}

		if (empty($this->data->value))
		{
			throw new \Exception("Empty value", 1);
		}
	}


	public function costWithDiscount($OrderModel) : ?Float
	{
		$this->setModel($this->createModel($OrderModel->discountCode()));

		return ($OrderModel->cost() - ( 15 * ( $this->DiscountModel->value() / 100 ) ));
	}


	public function validateCodeWithOrder($code, $OrderModel) : DiscountModel
	{
		
		$codeObject = $this->getByCode($code);

		if (empty($codeObject->publish))
		{
			throw new \Exception("Error: code not valid", 1);
		}

		// Set the DiscountModel data
		$this->setModel($this->createModel($codeObject));


		// Check if order minimum cost is valid 
		if ($this->DiscountModel->orderMinCost() > $OrderModel->cost())
		{
			throw new \Exception("Error: Minimum cost is " . $this->DiscountModel->orderMinCost(), 1);
		}

		// Check if code endTime is passed
		if (date("Y-m-d H:i:s") > $this->DiscountModel->endTime())
		{
			throw new \Exception("Error: Code is expired" , 1);
		}

		return $this->DiscountModel;

	}


}
