<?php

/**
 * Class Forminator_Addon_Mailjet_Form_Settings_Exception
 * Wrapper of Form Settings Mailjet Exception
 */
class Forminator_Addon_Mailjet_Quiz_Settings_Exception extends Forminator_Addon_Mailjet_Exception {

	/**
	 * Holder of input exceptions
	 *
	 * @var array
	 */
	protected $input_exceptions = array();

	/**
	 * Forminator_Addon_Mailjet_Form_Settings_Exception constructor.
	 *
	 * Useful if input_id is needed for later.
	 * If no input_id needed, use @see Forminator_Addon_Mailjet_Exception
	 *
	 * @param string $message
	 * @param string $input_id
	 */
	public function __construct( $message = '', $input_id = '' ) {
		parent::__construct( $message, 0 );
		if ( ! empty( $input_id ) ) {
			$this->add_input_exception( $message, $input_id );
		}
	}

	/**
	 * Set exception message for an input
	 *
	 * @param $message
	 * @param $input_id
	 */
	public function add_input_exception( $message, $input_id ) {
		$this->input_exceptions[ $input_id ] = $message;
	}

	/**
	 * Set exception message for an address input
	 *
	 * @param $message
	 * @param $input_id
	 * @param $sub_input
	 */
	public function add_sub_input_exception( $message, $input_id, $sub_input ) {
		$this->input_exceptions[ $input_id ][ $sub_input ] = $message;
	}

	/**
	 * Get all input exceptions
	 *
	 * @return array
	 */
	public function get_input_exceptions() {
		return $this->input_exceptions;
	}

	/**
	 * Check if there is input_exceptions_is_available
	 *
	 * @return bool
	 */
	public function input_exceptions_is_available() {
		return count( $this->input_exceptions ) > 0;
	}
}