<?php


function nc_pagination($max_num_pages = 0) {
    $links = [];

    /** Stop execution if there's only 1 page */
    if( $max_num_pages <= 1 )
        return;

    $paged = ( !empty($_GET['pg']) ? (int)$_GET['pg'] : 1 );

    /** Add current page to the array */
    if ( $paged >= 1 )
        $links[] = $paged;

    /** Add the pages around the current page to the array */
    if ( $paged >= 3 ) {
        $links[] = $paged - 1;
        $links[] = $paged - 2;
    }

    if ( ( $paged + 2 ) <= $max_num_pages ) {
        $links[] = $paged + 2;
        $links[] = $paged + 1;
    }

    $url = '';
    if(!empty($_SERVER['REQUEST_URI'])){
        $tabUrl = explode('?', $_SERVER['REQUEST_URI']);
        $url = $tabUrl[0];
        if(count($tabUrl) > 1){ // On a des paramètres
            $params = explode('&', $tabUrl[1]);
            foreach ($params as $key => $param){
                if(strpos($param, 'pg=') !== false){
                    unset($params[$key]);
                }
            }

            $url .= '?' . implode('&', $params);

            if($max_num_pages > 1 && !empty($params)){
                $url .= "&";
            }
        }else{
            $url .= "?";
        }
    }

    render('partials/pagination', [
        'url' => $url,
        'links' => $links,
        'paged' => $paged,
        'max_num_pages' => $max_num_pages,
    ]);
}
