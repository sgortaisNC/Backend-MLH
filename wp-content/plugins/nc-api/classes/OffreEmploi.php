<?php

/**
 * Class OffreEmploi
 */
class OffreEmploi
{
    public function single($request) : array
    {
        $slug = $request['slug'];

        $offreBySlug = get_page_by_path( $slug, OBJECT, 'offre_emploi' );

        $offre = [];
        if ( $offreBySlug ) {
            $id = $offreBySlug->ID;
            $offre[] = [
                'id' => $id,
                'titre' => get_the_title($id),
                'description' => get_the_content(null, false, $id),
                'chapo' => has_excerpt($id) ? get_the_excerpt($id) : null,
                'image' => has_post_thumbnail($id) ?
                    get_the_post_thumbnail_url($id, 'nc_offre_single') :
                    wp_get_attachment_image_src(IMAGE_DEFAUT, 'nc_offre_single')[0],
                'date' => get_the_date('d M Y', $id),
                'reference' => get_field('reference_offre', $id) ?? null,
                'contrat' => get_the_terms( $id, 'type_de_contrat') ?
                    join(', ', wp_list_pluck(get_the_terms( $id, 'type_de_contrat'), 'name')) : null,
                'metier' => get_the_terms( $id, 'metier') ?
                    join(', ', wp_list_pluck(get_the_terms( $id, 'metier'), 'name')) : null,
                'pdf' => get_field('pdf_presentation', $id) ?? null,
                'lien' => removeDomain(get_permalink($id)),
            ];
        }

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
                'label' => $value,
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
                'label' => $value,
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
                'lien' => removeDomain(get_permalink()),
            ];
        }

        return [
            'emplois' => $emplois,
            'filtres' => $filtres,
            'max_num_pages' => $emploiQuery->max_num_pages,
        ];

    }


}
