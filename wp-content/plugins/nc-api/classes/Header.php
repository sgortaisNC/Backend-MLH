<?php

/**
 * Class Header
 */
class Header
{
    static function ariane($request): array
    {
        $slug = $request['slug'];

        $breadcrumb = [
            'links' => [
                [
                    'title' => "Accueil",
                    'url' => get_home_url(),
                ],
            ],
            'current' => [],
        ];

        $postBySlug = get_page_by_path($slug, OBJECT, 'page');

        if ($postBySlug && $postBySlug->post_type === "page") {
            $ancestors = get_post_ancestors($postBySlug->ID);

            foreach ($ancestors as $ancestor_id) {
                $ancestor = get_post($ancestor_id);
                $breadcrumb['links'][] = [
                    'title' => $ancestor->post_title,
                    'url' => get_permalink($ancestor_id),
                ];
            }
        }

        if ($postBySlug) {
            $breadcrumb['current'] = [
                'title' => $postBySlug->post_title,
                'url' => get_permalink($postBySlug->ID),
            ];
        }

        return $breadcrumb;
    }
}
