<?php

namespace Shared\dbaser;

use \Illuminate\Database\Eloquent\Model;

use Medians\Users\Domain\User;
use Medians\Content\Domain\Content;
use Medians\Views\Domain\View;
use Medians\Notifications\Domain\NotificationEvent;

use \config\APP;

class CustomModel extends Model
{
	

	function __construct()
	{
		$this->orderBy('id', 'DESC');
	}


	public function can($permission, $app)
	{
	    if (isset($app->auth->role_id))
	    {

	        if ($app->auth->role_id == 1)
	            return true;

		    if (isset($this->agent_id) && $this->agent_id == $app->auth->id)
	            return true;

		    if (isset($this->created_by) && $this->created_by == $app->auth->id)
	            return true;

		    if (get_class($this) == User::class && $this->id == $app->auth->id)
	            return true;

	    }


	    return null;
	}

	
	public function user()
	{
		return $this->hasOne(User::class, 'id', 'created_by');
	}

	public function content()
	{
		return $this->morphOne(Content::class, 'item')->where('lang', __('lang'));
	}

	public function en()
	{
		return $this->morphOne(Content::class, 'item')->where('lang', 'en');
	}

	public function ar()
	{
		return $this->morphOne(Content::class, 'item')->where('lang', 'ar');
	}


	public function sessionGuest()
	{
		if (empty($_SESSION['guest']))
		{
			$_SESSION['guest'] = sha1(md5(date('ymdhis').rand(9,99)));
		}

		return $_SESSION['guest'];
	}

	public function addView()
	{

		View::create(['session'=>$this->sessionGuest(), 'item_type'=>get_class($this), 'item_id'=>$this->id]);

	}


    protected function finishSave(array $options)
    {

    	if ($this->wasRecentlyCreated)
    		return $this->createdEvent();

    	return $this->updatedEvent();
    }


    /**
     * Handle the event after new item 
     * has been stored 
     * 
     */
    public function createdEvent()
    {

    	if (!$this->wasRecentlyCreated)
    		return true;
    	
    	// Insert activation code 
    	// return (new NotificationEvent)->handleEvent($this, 'create');
    }  

    /**
     * Handle the event after an item 
     * has been updated 
     * 
     */
    public function updatedEvent()
    {
    	if (empty($this->id))
    		return null;

    	$fields = array_fill_keys($this->fillable,1);
    	$updatedFields = array_intersect_key($fields, $this->getDirty());
    	if (empty($updatedFields))
    		return null;


    	// Insert activation code 
    	// return (new NotificationEvent)->handleEvent($this, 'update');

    }  

}



