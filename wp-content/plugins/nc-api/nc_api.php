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


require_once "classes/Actualite.php";
require_once "classes/OffreEmploi.php";
require_once "classes/Options.php";
require_once "classes/Location.php";

add_action( 'rest_api_init', function() {

    //Actalités
    register_rest_route( 'montlucon/v1', '/actualites', [
        'methods'  => 'GET',
        'callback' => [new Actualite(), 'list'],
    ] );

    register_rest_route( 'montlucon/v1', '/actualite', [
        'methods'  => 'GET',
        'callback' => [new Actualite(), 'single'],
        'args'     => [
            'id' => [
                'validate_callback' => function( $param, $request, $key ) {
                    return is_numeric( $param );
                },
            ],
        ],
    ] );

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

    register_rest_route( 'montlucon/v1', '/options/footer', [
        'methods'  => 'GET',
        'callback' => [new Options(), 'footer'],
    ] );

    register_rest_route( 'montlucon/v1', '/options/homepage', [
        'methods'  => 'GET',
        'callback' => [new Options(), 'homepage'],
    ] );

    // Locations
    register_rest_route( 'montlucon/v1', '/locations', [
        'methods'  => 'GET',
        'callback' => [new Location(), 'list'],
        'args'     => [
            'type' => [
                'validate_callback' => function( $param, $request, $key ) {
                    return is_numeric( $param );
                },
            ],
            'nombre' => [
                'validate_callback' => function( $param, $request, $key ) {
                    return is_numeric( $param );
                },
            ],
            'ville' => [
                'validate_callback' => function( $param, $request, $key ) {
                    return is_numeric( $param );
                },
            ],
        ],
    ] );

    register_rest_route( 'montlucon/v1', '/location', [
        'methods'  => 'GET',
        'callback' => [new Location(), 'single'],
    ] );
} );
