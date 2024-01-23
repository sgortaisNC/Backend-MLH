<?php

class Exemple_Widget extends WP_Widget
{
    public function __construct() {
        parent::__construct('exemple', 'Exemple');
    }

    public function widget($args, $instance) {
        render('widgets/exemple');
    }
}
