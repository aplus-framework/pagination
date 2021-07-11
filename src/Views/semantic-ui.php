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
<div class="ui container center aligned">
	<div class="ui pagination menu">
		<?php if ($pager->getCurrentPage() - $pager->getSurround() > 1) : ?>
			<a class="item" href="<?= $pager->getFirstPageURL() ?>"><?= $pager->getLanguage()
		    ->render('pagination', 'first') ?></a>
		<?php endif ?>

		<?php if ($pager->getPreviousPage() > 0) : ?>
			<a class="item" rel="prev" href="<?= $pager->getPreviousPageURL() ?>" title="<?=
            $pager->getLanguage()->render('pagination', 'previous') ?>">&laquo;</a>
		<?php endif ?>

		<?php foreach ($pager->getPreviousPagesURLs() as $p => $url) : ?>
			<a class="item" href="<?= $url ?>"><?= $p ?></a>
		<?php endforeach ?>

		<a class="item active" rel="canonical" href="<?= $pager->getCurrentPageURL() ?>">
			<?= $pager->getCurrentPage() ?>
		</a>

		<?php foreach ($pager->getNextPagesURLs() as $p => $url) : ?>
			<a class="item" href="<?= $url ?>"><?= $p ?></a>
		<?php endforeach ?>

		<?php if ($pager->getNextPage() && $pager->getNextPage() !== $pager->getLastPage()) : ?>
			<a class="item" rel="next" href="<?= $pager->getNextPageURL() ?>" title="<?=
            $pager->getLanguage()->render('pagination', 'next') ?>">&raquo;</a>
		<?php endif ?>

		<?php if ($pager->getLastPage()
            && $pager->getCurrentPage() + $pager->getSurround() < $pager->getLastPage()
        ) : ?>
			<a class="item" href="<?= $pager->getLastPageURL() ?>"><?= $pager->getLanguage()
		    ->render('pagination', 'last') ?></a>
		<?php endif ?>
	</div>
</div>
