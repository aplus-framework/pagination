<?php
/*
 * This file is part of The Framework Pagination Library.
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
<ul class="pagination text-center">
	<?php if ($pager->getCurrentPage() - $pager->getSurround() > 1) : ?>
        <li>
            <a href="<?= $pager->getFirstPageUrl() ?>"><?= $pager->getLanguage()
	    ->render('pagination', 'first') ?></a>
        </li>
	<?php endif ?>

	<?php if ($pager->getPreviousPage() > 0) : ?>
        <li>
            <a href="<?= $pager->getPreviousPageUrl() ?>" rel="prev" title="<?=
            $pager->getLanguage()->render('pagination', 'previous') ?>">&laquo;</a>
        </li>
	<?php endif ?>

	<?php foreach ($pager->getPreviousPagesUrls() as $p => $url) : ?>
        <li>
            <a href="<?= $url ?>"><?= $p ?></a>
        </li>
	<?php endforeach ?>

    <li class="current">
        <?= $pager->getCurrentPage() ?>
    </li>

	<?php foreach ($pager->getNextPagesUrls() as $p => $url) : ?>
        <li>
            <a href="<?= $url ?>"><?= $p ?></a>
        </li>
	<?php endforeach ?>

	<?php if ($pager->getNextPage() && $pager->getNextPage() < $pager->getLastPage() + 1) : ?>
        <li>
            <a href="<?= $pager->getNextPageUrl() ?>" rel="next" title="<?=
            $pager->getLanguage()->render('pagination', 'next') ?>">&raquo;</a>
        </li>
	<?php endif ?>

	<?php if ($pager->getLastPage()
        && $pager->getCurrentPage() + $pager->getSurround() < $pager->getLastPage()
    ) : ?>
        <li>
            <a href="<?= $pager->getLastPageUrl() ?>"><?= $pager->getLanguage()
	    ->render('pagination', 'last') ?></a>
        </li>
	<?php endif ?>
</ul>
