<?php

/**
 * Class Actualite
 */
class Actualite
{
    public function single()
    {
        $post = [];
        $id = $_GET['id'] ?? null;

        $post[] = [
            'titre' => get_the_title($id),
            'image' => (has_post_thumbnail() ? get_the_post_thumbnail_url($id, 'nc_post_single') :
                wp_get_attachment_image_src(IMAGE_DEFAUT, 'nc_post_single')[0]),
            'contenu' => get_the_content(null, false, $id) ?? null,
            'chapo' => has_excerpt($id) ? get_the_excerpt($id) : null,
            'date_partielle' => get_the_date('d M', $id),
            'date' => get_the_date('d/m/Y', $id),
            'lien' => get_permalink($id),
        ];

        return $post;
    }

    public function list(): array
    {

        $posts = [];

        $postsRequest = new WP_Query([
            'post_type' => 'post',
            'posts_per_page' => 12,
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC',
            'paged' => ( !empty($_GET['pg']) ? $_GET['pg'] : 1 ),
        ]);

        while ($postsRequest->have_posts()) {
            $postsRequest->the_post();

            $posts[get_the_ID()] = [
                'titre' => get_the_title(),
                'image' => (has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'nc_post_list') :
                    wp_get_attachment_image_src(IMAGE_DEFAUT, 'nc_post_list')[0]),
                'contenu' => get_the_content(null, false, get_the_ID()) ?? null,
                'chapo' => has_excerpt(get_the_ID()) ? get_the_excerpt(get_the_ID()) : null,
                'date_partielle' => get_the_date('d M', get_the_ID()),
                'date' => get_the_date('d M Y', get_the_ID()),
                'lien' => get_permalink(),
            ];
        }

        wp_reset_postdata();


        return [
            'posts' => $posts,
            'max_num_pages' => $postsRequest->max_num_pages,
        ];
    }

}