<?php namespace Framework\Pagination;

use Framework\HTTP\URL;
use Framework\Language\Language;
use InvalidArgumentException;
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
	protected int $totalPages;
	protected int $totalItems;
	protected int $itemsPerPage;
	/**
	 * @var array|mixed[]
	 */
	protected array $items;
	protected int $surround = 2;
	/**
	 * @var array|string[]
	 */
	protected array $views = [
		// HTML Head
		'head' => __DIR__ . '/Views/head.php',
		// HTTP Header
		'header' => __DIR__ . '/Views/header.php',
		// HTML Previous and Next
		'pager' => __DIR__ . '/Views/pager.php',
		// HTML Full
		'pagination' => __DIR__ . '/Views/pagination.php',
		// Bootstrap framework 4
		'bootstrap4' => __DIR__ . '/Views/bootstrap4.php',
		'bootstrap4-short' => __DIR__ . '/Views/bootstrap4-short.php',
		// Bulma framework 0
		'bulma' => __DIR__ . '/Views/bulma.php',
		'bulma-short' => __DIR__ . '/Views/bulma-short.php',
		// Semantic UI framework 2
		'semantic-ui' => __DIR__ . '/Views/semantic-ui.php',
		'semantic-ui-short' => __DIR__ . '/Views/semantic-ui-short.php',
	];
	protected string $defaultView = 'pagination';
	protected ?URL $url = null;
	protected string $query = 'page';
	protected Language $language;

	/**
	 * Pager constructor.
	 *
	 * @param int|string    $currentPage
	 * @param int           $itemsPerPage
	 * @param int           $total_items
	 * @param array|mixed[] $items          Current page items
	 * @param Language|null $language       Language instance
	 * @param string|null   $url
	 */
	public function __construct(
		int | string $currentPage,
		int $itemsPerPage,
		int $totalItems,
		array $items,
		Language $language = null,
		string $url = null
	) {
		$this->setLanguage($language ?? new Language('en'));
		$this->currentPage = $this->sanitizePageNumber($currentPage);
		$this->itemsPerPage = $this->sanitizePageNumber($itemsPerPage);
		$this->totalPages = (int) \ceil($totalItems / $this->itemsPerPage);
		if ($this->currentPage > 1 && $this->currentPage - 1 <= $this->totalPages) {
			$this->previousPage = $this->currentPage - 1;
		} elseif ($this->currentPage > 1 && $this->totalPages > 1) {
			$this->previousPage = $this->totalPages;
		}
		if ($this->currentPage < $this->totalPages) {
			$this->nextPage = $this->currentPage + 1;
		}
		$this->totalItems = $totalItems;
		$this->items = $items;
		$url ? $this->setURL($url) : $this->prepareURL();
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
	protected function sanitizePageNumber(int | string $number) : int
	{
		$number = $number < 1 || ! \is_numeric($number) ? 1 : $number;
		return $number > \PHP_INT_MAX ? \PHP_INT_MAX : (int) $number;
	}

	/**
	 * @param Language $language
	 *
	 * @return $this
	 */
	protected function setLanguage(Language $language)
	{
		$this->language = $language->addDirectory(__DIR__ . '/Languages');
		return $this;
	}

	public function getLanguage() : Language
	{
		return $this->language;
	}

	/**
	 * @param string $name
	 * @param string $filepath
	 *
	 * @return $this
	 */
	public function setView(string $name, string $filepath)
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
	 * @return array|string[]
	 */
	public function getViews() : array
	{
		return $this->views;
	}

	/**
	 * @return int
	 */
	public function getSurround() : int
	{
		return $this->surround;
	}

	/**
	 * @param int $surround
	 *
	 * @return $this
	 */
	public function setSurround(int $surround)
	{
		$this->surround = $surround < 0 ? 0 : $surround;
		return $this;
	}

	/**
	 * @param string $query
	 *
	 * @return $this
	 */
	public function setQuery(string $query = 'page')
	{
		$this->query = $query;
		return $this;
	}

	public function getQuery() : string
	{
		return $this->query;
	}

	/**
	 * @return array|mixed[]
	 */
	public function getItems() : array
	{
		return $this->items;
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
	 * @param string              $currentPageURL
	 * @param array|string[]|null $allowedQueries
	 *
	 * @return $this
	 */
	public function setURL(string $currentPageURL, array $allowedQueries = null)
	{
		$allowedQueries[] = $this->getQuery();
		$currentPageURL = new URL($currentPageURL);
		$currentPageURL->setQuery($currentPageURL->getQuery() ?? '', $allowedQueries);
		$this->url = $currentPageURL;
		return $this;
	}

	public function getPageURL(?int $page) : ?string
	{
		if (empty($this->url)) {
			throw new LogicException('The paginated URL was not set');
		}
		if (empty($page)) {
			return null;
		}
		return $this->url->addQuery($this->getQuery(), $page)->getAsString();
	}

	public function getCurrentPageURL() : string
	{
		return $this->getPageURL($this->currentPage);
	}

	public function getFirstPageURL() : ?string
	{
		return $this->getPageURL(1);
	}

	public function getLastPageURL() : ?string
	{
		return $this->getPageURL($this->totalPages);
	}

	public function getPreviousPageURL() : ?string
	{
		return $this->getPageURL($this->previousPage);
	}

	public function getNextPageURL() : ?string
	{
		return $this->getPageURL($this->nextPage);
	}

	/**
	 * @return array|string[]
	 */
	public function getPreviousPagesURLs() : array
	{
		$urls = [];
		if ($this->currentPage > 1 && $this->currentPage <= $this->totalPages) {
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
	 * @return array|string[]
	 */
	public function getNextPagesURLs() : array
	{
		$urls = [];
		if ($this->currentPage < $this->totalPages) {
			$range = \range($this->currentPage + 1, $this->currentPage + $this->surround);
			foreach ($range as $page) {
				if ($page > $this->totalPages) {
					break;
				}
				$urls[$page] = $this->getPageURL($page);
			}
		}
		return $urls;
	}

	/**
	 * @param bool $withURLs
	 *
	 * @return array|mixed[]
	 */
	public function get(bool $withURLs = false) : array
	{
		return [
			'firstPage' => $withURLs ? $this->getFirstPageURL() : 1,
			'previousPage' => $withURLs ? $this->getPreviousPageURL() : $this->previousPage,
			'currentPage' => $withURLs ? $this->getCurrentPageURL() : $this->currentPage,
			'nextPage' => $withURLs ? $this->getNextPageURL() : $this->nextPage,
			'lastPage' => $withURLs ? $this->getLastPageURL() : $this->totalPages,
			'totalPages' => $this->totalPages,
			'itemsPerPage' => $this->itemsPerPage,
			'totalItems' => $this->totalItems,
			'currentPageItems' => $this->items,
		];
	}

	/**
	 * @param string|null $view
	 *
	 * @return string
	 */
	public function render(string $view = null) : string
	{
		$view = $this->getView($view ?? $this->getDefaultView());
		\ob_start();
		require $view;
		return (string) \ob_get_clean();
	}

	public function setDefaultView(string $defaultView) : void
	{
		if ( ! \array_key_exists($defaultView, $this->views)) {
			throw new LogicException('Default view is not a valid value');
		}
		$this->defaultView = $defaultView;
	}

	public function getDefaultView() : string
	{
		return $this->defaultView;
	}

	public function jsonSerialize()
	{
		return $this->get(true);
	}
}
