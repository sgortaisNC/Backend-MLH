<?php

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Plugin;
use Elementor\Widget_Base;

class NC_Accordion_Widget extends Widget_Base
{
    public function get_name()
    {
        return 'nc_accordion';
    }

    public function get_title()
    {
        return "Accordéon";
    }

    public function get_icon()
    {
        return 'eicon-accordion';
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
                'label' => "Accordéon",
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'titre',
            [
                'label' => "Titre",
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'contenu',
            [
                'label' => "Contenu",
                'label_block' => true,
                'type' => Controls_Manager::WYSIWYG,
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

        render('widgets/elementor/accordion', [
            'id' => $this->get_id(),
            'items' => $settings['repeater'],
        ]);
    }
}

Plugin::instance()->widgets_manager->register(new NC_Accordion_Widget());
