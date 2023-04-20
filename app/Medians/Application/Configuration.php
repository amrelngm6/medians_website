<?php

namespace Medians\Application;

use Shared\dbaser as MysqliDb;

class Configuration 
{

	/* //////////////////////////////////
	@Params MYSQL data
	///////////////////////////////////*/
	public $host;
	public $user;  
	public $pass;
	public $name;

	
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
	public  $admin_path = 'adminPanel';

		
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

	
	function __construct()
	{		
		$http = isset($_SERVER['HTTPS']) ? 'https' : 'http';

		$fullUrl  = $http."://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ;

		$this->url = $http."://$_SERVER[HTTP_HOST]". dirname($_SERVER['SCRIPT_NAME']).'/';
	}


	public function create($host, $user, $password, $database) : Configuration
	{

		$this->host = $host;
		$this->user = $user;  
		$this->pass = $password;
		$this->name = $database;

		return $this;
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

	public function checkDB() : MysqliDb
	{
	}
	
}