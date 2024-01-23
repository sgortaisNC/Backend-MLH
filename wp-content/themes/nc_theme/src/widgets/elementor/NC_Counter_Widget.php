<?php

use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Widget_Base;

class NC_Counter_Widget extends Widget_Base
{
    public function get_name()
    {
        return 'nc_counter';
    }

    public function get_title()
    {
        return "Chiffre clé";
    }

    public function get_icon()
    {
        return 'eicon-number-field';
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
                'label' => "Chiffre clé",
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'chiffre',
            [
                'label' => "Chiffre",
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'default' => "100 %",
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => "Description",
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'default' => "Lorem ipsum",
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        render('widgets/elementor/counter', [
            'chiffre' => $settings['chiffre'],
            'description' => $settings['description'],
        ]);
    }
}

Plugin::instance()->widgets_manager->register(new NC_Counter_Widget());
