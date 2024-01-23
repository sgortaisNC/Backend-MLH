<?php
/**
 * @var string $contenu
 * @var string $auteur
 * @var int $image
 * @see NC_Quote_Widget::render()
 */
?>

<div class="quote <?= !empty($image) ? 'quote-image' : ''; ?>">
    <span><?= $auteur; ?></span>
    <div><?= $contenu; ?></div>
    <?php if ( !empty($image) ) : ?>
        <img src="<?= wp_get_attachment_image_src($image, 'nc_elementor_quote')[0]; ?>" alt="<?= $auteur; ?>">
    <?php endif; ?>
</div>
