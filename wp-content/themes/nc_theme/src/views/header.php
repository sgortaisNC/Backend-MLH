<?php
/**
 * @var string $breadcrumb
 * @var string $share
 * @var string $espace
 * @var string $logo
 * @var string $menu
 * @var array $alerte
 * @var string $demande_logement
 * @see nc_header()
 */
?>

<header>
    <div style="display:flex">
        <p>Téléphone : 01 23 45 67 89 </p>

        <?php if (!empty($espace)) : ?>
            <a href="<?= $espace ?>" target="_blank">Espace locataire</a>
        <?php endif; ?>

        <?php if (!empty($demande_logement)) : ?>
            <a href="<?= $demande_logement ?>" target="_blank">Demande de logement</a>
        <?php endif; ?>

        <?php if (!empty($logo)) : ?>
            <a href="/"><img src="<?= wp_get_attachment_image_url($logo['ID'], "nc_header") ?>" alt=""></a>
        <?php endif; ?>

        <?= $menu ?>

        <?= get_search_form() ?>

        <?php if (!empty($share)): ?>
            <?= $share; ?>
        <?php endif; ?>

    </div>
    <?php if (!empty($breadcrumb)) : ?>
        <?= $breadcrumb; ?>
    <?php endif; ?>

    <?php if (!empty($alerte)) : ?>
        <div id="alerte" data-alerteid="<?= $alerte[0]['date_debut'] ?><?= $alerte[0]['date_fin'] ?>">
            <?php foreach ($alerte as $item) : ?>
                <p><?= $item['titre'] ?></p>
                <p><?= $item['contenu'] ?></p>
                <p><?= $item['date_debut'] ?></p>
                <p><?= $item['date_fin'] ?></p>
                <button id="btnAlerte">Fermer X</button>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</header>
