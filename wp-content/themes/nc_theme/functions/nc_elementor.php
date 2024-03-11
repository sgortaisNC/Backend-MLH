<?php

// Ajout des styles

add_action('elementor/editor/before_enqueue_styles', 'nc_elementor_styles');
add_action('elementor/editor/after_enqueue_styles', 'nc_elementor_styles');

function nc_elementor_styles() {
    wp_enqueue_style('nc-elementor', get_stylesheet_directory_uri() . '/assets/css/elementor.css');
}

// Création des nouvelles catégories

add_action('elementor/elements/categories_registered', function($elements_manager) {
    $elements_manager->add_category('nc-text', ['title' => "Texte"]);
    $elements_manager->add_category('nc-media', ['title' => "Média"]);
    $elements_manager->add_category('nc-advanced', ['title' => "Avancé"]);
    $elements_manager->add_category('nc-layout', ['title' => "Mise en page"]);
    $elements_manager->add_category('nc-embed', ['title' => "Intégration"]);
});

// Suppression des composants et modification des catégories

add_action('elementor/widgets/register', function($widgets_manager) {
    // Suppression des composants

    $whitelist = [
        'common', // /!\ Bloc obligatoire /!\
        'heading', // Titre / Sous-titre
        'text-editor', // Éditeur de texte
        'image', // Image
        'spacer', // Espaceur
        'inner-section', // Section interne
        'shortcode', // Code court
        'google_maps', // Google Maps
        'html', // HTML
    ];

    $widgets = $widgets_manager->get_widget_types();

    if ( !empty($widgets) ) {
        foreach ( array_keys($widgets) as $widget ) {
            if ( !in_array($widget, $whitelist) ) {
                $widgets_manager->unregister_widget_type($widget);
            }
        }
    }

    // Modification des catégories

    $widgets_manager->get_widget_types('heading')->set_config('categories', ['nc-text']);
    $widgets_manager->get_widget_types('text-editor')->set_config('categories', ['nc-text']);
    $widgets_manager->get_widget_types('image')->set_config('categories', ['nc-media']);
    $widgets_manager->get_widget_types('spacer')->set_config('categories', ['nc-layout']);
    $widgets_manager->get_widget_types('inner-section')->set_config('categories', ['nc-layout']);
    $widgets_manager->get_widget_types('shortcode')->set_config('categories', ['nc-embed']);
    $widgets_manager->get_widget_types('google_maps')->set_config('categories', ['nc-embed']);
    $widgets_manager->get_widget_types('html')->set_config('categories', ['nc-embed']);
}, 21);

// Suppression des menus

add_action('admin_menu', function() {
    if ( !User::isAdministrator() ) {
        remove_menu_page('elementor');
        remove_menu_page('edit.php?post_type=elementor_library');
    }
}, 21);
