<?php

use SearchWP_Live_Search_Utils as Utils;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SearchWP_Live_Search_Settings.
 *
 * The SearchWP Live Ajax Search settings.
 *
 * @since 1.7.0
 */
class SearchWP_Live_Search_Settings {

	/**
	 * Hooks.
	 *
	 * @since 1.7.0
	 */
	public function hooks() {

		add_action( 'admin_enqueue_scripts', [ $this, 'assets' ] );

        if ( Utils::is_searchwp_active() ) {
            $this->hooks_searchwp_enabled();
        } else {
	        $this->hooks_searchwp_disabled();
        }
	}

	/**
	 * Outputs the assets needed for the Settings page.
	 *
	 * @since 1.7.0
	 *
	 * @return void
	 */
	public function assets() {

		if ( ! Utils::is_settings_page() ) {
			return;
		}

		wp_enqueue_style(
			'searchwp-live-search-styles',
			SEARCHWP_LIVE_SEARCH_PLUGIN_URL . 'assets/styles/admin/style.css',
			[],
			SEARCHWP_LIVE_SEARCH_VERSION
		);

		if ( ! Utils::is_searchwp_active() ) {
			// FontAwesome.
			wp_enqueue_style(
				'searchwp-font-awesome',
				SEARCHWP_LIVE_SEARCH_PLUGIN_URL . 'assets/vendor/fontawesome/css/font-awesome.min.css',
				null,
				'4.7.0'
			);
		}
	}

	/**
	 * Hooks if SearchWP is enabled.
	 *
	 * @since 1.7.0
	 */
	private function hooks_searchwp_enabled() {

		add_action( 'searchwp\settings\nav\after', function () {
			if ( ! class_exists( '\\SearchWP\\Admin\\NavTab' ) ) {
				return;
			}
			if ( ! Utils::is_parent_settings_page() ) {
				return;
			}
			new \SearchWP\Admin\NavTab( [
				'page'       => 'forms',
				'tab'        => 'live-search',
				'label'      => esc_html__( 'Live Search', 'searchwp-live-ajax-search' ),
			] );
		} );

		if ( Utils::is_settings_page() ) {
			add_action( 'searchwp\settings\view', [ $this, 'output' ] );
		}
    }

	/**
	 * Hooks if SearchWP is disabled.
	 *
	 * @since 1.7.0
	 */
	private function hooks_searchwp_disabled() {

		add_action( 'in_admin_header', [ $this, 'header_searchwp_disabled' ], 100 );
		add_filter( 'admin_footer_text', [ $this, 'admin_footer_rate_us_searchwp_disabled' ], 1, 2 );
		add_filter( 'update_footer', [ $this, 'admin_footer_hide_wp_version_searchwp_disabled' ], PHP_INT_MAX );

		add_action( 'admin_print_scripts', [ $this, 'admin_hide_unrelated_notices' ] );
    }

	/**
	 * Return array containing markup for all the appropriate settings fields.
	 *
	 * @since 1.7.0
	 *
	 * @return array
	 */
	private function get_settings_fields() {

		$fields   = [];
		$settings = searchwp_live_search()
            ->get( 'Settings_Api' )
            ->get_registered_settings();

		foreach ( $settings as $slug => $args ) {
			$fields[ $slug ] = $this->output_field( $args );
		}

		return apply_filters( 'searchwp_live_search_settings_fields', $fields );
	}

	/**
	 * Settings page output.
	 *
	 * @since 1.7.0
	 */
	public function output() {

		?>
		<div class="swp-content-container">
			<div class="swp-collapse swp-opened">
				<div class="swp-collapse--header">
					<h2 class="swp-h2">
						Live Search Settings
					</h2>

					<button class="swp-expand--button">
						<svg class="swp-arrow" width="17" height="11" viewBox="0 0 17 11" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M14.2915 0.814362L8.09717 6.95819L1.90283 0.814362L0 2.7058L8.09717 10.7545L16.1943 2.7058L14.2915 0.814362Z" fill="#0E2121" fill-opacity="0.8"></path>
						</svg>
					</button>
				</div>

				<div class="swp-collapse--content">
					<div class="searchwp-live-search-settings">
						<?php $this->output_form(); ?>
					</div>
				</div>

			</div>

			<p class="submit">
				<button type="submit" form="searchwp-live-search-admin-settings-form" class="searchwp-btn searchwp-btn-md searchwp-btn-accent" name="searchwp-live-search-settings-submit">
					<?php esc_html_e( 'Save Settings', 'searchwp-live-ajax-search' ); ?>
				</button>
			</p>

			<?php $this->output_after_settings(); ?>
			
        </div>
		<?php
	}

	/**
	 * Settings form output.
	 *
	 * @since 1.7.0
	 */
	private function output_form() {

		$fields = $this->get_settings_fields();

		?>
		<form id="searchwp-live-search-admin-settings-form" class="searchwp-admin-settings-form" method="post">
			<input type="hidden" name="action" value="update-settings">
			<input type="hidden" name="view" value="settings">
			<input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( 'searchwp-live-search-settings-nonce' ) ); ?>">
			<?php
			foreach ( $fields as $field ) {
				echo $field; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
		</form>
		<?php
	}

