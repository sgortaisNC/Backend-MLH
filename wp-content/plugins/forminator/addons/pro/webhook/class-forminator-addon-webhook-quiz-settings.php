<?php

require_once dirname( __FILE__ ) . '/class-forminator-addon-webhook-quiz-settings-exception.php';

/**
 * Class Forminator_Addon_Webhook_Quiz_Settings
 * Handle how quiz settings displayed and saved
 *
 * @since 1.6.2
 */
class Forminator_Addon_Webhook_Quiz_Settings extends Forminator_Addon_Quiz_Settings_Abstract {

	/**
	 * @var Forminator_Addon_Webhook
	 *
	 */
	protected $addon;

	/**
	 * Forminator_Addon_Webhook_Form_Settings constructor.
	 *
	 *
	 *
	 * @param Forminator_Addon_Abstract $addon
	 * @param                           $form_id
	 *
	 * @throws Forminator_Addon_Exception
	 */
	public function __construct( Forminator_Addon_Abstract $addon, $form_id ) {
		parent::__construct( $addon, $form_id );

		$this->_update_quiz_settings_error_message = esc_html__(
			'The update to your settings for this quiz failed, check the form input and try again.',
			'forminator'
		);
	}

	/**
	 * Webhook Quiz Settings wizard
	 *
	 * @since 1.6.2
	 * @return array
	 */
	public function quiz_settings_wizards() {
		// numerical array steps.
		return array(
			// 0
			array(
				'callback'     => array( $this, 'setup_webhook_url' ),
				'is_completed' => array( $this, 'setup_webhook_url_is_completed' ),
			),
		);
	}

	/**
	 * Setup webhook url
	 *
	 *
	 *
	 * @param $submitted_data
	 *
	 * @return array
	 */
	public function setup_webhook_url( $submitted_data ) {

		$template = forminator_addon_webhook_dir() . 'views/quiz-settings/setup-webhook.php';

		if ( ! isset( $submitted_data['multi_id'] ) ) {
			return $this->get_force_closed_wizard( esc_html__( 'Please pick valid connection', 'forminator' ) );
		}

		$multi_id = $submitted_data['multi_id'];
		unset( $submitted_data['multi_id'] );

		$template_params = array(
			'name'        => $this->get_multi_id_quiz_settings_value( $multi_id, 'name', '' ),
			'webhook_url' => $this->get_multi_id_quiz_settings_value( $multi_id, 'webhook_url', '' ),
			'multi_id'    => $multi_id,
		);

		$is_submit    = ! empty( $submitted_data );
		$has_errors   = false;
		$is_close     = false;
		$notification = array();

		if ( $is_submit ) {
			$name                    = isset( $submitted_data['name'] ) ? trim( $submitted_data['name'] ) : '';
			$template_params['name'] = $name;

			$webhook_url                    = isset( $submitted_data['webhook_url'] ) ? trim( $submitted_data['webhook_url'] ) : '';
			$template_params['webhook_url'] = $webhook_url;

			try {
				$input_exceptions = new Forminator_Addon_Webhook_Quiz_Settings_Exception();

				if ( empty( $name ) ) {
					$input_exceptions->add_input_exception( esc_html__( 'Please specify integration name.', 'forminator' ), 'name_error' );
				}

				$this->validate_and_send_sample( $submitted_data, $input_exceptions );

				if ( $input_exceptions->input_exceptions_is_available() ) {
					throw $input_exceptions;
				}

				$time_added = $this->get_multi_id_quiz_settings_value( $multi_id, 'time_added', time() );
				$this->save_multi_id_quiz_setting_values(
					$multi_id,
					array(
						'name'        => $name,
						'webhook_url' => $webhook_url,
						'time_added'  => $time_added,
					)
				);

				$notification = array(
					'type' => 'success',
					'text' => '<strong>' . $this->addon->get_title() . '</strong> ' . esc_html__( 'Successfully connected and sent sample data to your Webhook', 'forminator' ),
				);
				$is_close     = true;

			} catch ( Forminator_Addon_Webhook_Quiz_Settings_Exception $e ) {
				$template_params = array_merge( $template_params, $e->get_input_exceptions() );
				$has_errors      = true;
			} catch ( Forminator_Addon_Webhook_Exception $e ) {
				$template_params['error_message'] = $e->getMessage();
				$has_errors                       = true;
			}
		}

		$buttons = array();
		if ( $this->setup_webhook_url_is_completed( array( 'multi_id' => $multi_id ) ) ) {
			$buttons['disconnect']['markup'] = Forminator_Addon_Webhook::get_button_markup(
				esc_html__( 'Deactivate', 'forminator' ),
				'sui-button-ghost sui-tooltip sui-tooltip-top-center forminator-addon-form-disconnect',
				esc_html__( 'Deactivate Webhook from this Quiz.', 'forminator' )
			);
		}

		$buttons['next']['markup'] = '<div class="sui-actions-right">' .
									Forminator_Addon_Webhook::get_button_markup( esc_html__( 'Save', 'forminator' ), 'sui-button-primary forminator-addon-finish' ) .
									'</div>';

		return array(
			'html'         => Forminator_Addon_Abstract::get_template( $template, $template_params ),
			'buttons'      => $buttons,
			'redirect'     => false,
			'has_errors'   => $has_errors,
			'has_back'     => false,
			'is_close'     => $is_close,
			'notification' => $notification,
		);

	}


