<?php

/**
 * Class Search
 */
class Search
{
    static function results($request): array
    {


        nc_sanitize_url($_GET);
        $search = stripslashes($request['s'] ?? null);
        $search = urldecode($search);

        // Query
        $args = [
            'posts_per_page' => -1,
            'post_status' => ['publish'],
        ];

        $types = "";
        if (!empty($_GET['type'])) {
            $args['post_type'] = [$_GET['type']];
            $types = $_GET['type'];
        }

        $s = "";
        if (!empty($search)) {
            $args['s'] = $search;
            $s = $search;
            $swp_query = new SWP_Query($args);
        } else {
            $args['s'] = '';

            if (empty($_GET['type'])) {
                $args['post_type'] = [
                    'page',
                    'offre_emploi',
                    'bien_louer',
                    'post',
                ];
            }

            $swp_query = new WP_Query($args);
        }

//Pagination


        $results = [];
        if (!empty($swp_query->posts)) {
            foreach ($swp_query->posts as $post) {

                $chapoLength = 600;
                $chapo = get_the_excerpt($post->ID) ? str_ireplace($search, '<mark>' . $search . '</mark>', nc_substr(get_the_excerpt($post->ID), $chapoLength))
                    :
                    str_ireplace($search, '<mark>' . $search . '</mark>', nc_substr(get_the_content($post->ID), $chapoLength));

                $results[] = [
                    'id' => $post->ID,
                    'titre' => $post->post_title,
                    'resume' => $chapo,
                    'lien' => removeDomain(get_permalink($post->ID)),
                ];
            }
        }

        return [
            'results' => $results,
            'nombre' => count($results),
            's' => $s,
            'types' => $types,
        ];
    }
}
