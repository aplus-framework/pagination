<?php
/**
 * @var \Framework\Pagination\Pager $pager
 */
?>
<div class="ui container center aligned">
	<div class="ui pagination menu">
		<?php if ($pager->currentPage - $pager->surround > 1) : ?>
			<a class="item" href="<?= $pager->getFirstPageURL() ?>"><?= $pager->getLanguage()
			->render('pagination', 'first') ?></a>
		<?php endif ?>

		<?php if ($pager->previousPage > 0) : ?>
			<a class="item" rel="prev" href="<?= $pager->getPreviousPageURL() ?>" title="<?=
			$pager->getLanguage()->render('pagination', 'previous') ?>">&laquo;</a>
		<?php endif ?>

		<?php foreach ($pager->getPreviousPagesURLs() as $p => $url) : ?>
			<a class="item" href="<?= $url ?>"><?= $p ?></a>
		<?php endforeach ?>

		<a class="item active" rel="canonical" href="<?= $pager->getCurrentPageURL() ?>">
			<?= $pager->currentPage ?>
		</a>

		<?php foreach ($pager->getNextPagesURLs() as $p => $url) : ?>
			<a class="item" href="<?= $url ?>"><?= $p ?></a>
		<?php endforeach ?>

		<?php if ($pager->nextPage && $pager->nextPage !== $pager->totalPages) : ?>
			<a class="item" rel="next" href="<?= $pager->getNextPageURL() ?>" title="<?=
			$pager->getLanguage()->render('pagination', 'next') ?>">&raquo;</a>
		<?php endif ?>

		<?php if ($pager->totalPages && $pager->currentPage + $pager->surround < $pager->totalPages) : ?>
			<a class="item" href="<?= $pager->getLastPageURL() ?>"><?= $pager->getLanguage()
			->render('pagination', 'last') ?></a>
		<?php endif ?>
	</div>
</div>
