<?php
/**
 * @var int $id
 * @var array $items
 * @see NC_Accordion_Widget::render()
 */
?>

<div id="accordion_<?= $id; ?>" class="accordion">
    <?php if ( !empty($items) ) : ?>
        <?php foreach ( $items as $key => $item ) : ?>
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading_<?= $id; ?>_<?= $key; ?>">
                    <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapse_<?= $id; ?>_<?= $key; ?>" aria-expanded="false" aria-controls="collapse_<?= $id; ?>_<?= $key; ?>">
                        <?= $item['titre']; ?>
                    </button>
                </h2>
                <div id="collapse_<?= $id; ?>_<?= $key; ?>" class="accordion-collapse collapse" aria-labelledby="heading_<?= $id; ?>_<?= $key; ?>" data-bs-parent="#accordion_<?= $id; ?>">
                    <div class="accordion-body">
                        <?= $item['contenu']; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading_<?= $id; ?>_1">
                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapse_<?= $id; ?>_1" aria-expanded="false" aria-controls="collapse_<?= $id; ?>_1">
                    Lorem ipsum dolor sit amet
                </button>
            </h2>
            <div id="collapse_<?= $id; ?>_1" class="accordion-collapse collapse" aria-labelledby="heading_<?= $id; ?>_1" data-bs-parent="#accordion_<?= $id; ?>">
                <div class="accordion-body">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris ut fermentum diam. Donec sodales nunc vitae dictum mollis.
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
