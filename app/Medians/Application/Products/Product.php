<?php

namespace Medians\Application\Products;

use Medians\Infrastructure\Products as Repo;

use Medians\Domain\Products\ProductModel;

class Product
{

	/*
	// @var Object
	*/
	protected $repo;

	/*
	// @var Int
	*/
	protected $id;
	
	/*
	// @var Array
	*/
	protected $data = array();

	

	function __construct($data = null)
	{

		$this->data = (object) $data;

		$this->setModel($this->createModel($this->data));
		
		$this->repo = new Repo\ProductsRepository();
	}


	public static function createModel($ProductObject) : ProductModel
	{

		return ProductModel::create($ProductObject);
	}


	public function validatePublishModel($ProductObject) 
	{
		if (!empty($ProductObject->publish))
		{
			return ProductModel::create($ProductObject);
		}
	}


	public function getItem($id = 0) 
	{
		return $this->repo->getById($id);
	}


	public function getByProvider($providerId = 0) 
	{
		return $this->repo->getByProvider($providerId);
	}


	public function getAll($limit = null, $publish = null) : Array
	{
		return array_filter(array_map(function($data) use ($publish) {
				return $publish ? $this->validatePublishModel($data) :  $this->createModel($data);
		}, $this->repo->getAll( $limit )));
	}


	public function setModel(ProductModel $ProductModel) : void
	{

		$this->ProductModel = $ProductModel;
	}


	public function saveItem() : ProductModel
	{

		$this->ProductModel->setId(0);	

		$this->repo = $this->repo->createItem($this->ProductModel);	

		return ProductModel::create($this->repo->saveItem());
	}


	public function editItem() : ProductModel
	{

		$this->ProductModel->setId($this->ProductModel->id());	

		return $this->executeEdit();
	}


	public function executeEdit() : ProductModel
	{

		$this->repo = $this->repo->createItem($this->ProductModel);	
		$this->repo->setId($this->ProductModel->id());	


		$this->respone = $this->repo->edit();	
		
		return ProductModel::create($this->respone);

	}


	public function deleteItem($id = 0) 
	{

		$this->repo->setId($id);	

		return $this->repo->remove();

	}







	public function updateStock($id, $newVal) : ProductModel
	{

		$this->ProductModel = $this->createModel($this->getItem($id));	
		$this->ProductModel->setStock( $newVal );	

		return $this->executeEdit();
	}


	public function validate() 
	{

		if (empty($this->data->title))
		{
			throw new \Exception("Empty title", 1);
		}

		if (empty($this->data->price))
		{
			throw new \Exception("Empty price", 1);
		}

	}


}
