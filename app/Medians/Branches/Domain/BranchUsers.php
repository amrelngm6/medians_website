<?php

namespace Medians\Branches\Domain;


use Shared\dbaser\CustomModel;

class BranchUsers extends CustomModel
{


	/**
	* @var String
	*/
	protected $table = 'branch_users';

	/**
	* @var Array
	*/
	protected $fillable = [
    	'branch_id',
    	'user_id'
	];

	public $timestamps = null;

	public function branch()
	{
		return $this->hasOne(
			Branch::class,  'id', 'branch_id'
		);
	}
}
