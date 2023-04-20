<?php

namespace Medians\Application\Services;

use Medians\Application as apps;

use Medians\Infrastructure\Services as Repo;

class Service 
{

	/**
	* @var Object
	*/
	protected $repo;

	/**
	* @var Service
	*/
	protected $Service;

	/**
	* @var Array
	*/
	protected $data = array();
	

	function __construct($data = null)
	{
		$this->data = (object) $data;

		$this->repo = new Repo\ServicesRepository();
	}




	public function index($slug , $app, $twig) 
	{

		$this->Service = $this->repo->where('slug', $slug)->first();

		if (isset($this->Service->status))
		{
		    return $twig->render('views/front/services/service.html.twig', [
		        'service' => $this->Service,
		        'app' => $app,
		    ]);
		}

		$this->Services = $this->repo->list();

	    return $twig->render('views/front/services/index.html.twig', [
	        'services' => $this->Services,
	        'app' => $app,
	    ]);
	}




	/**
	 * Admin index items
	 * 
	 * @param Silex\Application $app
	 * @param \Twig\Environment $twig
	 * 
	 */ 
	public function admin($app, $twig) 
	{

		$this->Service = 
		 $this->repo
		->with('content')
		->get();

	    return $twig->render('views/admin/services/admin.html.twig', [
	        'services' => $this->Service,
	        'app' => $app,
	    ]);

	    return $twig->render('views/404.html.twig', []);
	}




	/**
	 * Admin edit item
	 * 
	 * @param String $slug
	 * @param Silex\Application $app
	 * @param \Twig\Environment $twig
	 * 
	 */ 
	public function edit($slug, $app, $twig) 
	{

		$this->Service = $this->repo
		->where('slug', $slug)
		->with('content')
		->first()->toArray();

		$this->Service = (object) $this->Service;

		if (empty($this->Service->content))
		{
			$this->Service->content = [0,0,0,0,0,0,0,0,0,0];
		}

	    return $twig->render('views/admin/services/edit.html.twig', [
	        'title' => $this->Service->name,
	        'service' => $this->Service,
	        'app' => $app,
	        'formAction' => 'confirm_edit_service/'.$this->Service->slug
	    ]);

	}



	/**
	 * Admin edit item
	 * 
	 * @param Symfony\Component\HttpFoundation\Request $request
	 * 
	 */ 
	public function update($request) 
	{

		$this->repoModel = $this->repo->where('slug', $request->get('slug'))->first();

		$this->repoModel->name  = $request->get('name');

		$update = $this->repoModel->save();


		$deleteOld = $this->content_repo
		->where('model_type', get_class($this->repo))
		->where('model_id' ,$this->repoModel->id)
		->delete();

		
		if ($request->get('service_content'))
		{
			$this->storeContent($request);
		}

		return isset($save) ? array('success'=>1, 'data'=>'Updated', 'redirect'=>'') : [];
	}


	/**
	 * Admin create item
	 * 
	 * @param Symfony\Component\HttpFoundation\Request $request
	 * 
	 */ 
	public function create($request) 
	{

		$this->repoModel = $this->repo;

		$this->repoModel->name  = $request->get('name');
		$this->repoModel->slug  = strtolower(str_replace(' ', '-', $request->get('name')));
		$save = $this->repoModel->save();

		$deleteOld = $this->content_repo
		->where('model_type', get_class($this->repo))
		->where('model_id' ,$this->repoModel->id)
		->delete();

		if ($request->get('service_content'))
		{
			$this->storeContent($request);
		}


		return isset($save) ? array('success'=>1, 'data'=>'Updated', 'redirect'=>'') : [];
	}



	/**
	 * Admin save page content item
	 * 
	 * @param Symfony\Component\HttpFoundation\Request $request
	 * 
	 */ 
	public function storeContent($request) 
	{

		if (!empty($request->get('service_content')))
		{

			foreach ($request->get('service_content') as $key => $ServiceContent) 
			{
				foreach ($ServiceContent as $k => $v) 
				{
					$this->content_repo = new Content\ContentRepository;
					$this->content_repo->lang = 'en';
					$this->content_repo->code = $key;
					$this->content_repo->value = $v;
					$this->content_repo->model_id = $this->repoModel->id;
					$this->content_repo->model_type = get_class($this->repoModel);

					$save = $this->content_repo->save();
				}
			}
		}

		return isset($save) ? true : false;
	}


	/**
	 * Admin delete item
	 * 
	 * @param Symfony\Component\HttpFoundation\Request $request
	 * 
	 */ 
	public function delete($request) 
	{
		
		$id = isset($request->get('params')['id']) ? $request->get('params')['id'] : 0;	

		$this->repoModel = $this->repo->find($id); 

		$this->repoModel->delete();


		$deleteOld = $this->content_repo
		->where('model_type', get_class($this->repo))
		->where('model_id' ,$this->repoModel->id)
		->delete();
		
		return array('success'=>1, 'data'=>'Deleted', 'redirect'=>'') ;
	}


	/**
	 * Admin validate item
	 * 
	 * @param Symfony\Component\HttpFoundation\Request $request
	 * 
	 */ 
	public function validate() 
	{

		if (empty($this->data->name))
		{
			throw new \Exception("Empty name", 1);
		}

	}




}
