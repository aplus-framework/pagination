<?php
/**
 * @var \Framework\Pagination\Pager $this
 */

//var_dump(get_defined_vars());
//exit;
?>
<ul class="pagination">

	<?php if ($this->currentPage - $this->surround > 1) { ?>
		<li>
			<a href="<?= $this->getFirstPageURL(); ?>">First</a>
		</li>
	<?php } ?>

	<?php if ($this->previousPage > 0) { ?>
		<li>
			<a rel="prev" href="<?= $this->getPreviousPageURL(); ?>" title="Previous">&laquo;</a>
		</li>
	<?php } ?>

	<?php foreach ($this->getPreviousPagesURLs() as $p => $url) { ?>
		<li>
			<a href="<?= $url; ?>"><?= $p; ?></a>
		</li>
	<?php } ?>

	<li>
		<a rel="canonical" href="<?= $this->getCurrentPageURL(); ?>" class="active">
			<?= $this->currentPage; ?>
		</a>
	</li>

	<?php foreach ($this->getNextPagesURLs() as $p => $url) { ?>
		<li>
			<a href="<?= $url; ?>"><?= $p; ?></a>
		</li>
	<?php } ?>

	<?php if ($this->nextPage && $this->nextPage !== $this->totalPages) { ?>
		<li>
			<a rel="next" href="<?= $this->getNextPageURL(); ?>" title="Next">&raquo;</a>
		</li>
	<?php } ?>

	<?php if ($this->totalPages && $this->currentPage + $this->surround < $this->totalPages) { ?>
		<li>
			<a href="<?= $this->getLastPageURL(); ?>">Last</a>
		</li>
	<?php } ?>

</ul>
