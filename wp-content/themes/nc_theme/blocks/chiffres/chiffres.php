<?php
/**
 * Testimonial Block template.
 *
 * @param array $block The block settings and attributes.
 */

// Load values and assign defaults.
$chiffres = get_field('chiffres');

// Create class attribute allowing for custom "className" and "align" values.
$class_name = 'chiffres';

if (!empty($block['className'])) {
    $class_name .= ' ' . $block['className'];
}

if (!empty($block['align'])) {
    $class_name .= ' align' . $block['align'];
}
?>

<div class="<?php echo esc_attr($class_name); ?>">
    <?php if ($chiffres) : ?>
        <?php foreach ($chiffres as $chiffre): ?>
            <?php if(!empty($chiffre['chiffre'])): ?>
                <div class="chiffre">
                    <b><?php echo $chiffre['chiffre']; ?></b>
                    <p><?php echo $chiffre['description']; ?></p>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        Aucun chiffre clé n'est renseigné
    <?php endif; ?>
</div>
