<?php

namespace Medians\Auth\Application;
use Shared\dbaser\CustomController;


use Medians\Branches\Application\BranchController;

use Medians\Auth\Domain\AuthModel;


use Medians\Mail\Application\MailService;

use Google_Service_Oauth2;

class AuthService 
{

	/**
	 * Minimum length of the user password
	 * 
	 * @var Int
	*/
	private $passLen = 5;

	/**
	* @var Instance AuthModel
	*/
	private $AuthModel;

	/**
	* @var Instance Repo
	*/
	private $repo;

	protected $app;


	function __construct()
	{
		$this->repo = new \Medians\Users\Infrastructure\UserRepository();
	}
 

	/**
	 * Display login page 
	 */
	public function loginPage()
	{

		try {
				
			$this->app = new \config\APP;

			if (isset($this->app->auth()->id)) { return $this->app->redirect('/dashboard'); }

		    // return  render('login', [
			return render('views/admin/login.html.twig', [
		    	// 'load_vue' => true,
		        'title' => __('Login page'),
		        'app' => $this->app,
		        'google_login' => $this->loginWithGoogle(),
		        'formAction' => '/login',
		    ]);
		    
		} catch (Exception $e) {
        	throw new Exception("Error Processing Request", 1);
		}
	}


	public function verifyLoginWithGoogle()
	{

		$this->app = new \config\APP;


		try {
				
			// // Get system settings for Google Login
			// $SystemSettings = new SystemSettingsController;

			// $settings = $SystemSettings->getAll();

			// $Google = new GoogleService($settings['google_login_key'], $settings['google_login_secret']);

			// $code = $this->app->request()->get('code');

		  	// $Google->client->setAccessToken($Google->client->fetchAccessTokenWithAuthCode($code));

		  	// // Check if code is expired or invalid
		  	// if($Google->client->isAccessTokenExpired())
		  	// {
	  		// 	return false;
		  	// }


	  		// // Get user data through API
			// $google_oauth = new Google_Service_Oauth2($Google->client);
			// $user_info = $google_oauth->userinfo->get();

			// // Prepare user data to store
			// $params['email'] = $user_info['email'];
			// $params['first_name'] = $user_info['givenName'];
			// $params['last_name'] = $user_info['familyName'];
			// $params['profile_image'] = $user_info['picture'];
			// $params['role_id'] = 3;

			// // $params['field']['google_id'] = $user_info['id'];

			// $user = $this->repo->getByEmail($params['email']);

			// if (isset($user->id))
			// 	$user->update(['profile_image' => $user_info['picture']]);
			// else 
			// 	$user = $this->repo->store($params);

			// // Check if user saved
			// if (isset($user->id)){
			// 	$this->setSession($user);
		    // 	$this->repo->setCustomCode((object) $user, 'google_id', $user_info['id']);
			// } else {
			// 	return null;
			// }  

			// if (isset($user->field['activation_token']))
			// 	echo $this->app->redirect('./activate-account/'.$user->field['activation_token']);
			// else
			// 	echo $this->app->redirect('/dashboard');

		} catch (Exception $e) {
			return array('error'=>$e->getMessage());
		}


	}


	public function loginWithGoogle()
	{
		// $SystemSettings = new SystemSettingsController;

		// $settings = $SystemSettings->getAll();

		// if (empty($settings['google_login_key']))
		// 	return null;

		// $Google = new GoogleService($settings['google_login_key'],$settings['google_login_secret']);

		// return $Google->client->createAuthUrl();
	}


	/**
	 * User login request
	 */ 
	public function userLogin()
	{
		$this->app = new \config\APP;
		
        $params = $this->app->request()->get('params');

        try {
            
            $checkUser = $this->checkLogin($params['email'], $this->encrypt($params['password']));

            if (!empty($checkUser->id))
            {
                $this->setSession($checkUser);
            	echo json_encode(array('success'=>1, 'result'=>__('Logged in'), 'redirect'=>$this->app->CONF['url']));

            } else {
	            echo json_encode(array('error'=>$checkUser));
            }


        } catch (Exception $e) {
        	throw new Exception("Error Processing Request", 1);
        	
        }
	}


