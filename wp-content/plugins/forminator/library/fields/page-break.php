<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Class Forminator_PageBreak
 *
 * @since 1.0
 */
class Forminator_Page_Break extends Forminator_Field {

	/**
	 * @var string
	 */
	public $name = '';

	/**
	 * @var string
	 */
	public $slug = 'page-break';

	/**
	 * @var string
	 */
	public $type = 'page-break';

	/**
	 * @var int
	 */
	public $position = 18;

	/**
	 * @var array
	 */
	public $options = array();

	/**
	 * @var string
	 */
	public $category = 'standard';

	/**
	 * @var string
	 */
	public $hide_advanced = 'true';

	/**
	 * @var string
	 */
	public $icon = 'sui-icon forminator-icon-pagination';

	/**
	 * Forminator_Pagination constructor.
	 *
	 * @since 1.0
	 */
	public function __construct() {

		parent::__construct();

		$this->name = esc_html__( 'Page Break', 'forminator' );

	}

	/**
	 * Field defaults
	 *
	 * @since 1.0
	 * @return array
	 */
	public function defaults() {
		return apply_filters(
			'forminator_page_break_btn_label',
			array(
				'btn_left'  => esc_html__( '« Previous Step', 'forminator' ),
				'btn_right' => esc_html__( 'Next Step »', 'forminator' ),
			)
		);
	}

	/**
	 * Autofill Setting
	 *
	 * @since 1.0.5
	 *
	 * @param array $settings
	 *
	 * @return array
	 */
	public function autofill_settings( $settings = array() ) {
		// Unsupported Autofill.
		$autofill_settings = array();

		return $autofill_settings;
	}
}