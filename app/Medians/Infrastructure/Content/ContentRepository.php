<?php

namespace Medians\Infrastructure\Content;

use Shared\dbaser\CustomController;


class ContentRepository extends CustomController 
{

	/*
	/ @var String
	*/
	protected $table = 'pages_content';

	/**
	* @var String
	*/
    protected $fillable = [
    	'code',
    	'value',
    	'model_type',
    	'model_id',
    	'lang',
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



}
