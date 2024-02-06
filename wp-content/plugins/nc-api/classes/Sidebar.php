<?php

/**
 * Class Sidebar
 */
class Sidebar
{
    static function sidebar() : array
    {
        $documents = [];
        $id = $_GET['id'] ?? null;

        $documentsField = get_field('documents', $id);

        if(!empty($documentsField)){
            foreach ($documentsField as $document) {
                if (!empty($document['document'])) {
                    $documents[] = [
                        'id' => $document['document'] ? $document['document']['ID'] : null,
                        'titre' => $document['titre'] ? $document['titre'] : $document['document']['title'],
                        'lien' => wp_get_attachment_url($document['document']['ID']),
                    ];
                }
            }
        }

        $links = [];
        $liens = get_field('liens', $id);

        if((!empty($liens))){
            foreach ($liens as $lien) {
                $links[] = [
                    'url' =>  $lien['url'] ?? null,
                    'titre' => empty($lien['titre']) ? $lien['url'] : $lien['titre'],
                ];
            }
        }

        return [
            'documents' => $documents,
            'liens' => $links
        ];
    }
}