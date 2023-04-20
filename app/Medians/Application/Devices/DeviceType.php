<?php

namespace Medians\Application\Devices;

use Medians\Infrastructure\Devices as Repo;

use Medians\Domain\Devices\DeviceTypeModel;

use Medians\Domain\Devices\DeviceTypes as DeviceTypes;

class DeviceType
{

	/*
	// @var Object
	*/
	protected $repo;

	/*
	// @var Array
	*/
	protected $data = array();
	

	function __construct($data = null)
	{

		$this->data = (object) $data;

		$this->repo = new Repo\DevicesTypesRepository();

	}


	public function getItem($deviceId = null) : DeviceTypeModel
	{	
		return new DeviceTypeModel($this->repo->getByID($deviceId));
	}


	public function getAll() : Array
	{	

		return array_map(function($data) {

			return new DeviceTypeModel($data);

		}, $this->repo->getAll());
	}


	public function create($data = []) 
	{
		$this->data = (object) $data;

		return $this;
	}


	public function saveItem() : DeviceTypeModel
	{

		$this->DeviceTypeModel = new DeviceTypeModel($this->data);

		$this->DeviceTypeModel->setId(0);	

		return new DeviceTypeModel($this->repo->createItem($this->DeviceTypeModel)->saveItem());
	}



	public function editItem() : DeviceTypeModel
	{

		$this->DeviceTypeModel = new DeviceTypeModel($this->data);

		$this->DeviceTypeModel->setId($this->DeviceTypeModel->id());	
		$this->repo->setId($this->DeviceTypeModel->id());	

		return $this->repo->createItem($this->DeviceTypeModel)->edit();

	}

	public function deleteItem() : bool
	{

		$this->DeviceTypeModel = new DeviceTypeModel($this->data);

		$this->repo->setId($this->DeviceTypeModel->id());	

		if ($this->repo->remove())
		{
			return true;
		} 

		return false;

	}

	public function validate() 
	{

		if (empty($this->data->title))
		{
			throw new \Exception("Empty title", 1);
		}

	}

}
