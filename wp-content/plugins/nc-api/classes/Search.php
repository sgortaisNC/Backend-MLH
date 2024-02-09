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
        $nb_per_page = 15;
        $nb_delta = 2;

        $nb_results = $swp_query->found_posts;
        $page_en_cours = empty($_GET['page']) ? 1 : $_GET['page'];
        $nb_pages = ceil($nb_results / $nb_per_page);

        if ($page_en_cours < 1)
            $page_en_cours = 1;

        if ($page_en_cours > $nb_pages)
            $page_en_cours = $nb_pages;

        $nb_start = max(1, $page_en_cours - $nb_delta);
        $nb_end = min($nb_pages, $page_en_cours + $nb_delta);

        $results = [];
        if (!empty($swp_query->posts)) {
            $results = array_slice($swp_query->posts, ($page_en_cours - 1) * $nb_per_page, $nb_per_page);
        }

        return [
            'results' => $results,
            'nb_results' => $nb_results,
            'nb_pages' => $nb_pages,
            'page_en_cours' => $page_en_cours,
            'nb_start' => $nb_start,
            'nb_end' => $nb_end,
            's' => $s,
            'types' => $types,
        ];
    }
}
