<?php

add_action('widgets_init', function() {
    register_widget('NC_Liens_Widget');
    register_widget('NC_Documents_Widget');
});
