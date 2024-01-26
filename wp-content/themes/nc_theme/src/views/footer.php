<?php
/**
 * @see nc_footer()
 * @var string $logo
 * @var array $social
 * @var array $acces_rapide
 * @var string $coordonnees
 * @var string $coordonneesMaps
 * @var string $menu
 *
 */
?>

<footer>
    <?php if (!empty($logo)) : ?>
        <img src="<?= wp_get_attachment_image_url($logo['ID'], "nc_footer") ?>" alt="">
    <?php endif; ?>

    <?php if(!empty($menu)) : ?>
        <?= $menu ?>
    <?php endif; ?>

    <?php if(!empty($coordonnees)) : ?>
        <a href="https://www.google.com/maps/search/?api=1&query=<?= $coordonneesMaps ?>" target="_blank"><?= $coordonnees ?></a>
    <?php endif; ?>

    <div>
        <h3>Réseaux sociaux</h3>

        <?php if(!empty($social["linkedin"])) : ?>
            <a href="<?= $social["linkedin"] ?>" target="_blank">Linkedin</a>
        <?php endif; ?>

        <?php if(!empty($social["facebook"])) : ?>
            <a href="<?= $social["facebook"] ?>" target="_blank">Facebook</a>
        <?php endif; ?>

        <?php if(!empty($social["instagram"])) : ?>
            <a href="<?= $social["instagram"] ?>" target="_blank">Instagram</a>
        <?php endif; ?>

        <?php if(!empty($social["x"])) : ?>
            <a href="<?= $social["x"] ?>" target="_blank">X</a>
        <?php endif; ?>

        <hr>
    </div>

    <?php if(!empty($acces_rapide)) : ?>
        <?php foreach ($acces_rapide as $id => $value) : ?>
            <div>
                <a href="<?= get_permalink($id) ?? null ?>"><?= ucfirst($value) ?? null ?></a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</footer>
