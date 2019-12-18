<?php namespace Tests\Pagination;

use Framework\Pagination\Pager;

class PagerMock extends Pager
{
	protected function prepareURL()
	{
		$this->setURL('http://localhost');
	}
}
