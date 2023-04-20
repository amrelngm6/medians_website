<?php

namespace Medians\Domain\Settings;


class SettingsModel 
{

	/*
	/ @var int
	*/
	private $id;


	/*
	/ @var String
	*/
	private $code;


	/*
	/ @var String
	*/
	private $value;



	function __construct($code = null, $value = null)
	{
		$this->code = $code;
		$this->value = $value;
	}

	public static function create($data)
	{
		$data = (object) $data;
		return new self(isset($data->code) ? $data->code : '', isset($data->value) ? $data->value : '');
	}


	public function id() : ?int
	{
		return $this->id;
	}


	public function code() : String
	{
		return $this->code;
	}


	public function value() : ?String
	{
		return $this->value;
	}




	public function setId($id) : void
	{
		$this->id = $id;
	}

	public function setCode($code) : void
	{
		$this->code = $code;
	}

	public function setValue($value) : void
	{
		$this->value = $value;
	}


}
