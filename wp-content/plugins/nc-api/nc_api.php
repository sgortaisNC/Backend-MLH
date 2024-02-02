<?php
/**
 * NC Api
 *
 * Plugin Name: NC Api
 * Plugin URI:  https://www.net-com.fr/
 * Description: Création de l'api du projet
 * Version:     0.1
 * Author:      Bruno Etcheverry [Net.Com]
 */


require_once "classes/OffreEmploi.php";
require_once "classes/Options.php";

add_action( 'rest_api_init', function() {

    // Offres d'emploi
    register_rest_route( 'montlucon/v1', '/offre', [
        'methods'  => 'GET',
        'callback' => [new OffreEmploi(), 'single'],
        'args'     => [
            'id' => [
                'validate_callback' => function( $param, $request, $key ) {
                    return is_numeric( $param );
                },
            ],
        ],
    ] );

    register_rest_route( 'montlucon/v1', '/offres', [
        'methods'  => 'GET',
        'callback' => [new OffreEmploi(), 'list'],
        'args'     => [
            'contrat' => [
                'validate_callback' => function( $param, $request, $key ) {
                    return is_numeric( $param );
                },
            ],
            'metier' => [
                'validate_callback' => function( $param, $request, $key ) {
                    return is_numeric( $param );
                },
            ],
        ],
    ] );


    // Options de thème
    register_rest_route( 'montlucon/v1', '/options', [
        'methods'  => 'GET',
        'callback' => [new Options(), 'getAll'],
    ] );

    register_rest_route( 'montlucon/v1', '/options/header', [
        'methods'  => 'GET',
        'callback' => [new Options(), 'header'],
    ] );
} );
