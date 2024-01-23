<?php

add_action('save_post', function($post_id) {
    if ( class_exists('Hummingbird\Core\Utils') ) {
        Hummingbird\Core\Utils::get_module('page_cache')->clear_cache_action($post_id);

        $post_type = get_post_type($post_id);

        if ( defined(strtoupper($post_type) . "_LIST") ) {
            Hummingbird\Core\Utils::get_module('page_cache')->clear_cache_action(
                constant(strtoupper($post_type) . "_LIST")
            );
        }

        if ( defined("HOME") ) {
            Hummingbird\Core\Utils::get_module('page_cache')->clear_cache_action(HOME);
        }
    }
}, 99, 1);

add_action('redux/options/REDUX/saved', function() {
    if ( class_exists('Hummingbird\Core\Utils') ) {
        Hummingbird\Core\Utils::get_module('page_cache')->clear_cache();
    }
});
