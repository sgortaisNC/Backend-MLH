<?php

function nc_substr($string, $length = 100)
{
    return mb_strlen($string) > 100 ? mb_substr($string, 0, $length) : $string;
}
