<?php

namespace Medians\Application\Orders;

use Medians\Infrastructure\Orders as Repo;
use Medians\Application\Devices\Device;
use Medians\Application\Calculator\Calculator;
use Medians\Application\Prices\Prices;
use Medians\Application\Discounts\Discount;

use Medians\Domain\Orders\OrderModel;

class Order
{

	/*
	// @var Object
	*/
	protected $repo;

	/*
	// @var String
	*/
	protected $code;
	
	/*
	// @var String
	*/
	protected $orderModel;
	
	/*
	// @var Array
	*/
	protected $data = array();

	

	function __construct()
	{

		$this->repo = new Repo\OrdersRepository();
	}


	public function repo() : Repo\OrdersRepository
	{

		return $this->repo;
	}


	public function createModel($OrderModel) : OrderModel
	{

		return OrderModel::create($OrderModel);
	}


	public function getItem($id) 
	{

		return $this->repo->getById($id);
	}

	public function getByCode($code) 
	{

		return $this->repo->getByCode($code);
	}


	public function getByMonth(?Int $providerId, $month, $nextmonth) : Array
	{
		return $this->repo->getByMonth($providerId, $month, $nextmonth);
	}


	public function getByLastWeek($providerId ) : Array
	{

		return $this->repo->getByDate($providerId , date('Y-m-d', strtotime('-1 week') ), date('Y-m-d', strtotime('+1 day') ) );
	}

	public function getByLastDay($providerId) : Array
	{
		return $this->repo->getByDate($providerId, date('Y-m-d' ), date('Y-m-d', strtotime('+1 day') )  );
	}

	public function getSalesByDay($providerId, $day) 
	{
		return $this->repo->getSalesByDay($providerId , $day, date('Y-m-d', strtotime("+1 day", strtotime($day))) );
	}

	public function getAll($providerId, $limit = null) : Array
	{

		return array_map(function($data){
			$data->device = ((new Device())->getItem($data->device));
			return  $this->createModel($data);
		}, $this->repo->getAll( $providerId, $limit ));
	}



	public function setModel(OrderModel $OrderModel) : void
	{

		$this->OrderModel = $OrderModel;
	}

	public function setOrderedBy($orderedBy) : void
	{
		$this->OrderModel->setOrderedBy($orderedBy);
	}

	public function setProviderId($providerId) : void
	{
		$this->OrderModel->setProviderId($providerId);
	}

	public function setCode($code) : void
	{

		$this->code = $code;
	}


	public function setOrderPaid() : OrderModel
	{

		$this->OrderModel->setStatus('paid');	
		$this->repo = $this->repo->createItem($this->OrderModel);	
		$this->repo->setCode($this->OrderModel->code());	
		$this->repo->setField('device', $this->OrderModel->device()->id());	

		return  $this->createModel($this->repo->editByCode());	

	}

	public function checkDiscountUsed($discountCode) : Array
	{
		return $this->repo->getByDiscountCode($discountCode);
	}


	public function handleOrderDiscount($discountCode) : OrderModel
	{
		
		$this->discountCodeObject = (new Discount())->validateCodeWithOrder($discountCode, $this->OrderModel);

		if (count($this->checkDiscountUsed($this->discountCodeObject->code())) >= $this->discountCodeObject->useCount())
		{
			throw new \Exception("Error: This code usage limit has exceeded", 1);
		}

        $this->OrderModel->setDiscountCode($this->discountCodeObject->code());

        $this->OrderModel->setTotalCost($this->costWithDiscount($this->OrderModel));

        $this->editItem();


        return $this->OrderModel;

	}


	public function handle() : OrderModel
	{

		$this->OrderModel->setTime(date('Y-m-d H:i:s'));

		if (!empty($this->OrderModel->status()) && $this->OrderModel->status() == 'active')
		{
			return $this->editItem();
		} else {

			return $this->saveItem();
		}
	}

	public function saveItem() : OrderModel
	{

		$this->OrderModel->setId(0);	
        $this->OrderModel->setTotalCost($this->OrderModel->cost());

		$this->repo = $this->repo->createItem($this->OrderModel);	
		$this->repo->setDevice($this->OrderModel->device()->id());	

		$this->respone = $this->repo->saveItem();	
		$this->respone->device = ((new Device())->getItem($this->respone->device));	
		return OrderModel::create($this->respone);
	}


	public function editItem() : OrderModel
	{

		$this->OrderModel->setId($this->OrderModel->id());	

		return $this->executeEdit();
	}

	public function executeEdit() : OrderModel
	{

		$this->repo = $this->repo->createItem($this->OrderModel);	
		$this->repo->setId($this->OrderModel->id());	

		$this->respone = $this->repo->edit();	
		$this->respone->device = ((new Device())->getItem($this->respone->device));	
		return OrderModel::create($this->respone);

	}


	public function deleteItem() : OrderModel
	{

		$this->repo->setDevice($this->OrderModel->device()->id());	

		return $this->repo->remove();

	}

	public function validate() 
	{

		if (empty($this->data->device))
		{
			throw new \Exception("Empty device id ", 1);
		}

	}








	/*
	// Calculate Discount
	*/
	public function costWithDiscount(OrderModel $OrderModel) : ?Float
	{
		return ( new Discount)->costWithDiscount($OrderModel);
	}


	/*
	// Count methods
	*/
	public function getCostByMonth($providerId, $month = null)
	{
		$month = empty($month) ? date('Y-m') : $month;
		$nextmonth = date('Y-m', strtotime('+1 month', strtotime($month))) ;

		return $this->repo->getCostByMonth($providerId, $month, $nextmonth);

	} 





}
