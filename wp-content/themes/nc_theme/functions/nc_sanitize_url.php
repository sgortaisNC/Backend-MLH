<?php

function nc_sanitize_url(array $parameters, string $pattern = "/[^A-Za-z0-9-_]/") {
    $data = [];

    if ( !empty($parameters) ) {
        foreach ( $parameters as $key => $value ) {
            $data[$key] = preg_replace($pattern, "", $value);
        }
    }

    return $data;
}
