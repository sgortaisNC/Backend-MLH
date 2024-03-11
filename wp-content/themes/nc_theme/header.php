<!DOCTYPE html>

<!--[if lt IE 7]><html class="ie ie6 no-js" dir="ltr" lang="fr-FR"><![endif]-->
<!--[if IE 7]><html class="ie ie7 no-js" dir="ltr" lang="fr-FR"><![endif]-->
<!--[if IE 8]><html class="ie ie8 no-js" dir="ltr" lang="fr-FR"><![endif]-->
<!--[if IE 9]><html class="ie ie9 no-js" dir="ltr" lang="fr-FR"><![endif]-->
<!--[if gt IE 9]><html class="no-js" dir="ltr" lang="fr-FR"><![endif]-->
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?= wp_get_document_title(); ?></title>

        <?= wp_head(); ?>
    </head>
    <body class="stuck <?= UserApi::isLogged() && is_admin_bar_showing() ? 'admin_bar' : ''; ?>">
        <?= do_action('nc_header'); ?>
