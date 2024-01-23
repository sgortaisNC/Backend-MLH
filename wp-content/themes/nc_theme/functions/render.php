<?php

function render($view, $data = [], $return = false) {
    $file = get_stylesheet_directory() . '/src/views/' . dirname($view) . '/' . basename($view, '.php') . '.php';

    if ( !empty($file) && file_exists($file) ) {
        if ( !empty($data) && is_array($data) ) extract($data);
        ob_start();
        include $file;
        $content = ob_get_contents();
        ob_end_clean();

        if ( !empty($content) ) {
            if ( !empty($return) ) return $content;
            echo $content;
        }
    }
}
