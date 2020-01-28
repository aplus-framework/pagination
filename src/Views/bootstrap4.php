<?php
/**
 * @var \Framework\Pagination\Pager $this
 */
?>
<ul class="pagination justify-content-center">
	<?php if ($this->currentPage - $this->surround > 1) : ?>
		<li class="page-item">
			<a class="page-link" href="<?= $this->getFirstPageURL() ?>"><?= $this->getLanguage()
		->render('pagination', 'first') ?></a>
		</li>
	<?php endif ?>

	<?php if ($this->previousPage > 0) : ?>
		<li class="page-item">
			<a class="page-link" rel="prev" href="<?= $this->getPreviousPageURL(); ?>" title="<?=
			$this->getLanguage()->render('pagination', 'previous') ?>">&laquo;</a>
		</li>
	<?php endif ?>

	<?php foreach ($this->getPreviousPagesURLs() as $p => $url) : ?>
		<li class="page-item">
			<a class="page-link" href="<?= $url; ?>"><?= $p ?></a>
		</li>
	<?php endforeach ?>

	<li class="page-item active">
		<a class="page-link" rel="canonical" href="<?= $this->getCurrentPageURL() ?>">
			<?= $this->currentPage ?>
		</a>
	</li>

	<?php foreach ($this->getNextPagesURLs() as $p => $url) : ?>
		<li class="page-item">
			<a class="page-link" href="<?= $url ?>"><?= $p ?></a>
		</li>
	<?php endforeach ?>

	<?php if ($this->nextPage && $this->nextPage !== $this->totalPages) : ?>
		<li class="page-item">
			<a class="page-link" rel="next" href="<?= $this->getNextPageURL() ?>" title="<?=
			$this->getLanguage()->render('pagination', 'next') ?>">&raquo;</a>
		</li>
	<?php endif ?>

	<?php if ($this->totalPages && $this->currentPage + $this->surround < $this->totalPages) : ?>
		<li class="page-item">
			<a class="page-link" href="<?= $this->getLastPageURL() ?>"><?= $this->getLanguage()
		->render('pagination', 'last') ?></a>
		</li>
	<?php endif ?>
</ul>
