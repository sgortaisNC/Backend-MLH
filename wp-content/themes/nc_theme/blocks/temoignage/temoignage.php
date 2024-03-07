<?php
/**
 * Testimonial Block template.
 *
 * @param array $block The block settings and attributes.
 */

// Load values and assign defaults.
$image = get_field('image');
$contenu = !empty(get_field('contenu')) ? get_field('contenu') : '...';
$source = get_field('source');

// Create class attribute allowing for custom "className" and "align" values.
$class_name = 'temoignage';
if ($image) $class_name .= " temoignage--image";

if (!empty($block['className'])) {
    $class_name .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $class_name .= ' align' . $block['align'];
}
?>

<div class="<?php echo esc_attr($class_name); ?>">
    <?php if ($image) : ?>
        <figure class="temoignage__image">
            <?php echo wp_get_attachment_image($image['ID'], 'temoignage', '', array('class' => 'temoignage__img')); ?>
        </figure>
    <?php endif; ?>

    <div class="temoignage__content">
        <svg xmlns="http://www.w3.org/2000/svg" width="58.011" height="43.253" viewBox="0 0 58.011 43.253">
            <path d="M111.228,229.946h-7.735V209.77h23.7v13.817c0,14.027-9.524,26.179-22.875,29.1-1.2.262-1.914.338-1.914.338l-2.4-6.016C110.617,241.567,111.228,229.946,111.228,229.946Z" transform="translate(-100 -209.77)" fill="#a7c830"/>
            <path d="M536.225,229.946H528.49V209.77h23.7v13.817c0,14.027-9.525,26.179-22.876,29.1-1.2.262-1.914.338-1.914.338l-2.4-6.016C535.613,241.567,536.225,229.946,536.225,229.946Z" transform="translate(-494.182 -209.77)" fill="#a7c830"/>
        </svg>

        <?= $contenu; ?>

        <div class="temoignage__source">
            <?= $source; ?>
        </div>
    </div>

</div>
