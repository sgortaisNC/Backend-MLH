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



add_action( 'rest_api_init', 'hello_world' );

function hello_world()
{
    register_rest_route( 'montlucon/v1', '/hello', array(
        'methods' => 'GET',
        'callback' => 'nc_hello_world',
    ) );
}

function nc_hello_world()
{

    //get all acf options
    $a = get_fields('option');

    return $a;
}
