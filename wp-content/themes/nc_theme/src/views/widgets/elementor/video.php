<?php
/**
 * @var string $video
 * @var string $legende
 * @see NC_Video_Widget::render()
 */
?>

<div class="video">
    <iframe src="https://www.youtube.com/embed/<?= $video; ?>" allowfullscreen></iframe>
    <?php if ( !empty($legende) ) : ?>
        <span><?= $legende; ?></span>
    <?php endif; ?>
</div>
