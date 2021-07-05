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
<ul class="pagination justify-content-center">
	<?php if ($pager->getCurrentPage() - $pager->getSurround() > 1) : ?>
		<li class="page-item">
			<a class="page-link" href="<?= $pager->getFirstPageURL() ?>"><?= $pager->getLanguage()
		->render('pagination', 'first') ?></a>
		</li>
	<?php endif ?>

	<?php if ($pager->getPreviousPage() > 0) : ?>
		<li class="page-item">
			<a class="page-link" rel="prev" href="<?= $pager->getPreviousPageURL() ?>" title="<?=
			$pager->getLanguage()->render('pagination', 'previous') ?>">&laquo;</a>
		</li>
	<?php endif ?>

	<?php foreach ($pager->getPreviousPagesURLs() as $p => $url) : ?>
		<li class="page-item">
			<a class="page-link" href="<?= $url ?>"><?= $p ?></a>
		</li>
	<?php endforeach ?>

	<li class="page-item active">
		<a class="page-link" rel="canonical" href="<?= $pager->getCurrentPageURL() ?>">
			<?= $pager->getCurrentPage() ?>
		</a>
	</li>

	<?php foreach ($pager->getNextPagesURLs() as $p => $url) : ?>
		<li class="page-item">
			<a class="page-link" href="<?= $url ?>"><?= $p ?></a>
		</li>
	<?php endforeach ?>

	<?php if ($pager->getNextPage() && $pager->getNextPage() !== $pager->getLastPage()) : ?>
		<li class="page-item">
			<a class="page-link" rel="next" href="<?= $pager->getNextPageURL() ?>" title="<?=
			$pager->getLanguage()->render('pagination', 'next') ?>">&raquo;</a>
		</li>
	<?php endif ?>

	<?php if ($pager->getLastPage()
		&& $pager->getCurrentPage() + $pager->getSurround() < $pager->getLastPage()
	) : ?>
		<li class="page-item">
			<a class="page-link" href="<?= $pager->getLastPageURL() ?>"><?= $pager->getLanguage()
		->render('pagination', 'last') ?></a>
		</li>
	<?php endif ?>
</ul>
