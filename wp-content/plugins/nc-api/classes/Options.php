<?php

/**
 * Class OffreEmploi
 */
class Options
{
    static function getAll(): array
    {
        $options = get_fields('option');
        return $options;

    }

    static function header(): array
    {
        $menuQuery = wp_get_nav_menu_items(15);
        $menu = [];
        $orderNiveau1 = -1;
        $orderNiveau2 = -1;
        $orderNiveau3 = -1;


        foreach ($menuQuery as $item) {
            if ($item->menu_item_parent == 0) {
                $orderNiveau2 = -1;
                $orderNiveau1++;
                $menu[$orderNiveau1] = [
                    'id' => $item->ID,
                    'title' => $item->title,
                    'url' => removeDomain($item->url),
                    'niveau2' => [],
                ];
                $currentNiveau1 = &$menu[$orderNiveau1];
            } elseif (isset($currentNiveau1)) {
                if ($item->menu_item_parent == $currentNiveau1['id']) {
                    $orderNiveau3 = -1;
                    $orderNiveau2++;
                    $currentNiveau1['niveau2'][$orderNiveau2] = [
                        'id' => $item->ID,
                        'title' => $item->title,
                        'url' => removeDomain($item->url),
                        'niveau3' => [],
                    ];
                    $currentNiveau2 = &$currentNiveau1['niveau2'][$orderNiveau2];
                } elseif (isset($currentNiveau2) && $item->menu_item_parent == $currentNiveau2['id']) {

                    $orderNiveau3++;
                    $currentNiveau2['niveau3'][$orderNiveau3] = [
                        'id' => $item->ID,
                        'title' => $item->title,
                        'url' => removeDomain($item->url),
                    ];
                }
            }
        }

        unset($currentNiveau1, $currentNiveau2); // on supprime les réferences & pour éviter les problèmes et optimiser la libération de mémoire

        $alerteOption = get_field('alerte', 'option');

        $alerte = [];
        $today = date('d/m/Y');

        if (!empty($alerteOption) && $today >= $alerteOption['date_debut'] && $today <= $alerteOption['date_fin']) {
            $alerte[] = [
                'titre' => $alerteOption['titre'],
                'contenu' => $alerteOption['contenu'],
                'date_debut' => $alerteOption['date_debut'],
                'date_fin' => $alerteOption['date_fin'],
            ];
        }

        return [
            'alerte' => $alerte,
            'menu' => $menu,
            'logo' => get_field('logo', 'option') ?? null,
            'espace' => get_field('espace_locataire', 'option') ?? null,
            'demande_logement' => get_field('demande_logement', 'option') ?? null,
            'share' => (class_exists('NCShare') ? NCShare::render() : null),
            'breadcrumb' => (class_exists('NC_Ariane') && !is_front_page() ? NC_Ariane::render() : null),
        ];
    }

    static function footer(): array
    {
        //Menu footer
        $menuQuery = wp_get_nav_menu_items(16);
        $menu = [];
        $order = 0;

        foreach ($menuQuery as $item) {
            $menu[$order] = [
                'id' => $item->ID,
                'title' => $item->title,
                'url' => $item->url,
            ];
            $order++;
        }

        ksort($menu);

        //Accès rapides --
        $acces = [];
        $acces_rapide = get_field("acces_rapides", "options") ?? null;

        if (!empty($acces_rapide)) {
            foreach ($acces_rapide as $value) {
                $acces[] = [
                    'id' => $value['lien'][0]->ID,
                    'title' => $value['lien'][0]->post_title ?? null,
                    'url' => get_permalink($value['lien'][0]->ID) ?? null
                ];
            }
        }

        //Réseaux sociaux
        $social = [];
        $social_networks = get_field("reseaux_sociaux", "options") ?? null;

        if (!empty($social_networks)) {
            $social["linkedin"] = $social_networks['linkedin'] ?? null;
            $social["facebook"] = $social_networks['facebook'] ?? null;
            $social["instagram"] = $social_networks['instagram'] ?? null;
            $social["x"] = $social_networks['x'] ?? null;
        }

        $coordonneesMaps = get_field("coordonnees_localisation", "options") ?
            str_replace(" ", "+", get_field("coordonnees_localisation", "options")) : null;

        return [
            "logo" => get_field("logo_footer", "options") ?? null,
            "social" => $social ?? null,
            "acces_rapide" => $acces ?? null,
            "coordonnees" => get_field("coordonnees_localisation", "options") ?? null,
            "coordonneesMaps" => $coordonneesMaps ?? null,
            "menu" => $menu ?? null,
        ];
    }

