<?php

use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Widget_Base;

class NC_Button_Widget extends Widget_Base
{
    public function get_name()
    {
        return 'nc_button';
    }

    public function get_title()
    {
        return "Bouton";
    }

    public function get_icon()
    {
        return 'eicon-button';
    }

    public function get_categories()
    {
        return ['nc-advanced'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => "Bouton",
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'titre',
            [
                'label' => "Titre",
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'default' => "Bouton",
            ]
        );

        $this->add_control(
            'lien',
            [
                'label' => "Lien",
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'default' => get_home_url(),
            ]
        );

        $this->add_control(
            'style',
            [
                'label' => "Style",
                'label_block' => true,
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'primary' => "Style 1",
                    'secondary' => "Style 2",
                ],
                'default' => "primary",
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        render('widgets/elementor/button', [
            'titre' => $settings['titre'],
            'lien' => $settings['lien'],
            'style' => $settings['style'],
        ]);
    }
}

Plugin::instance()->widgets_manager->register(new NC_Button_Widget());
