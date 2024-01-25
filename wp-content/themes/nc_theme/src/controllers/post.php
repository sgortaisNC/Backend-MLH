<?php

function nc_post_list() {

    $posts = [];

    $postsRequest = new WP_Query([
        'post_type' => 'post',
        'posts_per_page' => 1,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
        'paged' => ( !empty($_GET['pg']) ? $_GET['pg'] : 1 ),
    ]);

    while ($postsRequest->have_posts()) {
        $postsRequest->the_post();

        $posts[get_the_ID()] = [
            'titre' => get_the_title(),
            'date' => get_the_date(),
            'lien' => get_permalink(),
        ];
    }

    wp_reset_postdata();

    var_dump($postsRequest->max_num_pages);

    render('post/list', [
        'posts' => $posts,
        'max_num_pages' => $postsRequest->max_num_pages,
    ]);
}

function nc_post_single() {

    render('post/single', [

    ]);
}
