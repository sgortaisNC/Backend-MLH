<?php
namespace FileBird\Classes;

defined( 'ABSPATH' ) || exit;

class Helpers {

    protected static $instance = null;

    public static function getInstance() {
		if ( null == self::$instance ) {
            self::$instance = new self();
		}
        return self::$instance;
    }

    public static function sanitize_array( $var ) {
        if ( is_array( $var ) ) {
            return array_map( 'self::sanitize_array', $var );
        } else {
            return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
        }
    }

    public static function sanitize_intval_array( $var ) {
        if ( is_array( $var ) ) {
            return array_map( 'intval', $var );
        } else {
            return intval( $var );
        }
    }

    public static function getAttachmentIdsByFolderId( $folder_id ) {
        global $wpdb;
        return $wpdb->get_col( 'SELECT `attachment_id` FROM ' . $wpdb->prefix . 'fbv_attachment_folder WHERE `folder_id` = ' . (int) $folder_id );
    }

    public static function getAttachmentCountByFolderId( $folder_id ) {
        return Tree::getCount( $folder_id );
    }

    public static function view( $path, $data = array() ) {
        extract( $data );
        ob_start();
        include_once NJFB_PLUGIN_PATH . 'views/' . $path . '.php';
        return ob_get_clean();
    }

    public static function isActivated() {
        $code = get_option( 'filebird_code', '' );
        $email = get_option( 'filebird_email', '' );
        return ( $code != '' && $email != '' );
    }

    public static function isListMode() {
		if ( function_exists( 'get_current_screen' ) ) {
            $screen = get_current_screen();
            return ( isset( $screen->id ) && 'upload' == $screen->id );
		}
        return false;
    }

    public static function wp_kses_i18n( $string ) {
        return wp_kses(
            $string,
            array(
                'strong' => array(),
                'a'      => array(
                    'target' => array(),
                    'href'   => array(),
                ),
            )
        );
    }

    public static function findFolder( $folder_id, $tree ) {
        $folder = null;
        foreach ( $tree as $k => $v ) {
            if ( $v['id'] == $folder_id ) {
                $folder = $v;
                break;
            } else {
                $folder = self::findFolder( $folder_id, $v['children'] );
                if ( ! is_null( $folder ) ) {
                    break;
                } else {
                    continue;
                }
            }
        }
        return $folder;
    }

    public static function get_bytes( $post_id ) {
        $bytes = '';
        $meta  = wp_get_attachment_metadata( $post_id );
        if ( isset( $meta['filesize'] ) ) {
            $bytes = $meta['filesize'];
        } else {
            $attached_file = get_attached_file( $post_id );
            if ( file_exists( $attached_file ) ) {
                $bytes = \wp_filesize( $attached_file );
            }
        }
        return $bytes;
    }

    public static function loadView( $view, $data = array(), $return_html = false ) {
        $viewPath = NJFB_PLUGIN_PATH . 'views/' . $view . '.php';
        if ( ! file_exists( $viewPath ) ) {
            die( 'View <strong>' . esc_html( $viewPath ) . '</strong> not found!' );
        }
        extract( $data );
        if ( $return_html === true ) {
            ob_start();
            include_once $viewPath;
            return ob_get_clean();
        }
        include_once $viewPath;
    }
}