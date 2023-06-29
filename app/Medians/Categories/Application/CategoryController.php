<?php

namespace Medians\Categories\Application;
use Shared\dbaser\CustomController;

use Medians\Categories\Infrastructure\CategoryRepository;


class CategoryController extends CustomController 
{

	/**
	* @var Object
	*/
	protected $repo;

	

	function __construct()
	{

		$this->repo = new CategoryRepository();
	}


	/**
	 * Columns list to view at DataTable 
	 *  
	 */ 
	public function columns( ) 
	{

		return [
            [
                'key'=> "id",
                'title'=> "#",
            ],
            [
                'key'=> "title",
                'title'=> __('title'),
                'sortable'=> true,
            ]
        ];
	}

	

	/**
	 * Admin index items
	 * 
	 * @param Silex\Application $app
	 * @param \Twig\Environment $twig
	 * 
	 */ 
	public function index( ) 
	{
		
		try {
			
		    return render('categories', [
		        'load_vue' => true,
		        'title' => __('categories'),
		        'columns' => $this->columns(),
		        'items' => $this->repo->get(),
		    ]);
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage(), 1);
			
		}
	}


	public function store() 
	{

		$this->app = new \config\APP;

		$params = $this->app->request()->get('params');

        try {	

        	$this->validate($params);

        	$params['model'] = 'Medians\Blog\Domain\Blog';
            $returnData = (!empty($this->repo->store($params))) 
            ? array('success'=>1, 'result'=>__('Added'), 'reload'=>1)
            : array('success'=>0, 'result'=>'Error', 'error'=>1);

        } catch (Exception $e) {
        	throw new Exception(json_encode(array('result'=>$e->getMessage(), 'error'=>1)), 1);
        }

		return $returnData;
	}



	public function update()
	{

		$this->app = new \config\APP;

		$params = $this->app->request()->get('params');

        try {

        	$params['status'] = !empty($params['status']) ? $params['status'] : 0;
            if ($this->repo->update($params))
            {
                return array('success'=>1, 'result'=>__('Updated'), 'reload'=>1);
            }
        

        } catch (\Exception $e) {
        	throw new \Exception("Error Processing Request", 1);
        	
        }

	}


	public function delete() 
	{


		$this->app = new \config\APP;

		$params = $this->app->request()->get('params');
		
        try {

            if ($this->repo->delete($params['id']))
            {
                return array('success'=>1, 'result'=>__('Deleted'), 'reload'=>1);
            }
            

        } catch (Exception $e) {
        	throw new \Exception("Error Processing Request", 1);
        	
        }

	}

	public function validate($params) 
	{

		// if (empty(trim($params['model'])))
		// {
        // 	throw new \Exception(json_encode(array('result'=>__('model_required'), 'error'=>1)), 1);
		// }

		if (empty($params['content']['ar']['title']))
		{
        	throw new \Exception(json_encode(array('result'=>__('NAME_EMPTY'), 'error'=>1)), 1);
		}

	}

}
