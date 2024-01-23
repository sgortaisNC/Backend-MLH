<?php

use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Widget_Base;

class NC_Quote_Widget extends Widget_Base
{
    public function get_name()
    {
        return 'nc_quote';
    }

    public function get_title()
    {
        return "Citation";
    }

    public function get_icon()
    {
        return 'eicon-testimonial';
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
                'label' => "Citation",
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'contenu',
            [
                'label' => "Contenu",
                'label_block' => true,
                'type' => Controls_Manager::TEXTAREA,
                'default' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris ut fermentum diam. Donec sodales nunc vitae dictum mollis.",
            ]
        );

        $this->add_control(
            'auteur',
            [
                'label' => "Auteur",
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'default' => "John Doe",
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

        render('widgets/elementor/quote', [
            'contenu' => $settings['contenu'],
            'auteur' => $settings['auteur'],
            'image' => $settings['image']['id'],
        ]);
    }
}

Plugin::instance()->widgets_manager->register(new NC_Quote_Widget());
