<?php
/*
 * This file is part of Aplus Framework Pagination Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
/**
 * HTTP Header Link.
 *
 * @see https://tools.ietf.org/html/rfc5988
 *
 * @var Framework\Pagination\Pager $pager
 */
$links = '';

if ($pager->getPreviousPage()) {
    $links .= '<' . $pager->getFirstPageUrl() . '>; rel="first",';
    $links .= '<' . $pager->getPreviousPageUrl() . '>; rel="prev"';
}

if ($pager->getNextPage()) {
    if ($links !== '') {
        $links .= ',';
    }
    $links .= '<' . $pager->getNextPageUrl() . '>; rel="next",';
    $links .= '<' . $pager->getLastPageUrl() . '>; rel="last"';
}

echo $links;
