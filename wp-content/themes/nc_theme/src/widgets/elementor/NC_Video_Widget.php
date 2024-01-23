<?php

use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Widget_Base;

class NC_Video_Widget extends Widget_Base
{
    public function get_name()
    {
        return 'nc_video';
    }

    public function get_title()
    {
        return "Vidéo YouTube";
    }

    public function get_icon()
    {
        return 'eicon-play';
    }

    public function get_categories()
    {
        return ['nc-media'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => "Vidéo YouTube",
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'video',
            [
                'label' => "Identifiant de la vidéo YouTube",
                'label_block' => true,
                'description' => "Il s'agit de l'identifiant qui se trouve à la fin de l'URL d'une vidéo YouTube. Exemple : https://www.youtube.com/watch?v=<b style='color: white;'>ScMzIvxBSi4</b>",
                'type' => Controls_Manager::TEXT,
                'default' => "ScMzIvxBSi4",
            ]
        );

        $this->add_control(
            'legende',
            [
                'label' => "Légende",
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        render('widgets/elementor/video', [
            'video' => $settings['video'],
            'legende' => $settings['legende'],
        ]);
    }
}

Plugin::instance()->widgets_manager->register(new NC_Video_Widget());
