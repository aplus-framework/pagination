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
use Framework\Language\Language;
use Framework\Pagination\Pager;
use PHPUnit\Framework\TestCase;

/**
 * Class PagerTest.
 */
final class PagerTest extends TestCase
{
	protected Pager $pager;

	protected function setUp() : void
	{
		$_SERVER['HTTP_HOST'] = 'localhost';
		$_SERVER['REQUEST_URI'] = '/';
		$this->pager = new Pager(0, 10, 31);
	}

	public function testGet() : void
	{
		self::assertSame([
			'self' => 1,
			'first' => 1,
			'prev' => null,
			'next' => 2,
			'last' => 4,
		], $this->pager->get());
	}

	public function testGetWithURL() : void
	{
		self::assertSame([
			'self' => 'http://localhost/?page=1',
			'first' => 'http://localhost/?page=1',
			'prev' => null,
			'next' => 'http://localhost/?page=2',
			'last' => 'http://localhost/?page=4',
		], $this->pager->getWithURL());
	}

	/**
	 * @runInSeparateProcess
	 */
	public function testPrepareURL() : void
	{
		$_SERVER['HTTPS'] = 'on';
		$_SERVER['HTTP_HOST'] = 'domain.tld';
		$_SERVER['REQUEST_URI'] = '/';
		$pager = new Pager(0, 10, 31);
		self::assertSame('https://domain.tld/?page=1', $pager->getCurrentPageURL());
		$pager = new Pager(5, 20, 31, null, 'http://foo.com');
		self::assertSame('http://foo.com/?page=5', $pager->getCurrentPageURL());
		$pager = new Pager(10, 20, 31, null, 'http://foo.com/?page=2');
		self::assertSame('http://foo.com/?page=10', $pager->getCurrentPageURL());
	}

	public function testClonedURL() : void
	{
		self::assertSame('http://localhost/', $this->pager->getURL()->getAsString());
		$url = new URL('http://domain.tld/foo?page=2');
		$this->pager->setURL($url);
		self::assertSame('http://domain.tld/foo?page=2', $this->pager->getURL()->getAsString());
		self::assertNotSame($url, $this->pager->getURL());
	}

	public function testQuery() : void
	{
		self::assertSame('page', $this->pager->getQuery());
		$this->pager->setQuery('foo');
		self::assertSame('foo', $this->pager->getQuery());
	}

	public function testSetAllowedQueries() : void
	{
		$this->pager->setURL(
			'http://domain.tld/slug?url=hello.com&page=8&perPage=10&order=desc&foo=bar'
		);
		self::assertSame(
			'http://domain.tld/slug?page=8',
			$this->pager->getURL()->getAsString()
		);
		$this->pager->setAllowedQueries(['order', 'perPage']);
		self::assertSame(
			'http://domain.tld/slug?page=8&perPage=10&order=desc',
			$this->pager->getURL()->getAsString()
		);
		$this->pager->setAllowedQueries(['order']);
		self::assertSame(
			'http://domain.tld/slug?page=8&order=desc',
			$this->pager->getURL()->getAsString()
		);
	}

	public function testSurround() : void
	{
		self::assertSame(2, $this->pager->getSurround());
		$this->pager->setSurround(5);
		self::assertSame(5, $this->pager->getSurround());
	}

	public function testPageURL() : void
	{
		self::assertSame('http://localhost/?page=1', $this->pager->getCurrentPageURL());
		self::assertNull($this->pager->getPreviousPageURL());
		self::assertSame('http://localhost/?page=1', $this->pager->getFirstPageURL());
		self::assertSame('http://localhost/?page=2', $this->pager->getNextPageURL());
		self::assertSame('http://localhost/?page=4', $this->pager->getLastPageURL());
		self::assertSame('http://localhost/?page=15', $this->pager->getPageURL(15));
	}

	/**
	 * @runInSeparateProcess
	 */
	public function testPreviousPagesURLsWithSurroundGreaterThanCurrentPage() : void
	{
		$_SERVER['HTTP_HOST'] = 'domain.tld';
		$_SERVER['REQUEST_URI'] = '/';
		$pager = new Pager(3, 10, 100);
		$pager->setSurround(5);
		self::assertSame([
			1 => 'http://domain.tld/?page=1',
			2 => 'http://domain.tld/?page=2',
		], $pager->getPreviousPagesURLs());
	}

	public function testPageMaxLimit() : void
	{
		$this->pager = new Pager(500, 10, 30);
		self::assertSame('http://localhost/?page=500', $this->pager->getCurrentPageURL());
		self::assertSame('http://localhost/?page=3', $this->pager->getPreviousPageURL());
		self::assertSame('http://localhost/?page=1', $this->pager->getFirstPageURL());
		self::assertNull($this->pager->getNextPageURL());
		self::assertSame('http://localhost/?page=3', $this->pager->getLastPageURL());
		self::assertSame('http://localhost/?page=15', $this->pager->getPageURL(15));
	}

