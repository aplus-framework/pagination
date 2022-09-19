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
$language = $pager->getLanguage();
?>
<div class="text-center">
    <?php if ($pager->getPreviousPage()) : ?>
        <a href="<?= $pager->getPreviousPageUrl() ?>" class="relative inline-flex items-center px-2 py-2 rounded-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
            <?= $language->render('pagination', 'previous') ?>
        </a>
    <?php endif ?>

    <?php if ($pager->getNextPage()) : ?>
        <a href="<?= $pager->getNextPageUrl() ?>" class="relative inline-flex items-center px-2 py-2 rounded-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
            <?= $language->render('pagination', 'next') ?>
        </a>
    <?php endif ?>
</div>
