<?php

class NC_Documents_Widget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct('documents', 'Documents');
    }

    public function widget($args, $instance)
    {
        $documents = [];
        $documentsField = get_field('documents');

        if(!empty($documentsField)){
            foreach ($documentsField as $document) {
                if (!empty($document['document'])) {
                    $documents[$document['document']['ID']] = [
                        'fichier' => $document['document'] ? $document['document']['ID'] : null,
                        'titre' => $document['titre'] ? $document['titre'] : $document['document']['title'],
                        'id' => $document['document']['ID'],
                    ];
                }
            }
        }

        if (!empty($documents)) {
            render('widgets/documents', [
                'documents' => $documents
            ]);
        }
    }
}
