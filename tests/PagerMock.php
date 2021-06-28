<?php
/*
 * This file is part of The Framework Pagination Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Tests\Pagination;

use Framework\HTTP\URL;
use Framework\Pagination\Pager;

class PagerMock extends Pager
{
	public ?URL $url = null;
}
