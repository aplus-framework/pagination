<?php declare(strict_types=1);
/*
 * This file is part of Aplus Framework Pagination Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Framework\Pagination;

use Framework\Helpers\Isolation;
use Framework\HTTP\URL;
use Framework\Language\Language;
use InvalidArgumentException;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Deprecated;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;
use LogicException;

/**
 * Class Pager.
 *
 * @package pagination
 */
class Pager implements JsonSerializable
{
    protected int $currentPage;
    protected ?int $previousPage = null;
    protected ?int $nextPage = null;
    protected int $lastPage;
    protected int $itemsPerPage;
    protected int $totalItems;
    protected int $surround = 2;
    /**
     * @var array<string,string>
     */
    protected array $views = [
        // HTML Head
        'head' => __DIR__ . '/Views/head.php',
        // HTTP Header
        'header' => __DIR__ . '/Views/header.php',
        // HTML Previous and Next
        'pager' => __DIR__ . '/Views/pagination-short.php',
        // HTML Full
        'pagination' => __DIR__ . '/Views/pagination.php',
        'pagination-short' => __DIR__ . '/Views/pagination-short.php',
        // Bootstrap 5
        'bootstrap' => __DIR__ . '/Views/bootstrap.php',
        'bootstrap-short' => __DIR__ . '/Views/bootstrap-short.php',
        'bootstrap5' => __DIR__ . '/Views/bootstrap.php',
        'bootstrap5-short' => __DIR__ . '/Views/bootstrap-short.php',
        // Bulma 0
        'bulma' => __DIR__ . '/Views/bulma.php',
        'bulma-short' => __DIR__ . '/Views/bulma-short.php',
        // Foundation 6
        'foundation' => __DIR__ . '/Views/foundation.php',
        'foundation-short' => __DIR__ . '/Views/foundation-short.php',
        'foundation6' => __DIR__ . '/Views/foundation.php',
        'foundation6-short' => __DIR__ . '/Views/foundation-short.php',
        // Materialize 1
        'materialize' => __DIR__ . '/Views/materialize.php',
        'materialize-short' => __DIR__ . '/Views/materialize-short.php',
        'materialize1' => __DIR__ . '/Views/materialize.php',
        'materialize1-short' => __DIR__ . '/Views/materialize-short.php',
        // Primer 20
        'primer' => __DIR__ . '/Views/primer.php',
        'primer-short' => __DIR__ . '/Views/primer-short.php',
        'primer20' => __DIR__ . '/Views/primer.php',
        'primer20-short' => __DIR__ . '/Views/primer-short.php',
        // Semantic UI 2
        'semantic-ui' => __DIR__ . '/Views/semantic-ui.php',
        'semantic-ui-short' => __DIR__ . '/Views/semantic-ui-short.php',
        'semantic-ui2' => __DIR__ . '/Views/semantic-ui.php',
        'semantic-ui2-short' => __DIR__ . '/Views/semantic-ui-short.php',
        // Tailwind CSS 3
        'tailwind' => __DIR__ . '/Views/tailwind.php',
        'tailwind-short' => __DIR__ . '/Views/tailwind-short.php',
        'tailwind3' => __DIR__ . '/Views/tailwind.php',
        'tailwind3-short' => __DIR__ . '/Views/tailwind-short.php',
        // W3.CSS 4
        'w3' => __DIR__ . '/Views/w3.php',
        'w3-short' => __DIR__ . '/Views/w3-short.php',
        'w34' => __DIR__ . '/Views/w3.php',
        'w34-short' => __DIR__ . '/Views/w3-short.php',
    ];
    protected string $defaultView = 'pagination';
    protected URL $url;
    protected string $oldUrl;
    protected string $query = 'page';
    protected Language $language;

    /**
     * Pager constructor.
     *
     * @param int|string $currentPage
     * @param int|string $itemsPerPage
     * @param int $totalItems
     * @param Language|null $language Language instance
     * @param string|null $url
     */
    public function __construct(
        int | string $currentPage,
        int | string $itemsPerPage,
        int $totalItems,
        Language $language = null,
        string $url = null
    ) {
        if ($language) {
            $this->setLanguage($language);
        }
        $this->setCurrentPage(static::sanitize($currentPage))
            ->setItemsPerPage(static::sanitize($itemsPerPage))
            ->setTotalItems($totalItems);
        $lastPage = (int) \ceil($this->getTotalItems() / $this->getItemsPerPage());
        $this->setLastPage(static::sanitize($lastPage));
        if ($this->getCurrentPage() > 1) {
            if ($this->getCurrentPage() - 1 <= $this->getLastPage()) {
                $this->setPreviousPage($this->getCurrentPage() - 1);
            } elseif ($this->getLastPage() > 1) {
                $this->setPreviousPage($this->getLastPage());
            }
        }
        if ($this->getCurrentPage() < $this->getLastPage()) {
            $this->setNextPage($this->getCurrentPage() + 1);
        }
        isset($url) ? $this->setUrl($url) : $this->prepareUrl();
    }

