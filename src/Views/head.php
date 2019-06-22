<?php
/**
 * @var \Framework\Pagination\Pager $this
 */
?>

<?php if ($this->previousPage > 0) { ?>
	<link rel="prev" href="<?= $this->getPreviousPageURL(); ?>">
<?php } ?>
	<link rel="canonical" href="<?= $this->getCurrentPageURL(); ?>">
<?php if ($this->nextPage) { ?>
	<link rel="next" href="<?= $this->getNextPageURL(); ?>">
<?php } ?>
