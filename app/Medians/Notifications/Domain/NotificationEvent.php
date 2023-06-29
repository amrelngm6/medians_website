<?php

namespace Medians\Notifications\Domain;

use Shared\dbaser\CustomModel;

use Medians\Users\Domain\User;
use Medians\Branches\Domain\Branch;

/**
 * NotificationEvent class database queries
 */
class NotificationEvent extends CustomModel
{


	/**
	* @var String
	*/
	protected $table = 'notifications_events';

	/**
	* @var Array
	*/
	protected $fillable = [
		'title',
		'receiver_model',
		'model',
		'action_field',
		'action_value',
		'action',
		'subject',
		'body',
		'status',
    	'created_by',
	];


	/**
	* @var bool
	*/
	// public $timestamps = false;

	// public $appends = ['is_expired', 'branch_name', 'user_name', 'plan_name', ];


	public function getFields()
	{
		return $this->fillable;
	}



	/**
	 * handle event and process the Notification
	 * Body and information
	 * 
	 * @param $model Object Event related model
	 * @param $action String action type at CRUD
	 */ 
	public function handleEvent($model, $action)
	{

    	$events = json_decode(NotificationEvent::where('action',$action)->where('model',get_class($model))->get());

    	foreach ($events as $event) 
    	{
    		$this->renderNotification($event, $model);
    	}

    	return true;
	}


	/**
	 * Prepare notification content 
	 * 
	 * @param $event Object
	 * @param $model Object Event related model
	 */
	public function renderNotification($event, $model)
	{

		// Get receiver User / Branch
    	$receiver = (strtolower($event->receiver_model) == 'branch') 
    	? Branch::find(isset($model->branch_id) ? $model->branch_id : $model->active_branch) 
    	: Users::find($model->user_id);

    	$app = new \config\APP;
    	$params = [];

    	/**
    	 * Get short name for the class to use as index
		 * Append the model as paramater to render the content
		 * And replace the shortcode
		 */ 
    	$params[strtolower((new \ReflectionClass($model))->getShortName())] = $model;
    	$body = $app->renderTemplate($event->body)->render($params);

    	return Notification::storeEventNotification($event->id, $event->subject , $body, $model, $receiver);
	}  
}
