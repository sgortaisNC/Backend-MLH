<?php
/**
 * @var string $contenu
 * @var int $image
 * @see NC_Featured_Widget::render()
 */
?>

<div class="featured <?= !empty($image) ? 'featured-image' : ''; ?>">
    <div><?= $contenu; ?></div>
    <?php if ( !empty($image) ) : ?>
        <img src="<?= wp_get_attachment_image_src($image, 'nc_elementor_featured')[0]; ?>" alt="<?php the_title(); ?>">
    <?php endif; ?>
</div>
