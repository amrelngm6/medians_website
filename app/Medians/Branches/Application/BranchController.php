<?php

namespace Medians\Branches\Application;
use Shared\dbaser\CustomController;

use Medians\Branches\Infrastructure\BranchRepository;


class BranchController extends CustomController 
{


	/*
	/ @var new CustomerRepository
	*/
	private $repo;

	public $app;


	function __construct()
	{
		
		$this->repo = new BranchRepository();
	}



	/**
	 * Index page
	 * 
	 */
	public function index()
	{

		return render('views/admin/branches/list.html.twig', [
			'items' => $this->getData(),
	        'title' => __('branches'),
	    ]);
	} 

	/**
	 * Index page
	 * 
	 */
	public function getData()
	{
		$this->app = new \config\APP;

		return ($this->app->auth()->role_id == 1) ? $this->repo->get() : $this->repo->find($this->app->branch->id);
	} 
	


}
