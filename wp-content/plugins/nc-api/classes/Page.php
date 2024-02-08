<?php

/**
 * Class Page
 */
class Page
{
    static function getOneBySlug($request) : array
    {
        
        $slug = $request['slug'];

        $pageBySlug = get_page_by_path( $slug, OBJECT, 'page' );
        $page = [];
        if ( $pageBySlug ) {
            $id = $pageBySlug->ID;
            $page[] = [
                'id' => $id,
                'titre' => get_the_title($id),
                'image' => (has_post_thumbnail() ? get_the_post_thumbnail_url($id, 'nc_page_single') :
                    wp_get_attachment_image_src(IMAGE_DEFAUT, 'nc_page_single')[0]),
                'contenu' => apply_shortcodes( get_the_content(null, false, $id)) ?? null,
                'chapo' => has_excerpt($id) ? get_the_excerpt($id) : null,
                'lien' => get_permalink($id),
            ];
        }

        return $page;
    }
}