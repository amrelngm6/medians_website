<?php

namespace Medians\Users\Infrastructure;

use Medians\Users\Domain\User;

class UserRepository 
{


	/**
	 * Load app for Sessions and helpful
	 * methods for authentication and
	 * settings for branch
	 */ 
	protected $app ;

	public function getModel()
	{
		return new User;
	}

	public function find($id)
	{
		return User::with('Role')->with('branch')->find($id);
	}

	public function findItem($id)
	{
		return User::with('Role')->with('branch')->find($id);
	}

	public function checkDuplicate($param)
	{
		return $this->validateEmail($param['email']);
	}


	public function getByID($customerId)
	{

		return User::find($customerId);

	}


	public function getByEmail($email)
	{

		return  User::where('email', $email)->first();
	}


	public function checkLogin($email, $password)
	{

		return User::where('password', $password)->where('email' , $email)->first();
	}

	public function get($limit = 100)
	{
		return User::with('Role', 'branch')->limit($limit)->get();
	}



	/**
	* Save item to database
	*/
	public function store($data) 
	{

		$Model = new User();

		$validateEmail = $this->validateEmail($data['email']);
		if ($validateEmail) {
			return $validateEmail;	
		}

		$Model = $Model->firstOrCreate($data);

    	$Model->update($data);
    	
    	$data['id'] = $Model->id;
    	$this->checkUpdatePassword($data);

		// Return the FBUserInfo object with the new data
    	return $Model;

	}
	

	/**
	* Update item to database
	*/
	public function update($data) 
	{
		try {
			
			$Object = User::find($data['id']);
			
			if (!$Object) {
				return __('this user not found');	
			}

			$validateEmail = $this->validateEmail($data['email'], $Object->id);
			if ($validateEmail) {
				return $validateEmail;	
			}

			// Return the FBUserInfo object with the new data
	    	$Object->update( (array) $data);
	    	
	    	$data['id'] = $Object->id;
	    	$this->checkUpdatePassword($data);

    		return $Object;	

		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}

	/**
	* validate Email 
	*/
	public function validateEmail($email, $id = 0) 
	{
		if (!empty($email))
		{
			$check = User::where('email', $email)->where('id', '!=', $id)->first();
		}

		if (isset($check->id) && $check->id != $id)
		{
			return __('EMAIL_FOUND');
		}
	}

	/**
	* Update item to database
	*/
	public static function checkUpdatePassword($data) 
	{
		if (isset($data['id']))
		{
			$Object = User::find($data['id']);
		}

		if (!empty($data['password']))
		{
			// Return the FBUserInfo object with the new data
    		$Object->password =  User::encrypt($data['password']);
    		$Object->save();
		}
    	
    	return isset($Object) ? $Object : null;	
	}


}
