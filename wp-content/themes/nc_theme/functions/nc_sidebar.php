<?php

function nc_sidebar($name = 'sidebar') {
    ob_start();
    dynamic_sidebar($name);
    $sidebar = ob_get_clean();

    return $sidebar;
}
