<?php
/**
 * @var \Framework\Pagination\Pager $this
 */
?>
<ul class="pagination">
	<?php if ($this->previousPage > 0) { ?>
		<li>
			<a rel="prev" href="<?= $this->getPreviousPageURL(); ?>" title="<?= $this->getLanguage()->render('pagination', 'previous'); ?>">
				&laquo; <?= $this->getLanguage()->render('pagination', 'previous'); ?>
			</a>
		</li>
	<?php } ?>

	<?php if ($this->nextPage) { ?>
		<li>
			<a rel="next" href="<?= $this->getNextPageURL(); ?>" title="<?= $this->getLanguage()->render('pagination', 'next'); ?>">
				<?= $this->getLanguage()->render('pagination', 'next'); ?> &raquo; </a>
		</li>
	<?php } ?>
</ul>
