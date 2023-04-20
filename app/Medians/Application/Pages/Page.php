<?php

namespace Medians\Application\Pages;

use Medians\Application as apps;

use Medians\Infrastructure\Pages as Repo;
use Medians\Infrastructure\Content as Content;

class Page 
{

	/*
	// @var Object
	*/
	protected $repo;

	/*
	// @var Object
	*/
	protected $content_repo;

	/*
	// @var Array
	*/
	protected $data = array();
	

	function __construct($data = null)
	{

		$this->data = (object) $data;

		$this->repo = new Repo\PagesRepository();

		$this->content_repo = new Content\ContentRepository();

	}



	public function home($app, $twig) 
	{

		$this->Page = $this->repo
	    ->where('slug', 'home')
	    ->with('content')
	    ->first();

	    return  $twig->render('views/front/home.html.twig', [
	        'title' => $this->Page->name,
	        'page' => $this->Page,
	        'app' => $app,
	    ]);


	}



	public function index($slug , $app, $twig) 
	{

		$this->Page = $this->repo
		->where('slug', $slug)
		->with('content')
		->first();

		if (isset($this->Page->status))
		{
		    return $twig->render('views/front/pages/index.html.twig', [
		        'title' => $this->Page->name,
		        'page' => $this->Page,
		        'app' => $app,
		    ]);
		}

	    return $twig->render('views/404.html.twig', []);
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

		$this->Page = $this->repo
		->with('content')
		->get();

	    return $twig->render('views/admin/pages/admin.html.twig', [
	        'pages' => $this->Page,
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

		$this->Page = $this->repo
		->where('slug', $slug)
		->with('content')
		->first()->toArray();

		$this->Page = (object) $this->Page;

		if (empty($this->Page->content))
		{
			$this->Page->content = [0,0,0,0,0,0,0,0,0,0];
		}

	    return $twig->render('views/admin/pages/edit.html.twig', [
	        'title' => $this->Page->name,
	        'page' => $this->Page,
	        'app' => $app,
	        'formAction' => 'confirm_edit_page/'.$this->Page->slug
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

		
		if ($request->get('page_content'))
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

		if ($request->get('page_content'))
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

		if (!empty($request->get('page_content')))
		{

			foreach ($request->get('page_content') as $key => $pageContent) 
			{
				foreach ($pageContent as $k => $v) 
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
