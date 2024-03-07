<?php
/**
 * Testimonial Block template.
 *
 * @param array $block The block settings and attributes.
 */

// Load values and assign defaults.
$accordeons = get_field('accordeons');

// Create class attribute allowing for custom "className" and "align" values.
$class_name = 'accordeons';

if (!empty($block['className'])) {
    $class_name .= ' ' . $block['className'];
}

if (!empty($block['align'])) {
    $class_name .= ' align' . $block['align'];
}
?>

<div class="<?php echo esc_attr($class_name); ?>">
    <?php if ($accordeons) : ?>
        <?php foreach ($accordeons as $accordeon): ?>
            <?php if (!empty($accordeon['titre'])): ?>
                <div class="accordeon">
                    <div class="accordeon__title">
                        <button class="accordion">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 420 420">
                                <line y2="384" transform="translate(210 18)" fill="none" stroke="#04add1" stroke-linecap="round" stroke-width="36"/>
                                <line y2="384" transform="translate(402 210) rotate(90)" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="36"/>
                            </svg>
                            <?php echo $accordeon['titre']; ?>
                        </button>
                    </div>
                    <div class="accordeon__content"><?php echo $accordeon['contenu']; ?></div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        Aucun contenu n'est renseigné
    <?php endif; ?>
</div>

<script>
    document.querySelectorAll('.accordion').forEach((acc) => {
        acc.addEventListener('click', () => {
            acc.parentNode.parentNode.classList.toggle('active');
        });
    });
</script>
