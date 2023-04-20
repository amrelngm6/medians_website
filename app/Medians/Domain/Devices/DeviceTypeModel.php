<?php

namespace Medians\Domain\Devices;

class DeviceTypeModel 
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
	/ @var Int
	*/
	private $publish;


	function __construct($data)
	{

		$data = (object) $data;

		$this->id = isset($data->id) ? $data->id : '';	

		$this->title = isset($data->title) ? $data->title : '';

		$this->publish = isset($data->publish) ? $data->publish : 0;

	}


	public static function applyId($id) : DeviceTypeModel
	{
		return new self(array('id'=> $id));
	}


	public function id() : ?String
	{
		return $this->id;
	}


	public function title() : String
	{
		return $this->title;
	}


	public function publish() : String
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

	public function setPublish($publish) : String
	{
		$this->publish = $publish;
	}


}
