<?php
/**
 * NC Reroute
 *
 * Plugin Name: NC Share
 * Plugin URI:  https://www.net-com.fr/
 * Description: Permet le partage de contenu via la fonction NCShare::render()
 * Version:     0.1
 * Author:      Sébastien Gortais [Net.Com]
 */


include_once 'classes/NCShare.php';


// Install / Uninstall
register_activation_hook(__FILE__, ['NCShare', 'install']);
register_uninstall_hook(__FILE__, ['NCShare', 'uninstall']);

// Lien de menu
add_action('admin_menu', ['NCShare', 'menu_link']);

// Ajax de sauvegarde
add_action('wp_ajax_saveShareOptions', ['NCShare','saveShareOptions']);
add_action('wp_ajax_nopriv_saveShareOptions', ['NCShare','saveShareOptions']);

// Ajax d'envoi de mail
add_action('wp_ajax_sendmailShare', ['NCShare','sendMail']);
add_action('wp_ajax_nopriv_sendmailShare', ['NCShare','sendMail']);

// Ajout du js au site
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_script('nc_share',plugin_dir_url(__FILE__).'js/nc_share.js',['jquery'],false,true);
    wp_localize_script( 'nc_share', 'ajax', array(
        'url' => admin_url( 'admin-ajax.php' )
    ) );
});
