<?php

class NCReroute{

    static function install(){
        add_option('nc_reroute',get_option('admin_email'));
    }

    static function uninstall(){
        delete_option('nc_reroute');
    }

    /** @noinspection PhpUnused */
    static function menu_link()
    {
        add_options_page(
            'Reroute mail',
            'Reroute mail',
            'manage_options',
            'nc-reroute',
            ['NCReroute', 'admin_page'],
            3
        );
    }

    static function admin_page(){
        include_once __DIR__.'/../views/admin.php';
    }

    static function saveRerouteMail(){
        $mail = $_POST['str'];
        if(update_option('nc_reroute',$mail)){
            wp_send_json_success();
        }else{
            wp_send_json_error();
        }
    }

    static function main($args){

        $to = get_option('nc_reroute');

        if  (strpos(get_site_url(),'.loc') !== false){
            $env = 'loc';
        }

        if  (strpos(get_site_url(),'netcomdev') !== false){
            $env = 'ppd';
        }

        if (!empty($env)) {
            switch ($env) {
                case 'loc':
                default:
                    if (is_array($args['to'])) {
                        $emails = implode(', ', $args['to']);
                    } else {
                        $emails = $args['to'];
                    }
                    $message = $args['message'] . "----- Email envoyé depuis l'environnement local -- Destinataire(s) du message : " . $emails . " -----";
                    break;

                case 'ppd':
                    if (is_array($args['to'])) {
                        $emails = implode(', ', $args['to']);
                    } else {
                        $emails = $args['to'];
                    }
                    $message = $args['message'] . "----- Email envoyé depuis l'environnement de pré-production -- Destinataire(s) du message : " . $emails . " -----";
                    break;
            }

            if (!empty($to)) {
                $args['to'] = $to;
            }

            if (!empty($message)) {
                $args['message'] = $message;
            }
        }

        return $args;
    }
}
