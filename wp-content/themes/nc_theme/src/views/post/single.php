<?php
/**
 * @see nc_post_single()
 * @var array $post
 */
?>

<h1><?php the_title() ?></h1>

<img src="<?= (has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'nc_post_single') :
    wp_get_attachment_image_src(IMAGE_DEFAUT, 'nc_post_single')[0]) ?>" alt="">

<?php if(has_excerpt()) : ?>
    <p><?= get_the_excerpt() ?></p>
<?php endif; ?>

<p><?= get_the_date('d M Y', get_the_ID())?></p>

<div>
    <?php the_content(); ?>
</div>

<a href="<?= get_permalink(POST_LIST) ?>">Liste des actualités</a>