	/**
	 * Settings field output.
	 *
	 * @since 1.7.0
	 *
	 * @param array $args Field config.
	 *
	 * @return string
	 */
	private function output_field( $args ) {

		// Define default callback for this field type.
		$callback = ! empty( $args['type'] ) && method_exists( $this, 'output_field_' . $args['type'] ) ? [ $this, 'output_field_' . $args['type'] ] : '';

		if ( empty( $callback ) ) {
			return '';
		}

		// Custom row classes.
		$class = ! empty( $args['class'] ) ? Utils::sanitize_classes( (array) $args['class'], [ 'convert' => true ] ) : '';

		// Build standard field markup and return.
		$output = '<div class="searchwp-settings-row searchwp-settings-row-' . sanitize_html_class( $args['type'] ) . ' ' . $class . '" id="searchwp-setting-row-' . sanitize_key( $args['slug'] ) . '">';

		if ( ! empty( $args['name'] ) ) {
			$output .= '<span class="searchwp-setting-label">';
			$output .= '<label for="searchwp-setting-' . sanitize_key( $args['slug'] ) . '">' . esc_html( $args['name'] ) . '</label>';
			$output .= '</span>';
		}

		$output .= '<span class="searchwp-setting-field">';

		// Get returned markup from callback.
		$output .= call_user_func( $callback, $args );

		if ( ! empty( $args['desc_after'] ) ) {
			$output .= $args['desc_after'];
		}

		$output .= '</span>';

		$output .= '</div>';

		return $output;
	}

	/**
	 * Settings checkbox field output.
	 *
	 * @since 1.7.0
	 *
	 * @param array $args Field config.
	 *
	 * @return string
	 */
	private function output_field_checkbox( $args ) {

		$default = isset( $args['default'] ) ? esc_html( $args['default'] ) : '';
		$value   = searchwp_live_search()->get( 'Settings_Api' )->get( $args['slug'], $default );
		$slug    = sanitize_key( $args['slug'] );
		$checked = ! empty( $value ) ? checked( 1, $value, false ) : '';

        $output = '<label class="swp-toggle">';

		$output .= '<input type="checkbox" class="swp-toggle-checkbox" id="searchwp-setting-' . $slug . '" name="' . $slug . '" ' . $checked . '>';

		$output .= '<div class="swp-toggle-switch"></div>';

		if ( ! empty( $args['desc'] ) ) {
			$output .= '<span class="swp-label">' . wp_kses_post( $args['desc'] ) . '</span>';
		}

		$output .= '</label>';

		return $output;
	}

	/**
	 * Settings select field output.
	 *
	 * @since 1.7.0
	 *
	 * @param array $args Field config.
	 *
	 * @return string
	 */
	private function output_field_select( $args ) {

		$default = isset( $args['default'] ) ? esc_html( $args['default'] ) : '';
		$slug    = sanitize_key( $args['slug'] );
		$value   = searchwp_live_search()->get( 'Settings_Api' )->get( $slug, $default );
		$data    = isset( $args['data'] ) ? (array) $args['data'] : [];
		$attr    = isset( $args['attr'] ) ? (array) $args['attr'] : [];

		foreach ( $data as $name => $val ) {
			$data[ $name ] = 'data-' . sanitize_html_class( $name ) . '="' . esc_attr( $val ) . '"';
		}

		$data = implode( ' ', $data );
		$attr = implode( ' ', array_map( 'sanitize_html_class', $attr ) );

		$output = '<select id="searchwp-live-search-setting-' . $slug . '" name="' . $slug . '" ' . $data . $attr . '>';

		foreach ( $args['options'] as $option => $name ) {
			if ( empty( $args['selected'] ) ) {
				$selected = selected( $value, $option, false );
			} else {
				$selected = is_array( $args['selected'] ) && in_array( $option, $args['selected'], true ) ? 'selected' : '';
			}
			$output .= '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . esc_html( $name ) . '</option>';
		}

		$output .= '</select>';

		if ( ! empty( $args['desc'] ) ) {
			$output .= '<p class="desc">' . wp_kses_post( $args['desc'] ) . '</p>';
		}

		return $output;
	}

	/**
	 * Settings content field output.
	 *
	 * @since 1.7.0
	 *
	 * @param array $args Field config.
	 *
	 * @return string
	 */
	private function output_field_content( $args ) {

		return ! empty( $args['content'] ) ? $args['content'] : '';
	}

