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
 * @var Framework\Pagination\Pager $pager
 */
?>

<?php if ($pager->getPreviousPage()) : ?>
    <link rel="prev" href="<?= $pager->getPreviousPageUrl() ?>">
<?php endif ?>
<link rel="canonical" href="<?= $pager->getCurrentPageUrl() ?>">
<?php if ($pager->getNextPage()) : ?>
    <link rel="next" href="<?= $pager->getNextPageUrl() ?>">
<?php endif ?>
