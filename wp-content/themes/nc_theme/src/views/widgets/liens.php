<?php
/**
 * @var array $liens
 * @see NC_Liens_Widget
 */
?>

<?php if (!empty(count($liens) > 0)): ?>

    <div class="annexe">
        <div class="annexe__title">
            Lien<?= (count($liens) > 1) ? 's' : null ?> utile<?= (count($liens) > 1) ? 's' : null ?>
        </div>
        <ul>
            <?php foreach ($liens as $lien): ?>
                <?php if(!empty($lien['url']) && !empty($lien['titre'])) : ?>
                    <li class="annexe__lien">

                        <a href="<?= $lien['url'] ?>" target="_blank">
                            <?= $lien['titre']; ?>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>

<?php endif; ?>