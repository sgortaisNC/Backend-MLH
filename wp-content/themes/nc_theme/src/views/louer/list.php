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

    <div>
        <label for="rayon">Sélectionnez un rayon:</label>
        <input type="range" name="rayon" id="rayon" min="5" max="100" step="5"
               value="<?= (!empty($params['rayon'])) ? $params['rayon'] : '5' ?>">
        <span id="rayonValue"><?= (!empty($params['rayon'])) ? $params['rayon'] : '5' ?> km</span>
    </div>
    <br>
    <div>
        <label for="type_logement">Sélectionnez un type de logement:</label>
        <?php foreach ($filtres['types'] as $id => $filtre) : ?>
            <input type="checkbox" id="type_logement" name="type[]" value="<?= $id ?>"
                <?= (!empty($params['type']) && in_array($id, $params['type'])) ? 'checked' : '' ?>>
            <label><?= ucfirst($filtre) ?></label>
        <?php endforeach; ?>
    </div>
    <br>
    <div>
        <label for="ville">Sélectionnez une ville:</label>
        <select name="ville" id="ville">
            <option value="">Sélectionnez une ville</option>
            <?php foreach ($filtres['villes'] as $id => $filtre) : ?>
                <option value="<?= $id ?>"
                    <?= (!empty($params['ville']) && $params['ville'] == $id) ? 'selected' : '' ?>>
                    <?= ucfirst($filtre) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <br>
    <div>
        <label for="nombre">Nombre de pièces:</label>
        <select name="nombre" id="nombre">
            <option value="">Sélectionnez un nombre de pièce</option>
            <?php foreach ($filtres['nombre_piece'] as $id => $filtre) : ?>
                <option value="<?= $id ?>"
                    <?= (!empty($params['nombre']) && $params['nombre'] == $id) ? 'selected' : '' ?>>
                    <?= ucfirst($filtre) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <br>
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
                <p><?= $location['surface'] ?> m²</p>
            <?php endif; ?>

            <?php if(!empty($location['nombre_pieces'])) : ?>
                <p><?=  $location['nombre_pieces'] ?></p>
            <?php endif; ?>

            <?php if(!empty($location['loyer'])) : ?>
                <p><?= $location['loyer'] ?> €</p>
            <?php endif; ?>

            <?php if(!empty($location['lien'])) : ?>
            <a href="<?= $location['lien'] ?>">Lire la suite</a>
            <?php endif; ?>

        </article>
    <?php endforeach; ?>
<?php endif; ?>

<?= nc_pagination($max_num_pages) ?>

<div id="map" data-marker='<?= json_encode($marker, JSON_HEX_APOS); ?>'
     style="height: 455px"></div>
