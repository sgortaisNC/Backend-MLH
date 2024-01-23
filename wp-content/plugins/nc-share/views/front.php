<?php
$options = [
    'ncshare_fb' => get_option('ncshare_fb'),
    'ncshare_twitter' => get_option('ncshare_twitter'),
    'ncshare_linkedin' => get_option('ncshare_linkedin'),
    'ncshare_pinterest' => get_option('ncshare_pinterest'),
    'ncshare_mail' => get_option('ncshare_mail'),
];

?>

<div>
    <?php if (get_option('ncshare_print') === '1'): ?>
    <span class="share-btn" id="share-print" onclick="window.print()"><i class="fas fa-print"></i></span>
    <?php endif ?>

    <?php if (get_option('ncshare_mail') === '1'): ?>
    <a href="mailto:?subject=<?= get_the_title(); ?>&body=<?= get_permalink(); ?>" id="share-email"><i class="fal fa-envelope"></i></a>
    <?php endif ?>

    <?php if (get_option('ncshare_fb') === '1'): ?>
    <span class="share-btn" id="share-facebook" data-url="{{ data.facebook.url }}"><i class="fab fa-facebook-f"></i></span>
    <?php endif ?>

    <?php if (get_option('ncshare_twitter') === '1'): ?>
    <span class="share-btn" id="share-twitter" data-url="{{ data.twitter.url }}"><i class="fab fa-twitter"></i></span>
    <?php endif ?>

    <?php if (get_option('ncshare_linkedin') === '1'): ?>
    <span class="share-btn" id="share-linkedin" data-url="{{ data.linkedin.url }}"><i class="fab fa-linkedin-in"></i></span>
    <?php endif ?>

    <?php if (get_option('ncshare_pinterest') === '1'): ?>
    <span class="share-btn" id="share-pinterest" data-url="{{ data.pinterest.url }}"><i class="fab fa-pinterest"></i></span>
    <?php endif ?>
</div>
