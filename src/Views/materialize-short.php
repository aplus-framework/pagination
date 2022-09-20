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
    <?php if ($pager->getPreviousPage()) : ?>
        <li class="waves-effect">
            <a rel="prev" href="<?= $pager->getPreviousPageUrl() ?>" title="<?=
            $language->render('pagination', 'previous') ?>">
                <i class="material-icons">chevron_left</i>
            </a>
        </li>
    <?php endif ?>

    <?php if ($pager->getNextPage()) : ?>
        <li class="waves-effect">
            <a rel="next" href="<?= $pager->getNextPageUrl() ?>" title="<?=
            $language->render('pagination', 'next') ?>">
                <i class="material-icons">chevron_right</i>
            </a>
        </li>
    <?php endif ?>
</ul>
