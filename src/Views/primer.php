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
            <a class="previous_page" rel="prev" href="<?=
            $pager->getPreviousPageUrl() ?>" aria-label="<?=
            $language->render('pagination', 'previous') ?>"><?=
                $language->render('pagination', 'previous') ?></a>
        <?php else: ?>
            <span class="previous_page" aria-disabled="true"><?=
                $language->render('pagination', 'previous') ?></span>
        <?php endif ?>

        <?php
        $page = array_key_first($pager->getPreviousPagesUrls());
?>
        <?php if ($page > 1): ?>
            <a href="<?= $pager->getFirstPageUrl() ?>" aria-label="Page <?= $pager->getFirstPage() ?>" title="<?=
    $pager->getLanguage()->render('pagination', 'first') ?>">
                <?= $pager->getFirstPage() ?>
            </a>
            <?php if ($page > 2): ?>
                <a href="<?= $pager->getPageUrl(2) ?>" aria-label="Page 2">
                    2
                </a>
                <?php if ($page > 3): ?>
                    <span class="gap">…</span>
                <?php endif ?>
            <?php endif ?>
        <?php endif ?>

        <?php foreach ($pager->getPreviousPagesUrls() as $p => $url) : ?>
            <a href="<?= $url ?>" aria-label="Page <?= $p ?>"><?= $p ?></a>
        <?php endforeach ?>

        <em aria-current="page"><?= $pager->getCurrentPage() ?></em>

        <?php foreach ($pager->getNextPagesUrls() as $p => $url) : ?>
            <a href="<?= $url ?>" aria-label="Page <?= $p ?>"><?= $p ?></a>
        <?php endforeach ?>

        <?php
$page = array_key_last($pager->getNextPagesUrls());
$lastPage = $pager->getLastPage();
?>
        <?php if ($page >= $pager->getCurrentPage()): ?>
            <?php if ($page < $lastPage - 2): ?>
                <span class="gap">…</span>
            <?php endif ?>
            <?php if ($page < $lastPage - 1): ?>
                <a href="<?= $pager->getPageUrl($lastPage - 1) ?>" aria-label="Page <?= $lastPage - 1 ?>">
                    <?= $lastPage - 1 ?>
                </a>
            <?php endif ?>
            <?php if ($page < $lastPage): ?>
                <a href="<?= $pager->getLastPageUrl() ?>" aria-label="Page <?= $pager->getLastPage() ?>" title="<?=
        $pager->getLanguage()->render('pagination', 'last') ?>">
                    <?= $pager->getLastPage() ?>
                </a>
            <?php endif ?>
        <?php endif ?>

        <?php if ($pager->getNextPage() && $pager->getNextPage() < $pager->getLastPage() + 1) : ?>
            <a class="next_page" rel="next" href="<?=
    $pager->getNextPageUrl() ?>" aria-label="<?=
    $language->render('pagination', 'next') ?>"><?=
        $language->render('pagination', 'next') ?></a>
        <?php else: ?>
            <span class="next_page" aria-disabled="true"><?=
        $language->render('pagination', 'next') ?></span>
        <?php endif ?>

    </div>
</nav>
