<?php

namespace Medians\Notifications\Infrastructure;

use Medians\Notifications\Domain\NotificationEvent;



/**
 * NotificationEvent class database queries
 */
class NotificationEventRepository 
{

	
	/**
	 * Load app for Sessions and helpful
	 * methods for authentication and
	 * settings for branch
	 */ 
	protected $app ;



	function __construct ()
	{
		$this->app = new \config\APP;
	}

	public function getModel()
	{
		return new NotificationEvent;
	}

	/**
	* Find item by `id` 
	*/
	public function find($id) 
	{

		return NotificationEvent::find($id);
	}

	/**
	* Find items by `params` 
	*/
	public function get($params = null) 
	{
		return NotificationEvent::get();
	}


	/**
	* Save item to database
	*/
	public function store($data) 
	{	

		$Model = new NotificationEvent();
		foreach ($data as $key => $value) 
		{
			if (in_array($key, $Model->getFields()))
			{
				$dataArray[$key] = $value;
			}
		}	

		// Return the Model object with the new data
    	$Object = NotificationEvent::firstOrCreate($dataArray);

    	return $Object;
	}


	/**
	* Update item to database
	*/
    public function update($data)
    {

		$Object = NotificationEvent::find($data['id']);
		
		// Return the Model object with the new data
    	$Object->update( (array) $data);

    	return $Object;
    } 



	/**
	* Delete item to database
	*
	* @Returns Boolen
	*/
	public function delete($id) 
	{
		try {
			
			return NotificationEvent::find($id)->delete();

		} catch (\Exception $e) {

			throw new \Exception("Error Processing Request " . $e->getMessage(), 1);
			
		}
	}



	/**
	 * Load all notifiable Models for 
	 * Dynamic events 
	 * 
	 */
	public function loadModels()
	{
		return [
			'Orders' => \Medians\Orders\Domain\Order::class,
			'Devices' => \Medians\Devices\Domain\Device::class,
			'Bookings' => \Medians\Devices\Domain\OrderDevice::class,
			'Users' => \Medians\Users\Domain\User::class,
			'Customers' => \Medians\Customers\Domain\Customer::class,
			'Games' => \Medians\Games\Domain\Game::class,
			'Expenses' => \Medians\Expenses\Domain\Expense::class,
		];
	}   
}