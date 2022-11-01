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
<div class="w3-center">
    <div class="w3-bar">
        <?php if ($pager->getCurrentPage() - $pager->getSurround() > 1) : ?>
            <a class="w3-button" href="<?= $pager->getFirstPageUrl() ?>"><?= $pager->getLanguage()
            ->render('pagination', 'first') ?></a>
        <?php endif ?>

        <?php if ($pager->getPreviousPage()) : ?>
            <a class="w3-button" rel="prev" href="<?= $pager->getPreviousPageUrl() ?>" title="<?=
            $language->render('pagination', 'previous') ?>">&laquo;</a>
        <?php endif ?>

        <?php foreach ($pager->getPreviousPagesUrls() as $p => $url) : ?>
            <a href="<?= $url ?>" class="w3-button"><?= $p ?></a>
        <?php endforeach ?>

        <a href="<?= $pager->getCurrentPageUrl() ?>" rel="canonical" class="w3-button w3-blue w3-hover-blue">
            <?= $pager->getCurrentPage() ?>
        </a>

        <?php foreach ($pager->getNextPagesUrls() as $p => $url) : ?>
            <a href="<?= $url ?>" class="w3-button"><?= $p ?></a>
        <?php endforeach ?>

        <?php if ($pager->getNextPage() && $pager->getNextPage() < $pager->getLastPage() + 1) : ?>
            <a class="w3-button" rel="next" href="<?= $pager->getNextPageUrl() ?>" title="<?=
            $language->render('pagination', 'next') ?>">&raquo;</a>
        <?php endif ?>

        <?php if ($pager->getLastPage()
            && $pager->getCurrentPage() + $pager->getSurround() < $pager->getLastPage()
        ) : ?>
            <a class="w3-button" href="<?= $pager->getLastPageUrl() ?>"><?= $pager->getLanguage()
            ->render('pagination', 'last') ?></a>
        <?php endif ?>
    </div>
</div>
