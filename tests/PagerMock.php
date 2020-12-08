<?php namespace Tests\Pagination;

use Framework\Pagination\Pager;

class PagerMock extends Pager
{
	protected function prepareURL() : void
	{
		$this->setURL('http://localhost');
	}

	public function setEmptyURL() : void
	{
		$this->url = null;
	}
}