	/**
	 * Renders the header logo.
	 *
	 * @since 1.7.3
	 *
	 * @return void
	 */
	private static function header_logo() {
		?>
		<svg fill="none" height="40" viewBox="0 0 186 40" width="186" xmlns="http://www.w3.org/2000/svg"
		     xmlns:xlink="http://www.w3.org/1999/xlink">
			<clipPath id="a">
				<path d="m0 0h26.2464v40h-26.2464z"/>
			</clipPath>
			<g fill="#456b47">
				<path d="m51.2968 15.3744c-.1125.2272-.225.4544-.45.568-.1126.1136-.3376.1136-.5626.1136-.2251 0-.4501-.1136-.7876-.2272-.2251-.2272-.5626-.3408-1.0127-.568s-.7876-.4544-1.3502-.568c-.4501-.2272-1.1252-.2272-1.8003-.2272s-1.1251.1136-1.5752.2272-.9001.3408-1.1252.6816c-.3375.2272-.5625.568-.6751.9088-.1125.3409-.225.7953-.225 1.2497 0 .568.1125 1.0224.4501 1.4768.3375.3408.7876.6817 1.2377 1.0225.5625.2272 1.1251.4544 1.8002.6816l2.0253.6816c.6751.2272 1.3502.568 2.0253.7953.6751.3408 1.2377.6816 1.8003 1.2496.5626.4544.9001 1.136 1.2377 1.8177.3375.6816.45 1.5904.45 2.6129 0 1.136-.225 2.1584-.5625 3.0673-.3376.9088-.9002 1.8176-1.5753 2.4993-.6751.6816-1.5752 1.2496-2.5879 1.704-1.0126.4544-2.2503.568-3.488.568-.7876 0-1.4627-.1136-2.2503-.2272s-1.4627-.3408-2.1378-.6816c-.6751-.2272-1.3502-.568-1.9128-1.0224-.1125-.1136-.2251-.2272-.4501-.2272-.6751-.5681-.9001-1.4769-.4501-2.2721l.5626-.9089c.1125-.1136.2251-.3408.4501-.3408.225-.1136.3375-.1136.5626-.1136.225 0 .5626.1136.9001.3408.3376.2272.6751.4545 1.1252.7953s.9001.568 1.5752.7952c.5626.2272 1.3502.3408 2.1378.3408 1.2377 0 2.2504-.3408 2.9255-.9088s1.0126-1.4769 1.0126-2.6129c0-.6816-.1125-1.1361-.45-1.5905-.3376-.4544-.7877-.7952-1.2377-1.0224-.5626-.2272-1.1252-.4544-1.8003-.6816s-1.3502-.3408-2.0253-.5681c-.6751-.2272-1.3502-.4544-2.0253-.7952s-1.2377-.6816-1.8003-1.2496c-.5626-.4544-.9001-1.1361-1.2377-1.9313-.3375-.7952-.45-1.7041-.45-2.8401 0-.9088.225-1.7041.5626-2.6129.3375-.7952.9001-1.5905 1.5752-2.2721s1.4627-1.136 2.4754-1.5904c1.0126-.3408 2.1378-.5681 3.3755-.5681 1.4627 0 2.7004.2273 3.9381.6817.7876.3408 1.5752.6816 2.1378 1.136.5626.3408.6751 1.1361.3375 1.7041z"/>
				<path d="m62.4361 17.7601c1.1252 0 2.0253.2272 2.9255.5681.9001.3408 1.6877.9088 2.3628 1.4768s1.1252 1.4769 1.5753 2.4993c.3375 1.0224.5625 2.0449.5625 3.2945v.7952c0 .2273-.1125.3409-.1125.4545-.1125.1136-.225.2272-.3375.2272s-.2251.1136-.4501.1136h-10.5766c.1125 1.8176.5626 3.0673 1.4627 3.8625.7877.7952 1.9128 1.2497 3.263 1.2497.6751 0 1.2377-.1136 1.6878-.2273.4501-.1136.9001-.3408 1.2377-.568.3375-.2272.6751-.3408.9001-.568.225-.1136.5626-.2272.7876-.2272.1125 0 .3376 0 .4501.1136s.225.1136.3375.3408l.2251.3408c.5626.7953.45 1.8177-.3376 2.3857-.1125 0-.1125.1136-.225.1136-.5626.3408-1.1252.6816-1.8003.9088-.5626.2273-1.2377.3409-1.9128.4545s-1.2376.1136-1.8002.1136c-1.2377 0-2.2504-.2272-3.263-.5681-1.0127-.3408-1.9128-1.0224-2.7004-1.8176s-1.3502-1.7041-1.8003-2.8401c-.4501-1.1361-.6751-2.4993-.6751-3.9762 0-1.136.225-2.272.5626-3.2945.3375-1.0224.9001-1.9312 1.5752-2.7265.6751-.7952 1.5753-1.3632 2.5879-1.8176 1.0127-.4545 2.1378-.6817 3.488-.6817zm0 2.9537c-1.2377 0-2.1378.3408-2.8129 1.0225-.6751.6816-1.1251 1.704-1.3502 2.9537h7.6512c0-.568-.1126-1.0225-.2251-1.4769s-.3375-.9088-.6751-1.2496c-.3375-.3408-.6751-.6816-1.1251-.7952-.4501-.1137-.7877-.4545-1.4628-.4545z"/>
				<path d="m85.277 33.5511c0 .9089-.7877 1.7041-1.6878 1.7041h-.225c-.3376 0-.6751-.1136-.9002-.2272-.225-.1136-.3375-.3408-.45-.6817l-.3376-1.2496c-.45.3408-.9001.6816-1.2377 1.0224-.45.3409-.9001.5681-1.2376.7953-.4501.2272-.9002.3408-1.4628.4544-.45.1136-1.0126.1136-1.6877.1136s-1.3502-.1136-2.0253-.3408c-.5626-.2272-1.1252-.4544-1.5752-.9089-.4501-.3408-.7877-.9088-1.0127-1.4768s-.3375-1.2497-.3375-2.0449c0-.6816.1125-1.2496.5625-1.9313.3376-.6816.9002-1.2496 1.6878-1.704s1.8003-.9088 3.1505-1.2497c1.3502-.3408 2.9254-.4544 4.8382-.4544v-1.0224c0-1.1361-.2251-2.0449-.6751-2.6129-.4501-.568-1.2377-.7952-2.1378-.7952-.6751 0-1.2377.1136-1.6878.2272s-.7876.3408-1.1252.568c-.3375.2272-.6751.3408-.9001.568-.3375.1136-.6751.2272-1.0126.2272-.2251 0-.5626-.1136-.6751-.2272-.2251-.1136-.3376-.3408-.4501-.568v-.1136c-.4501-.7952-.225-1.7041.4501-2.1585 1.6877-1.136 3.713-1.8177 5.9633-1.8177 1.0127 0 1.9128.1137 2.7004.4545.7877.3408 1.4628.7952 2.0253 1.3632.5626.568.9002 1.2497 1.2377 2.1585.3376.7952.4501 1.7041.4501 2.7265v9.2019zm-7.9887-.9088c.45 0 .7876 0 1.1251-.1136.3376-.1136.6751-.2272 1.0127-.3408.3375-.1136.6751-.3408.9001-.5681.3376-.2272.5626-.4544.9002-.7952v-2.8401c-1.2377 0-2.2504.1136-3.038.2272s-1.4627.3408-1.9128.568c-.45.2273-.7876.5681-1.0126.7953-.2251.3408-.3376.6816-.3376 1.0224 0 .6816.2251 1.2497.6751 1.5905.4501.3408 1.0127.4544 1.6878.4544z"/>
				<path d="m88.2024 33.8918v-13.8597c0-.9088.7876-1.704 1.6877-1.704h.7877c.45 0 .6751.1136.9001.2272.1125.1136.225.4544.3375.7952l.2251 2.0449c.5626-1.0225 1.3502-1.9313 2.1378-2.4993s1.8003-.9089 2.8129-.9089h.4501c.9001.1136 1.5752 1.0225 1.4627 1.9313l-.3375 1.7041c0 .2272-.1126.3408-.2251.4544s-.225.1136-.4501.1136c-.1125 0-.3375 0-.6751-.1136-.3375-.1136-.6751-.1136-1.1251-.1136-.9002 0-1.5753.2272-2.1378.6816-.5626.4544-1.1252 1.1361-1.5753 2.0449v9.0883c0 .9088-.7876 1.7041-1.6877 1.7041h-.9002c-.9001 0-1.6877-.6817-1.6877-1.5905z"/>
				<path d="m112.619 21.7363c-.113.1136-.225.2272-.338.3408s-.338.1136-.563.1136-.45-.1136-.562-.2272c-.225-.1136-.45-.2272-.675-.4544-.225-.1136-.563-.3408-1.013-.4544-.337-.1137-.9-.2273-1.463-.2273-.675 0-1.35.1136-1.912.3409-.563.2272-1.013.6816-1.351 1.136-.337.4544-.675 1.136-.787 1.8177-.225.6816-.225 1.4768-.225 2.3856 0 .9089.112 1.7041.337 2.4993.225.6817.45 1.3633.788 1.8177.337.4544.788.9088 1.35 1.136.563.2273 1.125.3409 1.8.3409s1.126-.1136 1.576-.2273c.45-.1136.787-.3408 1.012-.568s.563-.3408.675-.568c.225-.1136.45-.2272.675-.2272.338 0 .563.1136.788.3408l.225.3408c.563.6816.45 1.8177-.337 2.3857-.113.1136-.225.2272-.225.2272-.563.3408-1.126.6816-1.688.9088-.563.2273-1.125.3409-1.8.4545-.563.1136-1.238.1136-1.801.1136-1.012 0-2.025-.2272-2.925-.5681-.9-.3408-1.688-1.0224-2.476-1.704-.675-.7952-1.237-1.7041-1.687-2.8401-.4504-1.1361-.5629-2.3857-.5629-3.7489 0-1.2497.225-2.3857.5629-3.5218.337-1.0224.9-2.0448 1.575-2.8401.675-.7952 1.575-1.3632 2.588-1.8176 1.012-.4545 2.25-.6817 3.6-.6817 1.238 0 2.363.2272 3.376.5681.45.2272.787.3408 1.238.6816.787.568 1.012 1.5904.45 2.3857z"/>
				<path d="m115.094 33.8919v-21.5848c0-.9088.787-1.7041 1.688-1.7041h.787c.9 0 1.688.7953 1.688 1.7041v7.9523c.675-.6816 1.35-1.1361 2.138-1.5905.787-.3408 1.687-.568 2.813-.568.9 0 1.8.1136 2.475.4544s1.35.7952 1.8 1.3633c.45.568.9 1.2496 1.125 2.0448.225.7953.338 1.7041.338 2.6129v9.3156c0 .9088-.788 1.704-1.688 1.704h-.787c-.901 0-1.688-.7952-1.688-1.704v-9.3156c0-1.0224-.225-1.8176-.675-2.3857-.45-.568-1.238-.9088-2.138-.9088-.788 0-1.463.2272-2.138.568-.562.3408-1.125.7953-1.688 1.2497v10.7924c0 .9088-.787 1.704-1.687 1.704h-.788c-.788-.1136-1.575-.7952-1.575-1.704z"/>
				<path d="m132.197 12.9886c-.338-1.136.45-2.1585 1.575-2.1585h1.575c.45 0 .675.1136 1.013.2272.225.2273.45.4545.562.7953l4.163 14.8821c.113.3408.225.7952.225 1.1361.113.4544.113.9088.225 1.3632.113-.4544.225-.9088.338-1.3632.112-.4545.225-.7953.338-1.1361l4.838-14.8821c.112-.2272.225-.4544.562-.6816.225-.2273.563-.3409 1.013-.3409h1.35c.45 0 .675.1136 1.013.2272.225.2273.45.4545.562.7953l4.839 14.8821c.225.6816.45 1.5905.675 2.3857.112-.4544.112-.9088.225-1.2496.112-.4545.225-.7953.225-1.1361l4.163-14.8821c.112-.3408.225-.568.562-.6816.226-.2273.563-.3409 1.013-.3409h1.238c1.125 0 1.913 1.1361 1.575 2.1585l-6.526 21.3576c-.225.6816-.9 1.2496-1.575 1.2496h-1.688c-.788 0-1.35-.4544-1.575-1.136l-5.176-15.9046c-.112-.2272-.112-.4544-.225-.6816-.112-.2272-.112-.568-.225-.7952-.112.3408-.112.568-.225.7952s-.113.4544-.225.6816l-5.063 15.791c-.225.6816-.9 1.136-1.576 1.136h-1.687c-.788 0-1.35-.4544-1.576-1.2496z"/>
				<path d="m172.69 26.8484v7.0435c0 .9088-.788 1.704-1.688 1.704h-1.238c-.9 0-1.687-.7952-1.687-1.704v-21.4712c0-.9089.787-1.7041 1.687-1.7041h6.301c1.688 0 3.038.2272 4.276.568s2.138.9089 2.925 1.5905c.788.6816 1.351 1.4768 1.688 2.4993.338 1.0224.563 2.0449.563 3.1809 0 1.2496-.225 2.2721-.563 3.2945-.45 1.0225-1.012 1.8177-1.8 2.6129-.788.6816-1.8 1.2497-2.926 1.7041-1.237.4544-2.587.568-4.163.568h-3.375zm0-3.6353h3.375c.788 0 1.576-.1136 2.138-.3408.675-.2273 1.125-.5681 1.575-.9089s.675-.9088.901-1.4768c.225-.5681.337-1.2497.337-1.9313s-.112-1.2496-.337-1.8177c-.226-.568-.563-1.0224-.901-1.3632-.45-.3408-.9-.6816-1.575-.9089-.675-.2272-1.35-.3408-2.138-.3408h-3.375z"/>
			</g>
			<g clip-path="url(#a)">
				<g clip-rule="evenodd" fill="#456b47" fill-rule="evenodd">
					<path d="m24.5846 16.0458c0-.7083-.6797-1.4326-1.6619-1.4326v-1.7192c1.7686 0 3.3811 1.3387 3.3811 3.1518v16.5043c0 1.8132-1.6125 3.1519-3.3811 3.1519h-2.4068v-1.7192h2.4068c.9822 0 1.6619-.7243 1.6619-1.4327z"/>
					<path d="m.057373 16.0458c0-1.8131 1.612567-3.1518 3.381087-3.1518v1.7192c-.98219 0-1.66189.7243-1.66189 1.4326v16.5043c0 .7084.6797 1.4327 1.66189 1.4327h2.29226v1.7192h-2.29226c-1.76852 0-3.381087-1.3387-3.381087-3.1519z"/>
					<path d="m5.94932 16.2056c.25995-.6058.52876-1.2322.83483-1.8954l1.56096.7205c-.26042.5642-.51794 1.1623-.77746 1.765-.38453.893-.77343 1.7962-1.18259 2.6145-.87996 1.7599-1.36619 3.5097-1.06846 5.1968l.00446.0254.00295.0255c.31338 2.716 1.65716 4.9094 4.10687 6.6135l-.98178 1.4113c-2.81518-1.9584-4.45017-4.5703-4.83002-7.8025-.38037-2.2007.27811-4.3385 1.22828-6.2389.40036-.8007.7427-1.5985 1.10196-2.4357z"/>
					<path d="m21.13 17.6879c.1672.3555.3376.718.5103 1.0923l.0036.0078.0035.0078c.8235 1.8824 1.467 3.8834 1.2129 6.1702l-.0008.0075c-.376 3.1333-2.0144 5.7413-4.8125 7.8094l-1.0219-1.3825c2.473-1.8278 3.8144-4.0323 4.127-6.628.2029-1.8351-.2978-3.4985-1.0763-5.2796-.1596-.3457-.3213-.6894-.4829-1.0329-.519-1.1035-1.0373-2.2055-1.4838-3.3662l1.6046-.6172c.422 1.0971.9038 2.1216 1.4163 3.2114z"/>
					<path d="m4.46831 17.2516c.28355-.3807.82208-.4595 1.20285-.176l16.16044 12.0344c.3808.2835.4596.8221.176 1.2028-.2835.3808-.822.4596-1.2028.1761l-16.16046-12.0344c-.38077-.2836-.45958-.8221-.17603-1.2029z"/>
					<path d="m22.0076 17.2516c.2836.3808.2048.9193-.176 1.2029l-16.16044 12.0344c-.38077.2835-.9193.2047-1.20285-.1761-.28355-.3807-.20474-.9193.17603-1.2028l16.16046-12.0344c.3808-.2835.9193-.2047 1.2028.176z"/>
				</g>
				<path d="m18.1089 11.2321h-9.74213c-.34384 0-.57307-.2292-.57307-.5731v-1.48995c0-1.60458 1.26075-2.75072 2.7507-2.75072h5.2722c1.6046 0 2.7507 1.26075 2.7507 2.75072v1.37535c.1147.4585-.1146.6877-.4584.6877z"
				      fill="#77a872"/>
				<path clip-rule="evenodd"
				      d="m10.5444 7.27791c-1.03889 0-1.89112.7846-1.89112 1.89112v1.20347h9.05442v-1.20347c0-1.03889-.7846-1.89112-1.8911-1.89112zm-3.61032 1.89112c0-2.10264 1.66926-3.61031 3.61032-3.61031h5.2722c2.1026 0 3.6103 1.66925 3.6103 3.61031v1.28517c.0656.3566.0348.7697-.2292 1.1217-.2951.3934-.7352.5158-1.0888.5158h-9.74215c-.36726 0-.74011-.1262-1.0233-.4094-.2832-.2832-.40937-.656-.40937-1.0233z"
				      fill="#456b47" fill-rule="evenodd"/>
				<path clip-rule="evenodd"
				      d="m5.04306 12.3209c-.32755 0-.51576.1883-.51576.5158v.6304h17.192v-.6304c0-.3275-.1882-.5158-.5158-.5158zm-2.23495.5158c0-1.277.95792-2.235 2.23495-2.235h16.16044c1.2771 0 2.235.958 2.235 2.235v.9169c0 .3673-.1262.7401-.4094 1.0233s-.656.4094-1.0233.4094h-17.76503c-.36726 0-.74011-.1262-1.0233-.4094s-.40936-.656-.40936-1.0233z"
				      fill="#456b47" fill-rule="evenodd"/>
				<path clip-rule="evenodd"
				      d="m7.73657 5.61605c0-3.11085 2.44793-5.558738 5.55873-5.558738 3.1109 0 5.5587 2.447888 5.5587 5.558738 0 .46159-.1409 1.12803-.2548 1.58384l-.2599 1.0396-.9585-.47923c-.4293-.21463-.854-.3677-1.2202-.3677h-5.8452c-.43253 0-.69723.07877-.97424.28653l-1.09117.81838-.2675-1.33748c-.02047-.10234-.04319-.21143-.06586-.32022-.03398-.16313-.06783-.32558-.0937-.46359-.04491-.23951-.08636-.50482-.08636-.76013zm5.55873-3.83954c-2.1613 0-3.83953 1.67818-3.83953 3.83954 0 .03853.003.08603.00978.14544.27521-.06286.55735-.08813.84985-.08813h5.8452c.3357 0 .6605.05655.9604.1404.009-.07754.0139-.14461.0139-.19771 0-2.16136-1.6782-3.83954-3.8396-3.83954z"
				      fill="#456b47" fill-rule="evenodd"/>
				<path d="m19.9427 39.1977h-13.63894c-1.14613 0-2.06304-.9169-2.06304-2.063v-2.7508c0-1.2607 1.03152-2.2922 2.29227-2.2922h13.18051c1.2607 0 2.2923 1.0315 2.2923 2.2922v2.7508c0 1.1461-.9169 2.063-2.0631 2.063z"
				      fill="#77a872"/>
				<path clip-rule="evenodd"
				      d="m6.53297 32.9513c-.78601 0-1.43267.6467-1.43267 1.4327v2.7507c0 .6714.53205 1.2034 1.20344 1.2034h13.63896c.6714 0 1.2034-.532 1.2034-1.2034v-2.7507c0-.786-.6466-1.4327-1.4326-1.4327zm-3.15187 1.4327c0-1.7355 1.41638-3.1519 3.15187-3.1519h13.18053c1.7355 0 3.1518 1.4164 3.1518 3.1519v2.7507c0 1.6209-1.3017 2.9226-2.9226 2.9226h-13.63896c-1.62088 0-2.92264-1.3017-2.92264-2.9226z"
				      fill="#456b47" fill-rule="evenodd"/>
			</g>
		</svg>
		<?php
	}

