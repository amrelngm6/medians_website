<?php

namespace Medians\Customers\Domain;

use Shared\dbaser\CustomModel;

class Customer extends CustomModel
{

	/*
	/ @var String
	*/
	protected $table = 'customers';

	public $fillable = [
		'stage_id',
		'first_name',
		'last_name',
		'email',
		'phone',
		'business_type',
		'created_by',
		'agent_id',
		'source_type',
		'source_id',
	];



	public $appends = ['name', 'photo'];

	public function getNameAttribute() : String
	{
		return $this->name();
	}

	public function getPhotoAttribute() : ?String
	{
		return $this->photo();
	}


	public function photo() : String
	{
		return !empty($this->profile_image) ? $this->profile_image : '/uploads/images/default_profile.jpg';
	}

	public function name() : String
	{
		return $this->first_name.' '.$this->last_name;
	}



	public function getFields()
	{
		return array_filter(array_map(function ($q) 
		{
			return $q;
		}, $this->fillable));
	}


	/** 
	 * Render options values
	 */ 
	public function renderOptions($category)
	{
		return (object) array_column(
				array_map(function($q) use ($category) {
					if ($q->category == $category) { return $q; }
				}, (array) json_decode($this->SelectedOption))
			, 'value', 'code');

	}



}
