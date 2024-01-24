<?php
/**
 * @var string $breadcrumb
 * @see nc_header()
 */
?>

<header>
    <?php if ( !empty($breadcrumb) ) : ?>
        <?= $breadcrumb; ?>
    <?php endif; ?>
</header>