	public function testPageMinLimit() : void
	{
		$this->pager = new Pager(-5, 10, 30);
		self::assertSame('http://localhost/?page=1', $this->pager->getCurrentPageURL());
		self::assertNull($this->pager->getPreviousPageURL());
		self::assertSame('http://localhost/?page=1', $this->pager->getFirstPageURL());
		self::assertSame('http://localhost/?page=2', $this->pager->getNextPageURL());
		self::assertSame('http://localhost/?page=3', $this->pager->getLastPageURL());
		self::assertSame('http://localhost/?page=15', $this->pager->getPageURL(15));
	}

	public function testLanguageInstance() : void
	{
		self::assertInstanceOf(Language::class, $this->pager->getLanguage());
	}

	public function testViews() : void
	{
		$views = [
			'head' => \realpath(__DIR__ . '/../src/Views/head.php'),
			'header' => \realpath(__DIR__ . '/../src/Views/header.php'),
			'pager' => \realpath(__DIR__ . '/../src/Views/pagination-short.php'),
			'pagination' => \realpath(__DIR__ . '/../src/Views/pagination.php'),
			'pagination-short' => \realpath(__DIR__ . '/../src/Views/pagination-short.php'),
			'bootstrap' => \realpath(__DIR__ . '/../src/Views/bootstrap.php'),
			'bootstrap-short' => \realpath(__DIR__ . '/../src/Views/bootstrap-short.php'),
			'bootstrap4' => \realpath(__DIR__ . '/../src/Views/bootstrap.php'),
			'bootstrap4-short' => \realpath(__DIR__ . '/../src/Views/bootstrap-short.php'),
			'bootstrap5' => \realpath(__DIR__ . '/../src/Views/bootstrap.php'),
			'bootstrap5-short' => \realpath(__DIR__ . '/../src/Views/bootstrap-short.php'),
			'bulma' => \realpath(__DIR__ . '/../src/Views/bulma.php'),
			'bulma-short' => \realpath(__DIR__ . '/../src/Views/bulma-short.php'),
			'semantic-ui' => \realpath(__DIR__ . '/../src/Views/semantic-ui.php'),
			'semantic-ui-short' => \realpath(__DIR__ . '/../src/Views/semantic-ui-short.php'),
			'semantic-ui2' => \realpath(__DIR__ . '/../src/Views/semantic-ui.php'),
			'semantic-ui2-short' => \realpath(__DIR__ . '/../src/Views/semantic-ui-short.php'),
		];
		self::assertSame($views, $this->pager->getViews());
		$this->pager->setView('foo', __FILE__);
		self::assertSame(__FILE__, $this->pager->getView('foo'));
		$views['foo'] = __FILE__;
		self::assertSame($views, $this->pager->getViews());
	}

	public function testDefaultView() : void
	{
		self::assertSame('pagination', $this->pager->getDefaultView());
		$this->pager->setDefaultView('bootstrap4');
		self::assertSame('bootstrap4', $this->pager->getDefaultView());
		$this->expectException(\LogicException::class);
		$this->expectExceptionMessage('Default view is not a valid value');
		$this->pager->setDefaultView('unknown');
	}

	public function testSetInvalidViewPath() : void
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Invalid Pager view filepath: ' . __DIR__ . '/foo');
		$this->pager->setView('foo', __DIR__ . '/foo');
	}

	public function testViewNotSet() : void
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Pager view not found: foo');
		$this->pager->getView('foo');
	}

	/**
	 * @runInSeparateProcess
	 */
	public function testRender() : void
	{
		$this->pager = new Pager(3, 10, 31);
		self::assertStringContainsString(
			'<ul class="pagination">',
			$this->pager->render()
		);
		self::assertStringContainsString(
			'<ul class="pagination">',
			$this->pager->render('pagination')
		);
		self::assertStringContainsString(
			'<ul class="pagination">',
			$this->pager->render('pager')
		);
		self::assertStringContainsString(
			'rel="next"',
			$this->pager->render('header')
		);
		self::assertStringContainsString(
			'<link rel="canonical"',
			$this->pager->render('head')
		);
	}

	/**
	 * @runInSeparateProcess
	 */
	public function testRenderShort() : void
	{
		$this->pager->setDefaultView('pagination');
		self::assertStringContainsString('rel="canonical"', $this->pager->render());
		self::assertStringNotContainsString('rel="canonical"', $this->pager->renderShort());
	}

	/**
	 * @runInSeparateProcess
	 */
	public function testToString() : void
	{
		self::assertStringContainsString(
			'<ul class="pagination">',
			(string) $this->pager
		);
	}

	public function testJsonSerializable() : void
	{
		self::assertSame(
			\json_encode($this->pager->getWithURL()),
			\json_encode($this->pager)
		);
	}
}
