<?php

/**
 * Class Search
 */
class Search
{
    static function results($request) : array
    {


        nc_sanitize_url($_GET);
        $search = stripslashes($request['s'] ?? null);
        $search = urldecode($search);

//        $go_out = true;
//        if (empty($_GET['sr']) && !empty($_GET['sf']) && ($_GET['sf'] == 1)) {
//            $go_out = false;
//        }
//        if ($go_out !== false) {
//            echo 'robot';
//            exit;
//        }

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
            $results = $swp_query->posts;
        }

        return [
            'results' => $results,
            'nombre' => count($results),
            's' => $s,
            'types' => $types,
        ];
    }
}
