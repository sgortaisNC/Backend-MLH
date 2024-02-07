<?php

/**
 * Class OffreEmploi
 */
class OffreEmploi
{
    public function single() : array
    {
        $offre = [];
        $offre['id'] = $_GET['id'] ?? null;
        if($offre['id'] == null) {
            return [];
        }

        $offre = [
            'id' => $offre['id'],
            'titre' => get_the_title($offre['id']),
            'description' => get_the_content(null, false, $offre['id']),
            'chapo' => has_excerpt($offre['id']) ? get_the_excerpt($offre['id']) : null,
            'image' => has_post_thumbnail($offre['id']) ?
                get_the_post_thumbnail_url($offre['id'], 'nc_post_list') :
                wp_get_attachment_image_src(IMAGE_DEFAUT, 'nc_post_list')[0],
            'date' => get_the_date('d M Y',  $offre['id']),
            'reference' => get_field('reference_offre', $offre['id']) ?? null,
            'contrat' => get_the_terms( $offre['id'], 'type_de_contrat') ?
                join(', ', wp_list_pluck(get_the_terms( $offre['id'], 'type_de_contrat'), 'name')) : null,
            'metier' => get_the_terms( $offre['id'], 'metier') ?
                join(', ', wp_list_pluck(get_the_terms( $offre['id'], 'metier'), 'name')) : null,
            'pdf' => get_field('pdf_presentation', $offre['id']) ?? null,
            'lien' => get_permalink($offre['id']),
        ];

        return $offre;

    }

    public function list() : array
    {
        $filtres = null;

        $filtresTerm['contrats'] = get_terms([
            'taxonomy' => 'type_de_contrat',
            'fields' => "id=>name",
            'hide_empty' => false,
            'parent' => 0,
        ]);

        foreach ($filtresTerm['contrats'] as $key => $value) {
            $filtres['contrats'][] = [
                'value' => $key,
                'name' => $value,
            ];
        }

        $filtresTerm['metiers'] = get_terms([
            'taxonomy' => 'metier',
            'fields' => "id=>name",
            'hide_empty' => false,
            'parent' => 0,
        ]);

        foreach ($filtresTerm['metiers'] as $key => $value) {
            $filtres['metiers'][] = [
                'value' => $key,
                'name' => $value,
            ];
        }

        $args = [
            'post_type' => "offre_emploi",
            'post_status' => 'publish',
            'posts_per_page' => 12,
            'orderby' => 'date',
            'paged' => ( !empty($_GET['pg']) ? $_GET['pg'] : 1 ),
        ];

        if(!empty($_GET['contrat'])){
            $args['tax_query'][] = [
                [
                    'taxonomy' => 'type_de_contrat',
                    'field' => 'term_taxonomy_id',
                    'terms' => $_GET['contrat'],
                    'operator' => 'IN'
                ]
            ];
        }

        if(!empty($_GET['metier'])){
            $args['tax_query'][] = [
                [
                    'taxonomy' => 'metier',
                    'field' => 'term_taxonomy_id',
                    'terms' => $_GET['metier'],
                    'operator' => 'IN'
                ]
            ];
        }

        $emploiQuery = new WP_Query($args);

        $emplois = [];

        while ($emploiQuery->have_posts()) {
            $emploiQuery->the_post();

            $emplois[] = [
                'id' => get_the_ID(),
                'titre' => get_the_title(),
                'date' => get_the_date(),
                'reference' => get_field('reference_offre') ?? null,
                'contrat' => get_the_terms(get_the_ID(), 'type_de_contrat') ?
                    join(', ', wp_list_pluck(get_the_terms(get_the_ID(), 'type_de_contrat'), 'name')) : null,
                'metier' => get_the_terms(get_the_ID(), 'metier') ?
                    join(', ', wp_list_pluck(get_the_terms(get_the_ID(), 'metier'), 'name')) : null,
                'lien' => get_permalink(),
            ];
        }

        return [
            'emplois' => $emplois,
            'filtres' => $filtres,
            'max_num_pages' => $emploiQuery->max_num_pages,
        ];

    }


}