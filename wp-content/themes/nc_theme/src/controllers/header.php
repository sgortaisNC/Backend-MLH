<?php

function nc_header() {
    render('header', [
        'breadcrumb' => (class_exists('NC_Ariane') && !is_front_page() ? NC_Ariane::render() : null),
    ]);
}
