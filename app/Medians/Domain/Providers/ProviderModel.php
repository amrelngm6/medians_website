<?php

namespace Medians\Domain\Providers;

class ProviderModel 
{

	/*
	/ @var int
	*/
	private $id;


	/*
	/ @var String
	*/
	private $title;



	/*
	/ @var int
	*/
	private $publish;





	function __construct($data)
	{

		$data = (object) $data;

		$this->setId(isset($data->id) ? $data->id : 0);
		
		$this->setTitle( isset($data->title) ? $data->title : '');


		$this->setPublish( !empty($data->publish) ? '1' : '0');
	
	}


	public static function create($data) : ProviderModel
	{
		return new self($data);
	}

	public function id() : String
	{
		return $this->id;
	}


	public function title() : String
	{
		return $this->title;
	}


	public function publish() : ?String
	{
		return $this->publish;
	}


	public function setId($id) : void
	{
		$this->id = $id;
	}

	public function setTitle($title) : void
	{
		$this->title = $title;
	}


	public function setPublish($publish) : void
	{
		$this->publish = $publish;
	}


}
