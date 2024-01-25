<?php
/**
 * @see nc_post_list()
 * @var array $emplois
 * @var array $filtres
 * @var array $params
 *
 */
?>


<h1><?php the_title() ?></h1>

<form action="">

    <select name="contrat">
        <option value="">Sélectionnez un contrat</option>
        <?php foreach ($filtres['contrats'] as $id => $filtre) : ?>
            <option value="<?= $id ?>"
                <?= (!empty($params['contrat']) && $params['contrat'] == $id) ? 'selected="selected"' : null ?>>
                <?= ucfirst($filtre) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="metier">
        <option value="">Sélectionnez un métier</option>
        <?php foreach ($filtres['metiers'] as $id => $filtre) : ?>
            <option value="<?= $id ?>"
                <?= (!empty($params['metier']) && $params['metier'] == $id) ? 'selected="selected"' : null ?>>
                <?= ucfirst($filtre) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <input type="submit" value="Rechercher">
    <a href="<?= get_permalink(EMPLOI_LIST) ?>" class="btn">Réinitialiser</a>

</form>

<a href="<?= get_permalink(164) ?>">Candidature spontannée</a>

<?php foreach ($emplois as $emploi) : ?>
    <article>
        <h2><?= $emploi['titre'] ?></h2>
        <p><?= $emploi['date'] ?></p>
        <p><?= $emploi['metier'] ?></p>
        <p><?= $emploi['contrat'] ?></p>
        <a href="<?= $emploi['lien'] ?>">Lire la suite</a>
    </article>
<?php endforeach; ?>

<?php if (!empty($max_num_pages > 1)) : ?>
    <section class="pagination">
        <?php nc_pagination($max_num_pages); ?>
    </section>
<?php endif; ?>
