<?php
namespace FileBird\Addons\PostType\Models;

use FileBird\Addons\PostType\Init;

class Main {
    const ORDER_META_KEY = 'fbv_tax_order';
    const AUTHOR_KEY     = 'fbv_author';

    public static function getFolders( $post_type, $order_by = null ) {
        $args = array(
            'taxonomy'   => self::getTaxonomyName( $post_type ),
            'hide_empty' => false,
            'meta_key'   => self::ORDER_META_KEY,
            'orderby'    => 'meta_value_num',
            'order'      => 'ASC',
            'meta_query' => array(
                array(
                    'key'     => self::AUTHOR_KEY,
                    'value'   => apply_filters( 'fbv_folder_created_by', 0 ),
                    'compare' => '=',
                ),
            ),
        );

        if ( ! is_null( $order_by ) ) {
            if ( $order_by === 'asc' ) {
                $args['orderby'] = 'name';
                $args['order']   = 'asc';
            } elseif ( $order_by === 'desc' ) {
                $args['orderby'] = 'name';
                $args['order']   = 'desc';
            }
        }
        $terms = get_terms( $args );
        return $terms;
    }

    public static function getFolderOfPost( $post_type, $post_id ) {
        $terms = wp_get_post_terms( (int) $post_id, self::getTaxonomyName( $post_type ), array( 'fields' => 'ids' ) );
        return isset( $terms[0] ) ? $terms[0] : null;
    }

    public static function convertFormat( $term, $colors = array() ) {
        return json_decode(
            wp_json_encode(
                array(
					'id'         => intval( $term->term_id ),
                    'key'        => intval( $term->term_id ),
					'title'      => $term->name,
					'text'       => $term->name,
                    'data-id'    => intval( $term->term_id ),
					'data-count' => 0,
                    'color'      => $colors[ $term->term_id ] ?? '',
					'parent'     => intval( $term->parent ),
					'children'   => array(),
                )
            )
        );
    }

    public static function sortTerms( &$terms, &$out_put, $parent_id = 0 ) {
		foreach ( $terms as $i => $cat ) {
            if ( $cat->parent == $parent_id ) {
                $out_put[] = $cat;
                unset( $terms[ $i ] );
            }
		}

		foreach ( $out_put as $topCat ) {
			$topCat->children = array();
			self::sortTerms( $terms, $topCat->children, $topCat->id );
		}
    }

    public static function getTaxonomyName( $post_type ) {
        return Init::PREFIX . $post_type;
    }

    public static function isFolderExist( $folder_id, $taxonomy ) {
        $isExist = get_terms(
            array(
                'fields'       => 'ids',
				'include'      => array( $folder_id ),
                'hide_empty'   => false,
				'taxonomy'     => $taxonomy,
                'meta_key'     => self::AUTHOR_KEY,
                'meta_value'   => apply_filters( 'fbv_folder_created_by', 0 ),
                'meta_compare' => '=',
			)
        );

        return count( $isExist ) > 0 ? true : false;
    }
}
