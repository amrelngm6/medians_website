<?php

namespace config;

use Illuminate\Database\Capsule\Manager as Capsule;

class Configuration 
{

	/* //////////////////////////////////
	@Params The Installation URL
	///////////////////////////////////*/
	// public  $url = 'http://192.168.1.99/Medians/ddd_demo/';
	public  $url = '';

	
	/* //////////////////////////////////
	@Params The main path 
	if installed at root type '/' 
	else type '/[foldername]/' 
	///////////////////////////////////*/
	// public  $path = '/Medians/ddd_demo/';
	public  $path = '';


	/* //////////////////////////////////
	@Params Administration Panel path 
	///////////////////////////////////*/
	public  $admin_path = 'admin';

		
	/* //////////////////////////////////
	@Params API Key for APIs requests 
	///////////////////////////////////*/
	public  $API_KEY = 'token';

	
	/////////////////////////////////////////////////////////
	// Don't Change them if you don't understand below lines
	// The Full path 
	/////////////////////////////////////////////////////////
	public  $full_path;
	
	
	/////////////////////////////////////////////////////////
	// Don't Change them if you don't understand below lines
	// The plugins path  
	/////////////////////////////////////////////////////////
	public  $plugins = './extensions/layout/';

	protected $capsule;	

	function __construct()
	{		
		$http = isset($_SERVER['HTTPS']) ? 'https' : 'http';

		$fullUrl  = $http."://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ;

		$dir = (dirname($_SERVER['SCRIPT_NAME']) && dirname($_SERVER['SCRIPT_NAME']) != '/') ? (dirname($_SERVER['SCRIPT_NAME'])) : '/';

		$this->url = $http."://$_SERVER[HTTP_HOST]". $dir;

		$this->url = str_replace('\\','/',$this->url);
	}


	public function setUrl($url) : Void
	{
		$this->url = $url;
	}

	public function setPath($path) : Void
	{
		$this->path = $path;
	}

	public function setAdminPath($admin_path) : Void
	{
		$this->admin_path = $admin_path;
	}

	public function getCONF() : Object
	{
		return $this;
	}

	public function getCONFArray() : array
	{
		return (array) $this;
	}

	/**
	 * Set the database connection using 
	 * @var Illuminate\Database\Capsule\Manager 
	 * library for all models
	 */ 
	public function checkDB() 
	{
		global $Capsule;

		$this->capsule = $Capsule;
		// if (isset($_SESSION['db_checked']) && $this->capsule->getConnection())
		// 	return $this->capsule;

		return $this->capsule;
	}


}