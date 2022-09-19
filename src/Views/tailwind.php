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
$hasFirst = $pager->getCurrentPage() - $pager->getSurround() > 1;
$hasLast = $pager->getLastPage()
    && $pager->getCurrentPage() + $pager->getSurround() < $pager->getLastPage();
$hasPrev = $pager->getPreviousPage();
?>
<div class="text-center">
    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
        <?php if ($hasFirst) : ?>
            <a href="<?= $pager->getFirstPageUrl() ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                <?= $language->render('pagination', 'first') ?>
            </a>
        <?php endif ?>

        <?php if ($hasPrev) : ?>
            <a href="<?= $pager->getPreviousPageUrl() ?>" class="relative inline-flex items-center px-2 py-2<?= $hasFirst
                ? ''
                : ' rounded-l-md' ?> border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50" title="<?= $pager->getLanguage()
                    ->render('pagination', 'previous') ?>">
                <span class="sr-only"><?= $pager->getLanguage()
                    ->render('pagination', 'previous') ?></span>
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            </a>
        <?php endif ?>

        <?php foreach ($pager->getPreviousPagesUrls() as $p => $url) : ?>
            <a href="<?= $url ?>" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                <?= $p ?>
            </a>
        <?php endforeach ?>

        <a href="<?= $pager->getCurrentPageUrl() ?>" rel="canonical" aria-current="page" class="z-10 bg-indigo-50 border-indigo-500 text-indigo-600 relative inline-flex items-center px-4 py-2<?= $hasPrev
            ? ''
            : ' rounded-l-md' ?><?= $pager->getNextPage() ? ''
            : ' rounded-r-md' ?> border text-sm font-medium">
            <?= $pager->getCurrentPage() ?>
        </a>

        <?php foreach ($pager->getNextPagesUrls() as $p => $url) : ?>
            <a href="<?= $url ?>" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                <?= $p ?>
            </a>
        <?php endforeach ?>

        <?php if ($pager->getNextPage() && $pager->getNextPage() < $pager->getLastPage() + 1) : ?>
            <a href="<?= $pager->getNextPageUrl() ?>" class="relative inline-flex items-center px-2 py-2<?= $hasLast
                ? ''
                : ' rounded-r-md' ?> border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50" title="<?= $pager->getLanguage()
                    ->render('pagination', 'next') ?>">
                <span class="sr-only"><?= $pager->getLanguage()
                    ->render('pagination', 'next') ?></span>
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
            </a>
        <?php endif ?>

        <?php if ($hasLast) : ?>
            <a href="<?= $pager->getLastPageUrl() ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                <?= $language->render('pagination', 'last') ?>
            </a>
        <?php endif ?>
    </nav>
</div>

