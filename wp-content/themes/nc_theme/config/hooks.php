<?php

add_action('wp', function() {
    add_action('nc_header', 'nc_header'); # /src/controllers/header.php
    add_action('nc_footer', 'nc_footer'); # /src/controllers/footer.php

    if ( get_post_type() == 'page' ) add_action('nc_content', 'nc_page_single'); # /src/controllers/page.php
    if ( get_post_type() == 'post' ) add_action('nc_content', 'nc_post_single'); # /src/controllers/post.php

    if ( get_post_type() == 'page' ) {

        if (HOME === get_the_ID()) {
            remove_action('nc_content', 'nc_page_single');
            add_action('nc_content', 'nc_page_home'); # /src/controllers/page.php
        }

        # Plan du site

        if (SITEMAP === get_the_ID()) {
            remove_action('nc_content', 'nc_page_single');
            add_action('nc_content', 'nc_page_sitemap'); # /src/controllers/page.php
        }

        # Pages de liste
        if (POST_LIST === get_the_ID()) {
            remove_action('nc_content', 'nc_page_single');
            add_action('nc_content', 'nc_post_list'); # /src/controllers/post.php
        }

        if (EMPLOI_LIST === get_the_ID()) {
            remove_action('nc_content', 'nc_page_single');
            add_action('nc_content', 'nc_emploi_list'); # /src/controllers/emploi.php
        }
    }
}, 1);