    public function __toString() : string
    {
        return $this->render();
    }

    /**
     * @since 3.5
     *
     * @return int
     */
    public function getItemsPerPage() : int
    {
        return $this->itemsPerPage;
    }

    /**
     * @since 3.5
     *
     * @param int $itemsPerPage
     *
     * @return static
     */
    protected function setItemsPerPage(int $itemsPerPage) : static
    {
        $this->itemsPerPage = $itemsPerPage;
        return $this;
    }

    /**
     * @since 3.5
     *
     * @return int
     */
    public function getTotalItems() : int
    {
        return $this->totalItems;
    }

    /**
     * @since 3.5
     *
     * @param int $totalItems
     *
     * @return static
     */
    protected function setTotalItems(int $totalItems) : static
    {
        $this->totalItems = $totalItems;
        return $this;
    }

    /**
     * @param Language|null $language
     *
     * @return static
     */
    public function setLanguage(Language $language = null) : static
    {
        $this->language = $language ?? new Language();
        $this->language->addDirectory(__DIR__ . '/Languages');
        return $this;
    }

    /**
     * @return Language
     */
    public function getLanguage() : Language
    {
        if ( ! isset($this->language)) {
            $this->setLanguage();
        }
        return $this->language;
    }

    /**
     * @param string $name
     * @param string $filepath
     *
     * @return static
     */
    public function setView(string $name, string $filepath) : static
    {
        if ( ! \is_file($filepath)) {
            throw new InvalidArgumentException('Invalid Pager view filepath: ' . $filepath);
        }
        $this->views[$name] = $filepath;
        return $this;
    }

    /**
     * Get a view filepath.
     *
     * @param string $name The view name. Default names are: head, header, pager and pagination
     *
     * @return string
     */
    public function getView(string $name) : string
    {
        if (empty($this->views[$name])) {
            throw new InvalidArgumentException('Pager view not found: ' . $name);
        }
        return $this->views[$name];
    }

    /**
     * @return array<string,string>
     */
    #[Pure]
    public function getViews() : array
    {
        return $this->views;
    }

    /**
     * @return int
     */
    #[Pure]
    public function getSurround() : int
    {
        return $this->surround;
    }

    /**
     * @param int $surround
     *
     * @return static
     */
    public function setSurround(int $surround) : static
    {
        $this->surround = $surround < 0 ? 0 : $surround;
        return $this;
    }

    /**
     * @param string $query
     *
     * @return static
     */
    public function setQuery(string $query = 'page') : static
    {
        $this->query = $query;
        return $this;
    }

    #[Pure]
    public function getQuery() : string
    {
        return $this->query;
    }

    /**
     * @param array<int,string> $allowed
     *
     * @return $this
     */
    public function setAllowedQueries(array $allowed) : static
    {
        $this->setUrl($this->oldUrl, $allowed);
        return $this;
    }

