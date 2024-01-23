<?php

function nc_page_single() {
    render('page/single', [
        'retour' => !empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], get_home_url()) !== false ? $_SERVER['HTTP_REFERER'] : get_home_url(),
    ]);
}
