<?php

function nc_substr($string)
{
    $string = strip_tags($string);
    $string = mb_strlen($string) > 100 ? mb_substr($string, 0, 100) . "..." : $string;

    return $string;
}
