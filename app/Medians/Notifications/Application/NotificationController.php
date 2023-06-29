<?php

namespace Medians\Notifications\Application;
use Shared\dbaser\CustomController;
use \Shared\dbaser\CustomController;

use Medians\Notifications\Infrastructure\NotificationRepository;
use Medians\Devices\Infrastructure\OrderDevicesRepository;
use Medians\Branches\Infrastructure\BranchRepository;

class NotificationController extends CustomController extends CustomController 
{

	/**
	* @var Object
	*/
	protected $repo;
	
	
	protected $branchRepo;



	function __construct()
	{
		$this->repo = new NotificationRepository();
		$this->orderDeviceRepo = new OrderDevicesRepository();
		$this->branchRepo = new BranchRepository();
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
                'key'=> "subject",
                'title'=> __('subject'),
                'sortable'=> false,
            ],
            [
                'key'=> "model_short_name",
                'title'=> __('Model'),
                'sortable'=> false,
            ],
            [
                'key'=> "receiver_name",
                'title'=> __('receiver_name'),
                'sortable'=> true,
            ],
            [
                'key'=> "date",
                'title'=> __('date'),
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
		return render('notifications', [
	        'load_vue' => true,
	        'title' => __('Notifications'),
	        'items' => $this->repo->get(),
	        'columns' => $this->columns(),

	    ]);
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

	/**
	 * Delete item from database
	 * 
	 * @return [] 
	*/
	public function read_notification() 
	{
		
		$this->app = new \config\APP;

		$params = $this->app->request()->get('params');

        try {
           	
           	response($this->repo->update(['id'=>$params['id'],'status' => 'read'])
            ? array('success'=>1, 'result'=>__('updated'))
           	: array('error'=>__('Not allowed')));

        } catch (Exception $e) {
            return array('error'=>$e->getMessage());
        }

	}


	/**
	 * Delete item from database
	 * 
	 * @return [] 
	*/
	public function check_notification() 
	{
		
		$this->app = new \config\APP;

		$params = $this->app->request()->get('params');

        try {

        	$check = $this->repo->get(10, $params['last_id']);

           	echo  (count($check))
            ? json_encode($this->loadLatestNotifications($check))
           	: 0;


        } catch (Exception $e) {
            return array('error'=>$e->getMessage());
        }

	}



	/**
	 * Admin index items
	 * 
	 * @param Silex\Application $app
	 * @param \Twig\Environment $twig
	 * 
	 */ 
	public function latest_notifications($last_id=0) 
	{
		$items = $this->repo->get(50, $last_id);

		return render('notifications', $this->loadLatestNotifications($items));
	}

	/**
	 * Load latest notifications based on role and limit
	 * 
	 */
	public function loadLatestNotifications($items=0)
	{
		return [
	        'load_vue' => true,
	        'title' => __('Notifications'),
	        'last_id' => !empty($items) ? $items->first()->id : 0,
	        'items' => $items,
	        'total_count' => $items->count(),
	        'new_count' => $items->where('status', 'new')->count(),
	    ];
	}  




	/**
	 * Handle the bookings notifications
	 * in background
	 */ 
	public function handleBookingsNotifications()
	{
		$this->branchRepo = new BranchRepository();

		foreach ($this->branchRepo->getIds() as $key => $value) 
		{
			$save = $this->handleBranchNotifications($value);
		}

		return true;
	}



	/**
	*  Handle Branch Notifications
	*/
	public function handleBranchNotifications($branch) 
	{

		$items = $this->orderDeviceRepo->getByBranch($branch->id);

		foreach ($items as $key => $value) {
			$this->store($branch, $value);
		}

		return $this;
	}



	/**
	*  Store item
	*/
	public function store($branch, $model) 
	{

		try {	


			$DashboardController = new \Medians\DashboardController;

	    	// Store notification
			$filled['receiver_type'] = get_class($branch);
			$filled['receiver_id'] = $branch->id;
			$filled['event_id'] = 0;
			$filled['model_type'] = get_class($model);
			$filled['model_id'] = $model->id;
	    	$filled['subject'] = __('Booking time ended') . (isset($model->title) ? $model->title : $model->id);
	    	$filled['body'] = __('Booking time ended and requires an action');
	    	$filled['status'] = 'new';

	    	$this->repo->store($filled);


        } catch (Exception $e) {

        }
	}

	
}
