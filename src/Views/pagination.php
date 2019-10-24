<?php
/**
 * @var \Framework\Pagination\Pager $this
 */
?>
<ul class="pagination">
	<?php if ($this->currentPage - $this->surround > 1) : ?>
		<li>
			<a href="<?= $this->getFirstPageURL() ?>"><?= $this->getLanguage()
		->render('pagination', 'first') ?></a>
		</li>
	<?php endif ?>

	<?php if ($this->previousPage > 0) : ?>
		<li>
			<a rel="prev" href="<?= $this->getPreviousPageURL(); ?>" title="<?=
			$this->getLanguage()->render('pagination', 'previous') ?>">&laquo;</a>
		</li>
	<?php endif ?>

	<?php foreach ($this->getPreviousPagesURLs() as $p => $url) : ?>
		<li>
			<a href="<?= $url; ?>"><?= $p ?></a>
		</li>
	<?php endforeach ?>
	<li>
		<a rel="canonical" href="<?= $this->getCurrentPageURL() ?>" class="active">
			<?= $this->currentPage ?>
		</a>
	</li>
	<?php foreach ($this->getNextPagesURLs() as $p => $url) : ?>
		<li>
			<a href="<?= $url ?>"><?= $p ?></a>
		</li>
	<?php endforeach ?>

	<?php if ($this->nextPage && $this->nextPage !== $this->totalPages) : ?>
		<li>
			<a rel="next" href="<?= $this->getNextPageURL() ?>" title="<?=
			$this->getLanguage()->render('pagination', 'next') ?>">&raquo;</a>
		</li>
	<?php endif ?>

	<?php if ($this->totalPages && $this->currentPage + $this->surround < $this->totalPages) : ?>
		<li>
			<a href="<?= $this->getLastPageURL() ?>"><?= $this->getLanguage()
		->render('pagination', 'last') ?></a>
		</li>
	<?php endif ?>
</ul>
