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

add_filter('rest_url', 'wptips_home_url_as_api_url');
function wptips_home_url_as_api_url($url) {
    $url = str_replace(home_url(),site_url() , $url);
    return $url;
}

function removeDomain($str): string
{
    return str_replace(home_url(),'',$str);
}

require_once "classes/Actualite.php";
require_once "classes/OffreEmploi.php";
require_once "classes/Options.php";
require_once "classes/Location.php";
require_once "classes/Sidebar.php";
require_once "classes/Formulaire.php";
require_once "classes/Page.php";
require_once "classes/Search.php";
require_once "classes/UserApi.php";

add_action( 'rest_api_init', function() {

    //Page
    register_rest_route( 'montlucon/v1', '/page/(?P<slug>[\w-]+)', [
        'methods'  => 'GET',
        'callback' => [new Page(), 'getOneBySlug']
    ] );

    //Actualités
    register_rest_route( 'montlucon/v1', '/actualite/(?P<slug>[\w-]+)', [
        'methods'  => 'GET',
        'callback' => [new Actualite(), 'single']
    ] );

    register_rest_route( 'montlucon/v1', '/actualites', [
        'methods'  => 'GET',
        'callback' => [new Actualite(), 'list'],
    ] );


    // Offres d'emploi
    register_rest_route( 'montlucon/v1', '/offre/(?P<slug>[\w-]+)', [
        'methods'  => 'GET',
        'callback' => [new OffreEmploi(), 'single'],
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
    register_rest_route( 'montlucon/v1', '/biens-louer', [
        'methods'  => 'GET',
        'callback' => [new Location(), 'list'],
    ] );

    register_rest_route( 'montlucon/v1', '/bien-louer/(?P<slug>[a-zA-Z0-9-%]+)', [
        'methods'  => 'GET',
        'callback' => [new Location(), 'single'],
    ] );

    //Sidebar
    register_rest_route( 'montlucon/v1', '/sidebar', [
        'methods'  => 'GET',
        'callback' => [new Sidebar(), 'sidebar'],
        'args'     => [
            'type' => [
                'validate_callback' => function( $param, $request, $key ) {
                    return is_numeric( $param );
                },
            ],
        ],
    ] );

    // Formulaires
    register_rest_route( 'montlucon/v1', '/formulaires', [
        'methods' => 'GET',
        'callback' => [new Formulaire(), 'liste_formulaires'],
    ]);

    register_rest_route( 'montlucon/v1', '/formulaire', [
        'methods' => 'GET',
        'callback' => [new Formulaire(), 'formulaire_by_id'],
        'args' => [
            'id' => [
                'validate_callback' => function( $param, $request, $key ) {
                    return is_numeric( $param );
                },
            ],
        ],
    ]);

    register_rest_route( 'montlucon/v1', '/submit-form', [
        'methods' => 'POST',
        'callback' => [new Formulaire(), 'submit_form'],
        'permission_callback' => '__return_true',
    ]);

    // Page résultat de recherche
    register_rest_route( 'montlucon/v1', "/recherche/(?P<s>[\p{L}\p{M}'\-\d\W]+)", [
        'methods'  => 'GET',
        'callback' => [new Search(), 'results'],
        'args' => [
            's' => [
                'required' => true,
                'validate_callback' => function($param, $request, $key) {
                    return is_string($param) && strlen($param) > 0;
                }
            ],
        ]
    ] );

    register_rest_route('montlucon/v1','/connect',[
        'methods' => 'POST',
        'callback' => [new UserApi(), 'custom_auth_callback'],
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('montlucon/v1','/sitemap',[
        'methods' => 'GET',
        'callback' => [new Options(), 'sitemapXML'],
        'permission_callback' => '__return_true',
    ]);
} );
