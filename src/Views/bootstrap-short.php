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
?>
<ul class="pagination justify-content-center">
    <?php if ($pager->getPreviousPage() > 0) : ?>
        <li class="page-item">
            <a class="page-link" rel="prev" href="<?= $pager->getPreviousPageUrl() ?>">
                <?= $pager->getLanguage()->render('pagination', 'previous') ?>
            </a>
        </li>
    <?php endif ?>

    <?php if ($pager->getNextPage()) : ?>
        <li class="page-item">
            <a class="page-link" rel="next" href="<?= $pager->getNextPageUrl() ?>">
                <?= $pager->getLanguage()->render('pagination', 'next') ?>
            </a>
        </li>
    <?php endif ?>
</ul>
