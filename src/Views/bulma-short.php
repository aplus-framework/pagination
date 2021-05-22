<?php
/**
 * @var \Framework\Pagination\Pager $this
 */
?>
<nav class="pagination is-centered">
	<ul class="pagination-list">
		<?php if ($this->previousPage > 0) : ?>
			<li>
				<a class="pagination-link" rel="prev" href="<?= $this->getPreviousPageURL() ?>">
					<?= $this->getLanguage()->render('pagination', 'previous') ?>
				</a>
			</li>
		<?php endif ?>

		<?php if ($this->nextPage) : ?>
			<li>
				<a class="pagination-link" rel="next" href="<?= $this->getNextPageURL() ?>">
					<?= $this->getLanguage()->render('pagination', 'next') ?>
				</a>
			</li>
		<?php endif ?>
	</ul>
</nav>
