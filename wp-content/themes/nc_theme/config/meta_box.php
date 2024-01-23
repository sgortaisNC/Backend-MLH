<?php

add_action('admin_menu', function() {
    remove_meta_box('postcustom', 'page', 'normal');
    remove_meta_box('postcustom', 'post', 'normal');
    remove_meta_box('pageparentdiv', 'post', 'side');
});

//add_action('admin_init', function() {
//    $user = get_current_user_id();
//
//    $meta_key = "metaboxhidden_post";
//
//    if ( isset($_GET['post_type']) ) {
//        $meta_key = "metaboxhidden_{$_GET['post_type']}";
//    } elseif ( isset($_GET['post']) ) {
//        $meta_key = "metaboxhidden_" . get_post_type($_GET['post']);
//    }
//
//    $unchecked = get_user_meta($user, $meta_key, true);
//    $key = array_search('postexcerpt', $unchecked);
//
//    if ( false !== $key ) {
//        unset($unchecked[$key]);
//        update_user_meta($user, $meta_key, $unchecked);
//    }
//}, 10);
