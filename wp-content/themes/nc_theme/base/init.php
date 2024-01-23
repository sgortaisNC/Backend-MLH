<?php

update_option('default_ping_status', 'closed');

add_action('init', function() {
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
});

add_filter('emoji_svg_url', '__return_false');

add_action('pre_ping', function(&$links) {
    $home = get_option('home');

    foreach ( $links as $l => $link ) {
        if ( 0 === strpos( $link, $home ) ) unset($links[$l]);
    }
});

add_filter('xmlrpc_methods', function($methods) {
    unset($methods['pingback.ping']);
    unset($methods['pingback.extensions.getPingbacks']);
    return $methods;
});

add_filter('wp_headers', function($headers) {
    unset( $headers['X-Pingback'] );
    return $headers;
});

remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
remove_action('wp_head', 'pingback_link');

add_action('wp_head', function() {
    echo "<script>(function(H){H.className=H.className.replace(/\bno-js\b/,'js')})(document.documentElement)</script>";
}, 1);

add_filter('comment_form_defaults', function($fields) {
    $fields['comment_notes_after'] = '';
    return $fields;
});

add_filter('editable_roles', function($all_roles) {
    $user = wp_get_current_user();
    $next_level = 'level_' . ($user->user_level + 1);

    foreach ( $all_roles as $name => $role ) {
        if ( isset($role['capabilities'][$next_level]) ) {
            unset($all_roles[$name]);
        }
    }

    return $all_roles;
});
