<?php

add_action('wp', function() {
    add_action('nc_header', 'nc_header'); # /src/controllers/header.php
    add_action('nc_footer', 'nc_footer'); # /src/controllers/footer.php

    if ( get_post_type() == 'page' ) add_action('nc_content', 'nc_page_single'); # /src/controllers/page.php
    if ( get_post_type() == 'post' ) add_action('nc_content', 'nc_post_single'); # /src/controllers/post.php
}, 1);
