<?php

/**
 * Class Location
 */
class Location
{
    public function single($request): array
    {
        $slug = $request['slug'];

        $locationBySlug = get_page_by_path($slug, OBJECT, 'bien_louer');

        $location = [];

        if ($locationBySlug) {
            $id = $locationBySlug->ID;

            $images = get_field('images', $id) ?? null;

            $location[] = [
                'id' => $id,
                'titre' => get_the_title($id),
                'description' => get_the_content(null, false, $id),
                'chapo' => has_excerpt($id) ? get_the_excerpt($id) : null,
                'image' => has_post_thumbnail($id) ?
                    get_the_post_thumbnail_url($id, 'nc_louer_single') :
                    wp_get_attachment_image_src(IMAGE_DEFAUT, 'nc_louer_single')[0],
                'date' => get_the_date('d F Y', $id),
                'loyer' => get_field('loyer_charges_comprises', $id) ??
                        get_field('loyer', $id) ?? null,
                'reference' => get_field('reference_bien', $id) ?? null,
                'chauffage' => get_field('type_chauffage', $id) ?? null,
                'charges' => get_field('charges', $id) ?? null,
                'energie' => get_field('bilan_energetique', $id) ?? null,
                'ges' => get_field('ges', $id) ?? null,
                'surface' => get_field('surface', $id) ?? null,
                'type' => get_the_terms($id, 'type_de_bien') ?
                    join(', ', wp_list_pluck(get_the_terms($id, 'type_de_bien'), 'name')) : null,
                'nombre_pieces' => get_the_terms($id, 'nombre_piece') ?
                    join(', ', wp_list_pluck(get_the_terms($id, 'nombre_piece'), 'name')) : null,
                'ville' => get_the_terms($id, 'ville_code_postal') ?
                    join(', ', wp_list_pluck(get_the_terms($id, 'ville_code_postal'), 'name')) : null,
                'markers' => [
                    'latitude' => get_field('latitude', $id) ?? null,
                    'longitude' => get_field('longitude', $id) ?? null,
                ],
                'lien' => get_permalink($id),
                'contactez_nous' => get_permalink(191) . '?reference=' . get_field('reference_bien', $id),
            ];
            $location[0]['images'] = [];
            if (!empty($images)) {
                foreach ($images as $image) {
                    $id = $image['image']['ID'] ?? null;
                    $location[0]['images'][] = [
                        'id' => $id,
                        'url' => wp_get_attachment_image_src($id, 'nc_louer_single')[0] ?? null,
                        'alt' => $image['image']['alt'] ?? null,
                    ];
                }
            }
        }


        return $location;
    }


