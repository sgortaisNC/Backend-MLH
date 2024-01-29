<?php
/**
 * @var array $popup
 */
?>

<?php if (!empty($popup)) : ?>
    <article class="popCard">
        <?php if (!empty($popup['image'])) : ?>
            <img src="<?= $popup['image'] ?>" alt="">
        <?php endif; ?>

        <div>
            <?php if (!empty($popup['type'])) : ?>
                <span class=""><?= $popup['type'] ?></span>
            <?php endif; ?>

            <?php if (!empty($popup['titre'])) : ?>
                <h4 class=""><?= $popup['titre'] ?></h4>
            <?php endif; ?>

            <?php if (!empty($popup['ville'])) : ?>
                <div class=""><i class="fal fa-location-dot"></i><?= $popup['ville'] ?></div>
            <?php endif; ?>

            <?php if (!empty($popup['nombre_pieces'])) : ?>
                <div class=""><?= $popup['nombre_pieces'] ?>m²</div>
            <?php endif; ?>

            <?php if (!empty($popup['surface'])) : ?>
                <div class=""><?= $popup['surface'] ?>m²</div>
            <?php endif; ?>

            <?php if (!empty($popup['loyer'])) : ?>
                <div class=""><?= $popup['loyer'] ?>€</div>
            <?php endif; ?>

            <a class="stretched" href="<?= esc_html(get_permalink($popup['id'])) ?>">Voir le bien</a>

        </div>

    </article>
<?php endif; ?>
