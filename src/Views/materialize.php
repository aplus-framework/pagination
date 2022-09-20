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
<ul class="pagination center-align">
    <?php if ($pager->getCurrentPage() - $pager->getSurround() > 1) : ?>
        <li class="waves-effect">
            <a href="<?= $pager->getFirstPageUrl() ?>"><?= $pager->getLanguage()
        ->render('pagination', 'first') ?></a>
        </li>
    <?php endif ?>

    <?php if ($pager->getPreviousPage()) : ?>
        <li class="waves-effect">
            <a rel="prev" href="<?= $pager->getPreviousPageUrl() ?>" title="<?=
            $language->render('pagination', 'previous') ?>">
                <i class="material-icons">chevron_left</i>
            </a>
        </li>
    <?php endif ?>

    <?php foreach ($pager->getPreviousPagesUrls() as $p => $url) : ?>
        <li class="waves-effect">
            <a href="<?= $url ?>"><?= $p ?></a>
        </li>
    <?php endforeach ?>

    <li class="active">
        <a rel="canonical" href="<?= $pager->getCurrentPageUrl() ?>">
            <?= $pager->getCurrentPage() ?>
        </a>
    </li>

    <?php foreach ($pager->getNextPagesUrls() as $p => $url) : ?>
        <li class="waves-effect">
            <a href="<?= $url ?>"><?= $p ?></a>
        </li>
    <?php endforeach ?>

    <?php if ($pager->getNextPage() && $pager->getNextPage() < $pager->getLastPage() + 1) : ?>
        <li class="waves-effect">
            <a rel="next" href="<?= $pager->getNextPageUrl() ?>" title="<?=
            $language->render('pagination', 'next') ?>">
                <i class="material-icons">chevron_right</i>
            </a>
        </li>
    <?php endif ?>

    <?php if ($pager->getLastPage()
        && $pager->getCurrentPage() + $pager->getSurround() < $pager->getLastPage()
    ) : ?>
        <li class="waves-effect">
            <a href="<?= $pager->getLastPageUrl() ?>"><?= $pager->getLanguage()
        ->render('pagination', 'last') ?></a>
        </li>
    <?php endif ?>
</ul>

