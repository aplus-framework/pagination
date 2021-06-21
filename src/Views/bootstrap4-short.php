<?php
/**
 * @var \Framework\Pagination\Pager $pager
 */
?>
<ul class="pagination justify-content-center">
	<?php if ($pager->previousPage > 0) : ?>
		<li class="page-item">
			<a class="page-link" rel="prev" href="<?= $pager->getPreviousPageURL() ?>">
				<?= $pager->getLanguage()->render('pagination', 'previous') ?>
			</a>
		</li>
	<?php endif ?>

	<?php if ($pager->nextPage) : ?>
		<li class="page-item">
			<a class="page-link" rel="next" href="<?= $pager->getNextPageURL() ?>">
				<?= $pager->getLanguage()->render('pagination', 'next') ?>
			</a>
		</li>
	<?php endif ?>
</ul>
