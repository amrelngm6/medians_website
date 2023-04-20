<?php

namespace Medians\Application\Products;

use Medians\Infrastructure\Products as Repo;

use Medians\Application\Products\Product;

use Medians\Domain\Products\StockModel;

class Stock
{

	/*
	// @var Object
	*/
	protected $repo;

	/*
	// @var StockModel
	*/
	protected $StockModel;

	/*
	// @var ProductObject
	*/
	protected $product;

	/*
	// @var Array
	*/
	protected $data = array();
	

	function __construct($data = null)
	{

		$this->data = (object) $data;

		$this->repo = new Repo\StockRepository();

	}



	public function createModel($data) 
	{
		return new StockModel($data);
	}

	public static function create($data = []) 
	{
		return new self($data);
	}

	public function setModel(StockModel $StockModel) 
	{
		$this->StockModel = $StockModel;
	}


	public function setData($data) : void
	{
		$this->data = $data;
	}


	public function getItem($id) 
	{	
		return $this->repo->getById($id);
	}

	public function getByProvider($providerId, $limit = 100) : ?Array 
	{	
		return $this->repo->getByProvider($providerId, $limit);
	}

	public function getItemStock($id, $qty = 1) : ?int 
	{	
		return $this->repo->getStock($id, $qty);
	}


	public function getItemStockObject($id, $qty = 1) : ?Object 
	{	
		return $this->repo->getStockObject($id, $qty);
	}

	public function handleModel($data)
	{
		$data->product = (new Product())->getItem($data->product) ;
		
		return new StockModel($data);
	}

	public function handleModels($query) : Array
	{
		return array_map(function($data) {

			$data = (object) $data;
			return $this->handleModel($data);

		}, $query);
	}

	public function getAll($limit = 100) : Array
	{	
		$newThis = $this;

		return array_map(function($data) use ($newThis) {

			return $newThis->handleModel($data);

		}, $this->repo->getAll($limit));
	}


	public function saveItem() : StockModel
	{
		$this->data->product = (new Product)->getItem($this->data->product);

		$this->StockModel = new StockModel($this->data);

		$this->StockModel->setId(0);	
		$this->StockModel->setTime(date('Y-m-d H:i:s'));	
		$this->StockModel->setStock($this->StockModel->startStock());	
		$this->StockModel->setInsertedBy(1);	

		$save = $this->repo->createItem($this->StockModel)->saveItem();

		(new Product())->updateStock($this->StockModel->product()->id(), ($this->StockModel->stock() + $this->StockModel->product()->stock() ) );		

		return new StockModel($save);
	}

	public function updateStock($productId, Int $qty = 1)
	{
		$this->StockModel = $this->handleModel($this->getItemStockObject($productId, $qty));

		(new Product())->updateStock($this->StockModel->product()->id(), ($this->StockModel->product()->stock() - $qty) );		

		return $this->editItem(
			array(
				'stock' => ($this->StockModel->stock() - $qty)
			)
		);
	}


	public function editItem($data) 
	{

		$this->repo->setId($this->StockModel->id());	

		$this->repo->setData($data);	

		return $this->repo->edit();
	}


	public function deleteItem($id) 
	{

		$this->repo->setId($id);	

		return $this->repo->remove();

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
