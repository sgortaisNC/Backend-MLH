<?php
/**
 * @var string $retour
 * @see nc_page_single()
 */
?>

<div>
    <?php the_content(); ?>
    <?php if ( has_shortcode(get_the_content(), 'forminator_form') ) : ?>
        <a href="<?= $retour; ?>" title="Retour à la page précédente">
            Retour
        </a>
    <?php endif; ?>
</div>