    protected function prepareUrl() : void
    {
        $scheme = ((isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] === 'https')
            || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'))
            ? 'https'
            : 'http';
        $this->setUrl($scheme . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    }

    /**
     * @param string|URL $currentPageUrl
     * @param array<int,string> $allowedQueries
     *
     * @return static
     */
    public function setUrl(string | URL $currentPageUrl, array $allowedQueries = []) : static
    {
        $currentPageUrl = $currentPageUrl instanceof URL
            ? clone $currentPageUrl
            : new URL($currentPageUrl);
        $this->oldUrl = $currentPageUrl->toString();
        $allowedQueries[] = $this->getQuery();
        $currentPageUrl->setQuery($currentPageUrl->getQuery() ?? '', $allowedQueries);
        $this->url = $currentPageUrl;
        return $this;
    }

    public function getUrl() : URL
    {
        return $this->url;
    }

    public function getPageUrl(?int $page) : ?string
    {
        if ($page === null || $page === 0) {
            return null;
        }
        return $this->url->addQuery($this->getQuery(), $page)->toString();
    }

    public function getCurrentPage() : int
    {
        return $this->currentPage;
    }

    /**
     * @since 3.5
     *
     * @param int $currentPage
     *
     * @return static
     */
    protected function setCurrentPage(int $currentPage) : static
    {
        $this->currentPage = $currentPage;
        return $this;
    }

    public function getCurrentPageUrl() : string
    {
        return $this->getPageUrl($this->currentPage);
    }

    #[Pure]
    public function getFirstPage() : int
    {
        return 1;
    }

    public function getFirstPageUrl() : string
    {
        return $this->getPageUrl($this->getFirstPage());
    }

    #[Pure]
    public function getLastPage() : int
    {
        return $this->lastPage;
    }

    /**
     * @since 3.5
     *
     * @param int $lastPage
     *
     * @return static
     */
    protected function setLastPage(int $lastPage) : static
    {
        $this->lastPage = $lastPage;
        return $this;
    }

    public function getLastPageUrl() : string
    {
        return $this->getPageUrl($this->getLastPage());
    }

    #[Pure]
    public function getPreviousPage() : ?int
    {
        return $this->previousPage;
    }

    /**
     * @since 3.5
     *
     * @param int $previousPage
     *
     * @return static
     */
    protected function setPreviousPage(int $previousPage) : static
    {
        $this->previousPage = $previousPage;
        return $this;
    }

    public function getPreviousPageUrl() : ?string
    {
        return $this->getPageUrl($this->getPreviousPage());
    }

    #[Pure]
    public function getNextPage() : ?int
    {
        return $this->nextPage;
    }

    /**
     * @since 3.5
     *
     * @param int $nextPage
     *
     * @return static
     */
    protected function setNextPage(int $nextPage) : static
    {
        $this->nextPage = $nextPage;
        return $this;
    }

    public function getNextPageUrl() : ?string
    {
        return $this->getPageUrl($this->getNextPage());
    }

    /**
     * @return array<int,string>
     */
    public function getPreviousPagesUrls() : array
    {
        $urls = [];
        if ($this->currentPage > 1 && $this->currentPage <= $this->lastPage) {
            $range = \range($this->currentPage - $this->surround, $this->currentPage - 1);
            foreach ($range as $page) {
                if ($page < 1) {
                    continue;
                }
                $urls[$page] = $this->getPageUrl($page);
            }
        }
        return $urls;
    }

    /**
     * @return array<int,string>
     */
    public function getNextPagesUrls() : array
    {
        $urls = [];
        if ($this->currentPage < $this->lastPage) {
            $range = \range($this->currentPage + 1, $this->currentPage + $this->surround);
            foreach ($range as $page) {
                if ($page > $this->lastPage) {
                    break;
                }
                $urls[$page] = $this->getPageUrl($page);
            }
        }
        return $urls;
    }

    /**
     * @return array<string,int|null>
     */
    #[ArrayShape([
        'self' => 'int',
        'first' => 'int',
        'prev' => 'int|null',
        'next' => 'int|null',
        'last' => 'int',
    ])]
    #[Pure]
    public function get() : array
    {
        return [
            'self' => $this->getCurrentPage(),
            'first' => $this->getFirstPage(),
            'prev' => $this->getPreviousPage(),
            'next' => $this->getNextPage(),
            'last' => $this->getLastPage(),
        ];
    }

    /**
     * @return array<string,string|null>
     */
    #[ArrayShape([
        'self' => 'string',
        'first' => 'string',
        'prev' => 'string|null',
        'next' => 'string|null',
        'last' => 'string',
    ])]
    public function getWithUrl() : array
    {
        return [
            'self' => $this->getCurrentPageUrl(),
            'first' => $this->getFirstPageUrl(),
            'prev' => $this->getPreviousPageUrl(),
            'next' => $this->getNextPageUrl(),
            'last' => $this->getLastPageUrl(),
        ];
    }

    /**
     * @param string|null $view
     *
     * @return string
     */
    public function render(string $view = null) : string
    {
        $filename = $this->getView($view ?? $this->getDefaultView());
        \ob_start();
        Isolation::require($filename, ['pager' => $this]);
        return (string) \ob_get_clean();
    }

    public function renderShort() : string
    {
        $view = $this->getDefaultView();
        if ( ! \str_ends_with($view, '-short')) {
            $view .= '-short';
        }
        return $this->render($view);
    }

    public function setDefaultView(string $defaultView) : void
    {
        if ( ! \array_key_exists($defaultView, $this->views)) {
            throw new LogicException(
                'Default view "' . $defaultView . '" is not a valid value'
            );
        }
        $this->defaultView = $defaultView;
    }

    #[Pure]
    public function getDefaultView() : string
    {
        return $this->defaultView;
    }

    /**
     * @return array<string,string|null>
     */
    #[ArrayShape([
        'self' => 'string',
        'first' => 'string',
        'prev' => 'string|null',
        'next' => 'string|null',
        'last' => 'string',
    ])]
    public function jsonSerialize() : array
    {
        return $this->getWithUrl();
    }

    /**
     * @param int|string $number
     *
     * @return int
     *
     * @deprecated Use sanitize method
     *
     * @codeCoverageIgnore
     */
    #[Deprecated(
        reason: 'since version 3.1, use sanitize() instead',
        replacement: '%class%::sanitize(%parameter0%)'
    )]
    public static function sanitizePageNumber(int | string $number) : int
    {
        \trigger_error(
            'Method ' . __METHOD__ . ' is deprecated',
            \E_USER_DEPRECATED
        );
        $number = $number < 1 || ! \is_numeric($number) ? 1 : $number;
        return $number > \PHP_INT_MAX ? \PHP_INT_MAX : (int) $number;
    }

    /**
     * Sanitize a number.
     *
     * @param mixed $number
     *
     * @return int
     */
    #[Pure]
    public static function sanitize(mixed $number) : int
    {
        if ( ! \is_numeric($number)) {
            return 1;
        }
        if ($number < 1) {
            return 1;
        }
        return $number >= \PHP_INT_MAX ? \PHP_INT_MAX : (int) $number;
    }
}
