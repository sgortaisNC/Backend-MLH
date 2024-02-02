<?php
/**
 * @var array $baseline
 * @var array $filtres
 * @var array $biens
 * @var array $actualites
 * @var array $chiffres
 * @see nc_page_home()
 */
?>

<h2>Baseline</h2>
<div class="baseline">
    <div class="baseline__image">
        <?php if ( !empty($baseline['image']) ) : ?>
            <img src="<?= wp_get_attachment_image_url($baseline['image'], 'nc_page_baseline') ?>" alt="" width="100%" height="700px">
        <?php endif; ?>
    </div>

    <div class="baseline__text">
        <?php if(!empty($baseline['ligne1'])) : ?>
            <p><?= $baseline['ligne1'] ?></p>
        <?php endif; ?>

        <?php if(!empty($baseline['ligne2'])) : ?>
            <p><?= $baseline['ligne2'] ?></p>
        <?php endif; ?>

        <?php if(!empty($baseline['ligne3'])) : ?>
            <p><?= $baseline['ligne3'] ?></p>
        <?php endif; ?>

    </div>
</div>

<form action="<?= get_permalink(BIEN_LOUER_LIST) ?>">

    <div>
        <label for="rayon">Sélectionnez un rayon:</label>
        <input type="range" name="rayon" id="rayon" min="0" max="200" step="5"
               value="<?= (!empty($params['rayon'])) ? $params['rayon'] : '0' ?>">
        <span id="rayonValue"><?= (!empty($params['rayon'])) ? $params['rayon'] : '0' ?> km</span>
    </div>
    <br>
    <div>
        <label for="type_logement">Sélectionnez un type de logement:</label>
        <?php foreach ($filtres['types'] as $id => $filtre) : ?>
            <input type="checkbox" id="type_logement" name="type[]" value="<?= $id ?>">
            <label><?= ucfirst($filtre) ?></label>
        <?php endforeach; ?>
    </div>
    <br>
    <div>
        <label for="ville">Sélectionnez une ville:</label>
        <select name="ville" id="ville">
            <option value="">Sélectionnez une ville</option>
            <?php foreach ($filtres['villes'] as $id => $filtre) : ?>
                <option value="<?= $id ?>">
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
                <option value="<?= $id ?>">
                    <?= ucfirst($filtre) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <br>
    <input type="submit" value="Rechercher">
    <a href="<?= get_permalink(BIEN_LOUER_LIST) ?>" class="btn">Réinitialiser</a>

</form>

<hr>

<h2>Les biens à la une</h2>
<?php if(!empty($biens)) : ?>
    <?php foreach($biens as $bien) : ?>
        <article>
            <?php if(!empty($bien['titre'])) : ?>
                <h2><?= $bien['titre'] ?></h2>
            <?php endif; ?>

            <?php if(!empty($bien['image'])) : ?>
                <img src="<?= $bien['image'] ?>" alt="" width="200px">
            <?php endif; ?>

            <?php if(!empty($bien['type'])) : ?>
                <p><?= $bien['type']?> </p>
            <?php endif; ?>

            <?php if(!empty($bien['ville'])) : ?>
                <p><?= $bien['ville'] ?></p>
            <?php endif; ?>

            <?php if(!empty($bien['surface'])) : ?>
                <p><?= $bien['surface'] ?> m²</p>
            <?php endif; ?>

            <?php if(!empty($bien['nombre_pieces'])) : ?>
                <p><?=  $bien['nombre_pieces'] ?></p>
            <?php endif; ?>

            <?php if(!empty($bien['loyer'])) : ?>
                <p><?= $bien['loyer'] ?> €</p>
            <?php endif; ?>

            <?php if(!empty($bien['lien'])) : ?>
                <a href="<?= $bien['lien'] ?>">Lire la suite</a>
            <?php endif; ?>

        </article>
    <?php endforeach; ?>
<?php endif; ?>

<a href="<?= get_permalink(BIEN_LOUER_LIST) ?>">Tous les logements à louer</a>

<hr>

<h2>Les actualités</h2>
<?php if(!empty($actualites)) : ?>
    <?php foreach($actualites as $actualite) : ?>
        <article>
            <?php if(!empty($actualite['titre'])) : ?>
                <h2><?= $actualite['titre'] ?></h2>
            <?php endif; ?>

            <?php if(!empty($actualite['image'])) : ?>
                <img src="<?= $actualite['image'] ?>" alt="" width="200px">
            <?php endif; ?>

            <?php if(!empty($actualite['date'])) : ?>
                <p><?= $actualite['date'] ?></p>
            <?php endif; ?>

            <?php if(!empty($actualite['lien'])) : ?>
                <a href="<?= $actualite['lien'] ?>">Lire la suite</a>
            <?php endif; ?>

        </article>
    <?php endforeach; ?>
<?php endif; ?>

<a href="<?= get_permalink(POST_LIST) ?>">Toutes les actualités</a>

<hr>

<h2>Chiffres clés</h2>
<?php if(!empty($chiffres)) : ?>
    <?php foreach($chiffres as $chiffre) : ?>
        <article>

            <?php if(!empty($chiffre['pictogramme'])) : ?>
                <img src="<?= $chiffre['pictogramme'] ?>" alt="" width="50">
            <?php endif; ?>

            <?php if(!empty($chiffre['chiffre'])) : ?>
                <p><?= $chiffre['chiffre'] ?></p>
            <?php endif; ?>

            <?php if(!empty($chiffre['texte'])) : ?>
                <p><?= $chiffre['texte'] ?></p>
            <?php endif; ?>

        </article>
        <hr>
    <?php endforeach; ?>
<?php endif; ?>

<?php the_content(); ?>