    static function homepage(): array
    {
        //baseline + image option de thème
        $baselineOption = get_field('image_baseline', 'option') ?? null;
        $baseline = [
            'image' => wp_get_attachment_image_src($baselineOption['image']['ID'], 'nc_home_baseline')[0] ?? null,
            "ligne1" => $baselineOption["ligne_1"] ?? null,
            "ligne2" => $baselineOption["ligne_2"] ?? null,
            "ligne3" => $baselineOption["ligne_3"] ?? null,
        ];

        // moteur de recherche
        $filtres = [];


        $filtresTerms['types'] = get_terms([
            'taxonomy' => 'type_de_bien',
            'fields' => "id=>name",
            'hide_empty' => false,
            'parent' => 0,
        ]);

        foreach ($filtresTerms['types'] as $id => $filtre) {
            $filtres['types'][] = [
                'value' => $id,
                'label' => $filtre,
            ];
        }

        $filtresTerms['nombre_piece'] = get_terms([
            'taxonomy' => 'nombre_piece',
            'fields' => "id=>name",
            'hide_empty' => false,
            'parent' => 0,
        ]);

        foreach ($filtresTerms['nombre_piece'] as $id => $filtre) {
            $filtres['nombre_piece'][] = [
                'value' => $id,
                'label' => $filtre,
            ];
        }

        $filtresTerms['villes'] = get_terms([
            'taxonomy' => 'ville_code_postal',
            'fields' => "id=>name",
            'hide_empty' => false,
            'parent' => 0,
        ]);

        foreach ($filtresTerms['villes'] as $id => $filtre) {
            $filtres['villes'][] = [
                'value' => $id,
                'label' => $filtre,
            ];
        }

        //Biens à la une
        $biens = [];

        $biensAlaune = get_field('biens_alaune', 'option') ?? null;


        if (!empty($biensAlaune)) {
            foreach ($biensAlaune as $bien) {
                $id = $bien->ID;
                $biens[] = [
                    'id' => $id,
                    'titre' => get_the_title($id),
                    'image' => has_post_thumbnail($id) ?
                        get_the_post_thumbnail_url($id, 'nc_louer_list') :
                        wp_get_attachment_image_src(IMAGE_DEFAUT, 'nc_louer_list')[0],
                    'type' => get_the_terms($id, 'type_de_bien') ?
                        join(', ', wp_list_pluck(get_the_terms($id, 'type_de_bien'), 'name')) :
                        null,
                    'ville' => get_the_terms($id, 'ville_code_postal') ?
                        join(', ', wp_list_pluck(get_the_terms($id, 'ville_code_postal'), 'name')) :
                        null,
                    'loyer' => get_field('loyer_charges_comprises', $id) ?? null,
                    'surface' => get_field('surface', $id) ?? null,
                    'nombre_pieces' => get_the_terms($id, 'nombre_piece') ?
                        join(', ', wp_list_pluck(get_the_terms($id, 'nombre_piece'), 'name')) :
                        null,
                    'lien' => get_permalink($id),
                ];
            }
        }

        //Actualités
        $actualites = [];
        $actualitesIds = [];

        $actualitesOption = get_field('actualites', 'option') ?? null;

        if (!empty($actualitesOption)) {
            foreach ($actualitesOption as $actualite) {
                $id = $actualite->ID;
                $actualitesIds[] = $id;

                $actualites[] = [
                    'id' => $id,
                    'titre' => get_the_title($id),
                    'image' => has_post_thumbnail($id) ?
                        get_the_post_thumbnail_url($id, 'nc_home_actualites') :
                        wp_get_attachment_image_src(IMAGE_DEFAUT, 'nc_home_actualites')[0],
                    'jour' => get_the_date('d', $id),
                    'mois' => get_the_date('M', $id),
                    'date' => get_the_date('Y-m-d', $id),
                    'lien' => get_permalink($id),
                ];
            }
        }

        if (count($actualites) < 3) {
            $args = [
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => 3 - count($actualites),
                'orderby' => 'date',
                'order' => 'DESC',
                'post__not_in' => $actualitesIds,
            ];

            $actualitesQuery = new WP_Query($args);

            while ($actualitesQuery->have_posts()) {
                $actualitesQuery->the_post();

                $actualites[] = [
                    'id' => get_the_ID(),
                    'titre' => get_the_title(),
                    'image' => has_post_thumbnail(get_the_ID()) ?
                        get_the_post_thumbnail_url(get_the_ID(), 'nc_home_actualites') :
                        wp_get_attachment_image_src(IMAGE_DEFAUT, 'nc_home_actualites')[0],
                    'jour' => get_the_date('d'),
                    'mois' => get_the_date('M'),
                    'date' => get_the_date('Y-m-d'),
                    'lien' => get_permalink(),
                ];
            }

            wp_reset_postdata();
        }

        //Chiffres clés
        $chiffres = [];

        $chiffresOption = get_field('chiffres_cles', 'option') ?? null;

        if (!empty($chiffresOption)) {
            foreach ($chiffresOption as $chiffre) {
                $chiffres[] = [
                    'texte' => $chiffre['texte'] ?? null,
                    'chiffre' => $chiffre['chiffre'] ?? null,
                    'pictogramme' => wp_get_attachment_image_src($chiffre['pictogramme']['ID'], 'nc_home_actualites')[0] ?? null,
                ];
            }
        }


        // focus = guttemberg
        $focus = [
            'contenu' => get_the_content(null, false, 5) ?? null,
            'test' => "test"
        ];

        return [
            'baseline' => $baseline,
            'filtres' => $filtres,
            'biens' => $biens,
            'actualites' => $actualites,
            'chiffres' => $chiffres,
            'focus' => $focus,
        ];
    }
}
