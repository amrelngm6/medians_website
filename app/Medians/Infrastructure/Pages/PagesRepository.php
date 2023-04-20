<?php

namespace Medians\Infrastructure\Pages;


use Medians\Infrastructure\Pages;
use Medians\Infrastructure\Content\ContentRepository;

use Medians\Domain\Pages\Page;
use Medians\Domain\Content\PagesContent;

use Shared\dbaser\CustomController;


class PagesRepository extends CustomController 
{

	/*
	/ @var String
	*/
	protected $table = 'pages';

	protected $fillable = [
    	'name',
    	'status',
    	'updated_by'
	];


	/*
	/ @var Int
	*/
	private $id;


	/*
	/ @var Array
	*/
	private $data = array();

	public $timestamps = false;


	function __construct()
	{
	}

	/*
	// return $table String 
	*/
	public function getTable() : String
	{
		return $this->table;
	}


	/**
	 * Relation with content
	 * 
	 */
	public function content()
	{
		return $this->morphMany(ContentRepository::class, 'model' );
	}

	/*
	// Set `id` 
	*/
	public function setId($id)
	{
		$this->id = $id;
	}



	/*
	// Set `data` 
	*/
	public function setData($data)
	{
		$this->data = $data;
	}


	/**
	* Get index page Pages 
	*/
	public function list()
	{
		return $this->where('status', 1)->get();
	}




}
