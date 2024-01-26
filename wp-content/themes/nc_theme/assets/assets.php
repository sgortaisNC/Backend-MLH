<?php

add_action('wp_enqueue_scripts', function() {
    # jQuery
    # wp_deregister_script('jquery'); // (découpe)
    # wp_enqueue_script('jquery', get_stylesheet_directory_uri() . '/assets/js/vendor/jquery-3.3.1.min.js'); // (découpe)

    # Bootstrap
    # wp_enqueue_script('bootstrap', get_stylesheet_directory_uri() . '/assets/js/vendor/bootstrap.min.js'); // (découpe)
    # wp_enqueue_style('bootstrap', get_stylesheet_directory_uri() . '/assets/css/vendor/bootstrap.min.css'); // (découpe)

    # Font Awesome 5.10.2
    wp_enqueue_style('fontawesome', get_stylesheet_directory_uri() . '/assets/css/vendor/fontawesome.min.css');

    # nice select 2
     wp_enqueue_style('niceselect2', 'https://cdn.jsdelivr.net/npm/nice-select2@2.1.0/dist/css/nice-select2.min.css');
     wp_enqueue_script('niceselect2', 'https://cdn.jsdelivr.net/npm/nice-select2@2.1.0/dist/js/nice-select2.min.js');
     wp_enqueue_script('nc-select', get_stylesheet_directory_uri() . '/assets/js/select.js', [], '1.0', true);

    # Leaflet 1.5.1
     wp_enqueue_style('leaflet', get_stylesheet_directory_uri() . '/assets/css/vendor/leaflet.min.css');
     wp_enqueue_script('leaflet', get_stylesheet_directory_uri() . '/assets/js/vendor/leaflet.min.js');

    # tarteaucitron 1.2
    # wp_enqueue_script('tarteaucitron', get_stylesheet_directory_uri() . '/assets/js/vendor/tarteaucitron/tarteaucitron.js');

    # Net.Com
    wp_enqueue_style('netcom', get_stylesheet_directory_uri() . '/assets/css/main.css');
    wp_enqueue_script('netcom', get_stylesheet_directory_uri() . '/assets/js/ajax/alerte.js',[],'1.0',true);
    wp_enqueue_script('netcom', get_stylesheet_directory_uri() . '/assets/js/app.js');
});

add_image_size('nc_header', 100, 100, true);
add_image_size('nc_footer', 100, 100, true);
add_image_size('nc_home', 100, 100, true);
add_image_size('nc_post_list', 100, 100, true);
add_image_size('nc_louer_list', 100, 100, true);
add_image_size('nc_post_single', 100, 100, true);
add_image_size('nc_page_single', 100, 100, true);
add_image_size('nc_louer_single', 100, 100, true);
add_image_size('nc_gutenberg', 100, 100, true);
add_image_size('nc_elementor_quote', 100, 100, true);
add_image_size('nc_elementor_featured', 100, 100, true);
add_image_size('nc_elementor_carousel', 100, 100, true);

add_filter('image_size_names_choose', function($sizes) {
    return array_merge($sizes, [
        'nc_header' => "Header",
        'nc_footer' => "Footer",
        'nc_home' => "Page d'accueil",
        'nc_post_list' => "Liste des actualités",
        'nc_post_single' => "Détail d'une actualité",
        'nc_page_single' => "Page standard",
        'nc_gutenberg' => "Gutenberg",
        'nc_elementor_quote' => "Citation",
        'nc_elementor_featured' => "Mise en lumière",
        'nc_elementor_carousel' => "Carrousel d'images",
    ]);
});
