<?php

$image_default_size = get_option('image_default_size');

if ( $image_default_size !== 'nc_gutenberg' ) {
    update_option('image_default_size', 'nc_gutenberg');
}
