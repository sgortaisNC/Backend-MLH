<?php

namespace SmartCrawl;

$image_url = sprintf( '%s/assets/images/%s', SMARTCRAWL_PLUGIN_URL, 'empty-box.svg' );
$image_url = \SmartCrawl\Controllers\White_Label::get()->get_wpmudev_hero_image( $image_url );

$content         = esc_html__( 'Search engines use web crawlers (bots) to explore and index the internet. A robots.txt file is a critical text file that tells those bots what they can and can’t index, and where things are.', 'wds' );
$already_exists  = ! empty( $already_exists );
$rootdir_install = ! empty( $rootdir_install );
$notice          = \smartcrawl_format_link(
	/* translators: %s: Url to robots.txt file */
	esc_html__( "We've detected an existing %s file that we are unable to edit. You will need to remove it before you can enable this feature.", 'wds' ),
	\smartcrawl_get_robots_url(),
	'robots.txt',
	'_blank'
);
?>

<form method="post" class="wds-form">
	<div class="wds-disabled-component">
	<?php if ( ! empty( $image_url ) ) : ?>
		<p>
			<img
				src="<?php echo esc_attr( $image_url ); ?>"
				alt="<?php esc_attr_e( 'Disabled', 'wds' ); ?>"
				class="wds-disabled-image"
			/>
		</p>
	<?php endif; ?>
		<p><?php echo wp_kses_post( $content ); ?></p>

		<input type="hidden" name="wds-activate-component" value="<?php echo esc_attr( 'robots-txt' ); ?>"/>
		<?php wp_nonce_field( 'wds-settings-nonce', '_wds_nonce' ); ?>

		<?php if ( ! $already_exists && $rootdir_install ) : ?>
			<input
				name="submit" class="sui-button sui-button-blue"
				value="<?php echo esc_attr__( 'Activate', 'wds' ); ?>"
				type="submit"/>
		<?php endif; ?>

		<?php
		if ( $already_exists ) {
			$this->render_view(
				'notice',
				array(
					'message' => $notice,
				)
			);
		} elseif ( ! $rootdir_install ) {
			$this->render_view(
				'notice',
				array(
					'message' => esc_html__( "We've detected your site is installed on a sub-directory. Robots.txt files only work when added to the root directory of a domain, so you'll need to change how your WordPress installation is set up to use this feature.", 'wds' ),
				)
			);
		}
		?>
	</div>
</form>