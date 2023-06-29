<?php

namespace Medians;

use \Shared\dbaser\CustomController;

class HomeController extends CustomController 
{


	function __construct()
	{
		$this->app = new \config\APP;
		$this->blogRepo = new \Medians\Blog\Infrastructure\BlogRepository;
		$this->pagesRepo = new \Medians\Pages\Infrastructure\PageRepository;
	}

	/**
	 * Model object 
	 */
	public function index()
	{
		try {

			$item = $this->pagesRepo->homepage();
			$item->addView();

	        return  render('views/front/page.html.twig',[
	        	'blog'=> $this->blogRepo->get(3),
	        	'headerPosition'=> 'absolute',
		        'item' => $item,
	        ]);
	        
		} catch (\Exception $e) {
			echo $e->getMessage();
		}
	} 




}
