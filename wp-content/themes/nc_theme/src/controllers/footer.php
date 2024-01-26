<?php

function nc_footer() {
    //Menu footer
    $menu =  wp_nav_menu([
        'theme_location' => "menu_footer",
        'items_wrap' => '<ul class="%2$s">%3$s</ul>',
        'depth' => 2,
        'echo' => false,
        'container' => '',
    ]);

    //Accès rapides
    $acces = [];
    $acces_rapide = get_field("acces_rapides", "options") ?? null;

    if(!empty($acces_rapide)){
        foreach ($acces_rapide as $value) {
            $acces[$value['lien'][0]->ID] = $value['lien'][0]->post_name ?? null;
        }
    }

    //Réseaux sociaux
    $social = [];
    $social_networks = get_field("reseaux_sociaux", "options") ?? null;

    if(!empty($social_networks)){
        $social["linkedin"] = $social_networks['linkedin'] ?? null;
        $social["facebook"] = $social_networks['facebook'] ?? null;
        $social["instagram"] = $social_networks['instagram'] ?? null;
        $social["x"] = $social_networks['x'] ?? null;
    }

    $coordonneesMaps = get_field("coordonnees_localisation", "options") ?
        str_replace(" ", "+", get_field("coordonnees_localisation", "options")) : null;
    render('footer', [
        "logo" => get_field("logo_footer", "options") ?? null,
        "social" => $social ?? null,
        "acces_rapide" => $acces ?? null,
        "coordonnees" => get_field("coordonnees_localisation", "options") ?? null,
        "coordonneesMaps" => $coordonneesMaps ?? null,
        "menu" => $menu ?? null,
    ]);
}
