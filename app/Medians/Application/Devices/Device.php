<?php

namespace Medians\Application\Devices;

use Medians\Application as apps;

use Medians\Infrastructure\Devices as Repo;

use Medians\Domain\Devices as Devices;

use Medians\Domain\Devices\DeviceModel as DeviceModel;

class Device 
{

	/*
	// @var Object
	*/
	protected $repo;

	/*
	// @var DeviceModel
	*/
	protected $DeviceModel;

	/*
	// @var Array
	*/
	protected $data = array();
	

	function __construct($data = null)
	{

		$this->data = (object) $data;

		$this->repo = new Repo\DevicesRepository();

	    // Set ProductOrder 
	    $this->ProductOrderModel = new apps\Orders\ProductOrder(null);

	    // Set DeviceOrder
	    $this->DeviceOrder = new apps\Orders\DeviceOrder(null);

	    // Set PricesModel
	    $this->PricesModel = new apps\Prices\Prices();

	    // Set PricesModel
	    $this->DeviceTypeModel = new apps\Devices\DeviceType();

	}



	public function show(int $id , $app, $twig) 
	{

	    // Set Device model item with data by 'id'
	    $this->DeviceModel =  $this->createModel($this->getItem($id));


	    // Set DeviceType by type->id 
	    $this->DeviceModel->typeData = $this->DeviceTypeModel->getItem($this->DeviceModel->type()->id());


	    // Set DeviceOrderModel by type->id 
	    $this->DeviceOrderModel =  $this->DeviceOrder->createModel($this->DeviceOrder->getItem($this->DeviceModel->id()));
	    $this->DeviceOrderModel->setDevice($this->DeviceModel);

	    $this->DeviceModel->currentOrder = $this->DeviceOrderModel;

	    $this->DeviceModel->currentOrder->device()->prices = $this->PricesModel->getItem($this->DeviceModel->currentOrder->device()->id());

	    $this->DeviceModel->currentOrder->totalCost = $this->DeviceOrder->calculate($this->DeviceModel->currentOrder->deviceCost(), $this->DeviceModel->currentOrder->startTime(), date('Y-m-d H:i:s'));

	    $this->DeviceModel->currentOrder->products = $this->ProductOrderModel->handleModels($this->ProductOrderModel->getDeviceActiveItems($this->DeviceModel->id()));

	    $this->DeviceModel->currentOrder->productsCost = $this->ProductOrderModel->getDeviceActiveCost($this->DeviceModel->id());

	    return $twig->render('views/admin/devices/device.html.twig', [
	        'title' => 'Edit device',
	        'typesList' => $this->DeviceTypeModel->getAll(),
	        'app' => $app,
	        'device' => $this->DeviceModel
	    ]);
		return new self($data);
	}



	public function edit(int $id , $app, $twig) 
	{

		$this->DeviceModel = $this->DeviceModel->find($id);

	    $this->DeviceModel->prices = ($this->PricesModel($this->DeviceModel->id()))->getItem($this->DeviceModel->id());

	    return $twig->render('views/admin/forms/edit_device.html.twig', [
	        'title' => 'Edit device',
	        'typesList' => $this->DeviceTypeModel->getAll(),
	        'app' => $app,
	        'device' => $this->DeviceModel
	    ]);
	}



	public static function create($data = []) 
	{
		return new self($data);
	}

	public function createModel($DeviceObject) : DeviceModel
	{	
		return new DeviceModel($DeviceObject);
	}

	public function validatePublishModel($DeviceObject) 
	{
		if (!empty($DeviceObject->publish()))
		{
			return $this->createModel($DeviceObject);
		}
	}

	public function getItemObject($deviceId = null) : DeviceModel
	{	
		return $this->repo->getById($deviceId);
	}

	public function getItem($deviceId = null) : DeviceModel
	{	
		return $this->createModel($this->repo->getByID($deviceId));
	}


	public function getByProvider($providerId = null)
	{	
		return array_filter(array_map(function($data) {

			return $this->createModel($data); 

		}, $this->repo->getByProvider($providerId)));
	}



	public function getAll($limit = 100, $publish = null) : Array
	{	

		return array_filter(array_map(function($data) use ($publish) {

			return empty($publish) ? new DeviceModel($data) : $this->validatePublishModel($this->createModel($data)); 

		}, $this->repo->getAll($limit)));
	}



	public function saveItem($providerId = 0) : DeviceModel
	{

		$this->DeviceModel = new DeviceModel($this->data);

		$this->DeviceModel->setId(0);	

		return new DeviceModel($this->repo->createItem($this->DeviceModel)->saveItem());
	}


	public function setData($deviceId) : DeviceModel
	{
		$this->DeviceModel = $this->getItem($deviceId);

		return $this->DeviceModel;
	}

	public function editItem($deviceId) : DeviceModel
	{
		$this->DeviceModel = $this->setData($deviceId);

		$this->DeviceModel->setId($this->DeviceModel->id());	
		$this->DeviceModel->setProviderId($this->DeviceModel->providerId());	
		$this->DeviceModel->setType(isset($this->data->type) ? $this->data->type : $this->DeviceModel->type());	
		$this->DeviceModel->setTitle(isset($this->data->title) ? $this->data->title : $this->DeviceModel->title());	
		$this->DeviceModel->setPlaying(isset($this->data->playing) ? $this->data->playing : $this->DeviceModel->playing());	
		$this->DeviceModel->setPublish(isset($this->data->publish) ? 1 : 0);	
		$this->repo->setId($this->DeviceModel->id());	


		return $this->repo->createItem($this->DeviceModel)->edit();

	}

	public function deleteItem() 
	{

		$this->repo->setId($this->DeviceModel->id());	

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





    /**
     * Display a listing of the resource.
     *
     * @return \
     */

}
