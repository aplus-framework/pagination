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
        <?php if ($pager->getPreviousPage()) : ?>
            <a rel="prev" href="<?= $pager->getPreviousPageUrl() ?>" class="w3-button">
                <?= $language->render('pagination', 'previous') ?>
            </a>
        <?php endif ?>

        <?php if ($pager->getNextPage()) : ?>
            <a rel="next" href="<?= $pager->getNextPageUrl() ?>" class="w3-button">
                <?= $language->render('pagination', 'next') ?>
            </a>
        <?php endif ?>
    </div>
</div>
