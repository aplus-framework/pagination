<?php namespace Tests\Pagination;

use Framework\HTTP\URL;
use Framework\Pagination\Pager;

class PagerMock extends Pager
{
	public ?URL $url = null;
}
