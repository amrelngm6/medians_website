<?php

namespace Medians\Notifications\Infrastructure;

use Medians\Notifications\Domain\Notification;

use Medians\Branches\Domain\Branch;
use Medians\Users\Domain\User;


/**
 * Notification class database queries
 */
class NotificationRepository 
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
		return new Notification;
	}

	/**
	* Find item by `id` 
	*/
	public function find($id) 
	{

		return Notification::find($id);
	}

	/**
	* Find items by `params` 
	*/
	public function get($limit = 500,$last_id = 0) 
	{
		return ($this->app->auth()->role_id == 1 )
		? Notification::limit($limit)->get() 
		: Notification::limit($limit)
			->where('receiver_id', $this->app->branch->id)->where('receiver_type', Branch::class )
			->where('id', '>', $last_id)
			->orWhere('receiver_id', $this->app->auth()->id)->where('receiver_type', User::class )
			->where('id', '>', $last_id)
			->orderBy('created_at', 'DESC')
			->get() ;
	}


	/**
	* Save item to database
	*/
	public function store($data) 
	{	

		$Model = new Notification();
		foreach ($data as $key => $value) 
		{
			if (in_array($key, $Model->getFields()))
			{
				$dataArray[$key] = $value;
			}
		}	

		// Return the Model object with the new data
    	$Object = Notification::firstOrCreate($dataArray);

    	return $Object;
	}



	/**
	* Save item to database
	*/
	public function update($data) 
	{	

		$Object = Notification::find($data['id']);
		
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
			
			return Notification::find($id)->delete();

		} catch (\Exception $e) {

			throw new \Exception("Error Processing Request " . $e->getMessage(), 1);
			
		}
	}


}