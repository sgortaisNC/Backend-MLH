<?php

if ( !class_exists('Redux') ) return;

add_action('redux/loaded', 'remove_demo');

if ( !function_exists('remove_demo') && class_exists('ReduxFrameworkPlugin') ) {
    function remove_demo() {
        remove_filter('plugin_row_meta', [ReduxFrameworkPlugin::instance(), 'plugin_metalinks'], null, 2);
        remove_action('admin_notices', [ReduxFrameworkPlugin::instance(), 'admin_notices']);
    }
}

$opt_name = "REDUX";

Redux::set_args($opt_name, [
    'show_options_object'  => false,
    'opt_name'             => $opt_name,
    'display_name'         => "Net.Com Configuration",
    'display_version'      => false,
    'menu_type'            => "menu",
    'allow_sub_menu'       => true,
    'menu_title'           => "Net.Com Configuration",
    'page_title'           => "Net.Com Configuration",
    'google_api_key'       => "",
    'google_update_weekly' => false,
    'async_typography'     => true,
    //'disable_google_fonts_link' => true,
    'admin_bar'            => true,
    'admin_bar_icon'       => "dashicons-admin-tools",
    'admin_bar_priority'   => 50,
    'global_variable'      => "",
    'dev_mode'             => false,
    'update_notice'        => false,
    'customizer'           => true,
    //'open_expanded'     => true,
    //'disable_save_warn' => true,
    'page_priority'        => null,
    'page_parent'          => "themes.php",
    'page_permissions'     => "edit_theme_options",
    'menu_icon'            => "",
    'last_tab'             => "",
    'page_icon'            => "icon-themes",
    'page_slug'            => "",
    'save_defaults'        => true,
    'default_show'         => false,
    'default_mark'         => "",
    'show_import_export'   => false,
    'transient_time'       => 60 * MINUTE_IN_SECONDS,
    'output'               => true,
    'output_tag'           => true,
    'footer_credit'        => "",
    'database'             => "",
    'use_cdn'              => true,
    'intro_text'           => "Conçu pour vous par <a href='https://www.net-com.fr/' target='_blank'>Net.Com</a>.",
    'footer_text'          => false,
    'admin_bar_links'      => [
        [
            'id'    => "contact",
            'href'  => "https://www.net-com.fr/",
            'title' => "Contactez Net.Com",
        ],
    ],
]);

Redux::set_section($opt_name, [
    'title'            => "Général",
    'id'               => "general",
    'customizer_width' => "400px",
    'icon'             => "el el-cogs"
]);

Redux::set_section($opt_name, [
    'title'            => "Header",
    'id'               => "general-header",
    'subsection'       => true,
    'customizer_width' => "450px",
    'fields'           => [
        [
            'id'       => "general-header-logo",
            'type'     => "media",
            'title'    => "Logo",
        ],
    ],
]);

Redux::set_section($opt_name, [
    'title'            => "Réseaux sociaux",
    'id'               => "general-social",
    'subsection'       => true,
    'customizer_width' => "450px",
    'fields'           => [
        [
            'id'       => "general-social-facebook",
            'type'     => "text",
            'title'    => "Facebook",
            'validate' => "url"
        ],
        [
            'id'       => "general-social-twitter",
            'type'     => "text",
            'title'    => "Twitter",
            'validate' => "url"
        ],
    ],
]);

Redux::set_section($opt_name, [
    'title'            => "Footer",
    'id'               => "general-footer",
    'subsection'       => true,
    'customizer_width' => "450px",
    'fields'           => [
        [
            'id'       => "general-footer-logo",
            'type'     => "media",
            'title'    => "Logo",
        ],
    ],
]);
