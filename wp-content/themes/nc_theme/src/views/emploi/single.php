<?php
/**
 * @see nc_post_single()
 * @var array $emploi
 */
?>

<h1><?php the_title() ?></h1>

<?php if(!empty($emploi)) : ?>
    <div>
        <h3>Caractéristiques</h3>
        <ul>
            <?php if(!empty($emploi['reference'])) : ?>
                <li>Réference : <?= $emploi['reference'] ?></li>
            <?php endif; ?>

            <?php if(!empty($emploi['contrat'])) : ?>
                <li><?= $emploi['contrat'] ?></li>
            <?php endif; ?>

            <?php if(!empty($emploi['metier'])) : ?>
                <li><?= $emploi['metier'] ?></li>
            <?php endif; ?>

            <?php if(!empty($emploi['date'])) : ?>
                <li><?= $emploi['date'] ?></li>
            <?php endif; ?>

            <?php if(!empty($emploi['pdf'])) : ?>
                <li><a href="<?= wp_get_attachment_url($emploi['pdf']['ID']) ?>" target="_blank">Télécharger le fichier PDF</a></li>

            <?php endif; ?>

        </ul>
    </div>
<?php endif; ?>

<a href="<?= get_permalink(POSTULER) ?>">Postuler</a>

<div>
    <?php the_content(); ?>
</div>

<?php if(!empty(nc_sidebar())) : ?>
    <?= nc_sidebar() ?>
<?php endif; ?>

<a href="<?= get_permalink(EMPLOI_LIST) ?>">Liste des emplois</a>
