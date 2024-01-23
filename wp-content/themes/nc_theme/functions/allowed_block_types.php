<?php

add_filter('allowed_block_types', function() {
    $blocks = array_keys(WP_Block_Type_Registry::get_instance()->get_all_registered());
    $matches = preg_grep('/^nc\/(.*)/', $blocks);

    $allowed_block_types = [
        'core/buttons',
        'core/columns',
        'core/gallery',
        'core/heading',
        'core/image',
        'core/list',
        'core/list-item',
        'core/paragraph',
        'core/quote',
        'core/shortcode',
        'core/table',
    ];

    return array_merge($allowed_block_types, $matches);
});