	/**
	 * Renders the header if SearchWP is disabled.
	 *
	 * @since 1.7.0
	 *
	 * @return void
	 */
	public function header_searchwp_disabled() {

		if ( ! Utils::is_settings_page() ) {
			return;
		}

		do_action( 'searchwp_live_search_settings_header_before' );

		self::header_searchwp_disabled_main();
		self::header_searchwp_disabled_sub();

		echo '<hr class="wp-header-end">';

		do_action( 'searchwp_live_search_settings_header_after' );
	}

	/**
	 * Renders the main header.
	 *
	 * @since 1.7.3
	 *
	 * @return void
	 */
	private static function header_searchwp_disabled_main() {

		?>
        <div class="searchwp-settings-header">
            <div class="searchwp-logo" title="SearchWP">
				<?php self::header_logo(); ?>
            </div>
            <div class="searchwp-header-actions">
				<?php do_action( 'searchwp_live_search_settings_header_actions' ); ?>
            </div>
        </div>
		<?php
	}

	/**
	 * Renders the subheader.
	 *
	 * @since 1.7.3
	 *
	 * @return void
	 */
	private static function header_searchwp_disabled_sub() {

		?>
        <div class="searchwp-settings-subheader">
            <nav class="searchwp-settings-header-nav">
                <ul>
                    <li class="searchwp-settings-nav-tab-wrapper searchwp-settings-nav-tab-active postbox-wrapper searchwp-settings-nav-tab-searchwp-live-search-wrapper">
                        <a href="https://searchwp-plugin.local/wp-admin/admin.php?page=searchwp-live-search" class="searchwp-settings-nav-tab searchwp-settings-nav-tab-active postbox searchwp-settings-nav-tab-searchwp-live-search">
                            <span><?php esc_html_e( 'Settings', 'searchwp-live-ajax-search' ); ?></span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
		<?php
	}

