<?php

add_action('rest_api_init', function() {
    register_rest_route('nc/v1', '/forms', [
        'methods' => 'GET',
        'permission_callback' => '__return_true',
        'callback' => function() {
            $data = [];

            if ( class_exists('Forminator_API') ) {
                $forms = Forminator_API::get_forms();

                if ( !empty($forms) ) {
                    foreach ( $forms as $form ) {
                        $data[] = [
                            'id' => $form->id,
                            'label' => "{$form->settings['formName']} ({$form->id})",
                            'value' => $form->id,
                        ];
                    }

                    array_multisort(array_column($data, 'label'), SORT_DESC, $data);
                }
            }

            return $data;
        },
    ]);
});
