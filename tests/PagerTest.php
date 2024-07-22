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
        self::assertSame('http://localhost/', $this->pager->getUrl()->toString());
        $url = new URL('http://domain.tld/foo?page=2');
        $this->pager->setUrl($url);
        self::assertSame('http://domain.tld/foo?page=2', $this->pager->getUrl()->toString());
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
            'http://domain.tld/slug?Url=hello.com&page=8&perPage=10&order=desc&foo=bar',
            $this->pager->getUrl()->toString()
        );
        $this->pager->setAllowedQueries(null);
        self::assertSame(
            'http://domain.tld/slug?Url=hello.com&page=8&perPage=10&order=desc&foo=bar',
            $this->pager->getUrl()->toString()
        );
        $this->pager->setAllowedQueries([]);
        self::assertSame(
            'http://domain.tld/slug?page=8',
            $this->pager->getUrl()->toString()
        );
        $this->pager->setAllowedQueries(['order', 'perPage']);
        self::assertSame(
            'http://domain.tld/slug?page=8&perPage=10&order=desc',
            $this->pager->getUrl()->toString()
        );
        $this->pager->setAllowedQueries(['order']);
        self::assertSame(
            'http://domain.tld/slug?page=8&order=desc',
            $this->pager->getUrl()->toString()
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
    public function testPageUrlWithXss() : void
    {
        $_SERVER['HTTP_HOST'] = 'localhost';
        $_SERVER['REQUEST_URI'] = '/?foo=<script>alert("xss")</script>';
        $this->pager = new Pager(0, 10, 31);
        self::assertSame(
            'http://localhost/?foo=%3Cscript%3Ealert%28%22xss%22%29%3C%2Fscript%3E&page=1',
            $this->pager->getCurrentPageUrl()
        );
        self::assertNull($this->pager->getPreviousPageUrl());
        self::assertSame(
            'http://localhost/?foo=%3Cscript%3Ealert%28%22xss%22%29%3C%2Fscript%3E&page=1',
            $this->pager->getFirstPageUrl()
        );
        self::assertSame(
            'http://localhost/?foo=%3Cscript%3Ealert%28%22xss%22%29%3C%2Fscript%3E&page=2',
            $this->pager->getNextPageUrl()
        );
        self::assertSame(
            'http://localhost/?foo=%3Cscript%3Ealert%28%22xss%22%29%3C%2Fscript%3E&page=4',
            $this->pager->getLastPageUrl()
        );
        self::assertSame(
            'http://localhost/?foo=%3Cscript%3Ealert%28%22xss%22%29%3C%2Fscript%3E&page=15',
            $this->pager->getPageUrl(15)
        );
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
        $language = new Language();
        $this->pager->setLanguage($language);
        self::assertSame($language, $this->pager->getLanguage());
        $pager = new Pager(3, 10, 100, $language);
        self::assertSame($language, $pager->getLanguage());
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
            'bootstrap5' => \realpath(__DIR__ . '/../src/Views/bootstrap.php'),
            'bootstrap5-short' => \realpath(__DIR__ . '/../src/Views/bootstrap-short.php'),
            'bulma' => \realpath(__DIR__ . '/../src/Views/bulma.php'),
            'bulma-short' => \realpath(__DIR__ . '/../src/Views/bulma-short.php'),
            'bulma1' => \realpath(__DIR__ . '/../src/Views/bulma.php'),
            'bulma1-short' => \realpath(__DIR__ . '/../src/Views/bulma-short.php'),
            'foundation' => \realpath(__DIR__ . '/../src/Views/foundation.php'),
            'foundation-short' => \realpath(__DIR__ . '/../src/Views/foundation-short.php'),
            'foundation6' => \realpath(__DIR__ . '/../src/Views/foundation.php'),
            'foundation6-short' => \realpath(__DIR__ . '/../src/Views/foundation-short.php'),
            'materialize' => \realpath(__DIR__ . '/../src/Views/materialize.php'),
            'materialize-short' => \realpath(__DIR__ . '/../src/Views/materialize-short.php'),
            'materialize1' => \realpath(__DIR__ . '/../src/Views/materialize.php'),
            'materialize1-short' => \realpath(__DIR__ . '/../src/Views/materialize-short.php'),
            'primer' => \realpath(__DIR__ . '/../src/Views/primer.php'),
            'primer-short' => \realpath(__DIR__ . '/../src/Views/primer-short.php'),
            'primer20' => \realpath(__DIR__ . '/../src/Views/primer.php'),
            'primer20-short' => \realpath(__DIR__ . '/../src/Views/primer-short.php'),
            'semantic-ui' => \realpath(__DIR__ . '/../src/Views/semantic-ui.php'),
            'semantic-ui-short' => \realpath(__DIR__ . '/../src/Views/semantic-ui-short.php'),
            'semantic-ui2' => \realpath(__DIR__ . '/../src/Views/semantic-ui.php'),
            'semantic-ui2-short' => \realpath(__DIR__ . '/../src/Views/semantic-ui-short.php'),
            'tailwind' => \realpath(__DIR__ . '/../src/Views/tailwind.php'),
            'tailwind-short' => \realpath(__DIR__ . '/../src/Views/tailwind-short.php'),
            'tailwind3' => \realpath(__DIR__ . '/../src/Views/tailwind.php'),
            'tailwind3-short' => \realpath(__DIR__ . '/../src/Views/tailwind-short.php'),
            'w3' => \realpath(__DIR__ . '/../src/Views/w3.php'),
            'w3-short' => \realpath(__DIR__ . '/../src/Views/w3-short.php'),
            'w34' => \realpath(__DIR__ . '/../src/Views/w3.php'),
            'w34-short' => \realpath(__DIR__ . '/../src/Views/w3-short.php'),
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
        $this->pager->setDefaultView('bootstrap');
        self::assertSame('bootstrap', $this->pager->getDefaultView());
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Default view "unknown" is not a valid value');
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

    /**
     * @dataProvider viewsProvider
     *
     * @runInSeparateProcess
     */
    public function testPaginationViews(string $view) : void
    {
        $pager = new Pager(4, 10, 100);
        $contents = $pager->render($view);
        self::assertStringContainsString('First', $contents);
        self::assertStringContainsString('Previous', $contents);
        self::assertStringContainsString('Next', $contents);
        self::assertStringContainsString('Last', $contents);
    }

    /**
     * @dataProvider previousDisabledProvider
     *
     * @runInSeparateProcess
     *
     * @param string $view
     * @param string $needle
     */
    public function testPreviousIsDisabled(string $view, string $needle) : void
    {
        $pager = new Pager(1, 10, 100);
        $contents = $pager->render($view);
        self::assertStringContainsString($needle, $contents);
    }

    /**
     * @dataProvider nextDisabledProvider
     *
     * @runInSeparateProcess
     *
     * @param string $view
     * @param string $needle
     */
    public function testNextIsDisabled(string $view, string $needle) : void
    {
        $pager = new Pager(10, 10, 100);
        $contents = $pager->render($view);
        self::assertStringContainsString($needle, $contents);
    }

    /**
     * @runInSeparateProcess
     */
    public function testPrimerGap() : void
    {
        $pager = new Pager(5, 10, 100);
        $contents = $pager->render('primer');
        self::assertStringContainsString('<span class="gap">…</span>', $contents);
    }

    /**
     * @dataProvider viewsProvider
     *
     * @runInSeparateProcess
     */
    public function testShortViews(string $view) : void
    {
        $pager = new Pager(4, 10, 100);
        $contents = $pager->render($view . '-short');
        self::assertStringContainsString('Previous', $contents);
        self::assertStringContainsString('Next', $contents);
    }

    public function testSanitize() : void
    {
        self::assertSame(1, Pager::sanitize([]));
        self::assertSame(1, Pager::sanitize(-5));
        self::assertSame(1, Pager::sanitize('-' . \PHP_INT_MIN . '123'));
        self::assertSame(\PHP_INT_MAX, Pager::sanitize(\PHP_INT_MAX . '123'));
        self::assertSame(\PHP_INT_MAX - 1, Pager::sanitize(\PHP_INT_MAX - 1));
    }

    /**
     * @return array<array<string>>
     */
    public static function viewsProvider() : array
    {
        return [
            ['pagination'],
            ['bootstrap'],
            ['bulma'],
            ['foundation'],
            ['materialize'],
            ['primer'],
            ['semantic-ui'],
            ['tailwind'],
            ['w3'],
        ];
    }

    /**
     * @return array<array<string>>
     */
    public static function previousDisabledProvider() : array
    {
        return [
            ['primer', 'aria-disabled="true"'],
            ['primer-short', 'aria-disabled="true"'],
        ];
    }

    /**
     * @return array<array<string>>
     */
    public static function nextDisabledProvider() : array
    {
        return [
            ['primer', 'aria-disabled="true"'],
            ['primer-short', 'aria-disabled="true"'],
        ];
    }
}
