<?php

function nc_post_list() {

    $posts = [];

    $postsRequest = new WP_Query([
        'post_type' => 'post',
        'posts_per_page' => 12,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
        'paged' => ( !empty($_GET['pg']) ? $_GET['pg'] : 1 ),
    ]);

    while ($postsRequest->have_posts()) {
        $postsRequest->the_post();

        $posts[get_the_ID()] = [
            'titre' => get_the_title(),
            'image' => (has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'nc_post_list') :
                wp_get_attachment_image_src(IMAGE_DEFAUT, 'nc_post_list')[0]),
            'date' => nc_date(get_the_date('d M Y', get_the_ID()), '%d %b %Y'),
            'lien' => get_permalink(),
        ];
    }

    wp_reset_postdata();

    render('post/list', [
        'posts' => $posts,
        'max_num_pages' => $postsRequest->max_num_pages,
    ]);
}

function nc_post_single() {



    render('post/single', [

    ]);
}
