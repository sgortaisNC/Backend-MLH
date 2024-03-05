<?php

include_once "../wp-load.php";
die();
//$terms = get_terms( array(
//    'taxonomy' => 'ville_code_postal',
//    'hide_empty' => false, // Inclure les termes vides
//) );
//
//// Vérifier si des termes ont été trouvés
//if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
//    // Parcourir les termes et les supprimer un par un
//    foreach ( $terms as $term ) {
//        $deleted = wp_delete_term( $term->term_id, 'ville_code_postal', true ); // Supprimer le terme et tous ses enfants
//        if ( is_wp_error( $deleted ) ) {
//            // Gérer les erreurs
//            echo 'Erreur lors de la suppression du terme : ' . $deleted->get_error_message();
//        } else {
//            // Terme supprimé avec succès
//            echo 'Terme supprimé : ' . $term->name;
//        }
//    }
//} else {
//    // Aucun terme trouvé ou erreur de récupération
//    echo 'Aucun terme à supprimer.';
//}
//die();

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

    $url = 'https://api-adresse.data.gouv.fr/search/?q=03200'.$commune_code_postal[0];

    $response = file_get_contents($url);

    $coordinates = [];
    if ($response !== false) {
        $api_data = json_decode($response, true);
        if (isset($api_data['features'][0]['geometry']['coordinates'])) {
            $coordinates = $api_data['features'][0]['geometry']['coordinates'];
        }
    }

    // Vérifie si la commune existe déjà dans la taxonomie
    $existing_term = get_term_by('name', $code_postal." ".$commune_name, 'ville_code_postal');

    if ($existing_term) {
        // La commune existe déjà, on met à jour ses métadonnées
        update_term_meta($existing_term->term_id, 'code_postal', $commune_code_postal);
        update_field('latitude', $coordinates[1], 'ville_code_postal_' . $existing_term->term_id);
        update_field('longitude', $coordinates[0], 'ville_code_postal_' . $existing_term->term_id);
    } else {
        // La commune n'existe pas encore, on la crée
        $term_args = [
            'description' => '',
            'slug' => sanitize_title($commune_name),
        ];
        $term = wp_insert_term($code_postal." ".$commune_name, 'ville_code_postal', $term_args);
        if (!is_wp_error($term)) {
            // La création de la commune a réussi, on lui ajoute ses métadonnées
            add_term_meta($term['term_id'], 'code_postal', $commune_code_postal, true);

            update_field('latitude', $coordinates[1], 'ville_code_postal_' . $term['term_id']);
            update_field('longitude', $coordinates[0], 'ville_code_postal_' . $term['term_id']);

            echo "Commune $commune_name créée avec succès\n";
        } else {
            var_dump("erreur");
            die();
            // La création de la commune a échoué, on affiche un message d'erreur
            echo "Erreur lors de la création de la commune $commune_name : " . $term->get_error_message() . "\n";
        }
    }
}

foreach ($dataPuyDeDome as $commune) {
    $commune_name = $commune['nom'];
    $commune_code_postal = $commune['codesPostaux'];
    $code_postal = join(', ', $commune_code_postal);


    $url = 'https://api-adresse.data.gouv.fr/search/?q='.$commune_code_postal[0];

    $response = file_get_contents($url);

    $coordinates = [];
    if ($response !== false) {
        $api_data = json_decode($response, true);
        if (isset($api_data['features'][0]['geometry']['coordinates'])) {
            $coordinates = $api_data['features'][0]['geometry']['coordinates'];
        }
    }

    // Vérifie si la commune existe déjà dans la taxonomie
    $existing_term = get_term_by('name', $code_postal." ".$commune_name, 'ville_code_postal');

    if ($existing_term) {
        // La commune existe déjà, on met à jour ses métadonnées
        update_term_meta($existing_term->term_id, 'code_postal', $commune_code_postal);
        update_field('latitude', $coordinates[1], 'ville_code_postal_' . $existing_term->term_id);
        update_field('longitude', $coordinates[0], 'ville_code_postal_' . $existing_term->term_id);
    } else {
        // La commune n'existe pas encore, on la crée
        $term_args = [
            'description' => '',
            'slug' => sanitize_title($commune_name),
        ];
        $term = wp_insert_term($code_postal." ".$commune_name, 'ville_code_postal', $term_args);
        if (!is_wp_error($term)) {
            // La création de la commune a réussi, on lui ajoute ses métadonnées
            add_term_meta($term['term_id'], 'code_postal', $commune_code_postal, true);

            update_field('latitude', $coordinates[1], 'ville_code_postal_' . $term['term_id']);
            update_field('longitude', $coordinates[0], 'ville_code_postal_' . $term['term_id']);

            echo "Commune $commune_name créée avec succès\n";
        } else {
            var_dump("erreur");
            // La création de la commune a échoué, on affiche un message d'erreur
            echo "Erreur lors de la création de la commune $commune_name : " . $term->get_error_message() . "\n";
        }
    }
}

foreach ($dataCher as $commune) {
    $commune_name = $commune['nom'];
    $commune_code_postal = $commune['codesPostaux'];
    $code_postal = join(', ', $commune_code_postal);


    $url = 'https://api-adresse.data.gouv.fr/search/?q='.$commune_code_postal[0];

    $response = file_get_contents($url);

    $coordinates = [];
    if ($response !== false) {
        $api_data = json_decode($response, true);
        if (isset($api_data['features'][0]['geometry']['coordinates'])) {
            $coordinates = $api_data['features'][0]['geometry']['coordinates'];
        }
    }

    // Vérifie si la commune existe déjà dans la taxonomie
    $existing_term = get_term_by('name', $code_postal." ".$commune_name, 'ville_code_postal');

    if ($existing_term) {
        // La commune existe déjà, on met à jour ses métadonnées
        update_term_meta($existing_term->term_id, 'code_postal', $commune_code_postal);
        update_field('latitude', $coordinates[1], 'ville_code_postal_' . $existing_term->term_id);
        update_field('longitude', $coordinates[0], 'ville_code_postal_' . $existing_term->term_id);
    } else {
        // La commune n'existe pas encore, on la crée
        $term_args = [
            'description' => '',
            'slug' => sanitize_title($commune_name),
        ];
        $term = wp_insert_term($code_postal." ".$commune_name, 'ville_code_postal', $term_args);
        if (!is_wp_error($term)) {
            // La création de la commune a réussi, on lui ajoute ses métadonnées
            add_term_meta($term['term_id'], 'code_postal', $commune_code_postal, true);

            update_field('latitude', $coordinates[1], 'ville_code_postal_' . $term['term_id']);
            update_field('longitude', $coordinates[0], 'ville_code_postal_' . $term['term_id']);

            echo "Commune $commune_name créée avec succès\n";
        } else {
            var_dump("erreur");
            // La création de la commune a échoué, on affiche un message d'erreur
            echo "Erreur lors de la création de la commune $commune_name : " . $term->get_error_message() . "\n";
        }
    }
}
