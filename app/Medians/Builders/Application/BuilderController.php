<?php

namespace Medians\Builders\Application;
use Shared\dbaser\CustomController;

use Medians\Builders\Infrastructure\BuilderRepository;
use Medians\Content\Infrastructure\ContentRepository;


class BuilderController extends CustomController 
{

	function __construct()
	{
		$this->repo = new BuilderRepository;
		$this->contentRepo = new ContentRepository;
		$this->app = new \Config\APP;

	}

	/**
	 * Index builder 
	 */ 
	public function index()
	{

		try {

			$request = $this->app->request();
			$check = $this->contentRepo->find($request->get('prefix'));
			$check->switch_lang = $this->contentRepo->switch_lang($check);

			return render('views/admin/builder/index.html.twig', [
				'content' => $check->content, 
				'page' => $check, 
				'precode' => ($check->content && substr(trim($check->content), 0, 8) == '<section') ? '' : '<section id="newKeditItem" class="kedit">', 
				'postcode' => ($check->content && substr(trim($check->content), 0, 8) == '<section') ? '' : '</section>', 
			]);

		} catch (\Exception $e) {
			throw new \Exception($e->getMessage(), 1);
		}
	}



	/**
	 * Load builder assets
	 */ 
	public function load()
	{

		try {
			
			$request = $this->app->request();
			$page = $request->get('page');
			switch ($page) {
				case 'blocks':
					echo json_encode($this->repo->get());
					// $blocks = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/src/builder/assets/blocks.json');
					// foreach (json_decode($blocks) as $k => $row) 
					// {
					// 	foreach ($row as $value) 
					// 	{
					// 		// $k != 'columns' ? '' : $this->repo->store(['category'=>$k, 'content'=>$value->html]);
					// 	}
					// }
					// echo $blocks;
					break;
			}

		} catch (\Exception $e) {
			throw new \Exception($e->getMessage(), 1);
		}
	}


	/**
	 * Load builder meta
	 */ 
	public function meta()
	{

		try {
			
			$request = $this->app->request();
			$check = $this->contentRepo->find($request->get('prefix'));

			render('views/admin/builder/templates/meta.html.twig',['page'=>$check]);

		} catch (\Exception $e) {
			throw new \Exception($e->getMessage(), 1);
		}
	}




	/**
	 * Submit builder requests
	 */ 
	public function updateContent()
	{	

		$request = $this->app->request();
		
		if (!$request->get('contentJSON') || !$request->get('prefix'))			
			return true;

		$contentJSON = json_decode($request->get('contentJSON'));
		$check = $this->contentRepo->find($request->get('prefix'));
		$check->content = str_replace('data-src', 'src', $contentJSON->contentArea);
		$check->update(['content' => $check->content]);
		// file_put_contents($_SERVER['DOCUMENT_ROOT'].'/app/views/admin/builder/templates/home.html.twig', $contentJSON->contentArea);
		echo $check->content;
	}



	/**
	 * Update meta tags
	 */ 
	public function updateMeta()
	{	

		$request = $this->app->request();
		
		if (!$request->get('title') || !$request->get('prefix'))			
			return true;


		return $this->repo->updateMeta($request);
	}




	/**
	 * Submit builder requests
	 */ 
	public function submit()
	{


		try {
			
			$request = $this->app->request();
			$supermode = $request->get('supermode');
			switch ($supermode) 
			{
				case 'configUpdate':
					return $this->updateContent();		
					break;
				
				case 'updateMeta':
					return $this->updateMeta();		
					break;
				
				case 'insertContent':
					echo $this->repo->find($request->get('id'))->content;
					return true;		
					break;
				
				default:
					// code...
					break;
			}

			if ($request->get('prefix') && $supermode == 'updateMeta')
			{

			}

		} catch (\Exception $e) {
			throw new \Exception($e->getMessage(), 1);
		}
	}
}
