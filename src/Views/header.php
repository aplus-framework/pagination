<?php
/*
 * This file is part of The Framework Pagination Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
/**
 * @var \Framework\Pagination\Pager $pager
 *
 * @see https://tools.ietf.org/html/rfc5988
 * @see https://developer.github.com/v3/#pagination
 * @see https://github.com/bcit-ci/CodeIgniter4/pull/622
 */
/**
 * HTTP Header Link.
 */
$links = '';

if ($pager->previousPage > 0) {
	$links .= '<' . $pager->getFirstPageURL() . '>; rel="first",';
	$links .= '<' . $pager->getPreviousPageURL() . '>; rel="prev"';
}

if ($pager->nextPage) {
	$links .= '<' . $pager->getNextPageURL() . '>; rel="next",';
	$links .= '<' . $pager->getLastPageURL() . '>; rel="last"';
}

echo $links;
