<?php

namespace Medians\Application\Prices;

use Medians\Infrastructure\Prices as Repo;

use Medians\Domain\Prices\PricesModel;

class Prices
{

	/*
	// @var Int
	*/
	protected $device;

	/*
	// @var Object
	*/
	protected $repo;

	/*
	// @var Array
	*/
	protected $data = array();
	

	function __construct($deviceId = 0, $data = null)
	{

		$this->data = (object) $data;

		$this->device = $deviceId;

		$this->repo = new Repo\PricesRepository();

	}


	public function getItem($deviceId = null) : PricesModel
	{	
		return new PricesModel($deviceId,  $this->repo->getByDevice($deviceId));
	}


	public static function create($deviceId = 0, $data = []) 
	{
		return new self($deviceId, $data);
	}


	public function saveItem() : PricesModel
	{

		$this->PricesModel = $this->getItem($this->device);
		$this->PricesModel->setDevice($this->device);
		$this->PricesModel->setSinglePrice($this->data->single_price);
		$this->PricesModel->setMultiPrice($this->data->multi_price);

		if (!empty($this->PricesModel->id()))
		{
			return $this->editItem();
		}

		$this->repo->setDevice($this->PricesModel->device()->id());	

		$this->PricesModel->setId(0);	

		return $this->repo->createItem($this->PricesModel)->saveItem();
	}



	public function editItem() : PricesModel
	{

		if (empty($this->PricesModel->device()->id()))
		{
			throw new \Exception("Error device id");
		}
		
		$this->PricesModel->setId($this->PricesModel->device()->id());	
		$this->repo->setDevice($this->PricesModel->device()->id());	


		return $this->repo->createItem($this->PricesModel)->edit();

	}

	public function deleteItem() : PricesModel
	{

		$this->PricesModel = $this->getItem($this->device);

		$this->repo->setDevice($this->PricesModel->device()->id());	

		return $this->repo->remove();

	}

	public function validate() 
	{

		if (empty($this->data->title))
		{
			throw new \Exception("Empty title", 1);
		}

		if (empty($this->data->type))
		{
			throw new \Exception("Empty type", 1);
		}

	}

}
