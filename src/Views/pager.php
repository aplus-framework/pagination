<?php
/**
 * @var \Framework\Pagination\Pager $pager
 */
?>
<ul class="pagination">
	<?php if ($pager->previousPage > 0) : ?>
		<li>
			<a rel="prev" href="<?= $pager->getPreviousPageURL() ?>" title="<?= $pager->getLanguage()->render('pagination', 'previous') ?>">
				&laquo; <?= $pager->getLanguage()->render('pagination', 'previous') ?>
			</a>
		</li>
	<?php endif ?>

	<?php if ($pager->nextPage) : ?>
		<li>
			<a rel="next" href="<?= $pager->getNextPageURL() ?>" title="<?= $pager->getLanguage()->render('pagination', 'next') ?>">
				<?= $pager->getLanguage()->render('pagination', 'next') ?> &raquo; </a>
		</li>
	<?php endif ?>
</ul>
