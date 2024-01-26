<?php

function nc_emploi_list() {
    $filtres = null;

    $filtres['contrats'] = get_terms([
        'taxonomy' => 'type_de_contrat',
        'fields' => "id=>name",
        'hide_empty' => false,
        'parent' => 0,
    ]);

    $filtres['metiers'] = get_terms([
        'taxonomy' => 'metier',
        'fields' => "id=>name",
        'hide_empty' => false,
        'parent' => 0,
    ]);

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

        $emplois[get_the_ID()] = [
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

    wp_reset_postdata();

    render('emploi/list', [
        "emplois" => $emplois,
        "filtres" => $filtres,
        'params' => [
            'contrat' => (!empty($_GET['contrat'])) ? $_GET['contrat'] : null,
            'metier' => (!empty($_GET['metier'])) ? $_GET['metier'] : null,
        ],
        'max_num_pages' => $emploiQuery->max_num_pages,
    ]);
}

function nc_emploi_single() {

    $emploi = [
        'date' => get_the_date('d M Y', get_the_ID()),
        'reference' => get_field('reference_offre') ?? null,
        'contrat' => get_the_terms(get_the_ID(), 'type_de_contrat') ?
            join(', ', wp_list_pluck(get_the_terms(get_the_ID(), 'type_de_contrat'), 'name')) : null,
        'metier' => get_the_terms(get_the_ID(), 'metier') ?
            join(', ', wp_list_pluck(get_the_terms(get_the_ID(), 'metier'), 'name')) : null,
        'pdf' => get_field('pdf_presentation') ?? null,
    ];

    render('emploi/single', [
        'emploi' => $emploi,
    ]);
}
