<?php

namespace Medians\Branches\Infrastructure;

use Medians\Branches\Domain\Branch;


class BranchRepository   
{




	public function get()
	{

		return Branch::get();

	}


	public function find($id)
	{

		return Branch::find($id);

	}



}
