<?php

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Plugin;
use Elementor\Utils;
use Elementor\Widget_Base;

class NC_Carousel_Widget extends Widget_Base
{
    public function get_name()
    {
        return 'nc_carousel';
    }

    public function get_title()
    {
        return "Carrousel d'images";
    }

    public function get_icon()
    {
        return 'eicon-photo-library';
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
                'label' => "Carrousel d'images",
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'image',
            [
                'label' => "Image",
                'label_block' => true,
                'type' => Controls_Manager::MEDIA,
            ]
        );

        $repeater->add_control(
            'legende',
            [
                'label' => "Légende",
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
            ]
        );

        $this->add_control(
            'repeater',
            [
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'show_label' => false,
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        render('widgets/elementor/carousel', [
            'items' => $settings['repeater'],
            'default_image' => Utils::get_placeholder_image_src(),
        ]);
    }
}

Plugin::instance()->widgets_manager->register(new NC_Carousel_Widget());
