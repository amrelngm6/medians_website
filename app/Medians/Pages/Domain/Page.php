<?php

namespace Medians\Pages\Domain;

use Shared\dbaser\CustomModel;

use Medians\Content\Domain\Content;

class Page extends CustomModel
{

	/*
	/ @var String
	*/
	protected $table = 'pages';

	public $fillable = [
		'title', 
		'order', 
		'home', 
	];


	public $appends = ['photo','field','name'];



	public function getFieldAttribute() 
	{
		return !empty($this->custom_fields) ? array_column($this->custom_fields->toArray(), 'value', 'code') : [];
	}

	public function getNameAttribute() : ?String
	{
		return isset($this->content->title) ? $this->content->title : $this->title;
	}

	public function getPhotoAttribute() : ?String
	{
		return $this->photo();
	}


	public function photo() : String
	{
		return !empty($this->picture) ? $this->picture : '/uploads/images/default_profile.jpg';
	}

	public function getFields()
	{
		return $this->fillable;
	}

	public function category()
	{
		return $this->hasOne(Category::getClass(), 'id', 'category_id')->with('content');
	}

	public function content()
	{
		return $this->hasOne(Content::class, 'item_id', 'id')->where('item_type', Page::class);
	}

	public function custom_fields()
	{
		return $this->morphMany(CustomFields::class, 'item')->with('field');
	}




}