	/**
	 * Sending test sample towebhook URL
	 * Data sent will be used onwebhook to map fields on their zap action
	 *
	 *
	 *
	 * @param                                                 $submitted_data
	 * @param Forminator_Addon_Webhook_Quiz_Settings_Exception $current_input_exception
	 *
	 * @throws Forminator_Addon_Webhook_Quiz_Settings_Exception
	 * @throws Forminator_Addon_Webhook_Wp_Api_Not_Found_Exception
	 * @throws Forminator_Addon_Webhook_Wp_Api_Exception
	 */
	private function validate_and_send_sample( $submitted_data, Forminator_Addon_Webhook_Quiz_Settings_Exception $current_input_exception ) {
		$quiz_id = $this->quiz_id;
		if ( ! isset( $submitted_data['webhook_url'] ) ) {
			$current_input_exception->add_input_exception( esc_html__( 'Please put a valid Webhook URL.', 'forminator' ), 'webhook_url_error' );
			throw $current_input_exception;
		}

		// must not be in silent mode.
		if ( stripos( $submitted_data['webhook_url'], 'silent' ) !== false ) {
			$current_input_exception->add_input_exception( esc_html__( 'Please disable Silent Mode on Webhook URL.', 'forminator' ), 'webhook_url_error' );
			throw $current_input_exception;
		}

		$endpoint = wp_http_validate_url( $submitted_data['webhook_url'] );
		if ( false === $endpoint ) {
			$current_input_exception->add_input_exception( esc_html__( 'Please put a valid Webhook URL.', 'forminator' ), 'webhook_url_error' );
			throw $current_input_exception;
		}

		if ( $current_input_exception->input_exceptions_is_available() ) {
			throw $current_input_exception;
		}

		$connection_settings = $submitted_data;
		/**
		 * Filter Endpoint Webhook URL to send
		 *
		 * @since 1.6.2
		 *
		 * @param string $endpoint
		 * @param int    $quiz_id             current Form ID.
		 * @param array  $connection_settings Submitted data by user, it contains `name` and `webhook_url`.
		 */
		$endpoint = apply_filters_deprecated(
			'forminator_addon_zapier_quiz_endpoint',
			array( $endpoint, $quiz_id, $connection_settings ),
			'1.18.0',
			'forminator_addon_webhook_quiz_endpoint'
		);
		$endpoint = apply_filters(
			'forminator_addon_webhook_quiz_endpoint',
			$endpoint,
			$quiz_id,
			$connection_settings
		);

		forminator_addon_maybe_log( __METHOD__, $endpoint );
		$api = $this->addon->get_api( $endpoint );

		// build form sample data.
		$sample_data            = $this->build_form_sample_data();
		$sample_data            = self::replace_dashes_in_keys( $sample_data, $endpoint );
		$sample_data['is_test'] = true;

		/**
		 * Filter sample data to send to Webhook URL
		 *
		 * It fires when user saved Webhook connection on Form Settings Page.
		 * Sample data contains `is_test` key with value `true`,
		 * this key indicating that it wont process trigger on Webhook.
		 *
		 * @since 1.6.2
		 *
		 * @param array $sample_data
		 * @param int   $quiz_id        current Form ID.
		 * @param array $submitted_data Submitted data by user, it contains `name` and `webhook_url`.
		 */
		$sample_data = apply_filters_deprecated(
			'forminator_addon_zapier_quiz_sample_data',
			array( $sample_data, $quiz_id, $submitted_data ),
			'1.18.0',
			'forminator_addon_webhook_quiz_sample_data'
		);
		$sample_data = apply_filters(
			'forminator_addon_webhook_quiz_sample_data',
			$sample_data,
			$quiz_id,
			$submitted_data
		);

		$api->post_( $sample_data );
	}

