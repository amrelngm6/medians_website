<?php

namespace Medians\Notifications\Application;
use Shared\dbaser\CustomController;
use \Shared\dbaser\CustomController;

use Medians\Notifications\Infrastructure\NotificationEventRepository;

class NotificationEventController extends CustomController extends CustomController 
{

	/**
	* @var Object
	*/
	protected $repo;



	function __construct()
	{
		$this->repo = new NotificationEventRepository();
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
                'title'=> '#',
                'sortable'=> false,
            ],
            [
                'key'=> "title",
                'title'=> __('name'),
                'sortable'=> false,
            ],
            [
                'key'=> "receiver_model",
                'title'=> __('receiver_model'),
                'sortable'=> true,
            ],
            [
                'key'=> "subject",
                'title'=> __('subject'),
                'sortable'=> true,
            ],
            [
                'key'=> "status",
                'title'=> __('status'),
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
	public function index() 
	{
		return render('notifications_events', [
	        'load_vue' => true,
	        'title' => __('Notifications events'),
	        'items' => $this->repo->get(),
	        'columns' => $this->columns(),
	        'models' => $this->repo->loadModels(),
	    ]);
	}


	/**
	 * Supported models for events
	 *
	 */
	public function loadModels()
	{
	}  


	/**
	 * Store item to database
	 * 
	 * @return [] 
	*/
	public function store() 
	{	
		$this->app = new \config\APP;
        
		$params = $this->app->request()->get('params');

        try {
        	$params['created_by'] = $this->app->auth()->id;
            return ($this->repo->store($params))
            ? array('success'=>1, 'result'=>__('Added'), 'reload'=>1)
            : array('success'=>0, 'result'=>__('Error'), 'error'=>1);


        } catch (Exception $e) {
            return array('error'=>$e->getMessage());
        }
	
	}



	/**
	 * Update item to database
	 * 
	 * @return [] 
	*/
	public function update() 
	{

		$this->app = new \config\APP;

		$params = $this->app->request()->get('params');

        try {


           	$returnData =  ($this->repo->update($params))
           	? array('success'=>1, 'result'=>__('Updated'), 'reload'=>1)
           	: array('error'=>'Not allowed');


        } catch (Exception $e) {
            $returnData = array('error'=>$e->getMessage());
        }

        return $returnData;

	}

	/**
	 * Delete item from database
	 * 
	 * @return [] 
	*/
	public function delete() 
	{
		
		$this->app = new \config\APP;

		$params = $this->app->request()->get('params');

        try {

           	return  ($this->repo->delete($params['id']))
            ? array('success'=>1, 'result'=>__('Added'), 'reload'=>1)
           	: array('error'=>__('Not allowed'));


        } catch (Exception $e) {
            $returnData = array('error'=>$e->getMessage());
        }

        return $returnData;

	}



}
