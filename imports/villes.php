<?php

include_once "../wp-load.php";
die();
//Allier
$urlAllier = 'https://geo.api.gouv.fr/departements/03/communes';

$jsonAllier = file_get_contents($urlAllier);

$dataAllier = json_decode($jsonAllier, true);

//Puy de dome
$urlPuyDeDome = 'https://geo.api.gouv.fr/departements/63/communes';

$jsonPuyDeDome = file_get_contents($urlPuyDeDome);

$dataPuyDeDome = json_decode($jsonPuyDeDome, true);

//Cher
$urlCher = 'https://geo.api.gouv.fr/departements/18/communes';

$jsonCher = file_get_contents($urlCher);

$dataCher = json_decode($jsonCher, true);

foreach ($dataAllier as $commune) {
    $commune_name = $commune['nom'];
    $commune_code_postal = $commune['codesPostaux'];
    $code_postal = join(', ', $commune_code_postal);

    // Vérifie si la commune existe déjà dans la taxonomie
    $existing_term = get_term_by('name', $commune_name, 'ville_code_postal');

    if ($existing_term) {
        // La commune existe déjà, on met à jour ses métadonnées
        update_term_meta($existing_term->term_id, 'code_postal', $commune_code_postal);
    } else {
        // La commune n'existe pas encore, on la crée
        $term_args = array(
            'description' => '',
            'slug' => sanitize_title($commune_name),
        );
        $term = wp_insert_term($code_postal." ".$commune_name, 'ville_code_postal', $term_args);
        if (!is_wp_error($term)) {
            // La création de la commune a réussi, on lui ajoute ses métadonnées
            add_term_meta($term['term_id'], 'code_postal', $commune_code_postal, true);
        } else {
            // La création de la commune a échoué, on affiche un message d'erreur
            echo "Erreur lors de la création de la commune $commune_name : " . $term->get_error_message() . "\n";
        }
    }
}

foreach ($dataPuyDeDome as $commune) {
    $commune_name = $commune['nom'];
    $commune_code_postal = $commune['codesPostaux'];
    $code_postal = join(', ', $commune_code_postal);

    // Vérifie si la commune existe déjà dans la taxonomie
    $existing_term = get_term_by('name', $commune_name, 'ville_code_postal');

    if ($existing_term) {
        // La commune existe déjà, on met à jour ses métadonnées
        update_term_meta($existing_term->term_id, 'code_postal', $commune_code_postal);
    } else {
        // La commune n'existe pas encore, on la crée
        $term_args = array(
            'description' => '',
            'slug' => sanitize_title($commune_name),
        );
        $term = wp_insert_term($code_postal." ".$commune_name, 'ville_code_postal', $term_args);
        if (!is_wp_error($term)) {
            // La création de la commune a réussi, on lui ajoute ses métadonnées
            add_term_meta($term['term_id'], 'code_postal', $commune_code_postal, true);
        } else {
            // La création de la commune a échoué, on affiche un message d'erreur
            echo "Erreur lors de la création de la commune $commune_name : " . $term->get_error_message() . "\n";
        }
    }
}

foreach ($dataCher as $commune) {
    $commune_name = $commune['nom'];
    $commune_code_postal = $commune['codesPostaux'];
    $code_postal = join(', ', $commune_code_postal);

    // Vérifie si la commune existe déjà dans la taxonomie
    $existing_term = get_term_by('name', $commune_name, 'ville_code_postal');

    if ($existing_term) {
        // La commune existe déjà, on met à jour ses métadonnées
        update_term_meta($existing_term->term_id, 'code_postal', $commune_code_postal);
    } else {
        // La commune n'existe pas encore, on la crée
        $term_args = array(
            'description' => '',
            'slug' => sanitize_title($commune_name),
        );
        $term = wp_insert_term($code_postal." ".$commune_name, 'ville_code_postal', $term_args);
        if (!is_wp_error($term)) {
            // La création de la commune a réussi, on lui ajoute ses métadonnées
            add_term_meta($term['term_id'], 'code_postal', $commune_code_postal, true);
        } else {
            // La création de la commune a échoué, on affiche un message d'erreur
            echo "Erreur lors de la création de la commune $commune_name : " . $term->get_error_message() . "\n";
        }
    }
}
