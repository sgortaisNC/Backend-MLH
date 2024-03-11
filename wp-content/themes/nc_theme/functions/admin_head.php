<?php

add_action('admin_head', function() {
    if ( !UserApi::isAdministrator() ) {
        remove_all_actions('admin_notices');
    }
});
