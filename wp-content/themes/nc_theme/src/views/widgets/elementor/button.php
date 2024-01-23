<?php
/**
 * @var string $titre
 * @var string $lien
 * @var string $style
 * @see NC_Button_Widget::render()
 */
?>

<a href="<?= $lien; ?>" class="btn btn-<?= $style; ?>" target="<?= strpos($lien, get_home_url()) === false ? '_blank' : '_self'; ?>">
    <?= $titre; ?>
</a>
