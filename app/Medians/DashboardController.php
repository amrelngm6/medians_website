<?php

namespace Medians;
use \Shared\dbaser\CustomController;

use Medians\Infrastructure as Repo;

class DashboardController extends CustomController 
{

	/**
	* @var Object
	*/
	protected $repo;



	function __construct()
	{
		$this->app = new \config\APP;
	}

	/**
	 * Model object 
	 */
	public function index()
	{

		try {
			
	        return  render('dashboard', [
	        	'load_vue'=> true,
	            'title' => __('Dashboard')
	        ]);
	        
		} catch (Exception $e) {
			return $e->getMessage();
		}
	} 


}