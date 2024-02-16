<?php

/**
 * Class Page
 */
class Page
{
    static function getOneBySlug($request): array
    {
        $the_slug = $request['slug'];
        $args = array(
            'name'           => $the_slug,
            'post_type'      => 'page',
            'post_status'    => 'publish',
            'posts_per_page' => 1
        );
        $my_posts = get_posts($args);
        $pageBySlug = null;
        if (!empty($my_posts)){
            $pageBySlug = $my_posts[0];
        }

        $page = [];
        if ($pageBySlug) {

            $shortcode_id = null;
            $pattern = '/\[forminator_form id="(\d+)"\]/';
            $text = $pageBySlug->post_content;
            if (preg_match($pattern, $text, $matches)) {
                // $matches[0] contains the entire shortcode, $matches[1] contains the ID
                $shortcode_id = $matches[1];

                // Remove the shortcode from the text
                $text = preg_replace($pattern, '', $text);
            }

            $id = $pageBySlug->ID;

            $documents = [];
            $documentsField = get_field('documents', $id);

            if(!empty($documentsField)){
                foreach ($documentsField as $document) {
                    if (!empty($document['document'])) {
                        $documents[] = [
                            'id' => $document['document'] ? $document['document']['ID'] : null,
                            'titre' => $document['titre'] ? $document['titre'] : $document['document']['title'],
                            'lien' => wp_get_attachment_url($document['document']['ID']),
                        ];
                    }
                }
            }

            $links = [];
            $liens = get_field('liens', $id);

            if((!empty($liens))){
                foreach ($liens as $lien) {
                    $links[] = [
                        'url' =>  $lien['url'] ?? null,
                        'titre' => empty($lien['titre']) ? $lien['url'] : $lien['titre'],
                    ];
                }
            }
            $imgDefaut = wp_get_attachment_image_src(IMAGE_DEFAUT, 'nc_page_single') ?
                wp_get_attachment_image_src(IMAGE_DEFAUT, 'nc_page_single')[0] : null;
            $page[] = [
                'id' => $id,
                'titre' => get_the_title($id),
                'image' => (has_post_thumbnail($id) ? get_the_post_thumbnail_url($id, 'nc_page_single') :
                    $imgDefaut),
                'contenu' => $text ?? null,
                'chapo' => has_excerpt($id) ? get_the_excerpt($id) : null,
                'lien' => get_permalink($id),
                'formulaire' => $shortcode_id ? Forminator_API::get_form_wrappers($shortcode_id) : null,
                'formID' => $shortcode_id ? $shortcode_id : null,
                'documents' => $documents,
                'liens' => $links
            ];
        }

        return $page;
    }
}
