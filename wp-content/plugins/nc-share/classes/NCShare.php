<?php

/**
 * Class NCShare
 */
class NCShare
{
    /**
     * Ajoute les options lors de l'installation du module
     */
    static function install()
    {
        add_option('ncshare_fb', 0);
        add_option('ncshare_twitter', 0);
        add_option('ncshare_linkedin', 0);
        add_option('ncshare_pinterest', 0);
        add_option('ncshare_mail', 0);
        add_option('ncshare_print', 0);
    }

    /**
     * Supprime les options lors de la désinstallation du module
     */
    static function uninstall()
    {
        delete_option('ncshare_fb');
        delete_option('ncshare_twitter');
        delete_option('ncshare_linkedin');
        delete_option('ncshare_pinterest');
        delete_option('ncshare_mail');
        delete_option('ncshare_print');
    }

    /**
     * Créé le lien de menu en backoffice
     * @noinspection PhpUnused
     */
    static function menu_link()
    {
        add_options_page(
            'Liens de partage',
            'Liens de partage',
            'manage_options',
            'nc-share',
            ['NCShare', 'admin_page'],
            3
        );
    }

    /**
     *  Contenu de la page d'administration
     */
    static function admin_page()
    {
        include_once __DIR__ . '/../views/admin.php';
    }

    /**
     * AJAX de sauvegarde du formulaire de configuration back-office
     * @noinspection PhpUnused
     */
    static function saveShareOptions()
    {
        $field = $_POST['field'];
        $val = $_POST['val'];
        if (update_option($field,$val)){
            wp_send_json_success();
        }else{
            wp_send_json_error();
        }
    }

    /**
     * Fonction d'affichage en front-office
     * @return string
     */
    static function render(): string
    {
        ob_start();
        include_once __DIR__ . '/../views/front.php';
        $return = ob_get_contents();
        ob_clean();

        if  ($return === false){
            $return = 'Erreur';
        }

        return $return;
    }

    static function sendMail(){
        $nom = $_POST['name'];
        $email = $_POST['email'];
        $lien = $_POST['link'];
        ob_start();
        include_once __DIR__ . '/../views/mail.php';
        $return = ob_get_contents();
        ob_clean();

        if (wp_mail($email,$nom." souhaite vous partager cette page du site ".get_option('blogname'),$return)){
           wp_send_json_success();
        }else{
            wp_send_json_error();
        }
    }
}
