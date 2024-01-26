<?php
/**
 * @var array $popup
 */
?>

<?php if (!empty($popup)) : ?>
    <article class="popCard">
        <?php if (!empty($popup['image'])) : ?>
            <img src="<?= $popup['image'] ?>" alt="<?= $popup['titre'] ?>">
        <?php endif; ?>

        <div>
            <?php if (!empty($popup['type'])) : ?>
                <span class="popCard__tags"><?= $popup['type'] ?></span>
            <?php endif; ?>

            <?php if (!empty($popup['titre'])) : ?>
                <div class="popCard__title"><?= $popup['titre'] ?></div>
            <?php endif; ?>

            <?php if (!empty($popup['ville'])) : ?>
                <div class="popCard__ville"><i class="fal fa-location-dot"></i><?= $popup['ville'] ?></div>
            <?php endif; ?>


            <a class="stretched" href="<?= esc_html(get_permalink($popup['id'])) ?>"></a>

        </div>

    </article>
<?php endif; ?>
