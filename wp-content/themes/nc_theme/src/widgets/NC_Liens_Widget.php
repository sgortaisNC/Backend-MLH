<?php

class NC_Liens_Widget extends WP_Widget{
    public function __construct(){
        parent::__construct('lien', 'Liens');
    }

    public function widget($args, $instance) {
        $links = [];
        $liens = get_field('liens');

        if((!empty($liens))){
            foreach ($liens as $lien) {
                $links[] = [
                    'url' =>  $lien['url'] ?? null,
                    'titre' => empty($lien['titre']) ? $lien['url'] : $lien['titre'],
                ];
            }
        }

        render('widgets/liens', [
            'liens' => $links
        ]);
    }
}
