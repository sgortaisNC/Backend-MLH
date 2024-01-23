<?php

$go_out = true;

// Si ("s_remember" est vide ou n'existe pas) && ("s_favorites" existe et est égal à 1) : on autorise

if ( empty($_GET['s_remember']) && !empty($_GET['s_favorites']) && $_GET['s_favorites'] == 1 ) {
    $go_out = false;
}

// Si non autorisé : on quitte

if ( $go_out !== false ) {
    echo 'robot';
    exit;
}

?>

<?= get_header(); ?>
    <main>
        <?= get_search_form(); ?>
    </main>
<?= get_footer(); ?>
