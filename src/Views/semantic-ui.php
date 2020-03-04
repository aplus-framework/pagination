<?php
/**
 * @var \Framework\Pagination\Pager $this
 */
?>
<div class="ui pagination menu">
	<?php if ($this->currentPage - $this->surround > 1) : ?>
		<a class="item" href="<?= $this->getFirstPageURL() ?>"><?= $this->getLanguage()
		->render('pagination', 'first') ?></a>
	<?php endif ?>

	<?php if ($this->previousPage > 0) : ?>
		<a class="item" rel="prev" href="<?= $this->getPreviousPageURL(); ?>" title="<?=
		$this->getLanguage()->render('pagination', 'previous') ?>">&laquo;</a>
	<?php endif ?>

	<?php foreach ($this->getPreviousPagesURLs() as $p => $url) : ?>
		<a class="item" href="<?= $url; ?>"><?= $p ?></a>
	<?php endforeach ?>

	<a class="item active" rel="canonical" href="<?= $this->getCurrentPageURL() ?>">
		<?= $this->currentPage ?>
	</a>

	<?php foreach ($this->getNextPagesURLs() as $p => $url) : ?>
		<a class="item" href="<?= $url ?>"><?= $p ?></a>
	<?php endforeach ?>

	<?php if ($this->nextPage && $this->nextPage !== $this->totalPages) : ?>
		<a class="item" rel="next" href="<?= $this->getNextPageURL() ?>" title="<?=
		$this->getLanguage()->render('pagination', 'next') ?>">&raquo;</a>
	<?php endif ?>

	<?php if ($this->totalPages && $this->currentPage + $this->surround < $this->totalPages) : ?>
		<a class="item" href="<?= $this->getLastPageURL() ?>"><?= $this->getLanguage()
		->render('pagination', 'last') ?></a>
	<?php endif ?>
</div>

