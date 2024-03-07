<?php
## Chargement de l'ensemble des fichiers contenus dans les dossiers du thème

## Fichiers liés au thème
load('base/');

## Fichiers de configuration
load('config/');

## Fonctions
load('functions/');

## Classes
load('classes/');

## Contrôleurs
load('src/controllers/');

## Widgets
load('src/widgets/');

## Widgets Elementor

//add_action('init', function() {
//    load('src/widgets/elementor/');
//});

## Shortcodes
load('src/shortcodes/');

## Chargement des styles/scripts
## Ajout des différentes tailles d'images (add_image_size())
include 'assets/assets.php';

function load($dir) {
    $dir = get_stylesheet_directory() . '/' . $dir;
    $root = scandir($dir);

    foreach ( $root as $value ) {
        if ( substr($value, -4) == '.php' ) include($dir . $value);
    }
}

function tt3child_register_acf_blocks() {
    /**
     * We register our block's with WordPress's handy
     * register_block_type();
     *
     * @link https://developer.wordpress.org/reference/functions/register_block_type/
     */
    register_block_type( __DIR__ . '/blocks/avant' );
    register_block_type( __DIR__ . '/blocks/chiffres' );
}
// Here we call our register_acf_block() function on init.
add_action( 'init', 'tt3child_register_acf_blocks' );
