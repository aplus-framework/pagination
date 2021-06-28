<?php
/*
 * This file is part of The Framework Pagination Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
/**
 * @var \Framework\Pagination\Pager $pager
 */
?>
<div class="ui container center aligned">
	<div class="ui pagination menu">
		<?php if ($pager->previousPage > 0) : ?>
			<a class="item" rel="prev" href="<?= $pager->getPreviousPageURL() ?>">
				<?= $pager->getLanguage()->render('pagination', 'previous') ?>
			</a>
		<?php endif ?>

		<?php if ($pager->nextPage) : ?>
			<a class="item" rel="next" href="<?= $pager->getNextPageURL() ?>">
				<?= $pager->getLanguage()->render('pagination', 'next') ?>
			</a>
		<?php endif ?>
	</div>
</div>
