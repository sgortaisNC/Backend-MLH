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
        $submission_data = do_shortcode( '[forminator_form id="'.$form_id.'"]');

        return rest_ensure_response( $submission_data);
    }

    static function submit_form(  )
    {
        return "En cours de développement";
    }
}