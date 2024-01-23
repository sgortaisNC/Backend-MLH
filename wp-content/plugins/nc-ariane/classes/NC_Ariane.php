<?php

class NC_Ariane
{
    public static function render() {
        global $wpdb;

        $excluded = [
            '"attachment"',
            '"elementor_library"',
            '"forminator_forms"',
            '"nav_menu_item"',
            '"revision"',
            '"_pods_field"',
            '"_pods_pod"',
            '"branda_email_log"',
        ];

        $results = $wpdb->get_results("SELECT DISTINCT post_type FROM {$wpdb->prefix}posts WHERE post_type NOT IN (" . implode(',', $excluded) . ")", ARRAY_A);

        $types = [];

        foreach ( $results as $result ) {
            if ( 'page' === $result['post_type'] ) {
                $types['page'] = get_the_ID();
            } else {
                if ( defined(strtoupper($result['post_type']) . "_LIST") ) {
                    $types[$result['post_type']] = constant(strtoupper($result['post_type']) . "_LIST");
                }
            }
        }

        $breadcrumb = [
            'links' => [
                [
                    'title' => "Accueil",
                    'url' => get_home_url(),
                ],
            ],
            'current' => [],
        ];

        if ( isset($types[get_post_type()]) && !is_search() ) {
            $ancestors = get_post_ancestors($types[get_post_type()]);

            if ( !empty($ancestors) ) {
                $ancestors = array_reverse($ancestors);

                foreach ( $ancestors as $ancestor ) {
                    $breadcrumb['links'][] = [
                        'title' => get_the_title($ancestor),
                        'url' => get_permalink($ancestor),
                    ];
                }
            }

            if ( get_post_type() !== "page" ) {
                $breadcrumb['links'][] = [
                    'title' => get_the_title($types[get_post_type()]),
                    'url' => get_permalink($types[get_post_type()]),
                ];
            }
        }

        if ( is_404() ) {
            $breadcrumb['current'] = "Page non trouvée";
        } elseif ( is_search() ) {
            $breadcrumb['current'] = "Résultats pour '{$_GET['s']}'";
        } else {
            $breadcrumb['current'] = get_the_title();
        }

        ob_start();
        include __DIR__ . "/../views/breadcrumb.php";
        return ob_get_clean();
    }
}
