<?php

namespace Medians\Application\Orders;

use Medians\Infrastructure\Orders as Repo;

use Medians\Application\Products\Product;

use Medians\Application\Products\Stock;

use Medians\Application\Devices\Device;

use Medians\Domain\Orders\ProductOrderModel;

class ProductOrder
{

	/*
	// @var Object
	*/
	protected $repo;

	/*
	// @var ProductOrderModel
	*/
	protected $ProductOrderModel;

	/*
	// @var Array
	*/
	protected $data = array();
	

	function __construct($data = null)
	{

		$this->data = (object) $data;

		$this->repo = new Repo\ProductOrdersRepository();

	}



	public function createModel($data) 
	{
		return new ProductOrderModel($data);
	}

	public static function create($data = []) 
	{
		return new self($data);
	}

	public function setModel(ProductOrderModel $ProductOrderModel) 
	{
		$this->ProductOrderModel = $ProductOrderModel;
	}

	public function getItem($id) 
	{	
		return $this->repo->getById($id);
	}

	public function getDeviceActiveItems($deviceId) : Array 
	{	
		return $this->repo->getByDeviceActive($deviceId);
	}

	public function getDeviceActiveCost($deviceId) : ?int 
	{	
		return $this->repo->getByDeviceCost($deviceId);
	}

	public function getByOrderCode($orderCode)  : Array
	{	
		return $this->repo->getByOrderCode($orderCode);
	}

	public function getSalesByDay($providerId, $day) 
	{
		return $this->repo->getSalesByDay($providerId , $day, date('Y-m-d', strtotime("+1 day", strtotime($day))) );
	}

	public function getAll($providerId, $limit = 100) : Array
	{	
		return $this->handleModels($this->repo->getAll($providerId, $limit));
	}

	public function handleModel($data) : ProductOrderModel
	{

		$data->product = (new Product)->getItem($data->product) ;
		$data->device = (new Device)->getItem($data->device) ;
		
		return new ProductOrderModel($data);
	}

	public function handleModels($array) : Array
	{

		return array_map(function($data) {

			$data->product = (new Product)->getItem($data->product) ;
			$data->device = (new Device)->getItem($data->device) ;
			
			return new ProductOrderModel($data);

		}, $array);

	}


	public function handle($data)
	{
		$this->data = (object) array();

		$this->data->device = (new Device)->getItem($data['id']);

		$this->data->providerId = $data['providerId'];

		foreach ($data['products'] as $key => $value)
		{
			$this->data->product = (new Product)->getItem($key);
			$this->data->qty = $value;

			if (empty($this->saveItem()->id()))
			{
				throw new Exception("Error adding item". $value, 1);
			}
		}

		return true;

	} 


	public function saveItem() : ProductOrderModel
	{

		$this->ProductOrderModel = new ProductOrderModel($this->data);

		$this->ProductOrderModel->setId(0);	
		$this->ProductOrderModel->setTime(date('Y-m-d H:i:s'));	
		$this->ProductOrderModel->setInsertedBy(1);	
		$this->ProductOrderModel->setStatus('active');	

		return new ProductOrderModel($this->repo->createItem($this->ProductOrderModel)->saveItem());
	}


	public function updateProductStock($orderCode) : void
	{
		
		$Stock = new Stock();

		foreach($this->getByOrderCode($orderCode) as $key => $value )
		{
			$Stock->updateStock($value->product, $value->qty);
		}

	}



	public function editItemStatus($data, $query, $orderCode)
	{
		// Update products stock 
		if ($this->editItem($data, $query))
		{
			// Update products stock 
			$this->updateProductStock($orderCode);
		}
		
	} 


	public function editItem($data, $query) 
	{
		
		$this->repo->setData($data);

		return $this->repo->edit($query);
	}


	public function deleteItem($id) 
	{

		$this->repo->setId($id);	

		return $this->repo->remove();

	}


	public function filterData($params)
	{
		$products =  array();

		foreach ($params as $key => $value)
		{
			if (isset($value['active']))
			{
				$products[$key] = $value['qty'];
			}
		}

		return $products;
	} 
	

	public function validate() 
	{

		if (empty($this->data->product))
		{
			throw new \Exception("Empty product", 1);
		}

		if (empty($this->data->startStock))
		{
			throw new \Exception("Empty stock", 1);
		}

	}

}
