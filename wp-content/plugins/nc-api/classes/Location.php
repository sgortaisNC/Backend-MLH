<?php

/**
 * Class Location
 */
class Location
{
    public function single(): array
    {
        $location = [];
        $location['id'] = $_GET['id'] ?? null;
        if($location['id'] == null) {
            return [];
        }

        $location['titre'] = get_the_title($location['id']);

        $location['description'] = get_the_content(null, false, $location['id']);

        $location['chapo'] = has_excerpt($location['id']) ? get_the_excerpt($location['id']) : null;

        $location['image'] = has_post_thumbnail($location['id']) ?
            get_the_post_thumbnail_url($location['id'], 'nc_louer_single') :
            wp_get_attachment_image_src(IMAGE_DEFAUT, 'nc_louer_single')[0];

        $images = get_field('images', $location['id']) ?? null;
        $location['images'] = [];

        if($images){
            foreach ($images as $image) {
                $id = $image['image']['ID'];
                $location['images'][] = [
                    'id' => $id,
                    'url' => wp_get_attachment_image_src($id, 'nc_louer_single')[0],
                    'alt' => $image['image']['alt'],
                ];
            }
        }

        $location['date'] = get_the_date('d M Y',  $location['id']);

        $location['loyer'] = get_field('loyer_charges_comprises', $location['id']) ??
            get_field('loyer', $location['id']) ?? null;

        $location['surface'] = get_field('surface', $location['id']) ?? null;

        $location['type'] = get_the_terms($location['id'], 'type_de_bien') ?
            join(', ', wp_list_pluck(get_the_terms($location['id'], 'type_de_bien'), 'name')) : null;

        $location['nombre_pieces'] = get_the_terms($location['id'], 'nombre_piece') ?
            join(', ', wp_list_pluck(get_the_terms($location['id'], 'nombre_piece'), 'name')) : null;

        $location['ville'] = get_the_terms($location['id'], 'ville_code_postal') ?
            join(', ', wp_list_pluck(get_the_terms($location['id'], 'ville_code_postal'), 'name')) : null;

        $location['markers'] = [
            'latitude' => get_field('latitude', $location['id']) ?? null,
            'longitude' => get_field('longitude', $location['id']) ?? null,
        ];

        $location['lien'] = get_permalink($location['id']);

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
                'name' => $value,
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
                'name' => $value,
            ];
        }

        $filtresTerm['villes'] = get_terms([
            'taxonomy' => 'ville_code_postal',
            'fields' => "id=>name",
            'hide_empty' => false,
            'parent' => 0,
        ]);

        foreach ($filtresTerm['villes'] as $key => $value) {

          $latitude = get_field('latitude', 'ville_code_postal_'.$key);
            $longitude = get_field('longitude', 'ville_code_postal_'.$key);

            $filtres['villes'][] = [
                'value' => $key,
                'name' => $value,
                'latitude' => $latitude,
                'longitude' => $longitude,
            ];
        }


        $args = [
            'post_type' => "bien_louer",
            'post_status' => 'publish',
            'posts_per_page' => 12,
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

        if(!empty($_GET['nombre'])){
            $args['tax_query'][] = [
                [
                    'taxonomy' => 'nombre_piece',
                    'field' => 'term_taxonomy_id',
                    'terms' => $_GET['nombre'],
                    'operator' => 'IN'
                ]
            ];
        }

//        if(!empty($_GET['ville'])){
//            $args['tax_query'][] = [
//                [
//                    'taxonomy' => 'ville_code_postal',
//                    'field' => 'term_taxonomy_id',
//                    'terms' => $_GET['ville'],
//                    'operator' => 'IN'
//                ]
//            ];
//        }


        $louerQuery = new WP_Query($args);

        $louer = [];

        while ($louerQuery->have_posts()) {
            $louerQuery->the_post();

            if(!empty($_GET['rayon']) && !empty($_GET['ville'])){
                $ville = get_term($_GET['ville'], 'ville_code_postal');
                $latitude = get_field('latitude', $ville);
                $longitude = get_field('longitude', $ville);
                $coordonnees = [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                ];

                if($coordonnees['latitude'] && $coordonnees['longitude'] && get_field('latitude') && get_field('longitude')) {
                    $distance = Location::distance_entre_deux_points(
                        $coordonnees['latitude'], $coordonnees['longitude'],
                        get_field('latitude'), get_field('longitude'), $_GET['rayon']);
                }
            }

            $louer[] = [
                'id' => get_the_ID(),
                'distance' => $distance ?? null,
                'titre' => get_the_title(),
                'image' => has_post_thumbnail(get_the_ID()) ?
                    get_the_post_thumbnail_url(get_the_ID(), 'nc_louer_list') :
                    wp_get_attachment_image_src(IMAGE_DEFAUT, 'nc_louer_list')[0],
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
                'lien' => get_permalink(),
            ];
        }

        wp_reset_postdata();

        return [
            'louer' => $louer,
            'filtres' => $filtres,
            'max_num_pages' => $louerQuery->max_num_pages,
        ];
    }

    function distance_entre_deux_points($lat1, $lon1, $lat2, $lon2, $rayon) {
        $R = 6371;

        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;

        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $R * $c;

        if($distance > $rayon){
            return null;
        }
        return $distance;
    }

}