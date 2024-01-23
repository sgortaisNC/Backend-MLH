<?php

use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Widget_Base;

class NC_Featured_Widget extends Widget_Base
{
    public function get_name()
    {
        return 'nc_featured';
    }

    public function get_title()
    {
        return "Mise en lumière";
    }

    public function get_icon()
    {
        return 'eicon-star-o';
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
                'label' => "Mise en lumière",
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'contenu',
            [
                'label' => "Contenu",
                'label_block' => true,
                'type' => Controls_Manager::WYSIWYG,
                'default' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris ut fermentum diam. Donec sodales nunc vitae dictum mollis.",
            ]
        );

        $this->add_control(
            'image',
            [
                'label' => "Image",
                'label_block' => true,
                'type' => Controls_Manager::MEDIA,
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        render('widgets/elementor/featured', [
            'contenu' => $settings['contenu'],
            'image' => $settings['image']['id'],
        ]);
    }
}

Plugin::instance()->widgets_manager->register(new NC_Featured_Widget());