	/**
	 * Renders the page content if SearchWP is disabled.
	 *
	 * @since 1.7.0
	 */
	public function page_searchwp_disabled() {

		searchwp_live_search()->get( 'Settings' )->output();
	}

	/**
	 * After settings content output.
	 *
	 * @since 1.7.0
	 */
    private function output_after_settings() {

	    if ( Utils::is_searchwp_active() ) {
		    return;
	    }

	    ?>
        <div class="searchwp-settings-cta">
            <h5><?php esc_html_e( 'Get SearchWP Pro and Unlock all the Powerful Features', 'searchwp-live-ajax-search' ); ?></h5>
            <p><?php esc_html_e( 'Thank you for being a loyal SearchWP Live Ajax Search user. Upgrade to SearchWP Pro to unlock all the powerful features and experience why SearchWP is the best WordPress search plugin.', 'searchwp-live-ajax-search' ); ?></p>
            <p>
			    <?php
			    printf(
				    wp_kses( /* translators: %s - star icons. */
					    esc_html__( 'We know that you will truly love SearchWP Pro. It’s used on over 30,000 smart WordPress websites and is consistently rated 5-stars (%s) by our customers.', 'searchwp-live-ajax-search' ),
					    [
						    'i' => [
							    'class'       => [],
							    'aria-hidden' => [],
						    ],
					    ]
				    ),
				    str_repeat( '<i class="fa fa-star" aria-hidden="true"></i>', 5 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			    );
			    ?>
            </p>
            <h6><?php esc_html_e( 'Pro Features:', 'searchwp-live-ajax-search' ); ?></h6>
            <div class="list">
                <ul>
                    <li><?php esc_html_e( 'Search all custom field data', 'searchwp-live-ajax-search' ); ?></li>
                    <li><?php esc_html_e( 'Make ecommerce metadata discoverable in search results', 'searchwp-live-ajax-search' ); ?></li>
                    <li><?php esc_html_e( 'Search PDF, .doc, .txt and other static documents', 'searchwp-live-ajax-search' ); ?></li>
                    <li><?php esc_html_e( 'Search custom database tables and other custom content', 'searchwp-live-ajax-search' ); ?></li>
                    <li><?php esc_html_e( 'Make your media library (images, videos, etc.) searchable', 'searchwp-live-ajax-search' ); ?></li>
                </ul>
                <ul>
                    <li><?php esc_html_e( 'Search categories, tags and even custom taxonomies', 'searchwp-live-ajax-search' ); ?></li>
                    <li><?php esc_html_e( 'Easy integration with all WordPress themes and page builders', 'searchwp-live-ajax-search' ); ?></li>
                    <li><?php esc_html_e( 'Advanced search metrics and insights on visitor activity', 'searchwp-live-ajax-search' ); ?></li>
                    <li><?php esc_html_e( 'Multiple custom search engines for different types of content', 'searchwp-live-ajax-search' ); ?></li>
                    <li><?php esc_html_e( 'WooCommerce & Easy Digital Downloads support', 'searchwp-live-ajax-search' ); ?></li>
                </ul>
            </div>
            <p><a href="https://searchwp.com/?utm_source=WordPress&utm_medium=Settings+Upgrade+Bottom+Link&utm_campaign=Live+Ajax+Search&utm_content=Get+SearchWP+Pro+Today+and+Unlock+all+the+Powerful+Features" target="_blank" rel="noopener noreferrer" title="<?php esc_html_e( 'Get SearchWP Pro Today', 'searchwp-live-ajax-search' ); ?>"><?php esc_html_e( 'Get SearchWP Pro Today and Unlock all the Powerful Features', 'searchwp-live-ajax-search' ); ?> &raquo;</a></p>
            <p>
	            <?php
	            echo wp_kses(
		            __( '<strong>Bonus:</strong> SearchWP Live Ajax Search users get <span class="green">50% off the regular price</span>, automatically applied at checkout!', 'searchwp-live-ajax-search' ),
		            [
			            'strong' => [],
			            'span'   => [
				            'class' => [],
			            ],
		            ]
	            );
	            ?>
            </p>
        </div>
	    <?php
    }

	/**
	 * When user is on a SearchWP related admin page, display footer text
	 * that graciously asks them to rate us.
	 *
	 * @since 1.7.0
	 *
	 * @param string $text Footer text.
	 *
	 * @return string
	 */
	public function admin_footer_rate_us_searchwp_disabled( $text ) {

		global $current_screen;

		if ( empty( $current_screen->id ) || strpos( $current_screen->id, 'searchwp-live-search' ) === false ) {
			return $text;
		}

		$url = 'https://wordpress.org/support/plugin/searchwp-live-ajax-search/reviews/?filter=5#new-post';

		return sprintf(
			wp_kses( /* translators: $1$s - SearchWP plugin name; $2$s - WP.org review link; $3$s - WP.org review link. */
				__( 'Please rate %1$s <a href="%2$s" target="_blank" rel="noopener noreferrer">&#9733;&#9733;&#9733;&#9733;&#9733;</a> on <a href="%3$s" target="_blank" rel="noopener">WordPress.org</a> to help us spread the word. Thank you from the SearchWP team!', 'searchwp-live-ajax-search' ),
				[
					'a' => [
						'href'   => [],
						'target' => [],
						'rel'    => [],
					],
				]
			),
			'<strong>SearchWP Live Ajax Search</strong>',
			$url,
			$url
		);
	}

	/**
	 * Hide the wp-admin area "Version x.x" in footer on SearchWP pages.
	 *
	 * @since 1.7.0
	 *
	 * @param string $text Default "Version x.x" or "Get Version x.x" text.
	 *
	 * @return string
	 */
	public function admin_footer_hide_wp_version_searchwp_disabled( $text ) {

		// Reset text if we're not on a SearchWP screen or page.
		if ( Utils::is_settings_page() ) {
			return '';
		}

		return $text;
	}

	/**
	 * Output "Did you know" block.
	 *
	 * @since 1.7.0
	 *
	 * @return string
	 */
	public static function get_dyk_block_output() {

		if ( Utils::is_searchwp_active() ) {
			return '';
		}

		ob_start();

		?>
        <div class="searchwp-settings-dyk">
            <h5><?php esc_html_e( 'Did You Know?', 'searchwp-live-ajax-search' ); ?></h5>
            <p>
	            <?php
	            echo wp_kses(
		            __( 'By default, WordPress doesn’t make all your content searchable. <strong><em>That’s frustrating</em></strong>, because it leaves your visitors unable to find what they are looking for!', 'searchwp-live-ajax-search' ),
		            [
			            'strong' => [],
			            'em'     => [],
		            ]
	            );
	            ?>
            </p>
            <p><?php esc_html_e( 'With SearchWP Pro, you can overcome this obstacle and deliver the best, most relevant search results based on all your content, such as custom fields, ecommerce data, categories, PDF documents, rich media and more!', 'searchwp-live-ajax-search' ); ?></p>
            <p><a href="https://searchwp.com/?utm_source=WordPress&utm_medium=Settings+Did+You+Know+Upgrade+Link&utm_campaign=Live+Ajax+Search&utm_content=Get+SearchWP+Pro+Today" target="_blank" rel="noopener noreferrer" title="<?php esc_html_e( 'Get SearchWP Pro Today', 'searchwp-live-ajax-search' ); ?>"><?php esc_html_e( 'Get SearchWP Pro Today', 'searchwp-live-ajax-search' ); ?> &raquo;</a></p>
            <p>
	            <?php
	            echo wp_kses(
		            __( '<strong>Bonus:</strong> SearchWP Live Ajax Search users get <span class="green">50% off the regular price</span>, automatically applied at checkout!', 'searchwp-live-ajax-search' ),
		            [
			            'strong' => [],
			            'span'   => [
				            'class' => [],
			            ],
		            ]
	            );
	            ?>
            </p>
        </div>
		<?php

		return ob_get_clean();
	}

	/**
	 * Remove non-SearchWP notices from SearchWP pages.
	 *
	 * @since 1.7.3
	 */
	public function admin_hide_unrelated_notices() {

		if ( ! Utils::is_settings_page() ) {
			return;
		}

		global $wp_filter;

		// Define rules to remove callbacks.
		$rules = [
			'user_admin_notices' => [], // remove all callbacks.
			'admin_notices'      => [],
			'all_admin_notices'  => [],
			'admin_footer'       => [
				'render_delayed_admin_notices', // remove this particular callback.
			],
		];

		$notice_types = array_keys( $rules );

		foreach ( $notice_types as $notice_type ) {
			if ( empty( $wp_filter[ $notice_type ]->callbacks ) || ! is_array( $wp_filter[ $notice_type ]->callbacks ) ) {
				continue;
			}

			$remove_all_filters = empty( $rules[ $notice_type ] );

			foreach ( $wp_filter[ $notice_type ]->callbacks as $priority => $hooks ) {
				foreach ( $hooks as $name => $arr ) {
					if ( is_object( $arr['function'] ) && is_callable( $arr['function'] ) ) {
						if ( $remove_all_filters ) {
							unset( $wp_filter[ $notice_type ]->callbacks[ $priority ][ $name ] );
						}
						continue;
					}

					$class = '';
					if ( ! empty( $arr['function'][0] ) && is_object( $arr['function'][0] ) ) {
						$class = strtolower( get_class( $arr['function'][0] ) );
					}
					if ( ! empty( $arr['function'][0] ) && is_string( $arr['function'][0] ) ) {
						$class = strtolower( $arr['function'][0] );
					}

					// Remove all callbacks except SearchWP notices.
					if ( $remove_all_filters && strpos( $class, 'searchwp' ) === false ) {
						unset( $wp_filter[ $notice_type ]->callbacks[ $priority ][ $name ] );
						continue;
					}

					$cb = is_array( $arr['function'] ) ? $arr['function'][1] : $arr['function'];

					// Remove a specific callback.
					if ( ! $remove_all_filters && in_array( $cb, $rules[ $notice_type ], true ) ) {
						unset( $wp_filter[ $notice_type ]->callbacks[ $priority ][ $name ] );
					}
				}
			}
		}
	}
}
