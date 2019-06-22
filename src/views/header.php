<?php
/**
 * @var \Framework\Pagination\Pager $this
 *
 * @see https://tools.ietf.org/html/rfc5988
 * @see https://developer.github.com/v3/#pagination
 * @see https://github.com/bcit-ci/CodeIgniter4/pull/622
 */
/**
 * HTTP Header Link.
 */
$links = '';

if ($this->previousPage > 0) {
	$links .= '<' . $this->getFirstPageURL() . '>; rel="first",';
	$links .= '<' . $this->getPreviousPageURL() . '>; rel="prev"';
}

if ($this->nextPage) {
	$links .= '<' . $this->getNextPageURL() . '>; rel="next",';
	$links .= '<' . $this->getLastPageURL() . '>; rel="last"';
}

echo $links;
