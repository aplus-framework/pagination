<?php
/**
 * @var \Framework\Pagination\Pager $this
 */
?>
<ul class="pagination justify-content-center">
	<?php if ($this->previousPage > 0) : ?>
		<li class="page-item">
			<a class="page-link" rel="prev" href="<?= $this->getPreviousPageURL() ?>">
				<?= $this->getLanguage()->render('pagination', 'previous') ?>
			</a>
		</li>
	<?php endif ?>

	<?php if ($this->nextPage) : ?>
		<li class="page-item">
			<a class="page-link" rel="next" href="<?= $this->getNextPageURL() ?>">
				<?= $this->getLanguage()->render('pagination', 'next') ?>
			</a>
		</li>
	<?php endif ?>
</ul>
