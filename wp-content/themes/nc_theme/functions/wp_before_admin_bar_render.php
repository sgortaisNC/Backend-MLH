<?php

add_action('wp_before_admin_bar_render', function() {
    global $wp_admin_bar;

    $wp_admin_bar->remove_menu('customize');
    $wp_admin_bar->remove_menu('cxssh-main-menu');
    $wp_admin_bar->remove_menu('debug-bar');
    $wp_admin_bar->remove_menu('exactmetrics_frontend_button');
    $wp_admin_bar->remove_menu('itsec_admin_bar_menu');
    $wp_admin_bar->remove_menu('new-content');
    $wp_admin_bar->remove_menu('searchwp');
    $wp_admin_bar->remove_menu('seopress_custom_top_level');
    $wp_admin_bar->remove_menu('updates');
    $wp_admin_bar->remove_menu('elementor_inspector');
    $wp_admin_bar->remove_menu('wp-logo');
    $wp_admin_bar->remove_menu('wphb');
    $wp_admin_bar->remove_menu('archive');
    $wp_admin_bar->remove_menu('tarteaucitronjs');
}, 1000001);
