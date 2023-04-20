<?php

namespace Medians\Application\Settings;

use Medians\Infrastructure\Settings as Repo;

use Medians\Domain\Settings\SettingsModel;

class Settings
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

		$this->repo = new Repo\SettingsRepository();

	}


	public function getItem($code = null) : SettingsModel
	{	
		return $this->repo->getByCode($code);
	}


	public function getAll() : Array
	{	

		return array_map(function($data) {

			return new SettingsModel($data);

		}, $this->repo->getAll());
	}


	public function create($data = []) 
	{
		$this->data = (object) $data;

		return $this;
	}



	/*
	// Return the Settings
	*/
	public function updateSettings() 
	{

		foreach ($this->data as $code => $value)
		{

			$this->SettingsModel = $this->getItem($code);
			
			if ($this->SettingsModel->code())
			{
				$this->updated = $this->deleteItem($code);
			} else {
				$this->updated = true;
			}

			if (isset($this->updated))
			{
				$this->saveItem($code, $value);
			}
		}
	}




	public function saveItem($code, $value) : SettingsModel
	{


		$this->SettingsModel = SettingsModel::create(array('code'=>$code, 'value'=>$value));

		$this->SettingsModel->setId(0);	
	
		return $this->repo->createItem($this->SettingsModel)->saveItem();

	}



	public function editItem($code, $value) : SettingsModel
	{

		$this->SettingsModel = $this->getItem($code);
		$this->SettingsModel->setCode($code);
		$this->SettingsModel->setValue($value);
		
		$this->repo->setCode($this->SettingsModel->code());	

		return $this->repo->createItem($this->SettingsModel)->edit();

	}

	public function deleteItem($code) : SettingsModel
	{

		$this->SettingsModel = $this->getItem($code);

		$this->repo->setCode($this->SettingsModel->code());	

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
