<?php
/**
 * @var string $breadcrumb
 * @see nc_header()
 */
?>

<header>
    <h1><?php the_title(); ?></h1>
    <?php if ( !empty($breadcrumb) ) : ?>
        <?= $breadcrumb; ?>
    <?php endif; ?>
</header>
