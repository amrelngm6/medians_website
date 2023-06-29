<?php

namespace Medians\Settings\Infrastructure;

use Medians\Settings\Domain\Settings;


class SettingsRepository 
{


	
	/**
	 * Load app for Sessions and helpful
	 * methods for authentication and
	 * settings for branch
	 */ 
	protected $app ;



	function __construct()
	{
		$this->app = new \config\APP;
	}

	/*
	// Find item by `id` 
	*/
	public function find($id) : ?Settings
	{

		return Settings::find($id);

	}

	/*
	// Find item by `id` 
	*/
	public function getByCode($code) : ? String
	{
		try {
			
			$check = Settings::where('branch_id', $this->app->branch->id)->where('code', $code)->first();
			return isset($check->value) ? $check->value : '';
		} catch (\Exception $e) {
    		throw new \Exception($e->getMessage(), 1);
			
		}
	}

	/*
	// Find all items 
	*/
	public function getAll()
	{
		try {

			$nramchId = isset($this->app->branch->id) ? $this->app->branch->id : 0;
			
			return Settings::where('branch_id', $nramchId)->get();

		} catch (\Exception $e) {
    		throw new \Exception($e->getMessage(), 1);
		}
	}


	/**
	* Save item to database
	*/
	public function store($data) 
	{

		$Model = new Settings();

    	$Model->firstOrCreate($data);

		// Return the Settings object with the new data
		return $Model;
	}
	

	/**
	* Delete item from database
	*/
	public function delete($code) 
	{
		return Settings::where('branch_id', $this->app->branch->id)->where('code', $code)->delete();
	}


	/**
	* Clear item from database
	*/
	public function clear() 
	{
		Settings::where('branch_id', $this->app->branch->id)->delete();
		
		return $this;
	}


}