    public function list(): array
    {
        $filtres = [];

        $filtresTerm['types'] = get_terms([
            'taxonomy' => 'type_de_bien',
            'fields' => "id=>name",
            'hide_empty' => false,
            'parent' => 0,
        ]);

        foreach ($filtresTerm['types'] as $key => $value) {
            $filtres['types'][] = [
                'value' => $key,
                'label' => $value,
            ];
        }

        $filtresTerm['nombre_piece'] = get_terms([
            'taxonomy' => 'nombre_piece',
            'fields' => "id=>name",
            'hide_empty' => false,
            'parent' => 0,
        ]);

        foreach ($filtresTerm['nombre_piece'] as $key => $value) {
            $filtres['nombre_piece'][] = [
                'value' => $key,
                'label' => $value,
            ];
        }

        $filtresTerm['villes'] = get_terms([
            'taxonomy' => 'ville_code_postal',
            'fields' => "id=>name",
            'hide_empty' => false,
            'parent' => 0,
        ]);

        foreach ($filtresTerm['villes'] as $key => $value) {

            $latitude = get_field('latitude', 'ville_code_postal_' . $key);
            $longitude = get_field('longitude', 'ville_code_postal_' . $key);

            $filtres['villes'][] = [
                'value' => $key,
                'label' => $value,
                'latitude' => $latitude,
                'longitude' => $longitude,
            ];
        }


        $args = [
            'post_type' => "bien_louer",
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'paged' => (!empty($_GET['pg']) ? $_GET['pg'] : 1),
        ];

        if (!empty($_GET['type']) && $_GET['type'] !== 'null') {
            $args['tax_query'][] = [
                [
                    'taxonomy' => 'type_de_bien',
                    'field' => 'term_taxonomy_id',
                    'terms' => $_GET['type'],
                    'operator' => 'IN'
                ]
            ];
        }

        if (!empty($_GET['nombre'])) {
            $args['tax_query'][] = [
                [
                    'taxonomy' => 'nombre_piece',
                    'field' => 'term_taxonomy_id',
                    'terms' => $_GET['nombre'],
                    'operator' => 'IN'
                ]
            ];
        }

        if (!empty($_GET['surface'])) {
            $args['meta_query'][] = [
                'key' => 'surface',
                'value' => $_GET['surface'],
                'compare' => '<=',
                'type' => 'NUMERIC'
            ];
        }
        if (!empty($_GET['loyer'])) {
            $args['meta_query'][] = [
                'key' => 'loyer_charges_comprises',
                'value' => $_GET['loyer'],
                'compare' => '<=',
                'type' => 'NUMERIC'
            ];
        }

        if (!empty($_GET['ville']) && !empty($_GET['rayon'])) {
            // Récupération des villes dans un rayon de X kmbresso
            $centreVille = get_term_by('id',$_GET['ville'], 'ville_code_postal');
            $lat1 = get_field('latitude', 'ville_code_postal_' . $centreVille->term_id);
            $long1 = get_field('longitude', 'ville_code_postal_' . $centreVille->term_id);
            $villes = [];
            $tax = get_terms(['taxonomy' => 'ville_code_postal']);
            foreach ($tax as $t) {
                $latitude = get_field('latitude', 'ville_code_postal_' . $t->term_id);
                $longitude = get_field('longitude', 'ville_code_postal_' . $t->term_id);
                $distance = distance_entre_deux_points($lat1, $long1, $latitude, $longitude);
                if ($distance <= $_GET['rayon']) {
                    $villes[] = $t->term_id;
                }
            }

            $args['tax_query'][] = [
                [
                    'taxonomy' => 'ville_code_postal',
                    'field' => 'term_taxonomy_id',
                    'terms' => $villes,
                    'operator' => 'IN'
                ]
            ];

        } elseif (!empty($_GET['ville'])) {
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

        $louer = [];

        while ($louerQuery->have_posts()) {
            $louerQuery->the_post();

            $defautImg = wp_get_attachment_image_src(IMAGE_DEFAUT, 'nc_louer_list') ?
                wp_get_attachment_image_src(IMAGE_DEFAUT, 'nc_louer_list')[0] : null;

            $louer[] = [
                'id' => get_the_ID(),
                'titre' => get_the_title(),
                'image' => has_post_thumbnail(get_the_ID()) ?
                    get_the_post_thumbnail_url(get_the_ID(), 'nc_louer_list') :
                    $defautImg,
                'type' => get_the_terms(get_the_ID(), 'type_de_bien') ?
                    join(', ', wp_list_pluck(get_the_terms(get_the_ID(), 'type_de_bien'), 'name')) :
                    null,
                'ville' => get_the_terms(get_the_ID(), 'ville_code_postal') ?
                    join(', ', wp_list_pluck(get_the_terms(get_the_ID(), 'ville_code_postal'), 'name')) :
                    null,
                'loyer' => get_field('loyer_charges_comprises') ?? null,
                'surface' => get_field('surface') ?? null,
                'longitude' => get_field('longitude') ?? null,
                'latitude' => get_field('latitude') ?? null,
                'nombre_pieces' => get_the_terms(get_the_ID(), 'nombre_piece') ?
                    join(', ', wp_list_pluck(get_the_terms(get_the_ID(), 'nombre_piece'), 'name')) :
                    null,
                'lien' => removeDomain(get_permalink()),
            ];
        }

        wp_reset_postdata();

        return [
            'louer' => $louer,
            'filtres' => $filtres,
            'max_num_pages' => $louerQuery->max_num_pages,
        ];
    }
}

function distance_entre_deux_points($lat1, $lon1, $lat2, $lon2) {
    // Rayon de la Terre en kilomètres
    $R = 6371;

    // Conversion des degrés en radians
    $lat1 = deg2rad($lat1);
    $lon1 = deg2rad($lon1);
    $lat2 = deg2rad($lat2);
    $lon2 = deg2rad($lon2);

    // Différence de latitudes et de longitudes
    $dLat = $lat2 - $lat1;
    $dLon = $lon2 - $lon1;

    // Formule de la distance Haversine
    $a = sin($dLat/2) * sin($dLat/2) + cos($lat1) * cos($lat2) * sin($dLon/2) * sin($dLon/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    $distance = $R * $c;

    return $distance;
}
