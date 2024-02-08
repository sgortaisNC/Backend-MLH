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

    static function submit_form()
    {

        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );

        $id = $_POST['form_id'];
        unset($_POST['form_id']);

        $data = [];
        foreach ($_POST as $key => $value) {
            $data[] = [
                "name" => $key,
                "value" => $value
            ];
        }
        foreach ($_FILES as $key => $file) {
            $upload_overrides = array('test_form' => false);
            $movefile = wp_handle_upload($file, $upload_overrides);

            if ($movefile && !isset($movefile['error'])) {
                $data[] = [
                    "name" => $key,
                    "value" => array(
                        'file' => array(
                            'success'   => true,
                            'file_url'  => $movefile['url'],
                            'file_path' => '',
                        ),
                    ),
                ];
            }
        }

        $entry_id = Forminator_API::add_form_entry($id, $data);

        if (is_numeric($entry_id)) {
            $forminator_mail_sender = new Forminator_CForm_Front_Mail();
            $entry = new Forminator_Form_Entry_Model($entry_id);
            $module_id = $entry->form_id;

            $module_object = Forminator_Base_Form_Model::get_model($module_id);
            $forminator_mail_sender->process_mail($module_object, $entry);
            return $entry_id;
        } else {
            return false;
        }
    }
}
