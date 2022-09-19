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
<nav class="pagination is-centered">
    <ul class="pagination-list">
        <?php if ($pager->getCurrentPage() - $pager->getSurround() > 1) : ?>
            <li>
                <a class="pagination-link" href="<?= $pager->getFirstPageUrl() ?>"><?= $pager->getLanguage()
            ->render('pagination', 'first') ?></a>
            </li>
        <?php endif ?>

        <?php if ($pager->getPreviousPage()) : ?>
            <li>
                <a class="pagination-link" rel="prev" href="<?= $pager->getPreviousPageUrl(); ?>" title="<?=
                $language->render('pagination', 'previous') ?>">&laquo;</a>
            </li>
        <?php endif ?>

        <?php foreach ($pager->getPreviousPagesUrls() as $p => $url) : ?>
            <li>
                <a class="pagination-link" href="<?= $url ?>"><?= $p ?></a>
            </li>
        <?php endforeach ?>

        <li>
            <a class="pagination-link is-current" rel="canonical" href="<?= $pager->getCurrentPageUrl() ?>">
                <?= $pager->getCurrentPage() ?>
            </a>
        </li>

        <?php foreach ($pager->getNextPagesUrls() as $p => $url) : ?>
            <li>
                <a class="pagination-link" href="<?= $url ?>"><?= $p ?></a>
            </li>
        <?php endforeach ?>

        <?php if ($pager->getNextPage() && $pager->getNextPage() < $pager->getLastPage() + 1) : ?>
            <li>
                <a class="pagination-link" rel="next" href="<?= $pager->getNextPageUrl() ?>" title="<?=
                $language->render('pagination', 'next') ?>">&raquo;</a>
            </li>
        <?php endif ?>

        <?php if ($pager->getLastPage()
            && $pager->getCurrentPage() + $pager->getSurround() < $pager->getLastPage()
        ) : ?>
            <li>
                <a class="pagination-link" href="<?= $pager->getLastPageUrl() ?>"><?= $pager->getLanguage()
            ->render('pagination', 'last') ?></a>
            </li>
        <?php endif ?>
    </ul>
</nav>
