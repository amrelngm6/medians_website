<?php

namespace Medians;

use \Shared\dbaser\CustomController;

class FrontendController extends CustomController 
{

	/**
	* @var Object
	*/
	protected $repo;



	function __construct()
	{
		$this->app = new \config\APP;

		$this->repo = new \Medians\Bookings\Infrastructure\BookingRepository;
	
	}


	/**
	 * Model object 
	 * 
	 */
	public function form_submit($type)
	{

		$request = $this->app->request()->get('params');

		try {
			
			$Object = $this->repo->store($request);

			$response = $Object ? array('success'=>1, 'result'=> __('BOOKING_THANKS'), 'title'=>__('Done')) : 'error' ;

		} catch (Exception $e) {
			$response  = array('error'=>$e->getMessage()) ;
		}


		echo json_encode($response);
	} 

}
