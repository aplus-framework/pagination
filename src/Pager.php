<?php declare(strict_types=1);
/*
 * This file is part of The Framework Pagination Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Framework\Pagination;

use Framework\HTTP\URL;
use Framework\Language\Language;
use InvalidArgumentException;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;
use LogicException;

/**
 * Class Pager.
 */
class Pager implements JsonSerializable
{
	protected int $currentPage;
	protected ?int $previousPage = null;
	protected ?int $nextPage = null;
	protected int $lastPage;
	protected int $itemsPerPage;
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
		// Bootstrap framework 4/5
		'bootstrap' => __DIR__ . '/Views/bootstrap.php',
		'bootstrap-short' => __DIR__ . '/Views/bootstrap-short.php',
		'bootstrap4' => __DIR__ . '/Views/bootstrap.php',
		'bootstrap4-short' => __DIR__ . '/Views/bootstrap-short.php',
		'bootstrap5' => __DIR__ . '/Views/bootstrap.php',
		'bootstrap5-short' => __DIR__ . '/Views/bootstrap-short.php',
		// Bulma framework 0
		'bulma' => __DIR__ . '/Views/bulma.php',
		'bulma-short' => __DIR__ . '/Views/bulma-short.php',
		// Semantic UI framework 2
		'semantic-ui' => __DIR__ . '/Views/semantic-ui.php',
		'semantic-ui-short' => __DIR__ . '/Views/semantic-ui-short.php',
		'semantic-ui2' => __DIR__ . '/Views/semantic-ui.php',
		'semantic-ui2-short' => __DIR__ . '/Views/semantic-ui-short.php',
	];
	protected string $defaultView = 'pagination';
	protected URL $url;
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
		$this->setLanguage($language ?? new Language('en'));
		$this->currentPage = $this->sanitizePageNumber($currentPage);
		$this->itemsPerPage = $this->sanitizePageNumber($itemsPerPage);
		$this->lastPage = (int) \ceil($totalItems / $this->itemsPerPage);
		if ($this->currentPage > 1) {
			if ($this->currentPage - 1 <= $this->lastPage) {
				$this->previousPage = $this->currentPage - 1;
			} elseif ($this->lastPage > 1) {
				$this->previousPage = $this->lastPage;
			}
		}
		if ($this->currentPage < $this->lastPage) {
			$this->nextPage = $this->currentPage + 1;
		}
		isset($url) ? $this->setURL($url) : $this->prepareURL();
	}

	public function __toString() : string
	{
		return $this->render();
	}

	/**
	 * @param int|string $number
	 *
	 * @return int
	 */
	#[Pure]
	protected function sanitizePageNumber(int | string $number) : int
	{
		$number = $number < 1 || ! \is_numeric($number) ? 1 : $number;
		return $number > \PHP_INT_MAX ? \PHP_INT_MAX : (int) $number;
	}

	/**
	 * @param Language $language
	 *
	 * @return static
	 */
	public function setLanguage(Language $language) : static
	{
		$this->language = $language->addDirectory(__DIR__ . '/Languages');
		return $this;
	}

	#[Pure]
	public function getLanguage() : Language
	{
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

	protected function prepareURL() : void
	{
		$scheme = ((isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] === 'https')
			|| (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'))
			? 'https'
			: 'http';
		$this->setURL($scheme . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
	}

	/**
	 * @param string $currentPageURL
	 * @param array<int,string>|null $allowedQueries
	 *
	 * @return static
	 */
	public function setURL(string $currentPageURL, array $allowedQueries = null) : static
	{
		$allowedQueries[] = $this->getQuery();
		$currentPageURL = new URL($currentPageURL);
		$currentPageURL->setQuery($currentPageURL->getQuery() ?? '', $allowedQueries);
		$this->url = $currentPageURL;
		return $this;
	}

	public function getPageURL(?int $page) : ?string
	{
		if (empty($page)) {
			return null;
		}
		return $this->url->addQuery($this->getQuery(), $page)->getAsString();
	}

	public function getCurrentPage() : int
	{
		return $this->currentPage;
	}

	public function getCurrentPageURL() : string
	{
		return $this->getPageURL($this->currentPage);
	}

	#[Pure]
	public function getFirstPage() : int
	{
		return 1;
	}

	public function getFirstPageURL() : string
	{
		return $this->getPageURL($this->getFirstPage());
	}

	#[Pure]
	public function getLastPage() : int
	{
		return $this->lastPage;
	}

	public function getLastPageURL() : string
	{
		return $this->getPageURL($this->getLastPage());
	}

	#[Pure]
	public function getPreviousPage() : ?int
	{
		return $this->previousPage;
	}

	public function getPreviousPageURL() : ?string
	{
		return $this->getPageURL($this->getPreviousPage());
	}

	#[Pure]
	public function getNextPage() : ?int
	{
		return $this->nextPage;
	}

	public function getNextPageURL() : ?string
	{
		return $this->getPageURL($this->getNextPage());
	}

	/**
	 * @return array<int,string>
	 */
	public function getPreviousPagesURLs() : array
	{
		$urls = [];
		if ($this->currentPage > 1 && $this->currentPage <= $this->lastPage) {
			$range = \range($this->currentPage - $this->surround, $this->currentPage - 1);
			foreach ($range as $page) {
				if ($page < 1) {
					continue;
				}
				$urls[$page] = $this->getPageURL($page);
			}
		}
		return $urls;
	}

	/**
	 * @return array<int,string>
	 */
	public function getNextPagesURLs() : array
	{
		$urls = [];
		if ($this->currentPage < $this->lastPage) {
			$range = \range($this->currentPage + 1, $this->currentPage + $this->surround);
			foreach ($range as $page) {
				if ($page > $this->lastPage) {
					break;
				}
				$urls[$page] = $this->getPageURL($page);
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
	public function getWithURL() : array
	{
		return [
			'self' => $this->getCurrentPageURL(),
			'first' => $this->getFirstPageURL(),
			'prev' => $this->getPreviousPageURL(),
			'next' => $this->getNextPageURL(),
			'last' => $this->getLastPageURL(),
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
		require_isolated($filename, ['pager' => $this]);
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
			throw new LogicException('Default view is not a valid value');
		}
		$this->defaultView = $defaultView;
	}

	#[Pure]
	public function getDefaultView() : string
	{
		return $this->defaultView;
	}

	/**
	 * @return array<string,mixed>
	 */
	public function jsonSerialize() : array
	{
		return $this->getWithURL();
	}
}
