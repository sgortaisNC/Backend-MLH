<?php
/**
 * @see nc_louer_list()
 * @var array $louer
 * @var array $filtres
 * @var array $params
 * @var string $max_num_pages
 *
 */
?>

<h1><?php the_title() ?></h1>

<form action="">

    <select name="type">
        <option value="">Sélectionnez un type de logement</option>
        <?php foreach ($filtres['types'] as $id => $filtre) : ?>
            <option value="<?= $id ?>"
                <?= (!empty($params['type']) && $params['type'] == $id) ? 'selected="selected"' : null ?>>
                <?= ucfirst($filtre) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="ville">
        <option value="">Sélectionnez une ville</option>
        <?php foreach ($filtres['villes'] as $id => $filtre) : ?>
            <option value="<?= $id ?>"
                <?= (!empty($params['ville']) && $params['ville'] == $id) ? 'selected="selected"' : null ?>>
                <?= ucfirst($filtre) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <input type="submit" value="Rechercher">
    <a href="<?= get_permalink(BIEN_LOUER_LIST) ?>" class="btn">Réinitialiser</a>

</form>

<?php if(!empty($louer)) : ?>
    <?php foreach ($louer as $location) : ?>
        <article>
            <?php if(!empty($location['titre'])) : ?>
                <h2><?= $location['titre'] ?></h2>
            <?php endif; ?>

            <?php if(!empty($location['image'])) : ?>
                <img src="<?= $location['image'] ?>" alt="">
            <?php endif; ?>

            <?php if(!empty($location['type'])) : ?>
             <p><?= $location['type']?> </p>
            <?php endif; ?>

            <?php if(!empty($location['ville'])) : ?>
                <p><?= $location['ville'] ?></p>
            <?php endif; ?>

            <?php if(!empty($location['surface'])) : ?>
                <p><?= $location['surface'] ?></p>
            <?php endif; ?>

            <?php if(!empty($location['nombre_pieces'])) : ?>
                <p><?=  $location['nombre_pieces'] ?></p>
            <?php endif; ?>

            <?php if(!empty($location['loyer'])) : ?>
                <p><?= $location['loyer'] ?></p>
            <?php endif; ?>

            <?php if(!empty($location['lien'])) : ?>
            <a href="<?= $location['lien'] ?>">Lire la suite</a>
            <?php endif; ?>

        </article>
    <?php endforeach; ?>
<?php endif; ?>

<?= nc_pagination($max_num_pages) ?>
