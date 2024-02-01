<?php

function nc_page_home() {
    //baseline + image option de thème

    $baselineOption = get_field('image_baseline', 'option');
    $baseline = [
        'image' => $baselineOption['image']['ID'] ?? null,
        "ligne1" => $baselineOption["ligne_1"] ?? null,
        "ligne2" => $baselineOption["ligne_2"] ?? null,
        "ligne3" => $baselineOption["ligne_3"] ?? null,
    ];

    // moteur de recherche
    $filtres = null;

    $filtres['types'] = get_terms([
        'taxonomy' => 'type_de_bien',
        'fields' => "id=>name",
        'hide_empty' => false,
        'parent' => 0,
    ]);

    $filtres['nombre_piece'] = get_terms([
        'taxonomy' => 'nombre_piece',
        'fields' => "id=>name",
        'hide_empty' => false,
        'parent' => 0,
    ]);

    $filtres['villes'] = get_terms([
        'taxonomy' => 'ville_code_postal',
        'fields' => "id=>name",
        'hide_empty' => false,
        'parent' => 0,
    ]);

    //Biens à la une
    $biens = [];

    $biensAlaune = get_field('biens_alaune', 'option');


    if(!empty($biensAlaune)){
        foreach ($biensAlaune as $bien){
            $id = $bien->ID;
            $biens[] = [
                'titre' => get_the_title($id),
                'image' => has_post_thumbnail($id) ?
                    get_the_post_thumbnail_url($id, 'nc_home_alaune') :
                    wp_get_attachment_image_src(IMAGE_DEFAUT, 'nc_home_alaune')[0],
                'type' => get_the_terms($id, 'type_de_bien') ?
                    join(', ', wp_list_pluck(get_the_terms($id, 'type_de_bien'), 'name')) :
                    null,
                'ville' => get_the_terms($id, 'ville_code_postal') ?
                    join(', ', wp_list_pluck(get_the_terms($id, 'ville_code_postal'), 'name')) :
                    null,
                'loyer' => get_field('loyer_charges_comprises', $id) ?? null,
                'surface' => get_field('surface', $id) ?? null,
                'nombre_pieces' => get_the_terms($id, 'nombre_piece') ?
                    join(', ', wp_list_pluck(get_the_terms($id, 'nombre_piece'), 'name')) :
                    null,
                'lien' => get_permalink($id),
            ];
        }
    }

    render('page/homepage', [
        'baseline' => $baseline,
        'filtres' => $filtres,
        'biens' => $biens,
        'retour' => !empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], get_home_url()) !== false ? $_SERVER['HTTP_REFERER'] : get_home_url(),
    ]);
}

function nc_page_single() {
    render('page/single', [
        'retour' => !empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], get_home_url()) !== false ? $_SERVER['HTTP_REFERER'] : get_home_url(),
    ]);
}
