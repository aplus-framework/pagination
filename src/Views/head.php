<?php
/**
 * @var \Framework\Pagination\Pager $pager
 */
?>

<?php if ($pager->previousPage > 0) : ?>
	<link rel="prev" href="<?= $pager->getPreviousPageURL() ?>">
<?php endif ?>
<link rel="canonical" href="<?= $pager->getCurrentPageURL() ?>">
<?php if ($pager->nextPage) : ?>
	<link rel="next" href="<?= $pager->getNextPageURL() ?>">
<?php endif ?>
