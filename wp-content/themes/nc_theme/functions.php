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

## Chargement des blocs Gutenberg
foreach ( glob(get_stylesheet_directory() . "/blocks/*", GLOB_ONLYDIR) as $block ) {
    load('blocks/' . basename($block) . '/');
}

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
