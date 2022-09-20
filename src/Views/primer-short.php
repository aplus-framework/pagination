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
<nav class="paginate-container" aria-label="Pagination">
    <div class="pagination">
        <?php if ($pager->getPreviousPage()) : ?>
            <a class="previous_page" rel="prev" href="<?= $pager->getPreviousPageUrl() ?>" aria-label="Previous Page">
                <?= $language->render('pagination', 'previous') ?>
            </a>
        <?php else: ?>
            <span class="previous_page" aria-disabled="true">
                <?= $language->render('pagination', 'previous') ?>
            </span>
        <?php endif ?>

        <?php if ($pager->getNextPage()) : ?>
            <a class="next_page" rel="next" href="<?= $pager->getNextPageUrl() ?>" aria-label="Next Page">
                <?= $language->render('pagination', 'next') ?>
            </a>
        <?php else: ?>
            <span class="next_page" aria-disabled="true">
                <?= $language->render('pagination', 'next') ?>
            </span>
        <?php endif ?>
    </div>
</nav>