	/**
	 * Check login credentials
	 * 
	 * @return Object / String 
	 */ 
	public function checkLogin($email, $password)
	{

		$checkLogin = $this->repo->checkLogin($email, $password);

		if (empty($checkLogin->id))
		{
            return __("User credentials not valid");
		}

		if (empty($checkLogin->active))
		{
			return __("User account is not active");
			
		}

		return $checkLogin;
	}



	/**
	 * Signup page 
	 * @var Int
	 */
	public function signup()
	{

		$this->app = new \config\APP;

		try {

			if (isset($this->app->auth()->id)) {
				echo $this->app->redirect('/dashboard');
			}

			return render('views/front/signup.html.twig', [
		        'google_login' => $this->loginWithGoogle(),
			]);

		} catch (\Exception $e) {
			throw new \Exception($e->getMessage(), 1);
		}
	} 

	/**
	 * User login request
	 */ 
	public function userSignup()
	{

		$this->app = new \config\APP;
        
        $params = $this->app->request()->get('params');

        try {
            
            $validate = $this->validateSignup($params);


            /**
             * Check if data is Validated or 
             * return the error
             */  
            if (!empty($validate)){
            	echo $validate;
            	return $validate;
            }

            $params['active'] = '0';
            $params['role_id'] = 3;
            $params['active_branch'] = '0';

			$save = $this->repo->store($params);

        	echo json_encode(isset($save->id) 
           	? array('success'=>1, 'result'=>__('Created').__('check_email_for_activation'), 'reload'=>1)
        	: array('error'=> $save ));

    		$this->sendMail($save, "Activate your account", 'activate-account');


        } catch (Exception $e) {
        	throw new Exception("Error Processing Request", 1);
        	
        }
	}

	/**
	 * Validate the password length
	 * 
	 */ 
	public function validateSignup($params)
	{

        if (!empty($this->repo->getByEmail($params['email'])))
			return json_encode(array('error'=>__('Email already found')));

        if (empty($params['email']))
			return json_encode(array('error'=>__('Email required')));

        // if (empty($params['mobile']))
			// return json_encode(array('error'=>__('MOBILE_ERR')));

        if (empty($params['first_name']))
			return json_encode(array('error'=>__('Name required')));

		if (strlen($params['password']) < $this->passLen)
			return __("Password length must be $this->passLen at least ");

	} 

	/**
	 * Validate the password length
	 * 
	 */ 
	public function validatePassword($password)
	{
		if (strlen($password) < $this->passLen)
		{
			throw new \Exception("Password length must be $this->passLen at least ", 1);
		}

	} 


	/**
	 * Check session is valid or not 
	 * 
	 * @return ? AuthModel
	 */ 
	public function checkSession($code = null) 
	{
		$this->AuthModel = new AuthModel($code);

		if (!empty ( $this->AuthModel->checkSession($code) ))
		{
			return $this->repo->find($this->AuthModel->checkSession($code));
		}
	}



	/**
	 * Set session  
	 */ 
	public function setSession($data, $code = null) 
	{

		$this->AuthModel = new AuthModel($code);

		if ($this->AuthModel->setData($data)) 
		{
			return $this->AuthModel->checkSession($code);
		}
	}


	/**
	 * Clear session after logout
	 */ 
	public function unsetSession() 
	{
		
		$this->AuthModel = new AuthModel();
		return $this->AuthModel->unsetSession();
	}

	/**
	 * Encryption algoritm for password storage
	 */ 
	public static function encrypt($value) : String 
	{
		return sha1(md5($value));

	}



	/**
	 * Send email to activate the account
	 */ 
	public function sendMail($user, $subject, $template) 
	{
	
		$this->app = new \config\APP;

		$body =  $this->app->template()->render('views/email/email.html.twig', ['template'=>$template,'user'=>$user, 'app'=>$this->app]);

		$mail = new MailService($user->email, $user->email, $subject, $body);

		try {

			$mail->sendMail();

			return true;

		} catch (Exception $e) {
			throw new Exception("Error Processing Request ". $e->getMessage(), 1);
		}
	}




}
