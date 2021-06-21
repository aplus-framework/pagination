<?php
/**
 * @var \Framework\Pagination\Pager $pager
 */
?>
<ul class="pagination justify-content-center">
	<?php if ($pager->currentPage - $pager->surround > 1) : ?>
		<li class="page-item">
			<a class="page-link" href="<?= $pager->getFirstPageURL() ?>"><?= $pager->getLanguage()
		->render('pagination', 'first') ?></a>
		</li>
	<?php endif ?>

	<?php if ($pager->previousPage > 0) : ?>
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
			<?= $pager->currentPage ?>
		</a>
	</li>

	<?php foreach ($pager->getNextPagesURLs() as $p => $url) : ?>
		<li class="page-item">
			<a class="page-link" href="<?= $url ?>"><?= $p ?></a>
		</li>
	<?php endforeach ?>

	<?php if ($pager->nextPage && $pager->nextPage !== $pager->totalPages) : ?>
		<li class="page-item">
			<a class="page-link" rel="next" href="<?= $pager->getNextPageURL() ?>" title="<?=
			$pager->getLanguage()->render('pagination', 'next') ?>">&raquo;</a>
		</li>
	<?php endif ?>

	<?php if ($pager->totalPages && $pager->currentPage + $pager->surround < $pager->totalPages) : ?>
		<li class="page-item">
			<a class="page-link" href="<?= $pager->getLastPageURL() ?>"><?= $pager->getLanguage()
		->render('pagination', 'last') ?></a>
		</li>
	<?php endif ?>
</ul>
