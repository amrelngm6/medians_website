<?php

namespace helper;


class Lang
{
	public $lang;
	
	function __construct($lang)
	{
		$this->lang = $lang;
	}

	public function load()
	{

		switch ($this->lang) 
		{
			case 'arabic':
			case 'ar':
				return new langs\LangsAr();
				break;
			
			default:
				return new langs\LangsEn();
				break;
		}
	}

}

