<?php

namespace Medians;

use Medians\Users\Infrastructure\UserRepository;

use Medians\Devices\Infrastructure\OrderDevicesRepository;

use Medians\Devices\Infrastructure\DevicesRepository;

use Medians\Products\Infrastructure\ProductsRepository;


class APIController 
{

	/**
	* @var Object
	*/
	protected $repo;



	function __construct()
	{
	
	}


	/**
	 * Model object 
	 * 
	 */
	public function handle()
	{

		$this->app = new \config\APP;

		$return = [];
		switch ($this->app->request()->get('model')) 
		{
			case 'User':
				$controller = new UserRepository();
				break;
			
		}

		$return = isset($controller) ? $controller->find($this->app->request()->get('id')) : $return;

		return response(json_encode(['status'=>true, 'result'=>$return]));
	} 

	/**
	 * Create model 
	 * 
	 */
	public function create()
	{
		
		$app = new \config\APP;
		$request = $app->request();
		
		try {
				
			$return = [];
			switch ($request->get('type')) 
			{
				case 'Page.create':
					$return = (new Pages\Application\PageController())->store();
					break;

				case 'Doctor.create':
					$return = (new Doctors\Application\DoctorController())->store();
					break;

				case 'Category.create':
					$return = (new Categories\Application\CategoryController())->store();
					break;

				case 'Blog.create':
					$return = (new Blog\Application\BlogController())->store();
					break;

				case 'Story.create':
	                $return =  (new Stories\Application\StoryController())->store(); 
					break;

				case 'StoryDate.create':
	                $return =  (new StoryDates\Application\StoryDateController())->store(); 
					break;

				case 'Offer.create':
	                $return =  (new Offers\Application\OfferController())->store(); 
					break;

				case 'OnlineConsultation.create':
	                $return =  (new OnlineConsultations\Application\OnlineConsultationController())->store(); 
					break;

				case 'Specialization.create':
	                $return =  (new Specializations\Application\SpecializationController())->store(); 
					break;

	            case 'User.create':
	                $return =  (new Users\Application\UserController())->store(); 
	                break;

			}

			return response(json_encode($return));

		} catch (Exception $e) {
			return $e->getMessage();
		}
	} 

	/**
	 * Update model 
	 * 
	 */
	public function update()
	{
		$app = new \config\APP;
		$request = $app->request();

		$return = [];
		switch ($request->get('type')) 
		{

			case 'Story.update':
                $return =  (new Stories\Application\StoryController())->update(); 
				break;

			case 'StoryDate.update':
                $return =  (new StoryDates\Application\StoryDateController())->update(); 
				break;
				
			case 'Category.update':
				$return = (new Categories\Application\CategoryController)->update($request);
				break;
            case 'Settings.update':
                $return = (new Settings\Application\SettingsController())->update(); 
                break;

            case 'OnlineConsultation.update':
                $return = (new OnlineConsultations\Application\OnlineConsultationController())->update(); 
                break;

            case 'Blog.update':
                $return = (new Blog\Application\BlogController())->update(); 
                break;

            case 'Doctor.update':
                $return = (new Doctors\Application\DoctorController())->update(); 
                break;

            case 'Specialization.update':
                $return = (new Specializations\Application\SpecializationController())->update(); 
                break;

            case 'User.update':
                $return =  (new Users\Application\UserController())->update(); 
                break;
		}

		return response(json_encode($return));
	} 

	/**
	 * delete model 
	 * 
	 */
	public function delete()
	{

		$app = new \config\APP;
		$request = $app->request();

		try {
			
			$return = [];
			switch ($request->get('type')) 
			{
				case 'Page.delete':
					return response((new Pages\Application\PageController())->delete());
					break;

				case 'Story.delete':
					$return = (new Stories\Application\StoryController())->delete();
					break;

				case 'StoryDate.delete':
					$return = (new StoryDates\Application\StoryDateController())->delete();
					break;

				case 'Category.delete':
					$return = (new Categories\Application\CategoryController())->delete();
					break;

				case 'Product.delete':
					$return = (new Products\Application\ProductController())->delete();
					break;
					
				case 'Game.delete':
					return response((new Games\Application\GameController())->delete());
					break;

				case 'Blog.delete':
					return response((new Blog\Application\BlogController())->delete());
					break;

				case 'Offer.delete':
					return response((new Offers\Application\OfferController())->delete());
					break;

				case 'Doctor.delete':
					return response((new Doctors\Application\DoctorController())->delete());
					break;

				case 'Stock.delete':
					return (new Products\Application\StockController())->delete();
					break;


			}

			return response(json_encode($return));

		} catch (Exception $e) {
			throw new Exception("Error Processing Request", 1);
					
		}
	} 

	/**
	 * Update model 
	 * 
	 */
	public function updateStatus()
	{

		$app = new \config\APP;
		$request = $app->request();

		$id = $request->get('id');
		$status = $request->get('status');

		$return = [];
		switch ($request->get('model')) 
		{
			case 'Device':
				$return = (new DevicesRepository())->find($id)->update(['status'=>$status]);
				break;
		}

		return response(json_encode($return));
	} 

}
