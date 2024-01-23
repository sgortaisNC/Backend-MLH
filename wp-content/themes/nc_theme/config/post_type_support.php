<?php

add_action('init', function() {
    add_post_type_support('page', 'excerpt');
    //add_post_type_support('custom_post_type', 'revisions');
});
