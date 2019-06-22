<?php namespace Framework\Pagination;

use Framework\HTTP\URL;

/**
 * Class Pager.
 */
class Pager
{
	/**
	 * @var int
	 */
	protected $currentPage;
	/**
	 * @var int|null
	 */
	protected $previousPage;
	/**
	 * @var int|null
	 */
	protected $nextPage;
	/**
	 * @var int
	 */
	protected $totalPages;
	/**
	 * @var int
	 */
	protected $totalItems;
	/**
	 * @var int
	 */
	protected $itemsPerPage;
	/**
	 * @var array
	 */
	protected $items;
	/**
	 * @var int
	 */
	protected $surround = 3;
	/**
	 * @var array
	 */
	protected $views = [
		// HTML Head
		'head' => __DIR__ . '/views/head.php',
		// HTTP Header
		'header' => __DIR__ . '/views/header.php',
		// HTML Previous and Next
		'pager' => __DIR__ . '/views/pager.php',
		// HTML Full
		'pagination' => __DIR__ . '/views/pagination.php',
	];
	/**
	 * @var URL
	 */
	protected $url;
	/**
	 * @var string
	 */
	protected $query = 'page';

	/**
	 * Pager constructor.
	 *
	 * @param int   $current_page
	 * @param int   $items_per_page
	 * @param int   $total_items
	 * @param array $items          Current page items
	 */
	public function __construct(
		// int
		$current_page,
		int $items_per_page,
		int $total_items,
		array $items
	) {
		$current_page = $current_page < 1 || ! \is_numeric($current_page) ? 1 : $current_page;
		$current_page = $current_page > 1000000000000000 ? 1000000000000000 : (int) $current_page;
		$items_per_page = $items_per_page < 1 ? 1 : $items_per_page;
		$items_per_page = $items_per_page > 1000 ? 1000 : $items_per_page;
		$previous_page = null;
		$next_page = null;
		$total_pages = (int) \ceil($total_items / $items_per_page);
		if ($current_page > 1 && $current_page - 1 <= $total_pages) {
			$previous_page = $current_page - 1;
		} elseif ($current_page > 1 && $total_pages > 1) {
			$previous_page = $total_pages;
		}
		if ($current_page < $total_pages) {
			$next_page = $current_page + 1;
		}
		$this->currentPage = $current_page;
		$this->itemsPerPage = $items_per_page;
		$this->totalItems = $total_items;
		$this->items = $items;
		$this->previousPage = $previous_page;
		$this->nextPage = $next_page;
		$this->totalPages = $total_pages;
	}

	public function __toString() : string
	{
		return $this->render();
	}

	public function addView(string $name, string $filepath)
	{
		$this->views[$name] = $filepath;
		return $this;
	}

	/**
	 * Get one or all views filepaths.
	 *
	 * @param string|null $name Null to get all, or a string with the view name.
	 *                          Default names are: head, header, pager and pagination
	 *
	 * @return array|string
	 */
	public function getView(string $name = null)
	{
		if ($name === null) {
			return $this->views;
		}
		if (empty($this->views[$name]) || ! \file_exists($this->views[$name])) {
			throw new \Exception('Pager view "' . $name . '" not found');
		}
		return $this->views[$name];
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

	public function setQuery(string $query = 'page')
	{
		$this->query = $query;
		return $this;
	}

	public function getQuery() : string
	{
		return $this->query;
	}

	public function getItems() : array
	{
		return $this->items;
	}

	/**
	 * @param string     $current_page_url
	 * @param array|null $allowed_queries
	 *
	 * @return $this
	 */
	public function setURL(string $current_page_url, array $allowed_queries = null)
	{
		$allowed_queries[] = $this->getQuery();
		$current_page_url = new URL($current_page_url);
		$current_page_url->setQuery($current_page_url->getQuery() ?? '', $allowed_queries);
		$this->url = $current_page_url;
		return $this;
	}

	public function getPageURL(?int $page) : ?string
	{
		if (empty($this->url)) {
			throw new \Exception('You must set the paginated URL first.');
		}
		if (empty($page)) {
			return null;
		}
		return $this->url->addQuery($this->getQuery(), $page)->getURL();
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

	public function get(bool $with_urls = false) : array
	{
		return [
			'firstPage' => $with_urls ? $this->getFirstPageURL() : 1,
			'previousPage' => $with_urls ? $this->getPreviousPageURL() : $this->previousPage,
			'currentPage' => $with_urls ? $this->getCurrentPageURL() : $this->currentPage,
			'nextPage' => $with_urls ? $this->getNextPageURL() : $this->nextPage,
			'lastPage' => $with_urls ? $this->getLastPageURL() : $this->totalPages,
			'totalPages' => $this->totalPages,
			'itemsPerPage' => $this->itemsPerPage,
			'totalItems' => $this->totalItems,
			'currentPageItems' => $this->items,
		];
	}

	public function render(string $view = 'pagination') : string
	{
		$view = $this->getView($view);
		\ob_start();
		require $view;
		return \ob_get_clean();
	}
}
