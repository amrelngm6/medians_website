<?php

namespace Medians\Customers\Application;
use Shared\dbaser\CustomController;

use Medians\Customers\Infrastructure as Repo;



class CustomerController extends CustomController 
{


	/*
	/ @var new CustomerRepository
	*/
	private $repo;


	function __construct($app)
	{

		$this->repo = new Repo\CustomerRepository($app);


	}


	/**
	 * Index page
	 * 
	 */
	public function index($request, $app)
	{
		return render('views/admin/customers/list.html.twig', [
			'items' =>  $this->repo->get(),
	        'title' => 'Customers',
	        'app' => $app,
	    ]);
	} 


	/**
	 * Create page
	 * 
	 */
	public function create($request, $app)
	{
		return render('views/admin/customers/create.html.twig', [
	        'title' => 'Customers',
	        'Model' => $this->repo->getModel(),
	        'app' => $app,
	    ]);
	} 





	/**
	*  Store item
	*/
	public function store($request, $app) 
	{

		$params = (array) json_decode($request->get('params')['customer']);

		try {	

			if (empty($params['first_name']))
	        	return array('error'=>1, 'result'=>'First Name is required');

			if (empty($params['phone']))
	        	return array('error'=>1, 'result'=>'Phone is required');

			$this->repo->app = $app;
			$params['created_by'] = $app->auth->id;
			$Property = $this->repo->store($params);

        	return array('success'=>1, 'result'=>'Created');

        } catch (Exception $e) {
            return  array('error'=>$e->getMessage());
        }
	}



	/**
	*  Store item
	*/
	public function update($request, $app) 
	{

		$params = (array)  json_decode($request->get('params')['customer']);

		try {

			$this->repo->app = $app;
			$Property = $this->repo->update($params);

        	return array('success'=>1, 'result'=>'Updated');

        } catch (Exception $e) {
            return  array('error'=>$e->getMessage());
        }
	}



}
