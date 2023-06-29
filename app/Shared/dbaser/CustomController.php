<?php

namespace Shared\dbaser;


/**
 * Using this class for the common
 * functions between Controllers
 * and services inside APP layer
 * 
 */   
class CustomController 
{

	/**
	* @var Object
	*/
	protected $repo;

	public $app ;

	public $client ;

	public $blogRepo;
	public $doctorRepo;
	public $specsRepo;
	public $storiesRepo;
	public $storyDateRepo;
	public $pagesRepo;
	public $offersRepo;
	public $categoryRepo;
	public $contentRepo;
	public $storyRepo;

	/**
	 * Check if the user can Access specific feature
	 * by code pf the feature
	 * 
	 * Return upgrade message as JSON 	 
	 * 
	 * @param $code String => Code of the feature 
	 * @param $currentCount Int =>  current usage of the feature  
	 * 
	 * @return JSON
	 * 
	 */ 
	public function checkFeatureAccess(String $code, $currentCount)
	{

		$this->app = new \config\APP;

		$branchFeatures = (object) $this->app->branch->features;

		if (empty($branchFeatures->$code)){
			echo json_encode(['error'=>__('plan_limit_exceeded_upgrade_now')]); die();
		}

		if (isset($branchFeatures->$code) && $branchFeatures->$code <= $currentCount && $branchFeatures->$code > -1 ){
			echo json_encode(['error'=>__('plan_limit_exceeded_upgrade_now')]); die();
		}

	}


	/**
	 * Check if the user can created and assigned 
	 * to a branch 
	 * if Not he should be redirected to Get-Started page
	 * 
	 * Redirect to /get-started page 	 
	 * 
	 * @param $code String => Code of the feature 
	 * @param $limit Int =>  current usage of the feature  
	 * 
	 * @return JSON
	 * 
	 */ 
	public function checkBranch()
	{
		$this->app = new \config\APP;

		$checkUser = $this->app->auth();
		
		if (isset($checkUser->id) && $checkUser->id === 1)
			return true;

		if (isset($checkUser->id) && empty($checkUser->active_branch))
			echo $this->app->redirect('/get-started'); 

		if (empty($this->app->branch->plan))
			echo $this->app->redirect('/get-started'); 

	}


}



