<?php

namespace Medians\Hooks\Application;
use Shared\dbaser\CustomController;



class HookController extends CustomController 
{

	/**
	* @var Object
	*/
	protected $repo;

	

	function __construct()
	{

		$this->app = new \config\APP;

	}



}