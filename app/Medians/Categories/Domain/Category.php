<?php

namespace Medians\Categories\Domain;

use Shared\dbaser\CustomModel;

use Medians\Blog\Domain\Blog;

class Category extends CustomModel
{

	/*
	/ @var String
	*/
	protected $table = 'categories';

	public $fillable = [
		'name',
		'branch_id',
		'model',
		'status',
	];


	/**
	 * Disable create & update times fields
	 */ 
	public $timestamps = false;


	public function getFields()
	{
		return $this->fillable;
	}

	public static function byModel($Model)
	{
		return Category::where('model', $Model)->where('status', 'on')->get();
	}


	public function blog()
	{
		return $this->hasMany(Blog::class, 'category_id', 'id');
	}


}
