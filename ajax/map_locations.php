<?php

include_once "../wp-load.php";

$filtres = null;

$filtres['types'] = get_terms([
    'taxonomy' => 'type_de_bien',
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

$args = [
    'post_type' => "bien_louer",
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'paged' => ( !empty($_GET['pg']) ? $_GET['pg'] : 1 ),
];

if(!empty($_GET['type'])){
    $args['tax_query'][] = [
        [
            'taxonomy' => 'type_de_bien',
            'field' => 'term_taxonomy_id',
            'terms' => $_GET['type'],
            'operator' => 'IN'
        ]
    ];
}

if(!empty($_GET['ville'])){
    $args['tax_query'][] = [
        [
            'taxonomy' => 'ville_code_postal',
            'field' => 'term_taxonomy_id',
            'terms' => $_GET['ville'],
            'operator' => 'IN'
        ]
    ];
}

$louerQuery = new WP_Query($args);

$markers = [];

if($louerQuery->have_posts()){
    while ($louerQuery->have_posts()){
        $louerQuery->the_post();

        $popup = [
            'titre' => get_the_title(),
            'image' => has_post_thumbnail(get_the_ID()) ?
                get_the_post_thumbnail_url(get_the_ID(), 'nc_post_list') :
                wp_get_attachment_image_src(IMAGE_DEFAUT, 'nc_post_list')[0],
            'type' => get_the_terms(get_the_ID(), 'type_de_bien') ?
                join(', ', wp_list_pluck(get_the_terms(get_the_ID(), 'type_de_bien'), 'name')) :
                null,
            'ville' => get_the_terms(get_the_ID(), 'ville_code_postal') ?
                join(', ', wp_list_pluck(get_the_terms(get_the_ID(), 'ville_code_postal'), 'name')) :
                null,
            'loyer' => get_field('loyer_charges_comprises') ?? null,
            'surface' => get_field('surface') ?? null,
            'nombre_pieces' => get_the_terms(get_the_ID(), 'nombre_piece') ?
                join(', ', wp_list_pluck(get_the_terms(get_the_ID(), 'nombre_piece'), 'name')) :
                null,
            'id' => get_the_ID(),
        ];

        ob_start();
        include('../wp-content/themes/nc_theme/src/views/map/popup.php');
        $popup_content = ob_get_clean();

        $latitude = get_field('latitude');
        $longitude = get_field('longitude');
        
        if(!empty($latitude) && !empty($longitude)) {
            $latitude = str_replace(',', '.', $latitude);
            $longitude = str_replace(',', '.', $longitude);

            $markers[] = [
                'popup' => $popup_content ?? null,
                'latitude' => $latitude,
                'longitude' => $longitude,
            ];
        }
    }
}

wp_reset_postdata();

header('Content-Type: application/json');
echo json_encode($markers);
