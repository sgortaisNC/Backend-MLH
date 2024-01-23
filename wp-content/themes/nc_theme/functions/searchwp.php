<?php

// Configuration du module SearchWP Live Ajax Search

/*
add_filter('searchwp_live_search_configs', function($configs) {
    $configs['default']['input']['delay'] = 200;
    $configs['default']['input']['min_chars'] = 1;

    return $configs;
});

add_filter('searchwp_live_search_base_styles', '__return_false');
*/

// Suggestions de contenu : Suppression des types de contenu sans détails

/*
add_action('parse_query', function($query) {
    if ( !empty($_POST)
        && !empty($_POST['action'])
        && $_POST['action'] === 'searchwp_live_search'
        && !empty($query)
        && is_object($query)
        && !empty($query->query)
        && is_array($query->query)
        && !empty($query->query['post_type'])
        && is_array($query->query['post_type'])
        && in_array('SLUG_DU_TYPE_DE_CONTENU_A_SUPPRIMER', $query->query['post_type']) ) {
        $query->set('post_type', array_filter($query->query['post_type'], function($post_type) {
            return $post_type !== 'SLUG_DU_TYPE_DE_CONTENU_A_SUPPRIMER';
        }));
    }
});
*/
