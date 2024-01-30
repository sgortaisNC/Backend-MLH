<?php
/**
 * @var string $retour
 * @see nc_page_single()
 */
?>

    <h1><?php the_title() ?></h1>

    <?php if ( has_post_thumbnail())  : ?>
        <img src="<?= get_the_post_thumbnail_url(get_the_ID(), 'nc_page_single') ?>" alt="">
    <?php endif; ?>

    <?php if(has_excerpt()) : ?>
        <p><?php the_excerpt() ?></p>
    <?php endif; ?>

    <hr>

    <?php the_content(); ?>

    <?php if ( has_shortcode(get_the_content(), 'forminator_form') ) : ?>
        <a href="<?= $retour; ?>" title="Retour à la page précédente" class="btn">
            Retour
        </a>
    <?php endif; ?>

    <?php if(!empty(nc_sidebar())) : ?>
        <?= nc_sidebar() ?>
    <?php endif; ?>

    <a href="<?= $retour; ?>" title="Retour à la page précédente">
        Retour
    </a>

