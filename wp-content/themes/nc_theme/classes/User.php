<?php

class User
{
    public static function get($id = null) {
        if ( !empty($id) ) return get_user_by('ID', $id);
        return wp_get_current_user();
    }

    public static function isLogged() {
        return is_user_logged_in();
    }

    public static function isAdministrator($id = null) {
        if ( empty($id) ) $id = get_current_user_id();

        if ( user_can($id, 'administrator') ) return true;

        return false;
    }

    public static function isWebmaster($id = null) {
        if ( empty($id) ) $id = get_current_user_id();

        if ( user_can($id, 'webmaster') ) return true;

        return false;
    }
}
