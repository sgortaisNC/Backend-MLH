<?php

use FileBird\Admin\Settings;

$navigation = array(
    array(
        'label' => __( 'Activation', 'filebird' ),
        'link'  => 'activation',
        'icon'  => 'dashicons-awards',
    ),
    array(
        'label' => __( 'Settings', 'filebird' ),
        'link'  => 'settings',
        'icon'  => 'dashicons-admin-generic',
    ),
    array(
        'label' => __( 'Tools', 'filebird' ),
        'link'  => 'tools',
        'icon'  => 'dashicons-admin-tools',
    ),
    array(
        'label' => __( 'Import/Export', 'filebird' ),
        'link'  => 'import-export',
        'icon'  => 'dashicons-database',
    ),
);
?>

<div id="filebird-admin-header">
    <div id="filebird-admin-logo">
        <img src="<?php echo esc_attr( NJFB_PLUGIN_URL . 'assets/img/logo.svg' ); ?>" alt="filebird logo" />
        <div>
          <h1 class="wp-heading-inline">
            FileBird
          </h1>
          <p>
            <?php
            esc_html_e(
                'Get the most out of WordPress Media Library Folders & File Manager. Customize the look and feel of FileBird to match your preferences.',
                'filebird'
            )
			?>
          </p>
        </div>
    </div>
    <div class="filebird-admin-divider"></div>
    <div id="filebird-admin-navbar">
        <ul id="filebird-admin-menu">
            <?php foreach ( $navigation as $nav ) : ?>
            <li class="filebird-admin-menu-item">
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=' . Settings::SETTING_PAGE_SLUG . '#/' . $nav['link'] ) ); ?>">
                    <span class="dashicons <?php echo esc_attr( $nav['icon'] ); ?>"></span>
                    <span><?php echo esc_html( $nav['label'] ); ?></span>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>

        <ul>
            <li class="filebird-admin-menu-item">
                <a href="https://ninjateam.gitbook.io/filebird/features/interface" target="_blank" rel="noopener noreferrer">
                    <span class="dashicons dashicons-media-document"></span>
                    <span><?php esc_html_e( 'Document', 'filebird' ); ?></span>
                </a>
            </li>
            <li class="filebird-admin-menu-item">
                <a href="https://ninjateam.org/support/" target="_blank" rel="noopener noreferrer">
                    <span class="dashicons dashicons-format-chat"></span>
                    <span><?php esc_html_e( 'Help', 'filebird' ); ?></span>
                </a>
            </li>
        </ul>
    </div>
</div>