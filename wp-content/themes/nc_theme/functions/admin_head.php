<?php

add_action('admin_head', function() {
    if ( !User::isAdministrator() ) {
        remove_all_actions('admin_notices');
    }
});
