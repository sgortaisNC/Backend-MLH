<?php
/**
 * @see nc_louer_single()
 * @var array $marker
 * @var string $ville
 * @var string $type
 * @var string $nombre_pieces
 *
 */
?>

<h1><?php the_title() ?></h1>

<?php if(has_post_thumbnail())  :?>
    <img src="<?= get_the_post_thumbnail_url(get_the_ID(), "nc_louer_single") ?>" alt="" width="300px">
<?php endif; ?>

<?php if(!empty(get_field('images'))) : ?>
    <div class="row">
        <?php foreach (get_field('images') as $image) : ?>
            <div class="col-12 col-md-6 col-lg-4">
                <img src="<?= wp_get_attachment_image_url($image['image']['id'], "nc_louer_single") ?>" alt="" width="150px">
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<h3>Caractéristiques</h3>

<?php if(!empty(get_field('reference_bien'))) : ?>
    <p><?= get_field('reference_bien') ?></p>
<?php endif ?>

<?php if(!empty(get_field('adresse'))) : ?>
    <p><?= get_field('adresse') ?></p>
<?php endif ?>

<?php if(!empty($ville)) : ?>
    <p><?= $ville ?></p>
<?php endif ?>

<?php if(!empty($type)) : ?>
    <p><?= $type ?></p>
<?php endif ?>

<?php if(!empty($nombre_pieces)) : ?>
    <p><?= $nombre_pieces ?></p>
<?php endif ?>

<?php if(!empty(get_field('surface'))) : ?>
    <p><?= get_field('surface') ?>m²</p>
<?php endif ?>

<?php if(!empty(get_field('type_chauffage'))) : ?>
    <p><?= get_field('type_chauffage') ?></p>
<?php endif ?>

<?php if(!empty(get_field('bilan_energetique'))) : ?>
    <p><?= get_field('bilan_energetique') ?></p>
<?php endif ?>

<?php if(!empty(get_field('loyer'))) : ?>
    <p><?= get_field('loyer') ?>€</p>
<?php endif ?>

<?php if(!empty(get_field('charges'))) : ?>
    <p><?= get_field('charges') ?>€</p>
<?php endif ?>

<?php if(!empty(get_field('loyer_charges_comprises'))) : ?>
    <p><?= get_field('loyer_charges_comprises') ?>€</p>
<?php endif ?>

<?php if(!empty(get_the_date())) : ?>
    <p><?= get_the_date() ?></p>
<?php endif ?>


<?php if (!empty($marker['latitude']) && !empty($marker['longitude'])) : ?>
    <div class="col-12 col-lg-6">
        <h2>Situer ce bien</h2>
        <div id="map-single" data-carte='<?= json_encode($marker, JSON_HEX_APOS); ?>'
             style="height: 455px"></div>
    </div>
<?php endif; ?>

<?php if(!empty(nc_sidebar())) : ?>
    <?= nc_sidebar() ?>
<?php endif; ?>

<a href="">Imprimer la fiche</a>

<br>

<a href="<?= get_permalink(CONTACT) ?>">Cette annonce vous intéresse ?</a>

<br>

<a href="<?= get_permalink(BIEN_LOUER_LIST) ?>">Retour</a>
