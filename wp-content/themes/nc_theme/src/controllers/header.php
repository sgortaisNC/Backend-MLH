<?php

function nc_header() {
    $menu =  wp_nav_menu([
        'theme_location' => "menu_header",
        'items_wrap' => '<ul class="%2$s">%3$s</ul>',
        'depth' => 2,
        'echo' => false,
        'container' => '',
    ]);

    $alerteOption = get_field('alerte', 'option');

    $alerte = [];
    $today = date('d/m/Y');

    if(!empty($alerteOption) && $today >= $alerteOption['date_debut'] && $today <= $alerteOption['date_fin']) {
        $alerte[] = [
            'titre' => $alerteOption['titre'],
            'contenu' => $alerteOption['contenu'],
            'date_debut' => $alerteOption['date_debut'],
            'date_fin' => $alerteOption['date_fin'],
        ];
    }

    render('header', [
        'alerte' => $alerte,
        'menu' => $menu,
        'logo' => get_field('logo', 'option') ?? null,
        'espace' => get_field('espace_locataire', 'option') ?? null,
        'demande_logement' => get_field('demande_logement', 'option') ?? null,
        'share' => (class_exists('NCShare') ? NCShare::render() : null),
        'breadcrumb' => (class_exists('NC_Ariane') && !is_front_page() ? NC_Ariane::render() : null),
    ]);
}
