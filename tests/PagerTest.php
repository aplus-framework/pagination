<?php namespace Tests\Pagination;

use Framework\Language\Language;
use PHPUnit\Framework\TestCase;
use Tests\Pagination\PagerMock as Pager;

class PagerTest extends TestCase
{
	protected Pager $pager;

	protected function setUp() : void
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
		$this->assertEquals('http://localhost/?page=1', $this->pager->getCurrentPageURL());
		$this->assertNull($this->pager->getPreviousPageURL());
		$this->assertEquals('http://localhost/?page=1', $this->pager->getFirstPageURL());
		$this->assertEquals('http://localhost/?page=2', $this->pager->getNextPageURL());
		$this->assertEquals('http://localhost/?page=4', $this->pager->getLastPageURL());
		$this->assertEquals('http://localhost/?page=15', $this->pager->getPageURL(15));
	}

	public function testPageMaxLimit()
	{
		$this->pager = new Pager(500, 10, 30, []);
		$this->assertEquals('http://localhost/?page=500', $this->pager->getCurrentPageURL());
		$this->assertEquals('http://localhost/?page=3', $this->pager->getPreviousPageURL());
		$this->assertEquals('http://localhost/?page=1', $this->pager->getFirstPageURL());
		$this->assertNull($this->pager->getNextPageURL());
		$this->assertEquals('http://localhost/?page=3', $this->pager->getLastPageURL());
		$this->assertEquals('http://localhost/?page=15', $this->pager->getPageURL(15));
	}

	public function testPageMinLimit()
	{
		$this->pager = new Pager(-5, 10, 30, []);
		$this->assertEquals('http://localhost/?page=1', $this->pager->getCurrentPageURL());
		$this->assertNull($this->pager->getPreviousPageURL());
		$this->assertEquals('http://localhost/?page=1', $this->pager->getFirstPageURL());
		$this->assertEquals('http://localhost/?page=2', $this->pager->getNextPageURL());
		$this->assertEquals('http://localhost/?page=3', $this->pager->getLastPageURL());
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
			'bootstrap4' => \realpath(__DIR__ . '/../src/Views/bootstrap4.php'),
			'bulma' => \realpath(__DIR__ . '/../src/Views/bulma.php'),
			'semantic-ui' => \realpath(__DIR__ . '/../src/Views/semantic-ui.php'),
		];
		$this->assertEquals($views, $this->pager->getViews());
		$this->pager->setView('foo', __FILE__);
		$this->assertEquals(__FILE__, $this->pager->getView('foo'));
		$views['foo'] = __FILE__;
		$this->assertEquals($views, $this->pager->getViews());
	}

	public function testDefaultView()
	{
		$this->assertEquals('pagination', $this->pager->getDefaultView());
		$this->pager->setDefaultView('bootstrap4');
		$this->assertEquals('bootstrap4', $this->pager->getDefaultView());
		$this->expectException(\LogicException::class);
		$this->expectExceptionMessage('Default view is not a valid value');
		$this->pager->setDefaultView('unknown');
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

	/**
	 * @runInSeparateProcess
	 */
	public function testRender()
	{
		$this->pager = new Pager(3, 10, 31, []);
		$this->assertStringContainsString(
			'<ul class="pagination">',
			$this->pager->render()
		);
		$this->assertStringContainsString(
			'<ul class="pagination">',
			$this->pager->render('pagination')
		);
		$this->assertStringContainsString(
			'<ul class="pagination">',
			$this->pager->render('pager')
		);
		$this->assertStringContainsString(
			'rel="next"',
			$this->pager->render('header')
		);
		$this->assertStringContainsString(
			'<link rel="canonical"',
			$this->pager->render('head')
		);
	}

	/**
	 * @runInSeparateProcess
	 */
	public function testToString()
	{
		$this->assertStringContainsString(
			'<ul class="pagination">',
			(string) $this->pager
		);
	}

	public function testJsonSerializable()
	{
		$this->assertEquals(
			\json_encode($this->pager->get(true)),
			\json_encode($this->pager)
		);
	}
}
