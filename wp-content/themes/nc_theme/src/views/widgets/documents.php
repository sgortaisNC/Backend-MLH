<?php
/**
 * @var array $documents
 * @see NC_Documents_Widget
 */
?>

<?php if (!empty(count($documents) > 0)): ?>

    <div class="">
        <div class="">
            Document<?= (count($documents) > 1) ? 's' : null ?> utile<?= (count($documents) > 1) ? 's' : null ?>
        </div>
        <ul>
            <?php foreach ($documents as $document): ?>
                <?php if(!empty($document['fichier']) && !empty($document['titre'])) : ?>
                    <li class="annexe__document">

                        <a href="<?= wp_get_attachment_url($document['id']); ?>" target="_blank">
                            <?= $document['titre']; ?>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>

<?php endif; ?>