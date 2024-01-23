<?php

add_action('init', function() {
    add_rewrite_rule('^actualites/([^/]+)/?', 'index.php?name=$matches[1]&post_type=post', 'top');
});

add_filter('post_link', function($permalink, $post) {
    if ( 'post' === $post->post_type ) {
        $permalink = str_replace($post->post_name, "actualites/{$post->post_name}", $permalink);
    }

    return $permalink;
}, 10, 2);
