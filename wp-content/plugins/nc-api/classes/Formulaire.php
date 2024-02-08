<?php

/**
 * Class Formulaire
 */
class Formulaire
{

    static function liste_formulaires()
    {
        $forms = Forminator_API::get_forms();
        $formulaires = [];

        foreach ($forms as $key => $form) {
            $formulaires[] = [
                'id' => $form->id,
                'title' => $form->name,
            ];
        }

        return $formulaires;
    }

    static function formulaire_by_id()
    {
        $form_id = $_GET['id'] ?? null;
//        $submission_data = Forminator_API::get_form( $form_id );
        $submission_data = do_shortcode('[forminator_form id="' . $form_id . '"]');

        return rest_ensure_response($submission_data);
    }

    static function submit_form(WP_REST_Request $request)
    {


        $id = $_POST['form_id'];
        unset($_POST['form_id']);
        $data = [];
        foreach ($_POST as $key => $value) {
            $data[] = [
                "name" => $key,
                "value" => $value,
            ];
        }
        foreach ($_FILES as $key => $file) {
            $upload_overrides = array('test_form' => false);
            $movefile = wp_handle_upload($file, $upload_overrides);
            if ($movefile && !isset($movefile['error'])) {
                // File successfully uploaded, $movefile['file'] contains the path to the uploaded file
                $data[] = [
                    "name" => $key,
                    "value" => $movefile['file'],
                ];
            }
        }


        $entry_id = Forminator_API::add_form_entry($id, $data);

        if (is_numeric($entry_id)) {
            $forminator_mail_sender = new Forminator_CForm_Front_Mail();
            $entry = new Forminator_Form_Entry_Model($entry_id);
            if (empty($entry->form_id) || !empty($entry->draft_id)) {
                wp_send_json_error(esc_html__('Entry ID was not found.', 'forminator'));
            }
            $module_id = $entry->form_id;

            Forminator_Front_Action::$module_id = $module_id;
            Forminator_Front_Action::$module_object = Forminator_Base_Form_Model::get_model($module_id);
            // Emulate Forminator_Front_Action::$prepared_data.
            Forminator_Front_Action::$prepared_data = recreate_prepared_data(Forminator_Front_Action::$module_object, $entry);
            // Emulate Forminator_Front_Action::$hidden_fields.
            if (!Forminator_Front_Action::$module_object) {
                wp_send_json_error(esc_html__('Error: Module object is corrupted!', 'forminator'));
            }
            Forminator_Front_Action::$module_settings = method_exists(Forminator_Front_Action::$module_object, 'get_form_settings')
                ? Forminator_Front_Action::$module_object->get_form_settings() : Forminator_Front_Action::$module_object->settings;
            Forminator_CForm_Front_Action::check_fields_visibility();

            $module_object = Forminator_Base_Form_Model::get_model($module_id);
            $forminator_mail_sender->process_mail($module_object, $entry);
            return $entry_id;
        } else {
            return false;
        }
    }
}
