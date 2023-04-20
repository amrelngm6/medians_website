<?php

namespace Medians\Application\Providers;

use Medians\Infrastructure\Providers as Repo;

use Medians\Domain\Providers\ProviderModel;


class Provider
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
		
		$this->repo = new Repo\ProviderRepository();
	}


	public static function createModel($ProductObject) : ProviderModel
	{
		return new ProviderModel($ProductObject);
	}


	public function setModel(ProviderModel $ProviderModel) : void
	{

		$this->ProviderModel = $ProviderModel;
	}


	public function validatePublishModel($ProductObject) 
	{
		if (!empty($ProductObject->publish))
		{
			return ProviderModel::create($ProductObject);
		}
	}


	public function getItem($id = 0) 
	{
		return $this->repo->getById($id);
	}


	public function getAll($limit = null, $publish = null) : Array
	{
		return array_filter(array_map(function($data) use ($publish) {
				return $publish ? $this->validatePublishModel($data) :  $this->createModel($data);
		}, $this->repo->getAll( $limit )));
	}




	public function saveItem() : ProviderModel
	{

		$this->ProviderModel->setId(0);	

		return $this->repo->createItem($this->ProviderModel)->saveItem();
	}


	public function editItem() : ProviderModel
	{

		$this->ProviderModel->setId($this->ProviderModel->id());	

		return $this->executeEdit();
	}


	public function executeEdit() : ProviderModel
	{

		$this->repo = $this->repo->createItem($this->ProviderModel);	

		$this->repo->setId($this->ProviderModel->id());	

		return $this->repo->edit();

	}


	public function deleteItem($id = 0) 
	{

		$this->repo->setId($id);	

		return $this->repo->remove();

	}







	public function updateStock($id, $newVal) : ProviderModel
	{

		$this->ProviderModel = $this->createModel($this->getItem($id));	
		$this->ProviderModel->setStock( $newVal );	

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
