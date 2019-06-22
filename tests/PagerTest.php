<?php namespace Tests\Pagination;

use Framework\Pagination\Pager;
use PHPUnit\Framework\TestCase;

class PagerTest extends TestCase
{
	/**
	 * @var Pager
	 */
	protected $pager;

	protected function setUp()
	{
		$this->pager = new Pager(0, 10, 31, [
			[
				'id' => 1,
			],
		]);
	}

	public function testQuery()
	{
		$this->assertEquals('page', $this->pager->getQuery());
		$this->pager->setQuery('foo');
		$this->assertEquals('foo', $this->pager->getQuery());
	}

	public function testSurround()
	{
		$this->assertEquals(3, $this->pager->getSurround());
		$this->pager->setSurround(5);
		$this->assertEquals(5, $this->pager->getSurround());
	}

	public function testPageURL()
	{
		$this->pager->setURL('http://localhost');
		$this->assertEquals('http://localhost/?page=1', $this->pager->getCurrentPageURL());
		$this->assertNull($this->pager->getPreviousPageURL());
		$this->assertEquals('http://localhost/?page=1', $this->pager->getFirstPageURL());
		$this->assertEquals('http://localhost/?page=2', $this->pager->getNextPageURL());
		$this->assertEquals('http://localhost/?page=4', $this->pager->getLastPageURL());
		$this->assertEquals('http://localhost/?page=15', $this->pager->getPageURL(15));
	}

	public function testItems()
	{
		$this->assertEquals([
			[
				'id' => 1,
			],
		], $this->pager->getItems());
	}
}
