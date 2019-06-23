<?php namespace Tests\Pagination;

use Framework\Language\Language;
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

	public function testLanguageInstance()
	{
		$this->assertInstanceOf(Language::class, $this->pager->getLanguage());
	}

	public function testViews()
	{
		$views = [
			'head' => \realpath(__DIR__ . '/../src/Views/head.php'),
			'header' => \realpath(__DIR__ . '/../src/Views/header.php'),
			'pager' => \realpath(__DIR__ . '/../src/Views/pager.php'),
			'pagination' => \realpath(__DIR__ . '/../src/Views/pagination.php'),
		];
		$this->assertEquals($views, $this->pager->getViews());
		$this->pager->setView('foo', __FILE__);
		$this->assertEquals(__FILE__, $this->pager->getView('foo'));
		$views['foo'] = __FILE__;
		$this->assertEquals($views, $this->pager->getViews());
	}

	public function testSetInvalidViewPath()
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Invalid Pager view filepath: ' . __DIR__ . '/foo');
		$this->pager->setView('foo', __DIR__ . '/foo');
	}

	public function testViewNotSet()
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Pager view not found: foo');
		$this->pager->getView('foo');
	}
}
