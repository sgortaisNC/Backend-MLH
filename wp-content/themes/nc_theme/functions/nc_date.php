<?php

function nc_date($date, $format = '%d/%m/%Y') {
    date_default_timezone_set('Europe/Paris');
    setlocale(LC_TIME, 'fr_FR.UTF8', 'fr.UTF8', 'fr_FR.UTF-8', 'fr.UTF-8');

    return strftime($format, strtotime($date));
}
