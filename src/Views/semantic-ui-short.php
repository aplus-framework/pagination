<?php
/**
 * @var \Framework\Pagination\Pager $this
 */
?>
<div class="ui container center aligned">
	<div class="ui pagination menu">
		<?php if ($this->previousPage > 0) : ?>
			<a class="item" rel="prev" href="<?= $this->getPreviousPageURL() ?>">
				<?= $this->getLanguage()->render('pagination', 'previous') ?>
			</a>
		<?php endif ?>

		<?php if ($this->nextPage) : ?>
			<a class="item" rel="next" href="<?= $this->getNextPageURL() ?>">
				<?= $this->getLanguage()->render('pagination', 'next') ?>
			</a>
		<?php endif ?>
	</div>
</div>
