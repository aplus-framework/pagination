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
<div class="ui container center aligned">
    <div class="ui pagination menu">
        <?php if ($pager->getPreviousPage()) : ?>
            <a class="item" rel="prev" href="<?= $pager->getPreviousPageUrl() ?>">
                <?= $language->render('pagination', 'previous') ?>
            </a>
        <?php endif ?>

        <?php if ($pager->getNextPage()) : ?>
            <a class="item" rel="next" href="<?= $pager->getNextPageUrl() ?>">
                <?= $language->render('pagination', 'next') ?>
            </a>
        <?php endif ?>
    </div>
</div>
