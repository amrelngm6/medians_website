<?php

namespace Medians\Application\Calculator;

class Calculator 

{


	/*
	// @var Float
	*/
	protected $hours;


	/*
	// @var Float
	*/
	protected $price;


	/*
	// @var Float
	*/
	protected $total;



	function __construct($price, $hours, $minutes = 0 )
	{

		$this->hours = (Float) ($hours.'.' . ( ($minutes / 60) * 100 ));
		$this->price = (Float) $price;
	}



	/*
	// Return the total cost

	// @var Float
	// The tax amount to

	// Excute the Calculate to get the total
	// Excute the addCharge to the TAX
	*/
	public  function getCost($tax = 0) : ?Float
	{

		// Excute the Calculate to get the total
		$this->calculate();

		// Excute the addCharge to the TAX
		$this->addCharge($tax);


		return $this->total;
	}


	/*
	// Return the total cost

	// Excute the Calculate to get the total
	*/
	public function calculate() : Float
	{
		$this->total = ($this->hours * $this->price);

		return $this->total;
	}

	/*
	// Return the total cost
	// Excute the Calculate to get the total
	*/
	public function addCharge($tax = 0) : Float
	{
		$this->total = ($this->total + $tax);

		return $this->total;
	}

}
