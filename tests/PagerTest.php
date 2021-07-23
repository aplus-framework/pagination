<?php
/*
 * This file is part of Aplus Framework Pagination Library.
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

    public function testGetWithUrl() : void
    {
        self::assertSame([
            'self' => 'http://localhost/?page=1',
            'first' => 'http://localhost/?page=1',
            'prev' => null,
            'next' => 'http://localhost/?page=2',
            'last' => 'http://localhost/?page=4',
        ], $this->pager->getWithUrl());
    }

    /**
     * @runInSeparateProcess
     */
    public function testPrepareUrl() : void
    {
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['HTTP_HOST'] = 'domain.tld';
        $_SERVER['REQUEST_URI'] = '/';
        $pager = new Pager(0, 10, 31);
        self::assertSame('https://domain.tld/?page=1', $pager->getCurrentPageUrl());
        $pager = new Pager(5, 20, 31, null, 'http://foo.com');
        self::assertSame('http://foo.com/?page=5', $pager->getCurrentPageUrl());
        $pager = new Pager(10, 20, 31, null, 'http://foo.com/?page=2');
        self::assertSame('http://foo.com/?page=10', $pager->getCurrentPageUrl());
    }

    public function testClonedUrl() : void
    {
        self::assertSame('http://localhost/', $this->pager->getUrl()->getAsString());
        $url = new URL('http://domain.tld/foo?page=2');
        $this->pager->setUrl($url);
        self::assertSame('http://domain.tld/foo?page=2', $this->pager->getUrl()->getAsString());
        self::assertNotSame($url, $this->pager->getUrl());
    }

    public function testQuery() : void
    {
        self::assertSame('page', $this->pager->getQuery());
        $this->pager->setQuery('foo');
        self::assertSame('foo', $this->pager->getQuery());
    }

    public function testSetAllowedQueries() : void
    {
        $this->pager->setUrl(
            'http://domain.tld/slug?Url=hello.com&page=8&perPage=10&order=desc&foo=bar'
        );
        self::assertSame(
            'http://domain.tld/slug?page=8',
            $this->pager->getUrl()->getAsString()
        );
        $this->pager->setAllowedQueries(['order', 'perPage']);
        self::assertSame(
            'http://domain.tld/slug?page=8&perPage=10&order=desc',
            $this->pager->getUrl()->getAsString()
        );
        $this->pager->setAllowedQueries(['order']);
        self::assertSame(
            'http://domain.tld/slug?page=8&order=desc',
            $this->pager->getUrl()->getAsString()
        );
    }

    public function testSurround() : void
    {
        self::assertSame(2, $this->pager->getSurround());
        $this->pager->setSurround(5);
        self::assertSame(5, $this->pager->getSurround());
    }

    public function testPageUrl() : void
    {
        self::assertSame('http://localhost/?page=1', $this->pager->getCurrentPageUrl());
        self::assertNull($this->pager->getPreviousPageUrl());
        self::assertSame('http://localhost/?page=1', $this->pager->getFirstPageUrl());
        self::assertSame('http://localhost/?page=2', $this->pager->getNextPageUrl());
        self::assertSame('http://localhost/?page=4', $this->pager->getLastPageUrl());
        self::assertSame('http://localhost/?page=15', $this->pager->getPageUrl(15));
    }

    /**
     * @runInSeparateProcess
     */
    public function testPreviousPagesUrlsWithSurroundGreaterThanCurrentPage() : void
    {
        $_SERVER['HTTP_HOST'] = 'domain.tld';
        $_SERVER['REQUEST_URI'] = '/';
        $pager = new Pager(3, 10, 100);
        $pager->setSurround(5);
        self::assertSame([
            1 => 'http://domain.tld/?page=1',
            2 => 'http://domain.tld/?page=2',
        ], $pager->getPreviousPagesUrls());
    }

    public function testPageMaxLimit() : void
    {
        $this->pager = new Pager(500, 10, 30);
        self::assertSame('http://localhost/?page=500', $this->pager->getCurrentPageUrl());
        self::assertSame('http://localhost/?page=3', $this->pager->getPreviousPageUrl());
        self::assertSame('http://localhost/?page=1', $this->pager->getFirstPageUrl());
        self::assertNull($this->pager->getNextPageUrl());
        self::assertSame('http://localhost/?page=3', $this->pager->getLastPageUrl());
        self::assertSame('http://localhost/?page=15', $this->pager->getPageUrl(15));
    }

    public function testPageMinLimit() : void
    {
        $this->pager = new Pager(-5, 10, 30);
        self::assertSame('http://localhost/?page=1', $this->pager->getCurrentPageUrl());
        self::assertNull($this->pager->getPreviousPageUrl());
        self::assertSame('http://localhost/?page=1', $this->pager->getFirstPageUrl());
        self::assertSame('http://localhost/?page=2', $this->pager->getNextPageUrl());
        self::assertSame('http://localhost/?page=3', $this->pager->getLastPageUrl());
        self::assertSame('http://localhost/?page=15', $this->pager->getPageUrl(15));
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
            \json_encode($this->pager->getWithUrl()),
            \json_encode($this->pager)
        );
    }
}
