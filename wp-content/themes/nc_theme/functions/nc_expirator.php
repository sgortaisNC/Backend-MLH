<?php

add_action('add_meta_boxes', function($post_type) {
    if ( in_array($post_type, ['post']) ) {
        add_meta_box(
            "nc_expirator",
            "Programmer l'archivage",
            function($post) {
                date_default_timezone_set('Europe/Paris');

                if ( !empty($schedule = get_post_meta($post->ID, 'nc_expirator', true)) ) {
                    $enabled = true;
                    $date = (new DateTime())->setTimestamp($schedule);
                } else {
                    $enabled = false;
                    $date = (new DateTime())->modify("+1 day");
                }

                render('admin/expirator', [
                    'enabled' => $enabled,
                    'date' => $date->format("Y-m-d\TH:i"),
                ]);
            },
            $post_type,
            'side',
            'low'
        );
    }
});

add_action('save_post', function($post_ID, $post) {
    if ( in_array($post->post_type, ['post']) ) {
        if ( !empty($schedule = get_post_meta($post_ID, 'nc_expirator', true)) ) {
            wp_unschedule_event($schedule, 'nc_expirator', [$post_ID]);
            delete_post_meta($post_ID, 'nc_expirator');
        }

        if ( !empty($_POST['nc_expirator_enabled']) && !empty($_POST['nc_expirator_date']) ) {
            date_default_timezone_set('Europe/Paris');

            $timestamp = (new DateTime($_POST['nc_expirator_date']))->getTimestamp();

            update_post_meta(
                $post_ID,
                'nc_expirator',
                $timestamp
            );

            wp_schedule_single_event(
                $timestamp,
                'nc_expirator',
                [$post_ID]
            );
        }
    }
}, 10, 2);

add_action('nc_expirator', function($post_ID) {
    wp_update_post([
        'ID' => $post_ID,
        'post_status' => 'draft',
    ]);
}, 10, 1);