	/**
	 * Build sample data form current fields
	 *
	 * @since 1.6.2
	 *
	 * @return array
	 */
	private function build_form_sample_data() {
		$sample = array();

		$sample['quiz-name'] = forminator_get_name_from_model( $this->quiz );
		$answers             = array();

		$num_correct = 0;

		$questions = $this->quiz->questions;

		foreach ( $questions as $question ) {
			$question_title = isset( $question['title'] ) ? $question['title'] : '';
			$question_id    = isset( $question['slug'] ) ? $question['slug'] : uniqid();

			// bit cleanup.
			$question_id  = str_replace( 'question-', '', $question_id );
			$answer_title = 'Sample Answer';

			$answer = array(
				'question' => $question_title,
				'answer'   => $answer_title,
			);

			if ( 'knowledge' === $this->quiz->quiz_type ) {
				$answer['is_correct'] = wp_rand( 0, 1 ) ? true : false;

				if ( $answer['is_correct'] ) {
					$num_correct ++;
				}
			}

			$answers[ $question_id ] = $answer;
		}

		$sample['answers'] = $answers;
		$result            = array();

		if ( 'knowledge' === $this->quiz->quiz_type ) {
			$result['correct'] = $num_correct;
			$result['answers'] = count( $answers );

		} elseif ( 'nowrong' === $this->quiz->quiz_type ) {
			$results           = $this->quiz->results;
			$random_result_key = array_rand( $results );
			$result_title      = ( ( isset( $results[ $random_result_key ] ) && isset( $results[ $random_result_key ]['title'] ) ) ? $results[ $random_result_key ]['title'] : '' );
			$result['result']  = $result_title;
		}

		$sample['result'] = $result;

		$form_fields = isset( $this->form_fields ) ? $this->form_fields : array();
		if ( ! empty( $form_fields ) ) {
			foreach ( $form_fields as $form_field ) {
				$sample[ $form_field['element_id'] ] = $form_field['field_label'];

				if ( 'upload' === $form_field['type'] ) {

					$sample_file_path = '/fake/path';
					$upload_dir       = wp_get_upload_dir();
					if ( isset( $upload_dir['basedir'] ) ) {
						$sample_file_path = $upload_dir['basedir'];
					}

					$sample[ $form_field['element_id'] ] = array(
						'name'      => $form_field['field_label'],
						'type'      => 'image/png',
						'size'      => 0,
						'file_url'  => get_home_url(),
						'file_path' => $sample_file_path,
					);
				}
			}
		}

		return $sample;
	}

	/**
	 * Check if setup webhook url is completed
	 *
	 * @since 1.6.2
	 *
	 * @param $submitted_data
	 *
	 * @return bool
	 */
	public function setup_webhook_url_is_completed( $submitted_data ) {
		$multi_id = '';
		if ( isset( $submitted_data['multi_id'] ) ) {
			$multi_id = $submitted_data['multi_id'];
		}

		if ( empty( $multi_id ) ) {
			return false;
		}

		$name = $this->get_multi_id_quiz_settings_value( $multi_id, 'name', '' );
		$name = trim( $name );
		if ( empty( $name ) ) {
			return false;
		}
		$webhook_url = $this->get_multi_id_quiz_settings_value( $multi_id, 'webhook_url', '' );
		$webhook_url = trim( $webhook_url );
		if ( empty( $webhook_url ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Generate multi id for multiple connection
	 *
	 * @since 1.6.2
	 * @return string
	 */
	public function generate_multi_id() {
		return uniqid( 'webhook_', true );
	}


	/**
	 * Override how multi connection displayed
	 *
	 * @since 1.6.2
	 * @return array
	 */
	public function get_multi_ids() {
		$multi_ids = array();
		foreach ( $this->get_quiz_settings_values() as $key => $value ) {
			$multi_ids[] = array(
				'id'    => $key,
				// use name that was added by user on creating connection.
				'label' => isset( $value['name'] ) ? $value['name'] : $key,
			);
		}

		return $multi_ids;
	}

	/**
	 * Disconnect a connection from current quiz
	 *
	 * @since 1.6.2
	 *
	 * @param array $submitted_data
	 */
	public function disconnect_form( $submitted_data ) {
		// only execute if multi_id provided on submitted data.
		if ( isset( $submitted_data['multi_id'] ) && ! empty( $submitted_data['multi_id'] ) ) {
			$addon_quiz_settings = $this->get_quiz_settings_values();
			unset( $addon_quiz_settings[ $submitted_data['multi_id'] ] );
			$this->save_quiz_settings_values( $addon_quiz_settings );
		}
	}

	/**
	 * Check if multi_id quiz settings values completed
	 *
	 * @since 1.6.2
	 *
	 * @param $multi_id
	 *
	 * @return bool
	 */
	public function is_multi_quiz_settings_complete( $multi_id ) {
		$data = array( 'multi_id' => $multi_id );

		if ( ! $this->setup_webhook_url_is_completed( $data ) ) {
			return false;
		}

		return true;
	}
}