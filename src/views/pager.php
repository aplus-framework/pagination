<?php
/**
 * @var \Framework\Pagination\Pager $this
 */
?>
<ul class="pagination">
	<?php if ($this->previousPage > 0) { ?>
		<li>
			<a rel="prev" href="<?= $this->getPreviousPageURL(); ?>" title="Previous">
				&laquo; Previous
			</a>
		</li>
	<?php } ?>

	<?php if ($this->nextPage) { ?>
		<li>
			<a rel="next" href="<?= $this->getNextPageURL(); ?>" title="Next">
				Next &raquo;
			</a>
		</li>
	<?php } ?>
</ul>
