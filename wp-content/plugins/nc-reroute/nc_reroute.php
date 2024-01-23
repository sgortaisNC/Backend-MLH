<?php
/**
 * NC Reroute
 *
 * Plugin Name: NC Reroute
 * Plugin URI:  https://www.net-com.fr/
 * Description: Redirige tous les emails sortants
 * Version:     0.1
 * Author:      Sébastien Gortais [Net.Com]
 */


include_once 'classes/NCReroute.php';


// Install / Uninstall
register_activation_hook(__FILE__, ['NCReroute', 'install']);
register_uninstall_hook(__FILE__, ['NCReroute', 'uninstall']);

// Lien de menu
add_action('admin_menu', ['NCReroute', 'menu_link']);

// Ajax de sauvegarde
add_action('wp_ajax_saveRerouteMail', ['NCReroute','saveRerouteMail']);
add_action('wp_ajax_nopriv_saveRerouteMail', ['NCReroute','saveRerouteMail']);



## Redirection des emails si loc ou ppd
add_filter('wp_mail', ['NCReroute','main'], 10, 1);
